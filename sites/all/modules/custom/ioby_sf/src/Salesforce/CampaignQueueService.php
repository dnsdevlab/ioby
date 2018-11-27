<?php

namespace Drupal\ioby_sf\Salesforce;

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

    $this->insertCampaign($campaign);

    $contact_data = $this->repository->getContactForProject(node_load($campaign->getProjectNid()));
    $this->mergeContact($contact_data, $campaign->getProjectNid());
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
}
