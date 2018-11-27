<?php

namespace Drupal\ioby_sf\Salesforce;

use Drupal\ioby_sf\Salesforce\Models\Account;
use Drupal\ioby_sf\Salesforce\Models\Contact;
use Drupal\ioby_sf\Salesforce\Models\Campaign;
use Drupal\ioby_sf\Salesforce\Repository\CampaignRepository;

class CampaignQueueService
{
  /**
   * @var CampaignRepository
   */
  protected $repository;

  function __construct(CampaignRepository $repository = NULL) {
    if (!isset($repository)) {
      $repository = new CampaignRepository();
    }
    $this->repository = $repository;
  }

  /**
   * Retrieves projects that do not exist yet in the sync tables and inserts them
   * into the proper tables.
   */
  public function queueNewCampaigns() {
    $campaigns = $this->repository->getNewCampaigns();

    foreach ($campaigns as $campaign) {
      $this->populateCampaignTables($campaign);
    }
  }

  /**
   * Retrieves projects that exist in the sync tables but have changed since the
   * record was inserted or last modified.
   */
  public function queueModifiedCampaigns() {
    $campaigns = $this->repository->getModifiedCampaigns();

    foreach ($campaigns as $campaign) {
      $this->updateCampaign($campaign);
    }
  }

  /**
   * Populates the intermediate sync tables with data to build the related
   * Salesforce objects.
   *
   * @param $project_nid
   *  The node id of the project to sync.
   */
  private function populateCampaignTables(Campaign $campaign) {
    // Make sure this project hasn't already been populated in the sync table
    if ($existing_campaign = $this->repository->getCampaignFromTable($campaign->getProjectNid())) {
      throw new \Exception("The project node with nid: $campaign->getProjectNid() is already in the sync table.");
    }

    if ($account_data = $this->repository->getAccountForProject(node_load($campaign->getProjectNid()))) {
      $account_id = $this->insertAccount($account_data, $campaign->getProjectNid());
    }

    $this->insertCampaign($campaign);

    $contact_data = $this->repository->getContactForProject(node_load($campaign->getProjectNid()));
    $this->mergeContact($contact_data, $campaign->getProjectNid());

    // If there is account data with this campaign, link the account to the
    // project organizer as an affiliation
    if (isset($account_id)) {
      $this->insertAffiliation($contact_data->getEmail(), $account_id);
    }
  }

  /**
   * Compares current project node values with what is already stored in the
   * campaign table to determine if the campaign table values need to be updated.
   *
   * @param Campaign $campaign
   *    The nid of the project to check for updates to.
   *
   * @return array|bool
   *    An array of fields and values to update the campaign table with or FALSE
   *    if there are no changes.
   * @throws \Exception
   */
  private function getProjectChanges(Campaign $campaign) {
    if (!$campaign) {
      throw new \InvalidArgumentException("You must pass a valid campaign object.");
    }

    $changes = array();

    if ($existing_data = $this->repository->getCampaignFromTable($campaign->getProjectNid())) {
      if ($campaign->getName() != $existing_data->getName()) {
        $changes['name'] = $campaign->getName();
      }
      if ($campaign->getStatus() != $existing_data->getStatus()) {
        $changes['status'] = $campaign->getStatus();
      }
      if ($campaign->getStartDate() != $existing_data->getStartDate()) {
        $changes['start_date'] = $campaign->getStartDate();
      }
      if ($campaign->getDeadlineDate() != $existing_data->getDeadlineDate()) {
        $changes['deadline_date'] = $campaign->getDeadlineDate();
      }
      if ($campaign->getExpectedRevenue() != $existing_data->getExpectedRevenue()) {
        $changes['expected_revenue'] = $campaign->getExpectedRevenue();
      }

      if (!empty($changes)) {
        $changes['changed'] = REQUEST_TIME;
        $changes['needs_update'] = TRUE;
      }
    }

    return !empty($changes) ? $changes : FALSE;
  }

