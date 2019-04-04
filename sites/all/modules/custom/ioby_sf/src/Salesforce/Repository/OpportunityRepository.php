<?php

namespace Drupal\ioby_sf\Salesforce\Repository;

use Drupal\ioby_sf\Salesforce\Models\Account;
use Drupal\ioby_sf\Salesforce\Models\Contact;
use Drupal\ioby_sf\Salesforce\Models\ModelLoader;
use Drupal\ioby_sf\Salesforce\Models\Opportunity;

class OpportunityRepository
{
  /**
   * @return Opportunity[]
   */
  public function getNewManualOpportunities() {
    $query = db_select('node', 'n')
      ->fields('n', array('nid'));

    // Grab either donation nodes or donation match notes that have a complete status.
    $or = db_or()
      ->condition('n.type', IOBY_NODE_TYPE_DONATION, '=')
      ->condition(
        db_and()
          ->condition('n.type', IOBY_NODE_TYPE_DONATION_MATCH, '=')
          ->condition('s.field_donation_match_status_value', DONATION_MATCH_STATUS_COMPLETE, '=')
      );
    $query->condition($or)
      ->condition('et.field_entry_type_value', 'manual', '=')
      ->isNull('o.nid')
      ->orderBy('n.nid');
    $query->leftJoin('ioby_sf_opportunities', 'o', 'n.nid = o.nid');
    $query->leftJoin('field_data_field_donation_match_status', 's',
      'n.nid = s.entity_id AND (s.entity_type = :entity_type AND s.deleted = 0)',
      array(':entity_type' => 'node'));
    $query->innerJoin('field_data_field_entry_type', 'et',
      'n.nid = et.entity_id AND (et.entity_type = :entity_type AND et.deleted = 0)',
      array(':entity_type' => 'node')
    );

    $nids = $query->execute()->fetchCol();

    if (empty($nids)) {
      return array();
    }

    $nodes = node_load_multiple($nids);
    $opportunities = array();

    foreach ($nodes as $node) {
      $opportunities[] = $this->getOpportunityFromNode($node);
    }

    return $opportunities;
  }

  /**
   * @return Opportunity[]
   */
  public function getNewOrderOpportunities() {
    $query = db_select('commerce_order', 'o')
      ->fields('o', array('order_id'))
      ->condition('o.created', IOBY_SF_ORDERS_START, '>=') // Data before this date is mostly broken
      ->condition('o.status', 'completed', '=')
      ->isNull('sfo.order_id')
      ->orderBy('o.order_id')
      ->range(0, 500);
    $query->leftJoin('ioby_sf_orders', 'sfo', 'o.order_id = sfo.order_id');

    $oids = $query->execute()->fetchCol();
    $orders = commerce_order_load_multiple($oids);

    $opportunities = array();
    foreach ($orders as $order) {
      $opportunities = array_merge($opportunities, $this->extractOpportunitiesFromOrder($order));
    }

    return $opportunities;
  }

