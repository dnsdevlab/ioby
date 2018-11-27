<?php

namespace Drupal\ioby_sf\Salesforce;


use Drupal\ioby_sf\Salesforce\Models\Opportunity;
use Drupal\ioby_sf\Salesforce\Repository\OpportunityRepository;

class OpportunityQueueService
{
  /**
   * @var OpportunityRepository
   */
  protected $repository;

  function __construct(OpportunityRepository $repository = NULL) {
    if (!isset($repository)) {
      $repository = new OpportunityRepository();
    }
    $this->repository = $repository;
  }

  public function queueOpportunities() {
    $manual_opportunities = $this->repository->getNewManualOpportunities();
    $order_opportunities = $this->repository->getNewOrderOpportunities();

    $opportunities = array_merge($manual_opportunities, $order_opportunities);

    foreach ($opportunities as $opportunity) {
      $this->populateOpportunityTables($opportunity);
    }
  }

  private function populateOpportunityTables(Opportunity $opportunity) {
    $contact = $opportunity->getContact();
    if (!empty($contact) && $opportunity->getOpportunityType() == Opportunity::TYPE_DONATION) {
      try {
        db_merge('ioby_sf_contacts')
          ->key(array('email' => $contact->getEmail()))
          ->insertFields(array(
            'email' => $contact->getEmail(),
            'uid' => $contact->getUid(), // Not sure if we need this or not
            'first_name' => $contact->getFirstName(),
            'last_name' => $contact->getLastName(),
            'mailing_street' => $contact->getMailingStreet(),
            'mailing_city' => $contact->getMailingCity(),
            'mailing_state' => $contact->getMailingState(),
            'mailing_zip' => $contact->getMailingZip(),
            'mailing_country' => $contact->getMailingCountry(),
            'needs_update' => intval(TRUE),
            'update_mailing_address' => intval(TRUE),
            'created' => REQUEST_TIME,
            'changed' => REQUEST_TIME,
          ))
          ->updateFields(array(
            'first_name' => $contact->getFirstName(),
            'last_name' => $contact->getLastName(),
            'mailing_street' => $contact->getMailingStreet(),
            'mailing_city' => $contact->getMailingCity(),
            'mailing_state' => $contact->getMailingState(),
            'mailing_zip' => $contact->getMailingZip(),
            'mailing_country' => $contact->getMailingCountry(),
            'alternate_email' => $contact->getAlternateEmail(),
            'changed' => REQUEST_TIME,
            'needs_update' => intval(TRUE),
            'update_mailing_address' => intval(TRUE),
          ))
          ->execute();
      }
      catch (\Exception $e) {
        $message = sprintf("There was a problem updating the opportunity contact with email: %s for the opportunity: %s",
          $contact->getEmail(),
          $opportunity->getName()
        );
        throw new \Exception($message, 0, $e);
      }

      if ($opportunity->getProjectNid() != NULL) {
        try {
          db_merge('ioby_sf_campaign_members')
            ->key(
              array(
                'email' => $contact->getEmail(),
                'project_nid' => $opportunity->getProjectNid()
              ))
            ->fields(
              array(
                'project_donor' => intval(TRUE),
                'needs_update' => intval(TRUE)
              ))->execute();
        }
        catch (\Exception $e) {
          $message = sprintf("There was a problem updating the campaign contact link with email: %s and project_nid: $%d for the opportunity: %s",
            $contact->getEmail(),
            $opportunity->getProjectNid(),
            $opportunity->getName()
          );
          throw new \Exception($message, 0, $e);
        }
      }
    }

    // If this is a coupon opportunity, make sure that the sponsor (account) record is created
    if ($opportunity->getOpportunityType() == Opportunity::TYPE_COUPON) {
      if ($account = $this->repository->getAccountForOpportunity($opportunity)) {
        try {
          db_merge('ioby_sf_accounts')
            ->key(array('sponsor_nid' => $account->getSponsorNid()))
            ->insertFields(
              array(
                'sponsor_nid' => $account->getSponsorNid(),
                'account_name' => $account->getAccountName(),
                'created' => REQUEST_TIME,
                'changed' => REQUEST_TIME
              ))
            ->updateFields(array('changed' => REQUEST_TIME))
            ->execute();
        }
        catch (\Exception $e) {
          $message = sprintf("There was a problem inserting the coupon sponsor account with sponsor_nid: %d and account_name: %s for coupon opportunity: %s",
            $account->getSponsorNid(),
            $account->getAccountName(),
            $opportunity->getName()
          );
          throw new \Exception($message, 0, $e);
        }
      }
    }

    try {
      db_insert('ioby_sf_opportunities')
        ->fields(
          array(
            'nid' => $opportunity->getNid(),
            'name' => $opportunity->getName(120),
            'description' => $opportunity->getDescription(),
            'entry_type' => $opportunity->getEntryType(),
            'opportunity_type' => $opportunity->getOpportunityType(),
            'project_nid' => $opportunity->getProjectNid(),
            'sponsor_nid' => $opportunity->getSponsorNid(),
            'order_id' => $opportunity->getOrderId(),
            'line_item_id' => $opportunity->getLineItemId(),
            'amount' => $opportunity->getAmount(),
            'closed_date' => $opportunity->getClosedDate(),
            'stage' => $opportunity->getStage(),
            'campaign_nid' => $opportunity->getCampaignNid(),
            'contact_email' => $opportunity->getContactEmail(),
            'created' => $opportunity->getCreated(),
            'changed' => $opportunity->getChanged(),
          )
        )->execute();

      if ($opportunity->getOrderId() != NULL) {
        // Track that we've processed opportunities for this order
        db_merge('ioby_sf_orders')
          ->key(array('order_id' => $opportunity->getOrderId()))->execute();
      }
    }
    catch (\Exception $e) {
      $message = sprintf("There was a problem inserting the opportunity with order_id: %d and line_item_id: %d.",
        $opportunity->getOrderId(),
        $opportunity->getLineItemId()
      );
      throw new \Exception($message, 0, $e);
    }
  }
}