  /**
   * Inserts the campaign record.
   *
   * @param Campaign $campaign
   *    The campaign to insert.
   *
   * @throws \Exception
   */
  private function insertCampaign(Campaign $campaign) {
    if ($campaign == NULL || $campaign->getProjectNid() == NULL) {
      throw new \Exception("There is no campaign data loaded to insert.");
    }

    $insert_fields = array(
      'project_nid' => $campaign->getProjectNid(),
      'name' => $campaign->getName(),
      'status' => $campaign->getStatus(),
      'start_date' => $campaign->getStartDate(),
      'deadline_date' => $campaign->getDeadlineDate(),
      'expected_revenue' => $campaign->getExpectedRevenue(),
      'volunteers_accepted' => $campaign->getVolunteersAccepted(),
      'volunteers_description' => $campaign->getVolunteersDescription(TRUE),
      'project_street' => $campaign->getProjectStreet(),
      'project_street2' => $campaign->getProjectStreet2(),
      'project_city' => $campaign->getProjectCity(),
      'project_state' => $campaign->getProjectState(),
      'project_zip' => $campaign->getProjectZip(),
      'project_borough' => $campaign->getProjectBorough(),
      'created' => $campaign->getCreated(),
      'changed' => $campaign->getChanged(),
      'is_new' => 1,
    );

    if ($campaign->getSalesforceRecordId() != NULL) {
      $insert_fields['salesforce_record_id'] = $campaign->getSalesforceRecordId();
    }

    try {
      db_insert('ioby_sf_campaigns')
        ->fields($insert_fields)
        ->execute();
    }
    catch (\Exception $e) {
      throw new \Exception("There was a problem inserting the campaign", 0, $e);
    }
  }

  private function updateCampaign(Campaign $campaign) {
    $changes = $this->getProjectChanges($campaign);
    if (!empty($changes)) {
      try {
        db_update('ioby_sf_campaigns')
          ->fields($changes)
          ->condition('project_nid', $campaign->getProjectNid(), '=')
          ->execute();
      }
      catch (\Exception $e) {
        throw new \Exception("There was a problem updating the campaign.", 0, $e);
      }
    }
  }

  /**
   * Inserts an account record if the account does not already exist using name,
   * city, and state as the match criteria.
   *
   * @param Account $account
   *    The account to insert.
   * @param int $project_nid
   *    The nid of the project that this account is associated to.
   *
   * @return int The account_id of the inserted record.
   * @throws \Exception
   */
  private function insertAccount(Account $account, $project_nid) {
    $account_id = NULL;

    // Check to see if account exists first
    $account_query = db_select('ioby_sf_accounts', 'a')
      ->fields('a', array())
      ->condition('account_name', $account->getAccountName(), '=');

    if ($account->getBillingCity() != NULL) {
      $account_query->condition('billing_city', $account->getBillingCity(), '=');
    }

    if ($account->getBillingState() != NULL) {
      $account_query->condition('billing_state', $account->getBillingState(), '=');
    }

    $account_query->range(0, 1);

    try {
      $existing_account = $account_query->execute()->fetchAssoc();
    }
    catch (\Exception $e) {
      throw $e;
    }

    if ($existing_account) {
      $account_id = $existing_account['account_id'];
    }
    else {
      try {
        $account_id = db_insert('ioby_sf_accounts')
          ->fields(
            array(
              'account_name' => $account->getAccountName(),
              'billing_street' => $account->getBillingStreet(),
              'billing_city' => $account->getBillingCity(),
              'billing_state' => $account->getBillingState(),
              'billing_zip' => $account->getBillingZip(),
              'billing_country' => $account->getBillingCountry(),
              'created' => $account->getCreated(),
              'changed' => $account->getChanged(),
            )
          )->execute();
      }
      catch (\Exception $e) {
        throw new \Exception("There was a problem inserting the account.", 0, $e);
      }
    }

    try {
      // Use merge query so that we don't have to check if the record exists first
      db_merge('ioby_sf_project_participations')
        ->key(
          array(
            'account_id' => $account_id,
            'project_nid' => $project_nid,
          )
        )->execute();
    }
    catch (\Exception $e) {
      throw new \Exception("There was a problem inserting the account campaign link.", 0, $e);
    }

    return $account_id;
  }

