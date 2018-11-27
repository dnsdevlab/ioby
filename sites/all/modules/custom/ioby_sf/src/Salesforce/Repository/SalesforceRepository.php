<?php

namespace Drupal\ioby_sf\Salesforce\Repository;


use Drupal\ioby_sf\Salesforce\Models\Account;
use Drupal\ioby_sf\Salesforce\Models\Affiliation;
use Drupal\ioby_sf\Salesforce\Models\Campaign;
use Drupal\ioby_sf\Salesforce\Models\CampaignMember;
use Drupal\ioby_sf\Salesforce\Models\Contact;
use Drupal\ioby_sf\Salesforce\Models\Fund;
use Drupal\ioby_sf\Salesforce\Models\FundCampaignGrouping;
use Drupal\ioby_sf\Salesforce\Models\ModelLoader;
use Drupal\ioby_sf\Salesforce\Models\Opportunity;
use Drupal\ioby_sf\Salesforce\Models\ProjectParticipation;

class SalesforceRepository
{

  protected $record_limit = 200;

  /**
   * @return Account[]
   */
  public function getAccounts() {
    $records = db_select('ioby_sf_accounts', 'a')
      ->fields('a')
      ->condition(
        db_or()
          ->isNull('a.salesforce_record_id')
          ->condition(
            db_and()
              ->isNotNull('a.salesforce_record_id')
              ->condition('a.needs_update', intval(TRUE), '='))
      )
      ->range(0, $this->record_limit)
      ->execute()->fetchAll();

    $accounts = array();

    foreach ($records as $record) {
      $accounts[] = $this->loadAccountRowData($record);
    }

    return $accounts;
  }

  /**
   * @param bool|TRUE $getNew
   *    TRUE to get new campaigns, FALSE to get modified campaigns
   *
   * @return Campaign[]
   */
  private function getCampaigns($getNew = TRUE) {
    $query = db_select('ioby_sf_campaigns', 'c')
      ->fields('c')
      ->range(0, $this->record_limit);

    if ($getNew) {
      $query->condition('c.is_new', intval(TRUE), '=');
    }
    else {
      $query->condition(
            db_and()
              ->isNotNull('c.salesforce_record_id')
              ->condition('c.needs_update', intval(TRUE), '=')
      );
    }

    $records = $query->execute()->fetchAll();

    $campaigns = array();

    foreach ($records as $record) {
      $campaigns[] = $this->loadCampaignRowData($record);
    }

    return $campaigns;
  }

  /**
   * @return Campaign[]
   */
  public function getNewCampaigns() {
    return $this->getCampaigns(TRUE);
  }

  /**
   * @return Campaign[]
   */
  public function getModifiedCampaigns() {
    return $this->getCampaigns(FALSE);
  }

  /**
   * @return Fund[]
   */
  public function getFunds() {
    $query = db_select('ioby_sf_funds', 'f')
      ->fields('f')
      ->condition(
        db_or()
          ->isNull('f.salesforce_record_id')
          ->condition(
            db_and()
              ->isNotNull('f.salesforce_record_id')
              ->condition('f.needs_update', intval(TRUE), '='))
      )
      ->range(0, $this->record_limit);

    $query->leftJoin('ioby_sf_accounts', 'a', 'f.sponsor_nid = a.sponsor_nid');
    $query->addField('a', 'salesforce_record_id', 'account_sf_id');

    $records = $query->execute()->fetchAll();

    $funds = array();

    foreach ($records as $record) {
      $funds[] = $this->loadFundRowData($record);
    }

    return $funds;
  }

  /**
   * This retrieves a mix of new and updated contacts. Since we're doing an upsert,
   * both can be handled at the same time.
   *
   * @return Contact[]
   */
  public function getContacts() {
    /**
     * SELECT contacts where the salesforce_record_id IS NULL OR
     * (salesforce_record_id IS NOT NULL AND needs_update = 1)
     */
    $records = db_select('ioby_sf_contacts', 'c')
      ->fields('c')
      ->condition(
        db_or()
          ->isNull('c.salesforce_record_id')
          ->condition(
            db_and()
            ->isNotNull('c.salesforce_record_id')
            ->condition('c.needs_update', intval(TRUE), '='))
      )
      ->range(0, $this->record_limit)
      ->execute()->fetchAll();

    $contacts = array();

    foreach ($records as $record) {
      $contacts[] = $this->loadContactRowData($record);
    }

    return $contacts;
  }