  /**
   * @param \stdClass $node
   *
   * @return Opportunity
   * @throws \InvalidArgumentException
   */
  public function getOpportunityFromNode($node) {
    if (empty($node) || !in_array($node->type, array(IOBY_NODE_TYPE_DONATION, IOBY_NODE_TYPE_DONATION_MATCH), TRUE)) {
      throw new \InvalidArgumentException("The node that was passed in is not a donation or donation match.");
    }

    $opportunity = new Opportunity();
    $opportunity->setNid($node->nid)
      ->setName($node->title)
      ->setCreated(REQUEST_TIME)
      ->setChanged(REQUEST_TIME)
      ->setEntryType(isset($node->field_entry_type[LANGUAGE_NONE][0]['value']) ? $node->field_entry_type[LANGUAGE_NONE][0]['value'] : NULL)
      ->setClosedDate($node->created)
      ->setStage(Opportunity::STAGE_POSTED);

    if ($node->type == IOBY_NODE_TYPE_DONATION) {
      $opportunity->setDescription(isset($node->field_donation_info[LANGUAGE_NONE][0]['value']) ? $node->field_donation_info[LANGUAGE_NONE][0]['value'] : NULL)
        ->setAmount(isset($node->field_donation_amount[LANGUAGE_NONE][0]['value']) ? $node->field_donation_amount[LANGUAGE_NONE][0]['value'] : 0)
        ->setProjectNid(isset($node->field_donation_project[LANGUAGE_NONE][0]['nid']) ? $node->field_donation_project[LANGUAGE_NONE][0]['nid'] : NULL)
        ->setLineItemId(isset($node->field_donation_line_item[LANGUAGE_NONE][0]['line_item_id']) ? $node->field_donation_line_item[LANGUAGE_NONE][0]['line_item_id'] : NULL)
        ->setOpportunityType(Opportunity::TYPE_DONATION)
        ->setContactEmail(isset($node->field_donation_user[LANGUAGE_NONE][0]['uid']) ? $this->getEmailFromUid($node->field_donation_user[LANGUAGE_NONE][0]['uid']) : NULL)
        ->setOrderId(isset($node->field_donation_line_item[LANGUAGE_NONE][0]['line_item_id']) ? $this->getOrderIdFromLineItem($node->field_donation_line_item[LANGUAGE_NONE][0]['line_item_id']) : NULL);
    }
    else {
      $opportunity->setAmount(isset($node->field_donation_match_amount[LANGUAGE_NONE][0]['value']) ? $node->field_donation_match_amount[LANGUAGE_NONE][0]['value'] : 0)
        ->setProjectNid(isset($node->field_donation_match_project[LANGUAGE_NONE][0]['nid']) ? $node->field_donation_match_project[LANGUAGE_NONE][0]['nid'] : NULL)
        ->setLineItemId(isset($node->field_donation_match_line_item[LANGUAGE_NONE][0]['line_item_id']) ? $node->field_donation_match_line_item[LANGUAGE_NONE][0]['line_item_id'] : NULL)
        ->setOpportunityType(Opportunity::TYPE_MATCH)
        ->setOrderId(isset($node->field_donation_match_line_item[LANGUAGE_NONE][0]['line_item_id']) ? $this->getOrderIdFromLineItem($node->field_donation_match_line_item[LANGUAGE_NONE][0]['line_item_id']) : NULL)
        ->setCampaignNid(isset($node->field_donation_match_campaign[LANGUAGE_NONE][0]['nid']) ? $node->field_donation_match_campaign[LANGUAGE_NONE][0]['nid'] : NULL)
        ->setSponsorNid($this->getCampaignSponsorNid($opportunity->getCampaignNid()));
    }

    return $opportunity;
  }

  /**
   * @param int $uid
   *
   * @return null
   */
  private function getEmailFromUid($uid) {
    $email = db_select('users', 'u')->fields('u', array('mail'))->condition('uid', $uid, '=')->execute()->fetchField();
    return ($email) ? $email : NULL;
  }

  /**
   * @param int $line_item_id
   *
   * @return null
   */
  private function getOrderIdFromLineItem($line_item_id) {
    $order_id = db_select('commerce_line_item', 'li')->fields('li', array('order_id'))->condition('line_item_id', $line_item_id, NULL)->execute()->fetchField();
    return ($order_id) ? $order_id : NULL;
  }

