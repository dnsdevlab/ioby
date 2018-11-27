<?php

namespace Drupal\ioby_sf\Salesforce\Repository;

use Drupal\ioby_sf\Salesforce\Models\Account;
use Drupal\ioby_sf\Salesforce\Models\Campaign;
use Drupal\ioby_sf\Salesforce\Models\Contact;

class CampaignRepository
{

  /**
   * @return Campaign[]
   */
  public function getNewCampaigns() {
    // Get projects that don't exist in the campaigns table and don't have a manual
    // Salesforce Record Id set (or flagged to create a new one), so that we know
    // to add them to the ioby_sf_campaigns table.
    $query = db_select('node', 'n')
      ->fields('n', array('nid'))
      ->condition('n.status', NODE_PUBLISHED, '=')
      ->condition('n.type', IOBY_NODE_TYPE_PROJECT, '=')
      ->condition('create_new_sf_object', 1, '=')
      ->isNotNull('pp.nid')
      ->isNull('sfc.project_nid')
      ->orderBy('n.nid');
    $query->innerJoin('ioby_sf_potential_projects', 'pp', 'n.nid = pp.nid');
    $query->leftJoin('ioby_sf_campaigns', 'sfc', 'n.nid = sfc.project_nid');

    $nids = $query->execute()->fetchCol();

    if (empty($nids)) {
      return array();
    }

    $projects = node_load_multiple($nids);
    $campaigns = array();

    foreach ($projects as $project) {
      $campaigns[] = $this->getCampaignFromNode($project);
    }

    return $campaigns;
  }

  /**
   * @return Campaign[]
   */
  public function getModifiedCampaigns() {
    // Use a cutoff date so we don't get basically every existing project.
    $query = db_select('node', 'n')
      ->fields('n', array('nid'))
      ->condition('n.status', NODE_PUBLISHED, '=')
      ->condition('n.type', IOBY_NODE_TYPE_PROJECT, '=')
      ->where('n.changed > sfc.changed')
      ->where('n.changed > ' . IOBY_PROJECT_CUTOFF_DATE)
      ->isNotNull('sfc.project_nid')
      ->orderBy('n.nid');
    $query->innerJoin('ioby_sf_campaigns', 'sfc', 'n.nid = sfc.project_nid');

    $nids = $query->execute()->fetchCol();

    if (empty($nids)) {
      return array();
    }

    $projects = node_load_multiple($nids);
    $campaigns = array();

    foreach ($projects as $project) {
      $campaigns[] = $this->getCampaignFromNode($project);
    }

    return $campaigns;
  }

  /**
   * Pulls Potential Projects that have been claimed by a Drupal Project, which
   * is any row that has an nid attached and which has a 'create_new_sf_object'
   * of 1. This means we should update the record in Salesforce in preparation
   * for the upsert in ioby_sf_push_campaign_objects().
   *
   * @see ioby_sf_push_campaign_objects()
   *
   * @author Paul Venuti
   */
  public function getNewPotentialProjects() {
    $query = db_select('node', 'n')
      ->fields('n', array('nid'))
      ->fields('pp', array('salesforce_record_id'))
      ->condition('n.status', NODE_PUBLISHED, '=')
      ->condition('n.type', IOBY_NODE_TYPE_PROJECT, '=')
      ->condition('pp.create_new_sf_object', 1, '=')
      ->isNotNull('pp.salesforce_record_id')
      ->isNotNull('pp.nid')
      ->isNotNull('sfc.project_nid')
      ->orderBy('n.nid');
    $query->innerJoin('ioby_sf_potential_projects', 'pp', 'n.nid = pp.nid');
    $query->leftJoin('ioby_sf_campaigns', 'sfc', 'n.nid = sfc.project_nid');

    $potential_projects = $query->execute()->fetchAll();

    if (empty($potential_projects)) {
      return array();
    }

    return $potential_projects;
  }