  /**
   * @return Affiliation[]
   */
  public function getAffiliations() {
    $query = db_select('ioby_sf_affiliations', 'af')
      ->fields('af')
      ->isNull('af.salesforce_record_id')
      ->range(0, $this->record_limit);

    $query->innerJoin('ioby_sf_contacts', 'c', 'af.email = c.email');
    $query->addField('c', 'salesforce_record_id', 'contact_sf_id');
    $query->isNotNull('c.salesforce_record_id');

    $query->innerJoin('ioby_sf_accounts', 'a', 'af.account_id = a.account_id');
    $query->addField('a', 'salesforce_record_id', 'account_sf_id');
    $query->isNotNull('a.salesforce_record_id');

    $records = $query->execute()->fetchAll();

    $affiliations = array();

    foreach ($records as $record) {
      $affiliations[] = $this->loadAffiliationRowData($record);
    }

    return $affiliations;
  }

  /**
   * @return ProjectParticipation[]
   */
  public function getProjectParticipations() {
    $query = db_select('ioby_sf_project_participations', 'pp')
      ->fields('pp')
      ->isNull('pp.salesforce_record_id')
      ->range(0, $this->record_limit);

    $query->innerJoin('ioby_sf_accounts', 'a', 'pp.account_id = a.account_id');
    $query->addField('a', 'salesforce_record_id', 'account_sf_id');
    $query->isNotNull('a.salesforce_record_id');

    $query->innerJoin('ioby_sf_campaigns', 'c', 'pp.project_nid = c.project_nid');
    $query->addField('c', 'salesforce_record_id', 'campaign_sf_id');
    $query->isNotNull('c.salesforce_record_id');

    $records = $query->execute()->fetchAll();

    $project_participations = array();

    foreach ($records as $record) {
      $project_participations[] = $this->loadProjectParticipationRowData($record);
    }

    return $project_participations;
  }

  /**
   * @return CampaignMember[]
   */
  public function getCampaignMembers() {
    $query = db_select('ioby_sf_campaign_members', 'cm')
      ->fields('cm')
      ->condition(
        db_or()
          ->isNull('cm.salesforce_record_id')
          ->condition(
            db_and()
              ->isNotNull('cm.salesforce_record_id')
              ->condition('cm.needs_update', intval(TRUE), '='))
      )
      ->range(0, $this->record_limit);

    $query->innerJoin('ioby_sf_campaigns', 'c', 'cm.project_nid = c.project_nid');
    $query->addField('c', 'salesforce_record_id', 'campaign_sf_id');
    $query->isNotNull('c.salesforce_record_id');

    $query->innerJoin('ioby_sf_contacts', 'ct', 'cm.email = ct.email');
    $query->addField('ct', 'salesforce_record_id', 'contact_sf_id');
    $query->isNotNull('ct.salesforce_record_id');

    $records = $query->execute()->fetchAll();

    $campaign_members = array();

    foreach ($records as $record) {
      $campaign_members[] = $this->loadCampaignMemberRowData($record);
    }

    return $campaign_members;
  }

  /**
   * @return FundCampaignGrouping[]
   */
  public function getFundCampaignGroupings() {
    $query = db_select('ioby_sf_fund_groupings', 'fg')
      ->fields('fg')
      ->isNull('fg.salesforce_record_id')
      ->range(0, $this->record_limit);

    $query->innerJoin('ioby_sf_campaigns', 'c', 'fg.project_nid = c.project_nid');
    $query->addField('c', 'salesforce_record_id', 'campaign_sf_id');
    $query->isNotNull('c.salesforce_record_id');

    $query->innerJoin('ioby_sf_funds', 'f', 'fg.campaign_nid = f.campaign_nid');
    $query->addField('f', 'salesforce_record_id', 'fund_sf_id');
    $query->isNotNull('f.salesforce_record_id');

    $records = $query->execute()->fetchAll();

    $groupings = array();

    foreach ($records as $record) {
      $groupings[] = $this->loadFundCampaignGroupingsRowData($record);
    }

    return $groupings;
  }