  /**
   * Inserts the campaign contact if it does not already exist, otherwise it just
   * updates a subset of the fields and flags that the contact will need to be
   * updated in the next Salesforce push.
   *
   * @param Contact $contact
   *    The contact to merge.
   * @param int $project_nid
   *    The nid of the project that this contact is associated with.
   *
   * @throws \Exception
   */
  private function mergeContact(Contact $contact, $project_nid) {
    try {
      db_merge('ioby_sf_contacts')
        ->key(array('email' => $contact->getEmail()))
        ->insertFields(array(
            'email' => $contact->getEmail(),
            'uid' => $contact->getUid(),  // Not sure if we need this or not
            'first_name' => $contact->getFirstName(),
            'last_name' => $contact->getLastName(),
            'alternate_email' => $contact->getAlternateEmail(),
            'phone' => $contact->getPhone(),
            'other_phone' => $contact->getOtherPhone(),
            'mailing_street' => $contact->getMailingStreet(),
            'mailing_city' => $contact->getMailingCity(),
            'mailing_state' => $contact->getMailingState(),
            'mailing_zip' => $contact->getMailingZip(),
            'mailing_country' => $contact->getMailingCountry(),
            'other_street' => $contact->getOtherStreet(),
            'other_city' => $contact->getOtherCity(),
            'other_state' => $contact->getOtherState(),
            'other_zip' => $contact->getOtherZip(),
            'other_country' => $contact->getOtherCountry(),
            'birth_date' => $contact->getBirthDate(),
            'update_mailing_address' => intval(TRUE),
            'update_other_address' => intval(TRUE),
            'created' => REQUEST_TIME,
            'changed' => REQUEST_TIME,
          ))
        ->updateFields(array(
          'first_name' => $contact->getFirstName(),
          'last_name' => $contact->getLastName(),
          'phone' => $contact->getPhone(),
          'other_phone' => $contact->getOtherPhone(),
          'other_street' => $contact->getOtherStreet(),
          'other_city' => $contact->getOtherCity(),
          'other_state' => $contact->getOtherState(),
          'other_zip' => $contact->getOtherZip(),
          'other_country' => $contact->getOtherCountry(),
          'alternate_email' => $contact->getAlternateEmail(),
          'birth_date' => $contact->getBirthDate(),
          'changed' => REQUEST_TIME,
          'needs_update' => intval(TRUE),
          'update_other_address' => intval(TRUE), // For updates flag that only the other address fields should be updated in salesforce
        ))
        ->execute();
    }
    catch (\Exception $e) {
      throw new \Exception("There was a problem inserting the contact record.", 0, $e);
    }

    // Link the contact to the campaign
    try {
      db_merge('ioby_sf_campaign_members')
        ->key(
          array(
            'email' => $contact->getEmail(),
            'project_nid' => $project_nid,
          )
        )
        ->fields(
          array(
            'project_leader' => intval(TRUE),
            'needs_update' => intval(TRUE),
          )
        )->execute();
    }
    catch (\Exception $e) {
      throw new \Exception("There was a problem inserting the contact campaign link record.", 0, $e);
    }
  }

  /**
   * @param string $email
   *    The email address of the project organizer contact.
   * @param int $account_id
   *    The account_id of the project organization.
   *
   * @throws \Exception
   */
  private function insertAffiliation($email, $account_id) {
    try {
      db_merge('ioby_sf_affiliations')
        ->key(
          array(
            'email' => $email,
            'account_id' => $account_id,
          )
        )->execute();
    }
    catch (\Exception $e) {
      throw new \Exception("There was a problem creating the affiliation.", 0, $e);
    }
  }
}