  /**
   * @param \stdClass $project_node
   *
   * @return Campaign
   * @throws \InvalidArgumentException
   */
  public function getCampaignFromNode($project_node) {
    if (empty($project_node) || $project_node->type != IOBY_NODE_TYPE_PROJECT) {
      throw new \InvalidArgumentException("The node that was passed in is not a project.");
    }

    $campaign = new Campaign();
    $campaign
      ->setProjectNid($project_node->nid)
      ->setName($project_node->title)
      ->setStartDate(!empty($project_node->field_project_start_date[LANGUAGE_NONE][0]['value']) ? $project_node->field_project_start_date[LANGUAGE_NONE][0]['value'] : format_date($project_node->created, 'custom', DATE_FORMAT_ISO))
      ->setDeadlineDate(!empty($project_node->field_deadline[LANGUAGE_NONE][0]['value']) ? $project_node->field_deadline[LANGUAGE_NONE][0]['value'] : NULL)
      ->setStatus(isset($project_node->field_project_status[LANGUAGE_NONE][0]['value']) ? $campaign->status_map[$project_node->field_project_status[LANGUAGE_NONE][0]['value']] : '')
      ->setExpectedRevenue(isset($project_node->field_project_cost[LANGUAGE_NONE][0]['value']) ? ($project_node->field_project_cost[LANGUAGE_NONE][0]['value']) : 0)
      ->setVolunteersAccepted(isset($project_node->field_project_volunteers[LANGUAGE_NONE][0]['value']) ? $project_node->field_project_volunteers[LANGUAGE_NONE][0]['value'] : FALSE)
      ->setVolunteersDescription(isset($project_node->field_project_vol_reason[LANGUAGE_NONE][0]['value']) ? $project_node->field_project_vol_reason[LANGUAGE_NONE][0]['value'] : NULL)
      ->setProjectStreet(isset($project_node->field_project_address[LANGUAGE_NONE][0]['street']) ? $project_node->field_project_address[LANGUAGE_NONE][0]['street'] : NULL)
      ->setProjectStreet2(isset($project_node->field_project_address[LANGUAGE_NONE][0]['additional']) ? $project_node->field_project_address[LANGUAGE_NONE][0]['additional'] : NULL)
      ->setProjectCity(isset($project_node->field_project_address[LANGUAGE_NONE][0]['city']) ? $project_node->field_project_address[LANGUAGE_NONE][0]['city'] : NULL)
      ->setProjectState(isset($project_node->field_project_address[LANGUAGE_NONE][0]['province']) ? $project_node->field_project_address[LANGUAGE_NONE][0]['province'] : NULL)
      ->setProjectZip(isset($project_node->field_project_address[LANGUAGE_NONE][0]['postal_code']) ? $project_node->field_project_address[LANGUAGE_NONE][0]['postal_code'] : NULL)
      ->setProjectBorough(!empty($project_node->field_project_boroughs[LANGUAGE_NONE][0]['tid']) ? $this->getBoroughFromTid($project_node->field_project_boroughs[LANGUAGE_NONE][0]['tid']) : NULL)
      ->setCreated(REQUEST_TIME)
      ->setChanged(REQUEST_TIME);

    if (!empty($project_node->salesforce_record_id)) {
      $campaign->setSalesforceRecordId($project_node->salesforce_record_id);
    }

    return $campaign;
  }