  /**
   * @return Opportunity[]
   */
  public function getOpportunities() {
    $query = db_select('ioby_sf_opportunities', 'o')
      ->fields('o')
      ->isNull('o.salesforce_record_id')
      ->range(0, $this->record_limit);

    $query->leftJoin('ioby_sf_contacts', 'c', 'o.contact_email = c.email');
    $query->addField('c', 'salesforce_record_id', 'contact_sf_id');

    $query->leftJoin('ioby_sf_accounts', 'a', 'o.sponsor_nid = a.sponsor_nid');
    $query->addField('a', 'salesforce_record_id', 'account_sf_id');

    $query->leftJoin('ioby_sf_campaigns', 'p', 'o.project_nid = p.project_nid');
    $query->addField('p', 'salesforce_record_id', 'campaign_sf_id');

    $query->leftJoin('ioby_sf_funds', 'f', 'o.campaign_nid = f.campaign_nid');
    $query->addField('f', 'salesforce_record_id', 'fund_sf_id');

    $records = $query->execute()->fetchAll();

    $opportunities = array();

    foreach ($records as $record) {
      $opportunities[] = ModelLoader::loadOpportunityRowData($record);
    }

    return $opportunities;
  }

  /**
   * @return Opportunity[]
   */
  public function getManualOpportunitiesForUpdate() {
    $records = db_select('ioby_sf_opportunities', 'o')
      ->fields('o')
      ->condition('o.entry_type', 'manual-salesforce', '=')
      ->condition('o.needs_update', intval(TRUE), '=')
      ->range(0, $this->record_limit)
      ->execute()->fetchAll();

    $opportunities = array();

    foreach ($records as $record) {
      $opportunities[] = ModelLoader::loadOpportunityRowData($record);
    }

    return $opportunities;
  }

  /**
   * @param \stdClass $account_row
   *
   * @return Account
   */
  private function loadAccountRowData($account_row) {
    $account = new Account();

    $account
      ->setAccountId($account_row->account_id)
      ->setAccountName($account_row->account_name)
      ->setBillingStreet($account_row->billing_street)
      ->setBillingCity($account_row->billing_city)
      ->setBillingState($account_row->billing_state)
      ->setBillingZip($account_row->billing_zip)
      ->setBillingCountry($account_row->billing_country)
      ->setSponsorNid($account_row->sponsor_nid)
      ->setSalesforceRecordId($account_row->salesforce_record_id)
      ->setCreated($account_row->created)
      ->setChanged($account_row->changed)
      ->setNeedsUpdate($account_row->needs_update);

    return $account;
  }

  /**
   * @param \stdClass $campaign_row
   *
   * @return Campaign
   */
  private function loadCampaignRowData($campaign_row) {
    $campaign = new Campaign();
    $campaign
      ->setProjectNid($campaign_row->project_nid)
      ->setName($campaign_row->name)
      ->setStatus($campaign_row->status)
      ->setStartDate($campaign_row->start_date)
      ->setDeadlineDate($campaign_row->deadline_date)
      ->setExpectedRevenue($campaign_row->expected_revenue)
      ->setVolunteersAccepted($campaign_row->volunteers_accepted)
      ->setVolunteersDescription($campaign_row->volunteers_description)
      ->setProjectStreet($campaign_row->project_street)
      ->setProjectStreet2($campaign_row->project_street2)
      ->setProjectCity($campaign_row->project_city)
      ->setProjectState($campaign_row->project_state)
      ->setProjectZip($campaign_row->project_zip)
      ->setProjectBorough($campaign_row->project_borough)
      ->setCreated($campaign_row->created)
      ->setChanged($campaign_row->changed)
      ->setNeedsUpdate($campaign_row->needs_update)
      ->setSalesforceRecordId($campaign_row->salesforce_record_id)
      ->setIsNew($campaign_row->is_new);

    return $campaign;
  }