  private function extractOpportunitiesFromOrder($order) {
    /**
     * @var $donation_opportunities Opportunity[]
     */
    $donation_opportunities = array();

    /**
     * @var $match_opportunities Opportunity[]
     */
    $match_opportunities = array();

    /**
     * @var $gratuity_opportunities Opportunity[]
     */
    $gratuity_opportunities = array();

    /**
     * @var $coupon_opportunities Opportunity[]
     */
    $coupon_opportunities = array();

    $commerce_line_items = $order->commerce_line_items[LANGUAGE_NONE];
    $order_total = commerce_currency_amount_to_decimal($order->commerce_order_total[LANGUAGE_NONE][0]['amount'], $order->commerce_order_total[LANGUAGE_NONE][0]['currency_code']);

    foreach ($commerce_line_items as $li) {
      $line_item = commerce_line_item_load($li['line_item_id']);
      if (!$line_item) {
        continue;
      }

      $opportunity = new Opportunity();
      $opportunity->setOrderId($order->order_id)
        ->setLineItemId($line_item->line_item_id)
        ->setName($line_item->line_item_label)
        ->setClosedDate($order->created)
        ->setContactEmail($order->mail)
        ->setContact($this->getContactFromOrder($order))
        ->setStage(Opportunity::STAGE_POSTED)
        ->setAmount(abs(commerce_currency_amount_to_decimal($line_item->commerce_unit_price[LANGUAGE_NONE][0]['amount'], $line_item->commerce_unit_price[LANGUAGE_NONE][0]['currency_code'])))
        ->setCreated(REQUEST_TIME)
        ->setChanged(REQUEST_TIME);

      if ($line_item->type == 'commerce_coupon') {
        $opportunity->setOpportunityType(Opportunity::TYPE_COUPON);
        $opportunity->setSponsorNid($this->getCouponSponsorNidForLineItem($line_item));
        $coupon = $opportunity;
      }
      else {
        if ($opportunity->getAmount() == 0) {
          // If the opportunity amount is 0, skip it
          continue;
        }

        if (substr($line_item->line_item_label, 0, 7) == 'project') { //This is a project donation line item
          $opportunity->setOpportunityType(Opportunity::TYPE_DONATION);
          $opportunity->setEntryType('website');
          if ($donation_node = $this->getDonationNodeFromLineItem($line_item->line_item_id)) {
            $opportunity->setNid($donation_node->nid);
            $opportunity->setDescription(isset($donation_node->field_donation_info[LANGUAGE_NONE][0]['value']) ? $donation_node->field_donation_info[LANGUAGE_NONE][0]['value'] : NULL);
          }
          $opportunity->setProjectNid($this->getProjectFromProduct($line_item->commerce_product[LANGUAGE_NONE][0]['product_id']));
          $donation_opportunities[] = $opportunity;

          if ($donation_matches = $this->getDonationMatchesFromLineItem($line_item->line_item_id)){
            foreach ($donation_matches as $donation_match) {
              $donation_match->setContactEmail($order->mail);
              $match_opportunities[] = $donation_match;
            }
          }
        }
        else { // This is a gratuity line item
          $opportunity->setOpportunityType(Opportunity::TYPE_GRATUITY);
          $gratuity_opportunities[] = $opportunity;
        }
      }
    }

    // If there is a coupon loop back through and make adjustments to the amounts
    if (isset($coupon)) {
      $coupon_total = $coupon->getAmount();

      if ($order_total < 0) { // If we have a negative order, reduce the coupon amount
        $coupon_total -= abs($order_total);

        // If we have a negative coupon amount set the coupon to 0
        if ($coupon_total < 0) {
          $coupon_total = 0;
        }

        $coupon->setAmount($coupon_total);
      }

      foreach ($donation_opportunities as $donation) {
        if ($donation->getAmount() >= $coupon_total) {
          $coupon_amount = $coupon_total;
          $donation->setAmount($donation->getAmount() - $coupon_total);
          $coupon_total = 0;
        }
        else {
          $coupon_amount = $donation->getAmount();
          $coupon_total -= $donation->getAmount();
          $donation->setAmount(0);
        }

        $new_coupon = clone $coupon;
        $new_coupon->setAmount($coupon_amount);
        $new_coupon->setProjectNid($donation->getProjectNid());
        $coupon_opportunities[] = $new_coupon;

        // exit if the coupon has been used up
        if ($coupon_total == 0) {
          break;
        }
      }

      /**
       * Coupons should not really be applied to gratuities and won't going
       * forward, but this is here to handle historical occurrences of this.
       */
      if ($coupon_total > 0 && count($gratuity_opportunities)) {
        // There shouldn't be more that one gratuity, but there are historical
        // instances of multiple gratuity line items due to past issues with the
        // code that manages gratuities.
        foreach ($gratuity_opportunities as $gratuity) {
          if ($gratuity->getAmount() >= $coupon_total) {
            $gratuity->setAmount($gratuity->getAmount() - $coupon_total);
            $coupon_total = 0;
          }
          else {
            $coupon_total -= $gratuity->getAmount();
            $gratuity->setAmount(0);
          }

          // exit if the coupon has been used up
          if ($coupon_total == 0) {
            break;
          }
        }
      }
    }

    // Get rid of Gratuities with $0
    $filtered_gratuity_opportunities = array();
    foreach ($gratuity_opportunities as $gratuity) {
      if ($gratuity->getAmount() > 0) {
        $filtered_gratuity_opportunities[] = $gratuity;
      }
    }

    $opportunities = array_merge($donation_opportunities, $match_opportunities, $filtered_gratuity_opportunities, $coupon_opportunities);

    return $opportunities;
  }