  /**
   * @param $project_node
   *
   * @return bool|Account
   * @throws \InvalidArgumentException
   */
  public function getAccountForProject($project_node) {
    if (empty($project_node) || $project_node->type != IOBY_NODE_TYPE_PROJECT) {
      throw new \InvalidArgumentException("The node that was passed in is not a project.");
    }

    if (empty($project_node->field_group_name[LANGUAGE_NONE][0]['safe_value'])) {
      return FALSE;
    }

    $account = new Account();
    $account
      ->setAccountName($project_node->field_group_name[LANGUAGE_NONE][0]['value'])
      ->setBillingStreet(isset($project_node->field_contact_address[LANGUAGE_NONE][0]['street']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['street'] . (!empty($project_node->field_contact_address[LANGUAGE_NONE][0]['additional']) ? "\n" . $project_node->field_contact_address[LANGUAGE_NONE][0]['additional'] : '') : '')
      ->setBillingCity(isset($project_node->field_contact_address[LANGUAGE_NONE][0]['city']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['city'] : '')
      ->setBillingState(isset($project_node->field_contact_address[LANGUAGE_NONE][0]['province']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['province'] : '')
      ->setBillingZip(isset($project_node->field_contact_address[LANGUAGE_NONE][0]['postal_code']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['postal_code'] : '')
      ->setBillingCountry(isset($project_node->field_contact_address[LANGUAGE_NONE][0]['country']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['country'] : '')
      ->setCreated(REQUEST_TIME)
      ->setChanged(REQUEST_TIME);

    return $account;
  }

  /**
   * @param \stdClass $project_node
   *
   * @return Contact
   * @throws \InvalidArgumentException
   */
  public function getContactForProject($project_node) {
    if (empty($project_node) || $project_node->type != IOBY_NODE_TYPE_PROJECT) {
      throw new \InvalidArgumentException("The node that was passed in is not a project.");
    }

    $contact = new Contact();

    $contact
      ->setUid($project_node->uid)
      ->setEmail($project_node->field_project_contact_email[LANGUAGE_NONE][0]['email'])
      ->setFirstName(isset($project_node->field_contact_first[LANGUAGE_NONE][0]['value']) ? $project_node->field_contact_first[LANGUAGE_NONE][0]['value'] : '')
      ->setLastName(isset($project_node->field_contact_last[LANGUAGE_NONE][0]['value']) ? $project_node->field_contact_last[LANGUAGE_NONE][0]['value'] : '')
      ->setMailingStreet((isset($project_node->field_contact_address[LANGUAGE_NONE][0]['street']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['street'] . (!empty($project_node->field_contact_address[LANGUAGE_NONE][0]['additional']) ? "\n" . $project_node->field_contact_address[LANGUAGE_NONE][0]['additional'] : '') : ''))
      ->setMailingCity(isset($project_node->field_contact_address[LANGUAGE_NONE][0]['city']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['city'] : '')
      ->setMailingState(isset($project_node->field_contact_address[LANGUAGE_NONE][0]['province']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['province'] : '')
      ->setMailingZip(isset($project_node->field_contact_address[LANGUAGE_NONE][0]['postal_code']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['postal_code'] : '')
      ->setMailingCountry(isset($project_node->field_contact_address[LANGUAGE_NONE][0]['country']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['country'] : '')
      ->setPhone(isset($project_node->field_project_contact_phone[LANGUAGE_NONE][0]['value']) ? $project_node->field_project_contact_phone[LANGUAGE_NONE][0]['value'] : '')
      ->setOtherStreet((isset($project_node->field_contact_address[LANGUAGE_NONE][0]['street']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['street'] . (!empty($project_node->field_contact_address[LANGUAGE_NONE][0]['additional']) ? "\n" . $project_node->field_contact_address[LANGUAGE_NONE][0]['additional'] : '') : ''))
      ->setOtherCity(isset($project_node->field_contact_address[LANGUAGE_NONE][0]['city']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['city'] : '')
      ->setOtherState(isset($project_node->field_contact_address[LANGUAGE_NONE][0]['province']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['province'] : '')
      ->setOtherZip(isset($project_node->field_contact_address[LANGUAGE_NONE][0]['postal_code']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['postal_code'] : '')
      ->setOtherCountry(isset($project_node->field_contact_address[LANGUAGE_NONE][0]['country']) ? $project_node->field_contact_address[LANGUAGE_NONE][0]['country'] : '')
      ->setOtherPhone(isset($project_node->field_project_contact_phone[LANGUAGE_NONE][0]['value']) ? $project_node->field_project_contact_phone[LANGUAGE_NONE][0]['value'] : '')
      ->setAlternateEmail(isset($project_node->field_project_contact_email[LANGUAGE_NONE][0]['email']) ? $project_node->field_project_contact_email[LANGUAGE_NONE][0]['email'] : '')
      ->setBirthDate(isset($project_node->field_contact_birth[LANGUAGE_NONE][0]['value']) ? $project_node->field_contact_birth[LANGUAGE_NONE][0]['value'] : NULL)
      ->setCreated(REQUEST_TIME)
      ->setChanged(REQUEST_TIME);

    return $contact;
  }

  /**
   * @param $project_nid
   *
   * @return bool|Account
   * @throws \InvalidArgumentException
   */
  public function getAccountFromTable($project_nid) {
    if (empty($project_nid) || !is_numeric($project_nid)) {
      throw new \InvalidArgumentException("The project_nid is invalid.");
    }

    $query = db_select('ioby_sf_accounts', 'a')
      ->fields('a')
      ->condition('c.project_nid', $project_nid, '=');
    $query->innerJoin('ioby_sf_project_participations', 'pp', 'a.account_id = pp.account_id');
    $query->innerJoin('ioby_sf_campaigns', 'c', 'c.project_nid = pp.project_nid');

    if (!$account_record = $query->execute()->fetch()) {
      return FALSE;
    }

    $account = new Account();
    $account
      ->setAccountId($account_record->account_id)
      ->setAccountName($account_record->account_name)
      ->setBillingStreet($account_record->billing_street)
      ->setBillingCity($account_record->billing_city)
      ->setBillingState($account_record->billing_state)
      ->setBillingZip($account_record->billing_zip)
      ->setBillingCountry($account_record->billing_country)
      ->setSponsorNid($account_record->sponsor_nid)
      ->setSalesforceRecordId($account_record->salesforce_record_id)
      ->setCreated($account_record->created)
      ->setChanged($account_record->changed)
      ->setNeedsUpdate($account_record->needs_update);

    return $account;
  }

  /**
   * @param $project_nid
   *
   * @return bool|Campaign
   */
  public function getCampaignFromTable($project_nid) {
    $campaign_record = db_select('ioby_sf_campaigns', 'c')
      ->fields('c')
      ->condition('c.project_nid', $project_nid, '=')
      ->execute()
      ->fetch();

    if (!$campaign_record) {
      return FALSE;
    }

    $campaign = new Campaign();
    $campaign
      ->setProjectNid($campaign_record->project_nid)
      ->setName($campaign_record->name)
      ->setStatus($campaign_record->status)
      ->setStartDate($campaign_record->start_date)
      ->setDeadlineDate($campaign_record->deadline_date)
      ->setExpectedRevenue($campaign_record->expected_revenue)
      ->setVolunteersAccepted($campaign_record->volunteers_accepted)
      ->setVolunteersDescription($campaign_record->volunteers_description)
      ->setProjectStreet($campaign_record->project_street)
      ->setProjectStreet2($campaign_record->project_street2)
      ->setProjectCity($campaign_record->project_city)
      ->setProjectState($campaign_record->project_state)
      ->setProjectZip($campaign_record->project_zip)
      ->setProjectBorough($campaign_record->project_borough)
      ->setCreated($campaign_record->created)
      ->setChanged($campaign_record->changed)
      ->setNeedsUpdate($campaign_record->needs_update)
      ->setSalesforceRecordId($campaign_record->salesforce_record_id)
      ->setIsNew($campaign_record->is_new);

    return $campaign;
  }

  /**
   * @param string $email
   *
   * @return bool|Contact
   */
  public function getContactFromTableByEmail($email) {
    $contact_record = db_select('ioby_sf_contacts', 'c')
      ->fields('c')
      ->condition('c.email', $email, '=')
      ->range(0,1)
      ->execute()->fetch();

    if (!$contact_record) {
      return FALSE;
    }

    $contact = $this->loadContactRowData($contact_record);

    return $contact;
  }

  /**
   * @param int $project_nid
   *
   * @return Contact[]
   */
  public function getContactsFromTableByNid($project_nid) {
    $contacts = array();

    $query = db_select('ioby_sf_contacts', 'c')
      ->fields('c')
      ->condition('cc.project_nid', $project_nid, '=');
    $query->innerJoin('ioby_sf_campaign_members', 'cm', 'c.email = cm.email');

    $contact_records = $query->execute()->fetchAll();

    foreach ($contact_records as $record) {
      $contacts[] = $this->loadContactRowData($record);
    }

    return $contacts;
  }

  /**
   * @param \stdClass $contact_record
   *
   * @return Contact
   */
  private function loadContactRowData($contact_record) {
    $contact = new Contact();
    $contact
      ->setUid($contact_record->uid)
      ->setEmail($contact_record->email)
      ->setFirstName($contact_record->first_name)
      ->setLastName($contact_record->last_name)
      ->setMailingStreet($contact_record->mailing_street)
      ->setMailingCity($contact_record->mailing_city)
      ->setMailingState($contact_record->mailing_state)
      ->setMailingZip($contact_record->mailing_zip)
      ->setMailingCountry($contact_record->mailing_country)
      ->setPhone($contact_record->phone)
      ->setOtherStreet($contact_record->other_street)
      ->setOtherCity($contact_record->other_city)
      ->setOtherState($contact_record->other_state)
      ->setOtherZip($contact_record->other_zip)
      ->setOtherCountry($contact_record->other_country)
      ->setOtherPhone($contact_record->other_phone)
      ->setAlternateEmail($contact_record->alternate_email)
      ->setBirthDate($contact_record->birth_date)
      ->setSalesforceRecordId($contact_record->salesforce_record_id)
      ->setCreated($contact_record->created)
      ->setChanged($contact_record->changed)
      ->setNeedsUpdate($contact_record->needs_update)
      ->setUpdateMailingAddress($contact_record->update_mailing_address)
      ->setUpdateOtherAddress($contact_record->update_other_address);

    return $contact;
  }

  /**
   * @param int $tid
   *
   * @return string|null
   *    Returns the term name if the term is found or NULL.
   */
  private function getBoroughFromTid($tid) {
    $term = taxonomy_term_load($tid);

    return ($term) ? $term->name : NULL;
  }
}