  /**
   * @param \stdClass $fund_row
   *
   * @return Fund
   */
  private function loadFundRowData($fund_row) {
    $fund = new Fund();
    $fund->setCampaignNid($fund_row->campaign_nid)
      ->setName($fund_row->name)
      ->setSponsorNid($fund_row->sponsor_nid)
      ->setTotalValue($fund_row->total_value)
      ->setDescription($fund_row->description)
      ->setAccountSfId($fund_row->account_sf_id)
      ->setSalesforceRecordId($fund_row->salesforce_record_id)
      ->setCreated($fund_row->created)
      ->setChanged($fund_row->changed)
      ->setNeedsUpdate($fund_row->needs_update);

    return $fund;
  }

  /**
   * @param \stdClass $contact_row
   *
   * @return Contact
   */
  private function loadContactRowData($contact_row) {
    $contact = new Contact();
    $contact
      ->setUid($contact_row->uid) // TODO: Possibly get rid of this field since it isn't unique in the contacts table so we can't push it
      ->setEmail($contact_row->email)
      ->setFirstName($contact_row->first_name)
      ->setLastName($contact_row->last_name)
      ->setMailingStreet($contact_row->mailing_street)
      ->setMailingCity($contact_row->mailing_city)
      ->setMailingState($contact_row->mailing_state)
      ->setMailingZip($contact_row->mailing_zip)
      ->setMailingCountry($contact_row->mailing_country)
      ->setPhone($contact_row->phone)
      ->setOtherStreet($contact_row->other_street)
      ->setOtherCity($contact_row->other_city)
      ->setOtherState($contact_row->other_state)
      ->setOtherZip($contact_row->other_zip)
      ->setOtherCountry($contact_row->other_country)
      ->setOtherPhone($contact_row->other_phone)
      ->setAlternateEmail($contact_row->alternate_email)
      ->setBirthDate($contact_row->birth_date)
      ->setSalesforceRecordId($contact_row->salesforce_record_id)
      ->setCreated($contact_row->created)
      ->setChanged($contact_row->changed)
      ->setNeedsUpdate($contact_row->needs_update)
      ->setUpdateMailingAddress($contact_row->update_mailing_address)
      ->setUpdateOtherAddress($contact_row->update_other_address);

    return $contact;
  }

  /**
   * @param \stdClass $affiliation_row
   *
   * @return Affiliation
   */
  private function loadAffiliationRowData($affiliation_row) {
    $affiliation = new Affiliation();
    $affiliation->setAffiliationId($affiliation_row->affiliation_id)
      ->setEmail($affiliation_row->email)
      ->setContactSfId($affiliation_row->contact_sf_id)
      ->setAccountId($affiliation_row->account_id)
      ->setAccountSfId($affiliation_row->account_sf_id)
      ->setSalesforceRecordId($affiliation_row->salesforce_record_id);

    return $affiliation;
  }


  /**
   * @param \stdClass $project_participation_row
   *
   * @return ProjectParticipation
   */
  private function loadProjectParticipationRowData($project_participation_row) {
    $project_participation = new ProjectParticipation();
    $project_participation->setProjectParticipationId($project_participation_row->project_participation_id)
      ->setAccountId($project_participation_row->account_id)
      ->setAccountSfId($project_participation_row->account_sf_id)
      ->setProjectNid($project_participation_row->project_nid)
      ->setCampaignSfId($project_participation_row->campaign_sf_id)
      ->setSalesforceRecordId($project_participation_row->salesforce_record_id);

    return $project_participation;
  }

  /**
   * @param \stdClass $campaign_member_row
   *
   * @return CampaignMember
   */
  private function loadCampaignMemberRowData($campaign_member_row) {
    $campaign_member = new CampaignMember();
    $campaign_member->setCampaignMemberId($campaign_member_row->campaign_member_id)
      ->setEmail($campaign_member_row->email)
      ->setContactSfId($campaign_member_row->contact_sf_id)
      ->setProjectNid($campaign_member_row->project_nid)
      ->setCampaignSfId($campaign_member_row->campaign_sf_id)
      ->setProjectDonor($campaign_member_row->project_donor)
      ->setProjectLeader($campaign_member_row->project_leader)
      ->setSalesforceRecordId($campaign_member_row->salesforce_record_id)
      ->setNeedsUpdate($campaign_member_row->needs_update);

    return $campaign_member;
  }