  /**
   * @param \stdClass $order
   *
   * @return Contact
   * @throws \Exception
   */
  private function getContactFromOrder($order) {
    if (empty($order->commerce_customer_billing[LANGUAGE_NONE][0]['profile_id'])) {
      throw new \Exception("There's no customer profile for order: {$order->order_id}");
    }

    $contact = new Contact();
    $contact->setCreated(REQUEST_TIME)
      ->setChanged(REQUEST_TIME);

    // Get the user's phone number if set
    $order_user = user_load($order->uid);
    if (!empty($order_user->field_data_field_user_phone[LANGUAGE_NONE][0]['value'])) {
      $contact->setPhone($order_user->field_data_field_user_phone[LANGUAGE_NONE][0]['value']);
    }

    // Get the user's billing information from the order
    $profile = commerce_customer_profile_load($order->commerce_customer_billing[LANGUAGE_NONE][0]['profile_id']);
    if ($profile) {
      $contact->setUid($profile->uid)
        ->setEmail($order->mail)
        ->setFirstName(isset($profile->commerce_customer_address[LANGUAGE_NONE][0]['first_name']) ? $profile->commerce_customer_address[LANGUAGE_NONE][0]['first_name'] : '')
        ->setLastName(isset($profile->commerce_customer_address[LANGUAGE_NONE][0]['last_name']) ? $profile->commerce_customer_address[LANGUAGE_NONE][0]['last_name'] : '')
        ->setMailingStreet((isset($profile->commerce_customer_address[LANGUAGE_NONE][0]['thoroughfare']) ? $profile->commerce_customer_address[LANGUAGE_NONE][0]['thoroughfare'] . (!empty($profile->commerce_customer_address[LANGUAGE_NONE][0]['premise']) ? "\n" . $profile->commerce_customer_address[LANGUAGE_NONE][0]['premise'] : '') : ''))
        ->setMailingCity(isset($profile->commerce_customer_address[LANGUAGE_NONE][0]['locality']) ? $profile->commerce_customer_address[LANGUAGE_NONE][0]['locality'] : '')
        ->setMailingState(isset($profile->commerce_customer_address[LANGUAGE_NONE][0]['administrative_area']) ? $profile->commerce_customer_address[LANGUAGE_NONE][0]['administrative_area'] : '')
        ->setMailingZip(isset($profile->commerce_customer_address[LANGUAGE_NONE][0]['postal_code']) ? $profile->commerce_customer_address[LANGUAGE_NONE][0]['postal_code'] : '')
        ->setMailingCountry(isset($profile->commerce_customer_address[LANGUAGE_NONE][0]['country']) ? $profile->commerce_customer_address[LANGUAGE_NONE][0]['country'] : '')
        ->setUpdateMailingAddress(TRUE);
    }

    return $contact;
  }

  /**
   * @param int $line_item_id
   *
   * @return Opportunity[]|bool
   */
  private function getDonationMatchesFromLineItem($line_item_id) {
    $query = db_select('node', 'n')
      ->fields('n', array('nid'))
      ->condition('n.type', IOBY_NODE_TYPE_DONATION_MATCH, '=')
      ->condition('s.field_donation_match_status_value', DONATION_MATCH_STATUS_COMPLETE, '=')
      ->condition('li.field_donation_match_line_item_line_item_id', $line_item_id, '=');

    $query->innerJoin('field_data_field_donation_match_status', 's',
      'n.nid = s.entity_id AND (s.entity_type = :entity_type AND s.deleted = 0)',
      array(':entity_type' => 'node'));

    $query->innerJoin('field_data_field_donation_match_line_item', 'li',
      'n.nid = li.entity_id AND (li.entity_type = :entity_type AND li.deleted = 0)',
      array(':entity_type' => 'node'));

    $nids = $query->execute()->fetchCol();
    if (empty($nids)) {
      return FALSE;
    }

    $nodes = node_load_multiple($nids);
    $donation_match_opportunities = array();
    foreach ($nodes as $node) {
      $donation_match_opportunities[] = $this->getOpportunityFromNode($node);
    }

    return $donation_match_opportunities;
  }

  /**
   * @param int $line_item_id
   *
   * @return bool|\stdClass
   *    Returns a loaded donation node or FALSE if not found.
   */
  private function getDonationNodeFromLineItem($line_item_id) {
    $query = db_select('node', 'n')
      ->fields('n', array('nid'))
      ->condition('n.type', IOBY_NODE_TYPE_DONATION, '=')
      ->condition('li.field_donation_line_item_line_item_id', $line_item_id, '=');

    $query->innerJoin('field_data_field_donation_line_item', 'li',
      'n.nid = li.entity_id AND (li.entity_type = :entity_type AND li.deleted = 0)',
      array(':entity_type' => 'node'));

    if ($nid = $query->execute()->fetchField()) {
      return node_load($nid);
    }

    return FALSE;
  }

  /**
   * @param int $product_id
   *
   * @return int
   * @throws \Exception
   */
  private function getProjectFromProduct($product_id) {
    $product = commerce_product_load($product_id);
    if ($product) {
      return $product->field_project_node[LANGUAGE_NONE][0]['nid'];
    }

    throw new \Exception("The product with product_id {$product_id} does not exist.");
  }

  private function getCouponSponsorNidForLineItem($line_item) {
    if (empty($line_item) || $line_item->type != 'commerce_coupon') {
      return NULL; // Probably don't need to throw an exception for this
//      throw new \InvalidArgumentException("The line item that was passed in is not a commerce_coupon.");
    }

    if (!empty($line_item->commerce_coupon_reference[LANGUAGE_NONE][0]['target_id'])) {
      if ($coupon = commerce_coupon_load($line_item->commerce_coupon_reference[LANGUAGE_NONE][0]['target_id'])) {
        if (!empty($coupon->field_campaign_sponsor[LANGUAGE_NONE][0]['nid'])) {
          return $coupon->field_campaign_sponsor[LANGUAGE_NONE][0]['nid'];
        }
      }
    }

    return NULL;
  }

  private function getCampaignSponsorNid($campaign_nid) {
    $campaign = node_load($campaign_nid);
    if ($campaign && (!empty($campaign->field_campaign_sponsor[LANGUAGE_NONE][0]['nid']))) {
      return $campaign->field_campaign_sponsor[LANGUAGE_NONE][0]['nid'];
    }

    return NULL;
  }

  /**
   * @return Opportunity[]
   */
  public function getNewManualSalesforceOpportunities() {
    $records = db_select('ioby_sf_opportunities', 'o')
      ->fields('o')
      ->isNull('o.nid')
      ->condition('entry_type', 'manual-salesforce')
      ->execute()->fetchAll();

    $opportunities = array();
    foreach ($records as $record) {
      $opportunities[] = ModelLoader::loadOpportunityRowData($record);
    }

    return $opportunities;
  }

  public function getAccountForOpportunity(Opportunity $opportunity) {
    if ($opportunity->getSponsorNid() == NULL) {
      return FALSE;
    }

    if ($sponsorship = node_load($opportunity->getSponsorNid())) {
      $account = new Account();
      $account->setSponsorNid($sponsorship->nid)
        ->setAccountName($sponsorship->title)
        ->setCreated(REQUEST_TIME)
        ->setChanged(REQUEST_TIME);

      return $account;
    }

    return FALSE;
  }
}