  /**
   * @param \stdClass $grouping_row
   *
   * @return FundCampaignGrouping
   */
  private function loadFundCampaignGroupingsRowData($grouping_row) {
    $grouping = new FundCampaignGrouping();
    $grouping->setFundGroupingId($grouping_row->fund_grouping_id)
      ->setCampaignNid($grouping_row->campaign_nid)
      ->setFundSfId($grouping_row->fund_sf_id)
      ->setProjectNid($grouping_row->project_nid)
      ->setCampaignSfId($grouping_row->campaign_sf_id)
      ->setSalesforceRecordId($grouping_row->salesforce_record_id);

    return $grouping;
  }

  /**
   * @param string $salesforce_record_id
   *
   * @return int|null
   *    Returns the project_nid if found or NULL.
   */
  public function getProjectNidById($salesforce_record_id) {
    if (empty($salesforce_record_id)) {
      return NULL;
    }

    $project_nid = db_select('ioby_sf_campaigns', 'c')
      ->fields('c', array('project_nid'))
      ->condition('c.salesforce_record_id', $salesforce_record_id, '=')
      ->execute()->fetchField();

    return ($project_nid) ? $project_nid : NULL;
  }

  /**
   * @param string $salesforce_record_id
   *
   * @return int|null
   *    Returns the campaign_nid if found or NULL.
   */
  public function getCampaignNidById($salesforce_record_id) {
    if (empty($salesforce_record_id)) {
      return NULL;
    }

    $campaign_nid = db_select('ioby_sf_funds', 'f')
      ->fields('f', array('campaign_nid'))
      ->condition('f.salesforce_record_id', $salesforce_record_id, '=')
      ->execute()->fetchField();

    return ($campaign_nid) ? $campaign_nid : NULL;
  }

  /**
   * @param string $salesforce_record_id
   *
   * @return string|null
   *    Returns the contact email if found or NULL.
   */
  public function getContactEmailById($salesforce_record_id) {
    if (empty($salesforce_record_id)) {
      return NULL;
    }

    $contact_email = db_select('ioby_sf_contacts', 'c')
      ->fields('c', array('email'))
      ->condition('c.salesforce_record_id', $salesforce_record_id, '=')
      ->execute()->fetchField();

    return ($contact_email) ? $contact_email : NULL;
  }

  /**
   * Helper to convert 15-digit Id to 18 digits
   *
   * @param $salesforceId
   *
   * @return string
   * @throws \Exception
   */
  public static function  getSalesforceIdWithChecksum($salesforceId){
    /* map of characters to be used for in checksum */
    $map = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ012345';
    $checksum = '';

    /* verify the checksum on 18 digit IDs */
    switch( strlen($salesforceId) ){
      case 18: // validate checksum, this can help you detect mangled case but is not foolproof.
        $input = $salesforceId;
        $salesforceId = substr( $input, 0, 15 );
        break;
      case 15:
        // generate checksum
        $input = false;
        break;
      default:
        // throw an error
        throw new \Exception( 'Wrong length for ' . $salesforceId . '. Expected 15 or 18 characters' );
    }

    /* split into the 3 extra characters */
    $chunks = str_split($salesforceId, 5);
    /* for each of the 3 characters */
    foreach ($chunks as $chunk) {
      /* place each character into an array */
      $chars = str_split($chunk, 1);
      $bits = ''; // reset the value
      /* for each character */
      foreach ($chars as $char) {
        /* replace upper case characters with a 1 and lower case with a 0 */
        $bits .= (!is_numeric($char) && $char == strtoupper($char)) ? '1' : '0';
      }

      /* convert from big endian binary to decimal
       * append the character in that position of the map to the checksum
       */
      $checksum .= substr($map, bindec( strrev($bits) ), 1);
    }

    /* if we were given a 18 digit id then verify the checksum */
    if( $input && ($salesforceId.$checksum != $input) ){
      throw new \Exception( 'Checksum mismatch for ' . $input . '.  Expected ' . $salesforceId.$checksum . '.' );
    }

    return $salesforceId . $checksum;
  }
}
