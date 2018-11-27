<?php

namespace Drupal\ioby_sf\Salesforce\Models;

/**
 * This represents an OrderLineItem in Salesforce, which is a bridge between a
 * line item (an Opportunity) and a Commerce order (an Order).
 *
 * Class OrderLineItem
 * @package Drupal\ioby_sf\Salesforce\Models
 */
class OrderLineItem extends SalesforceObjectBase {

  /**
   * This is the Salesforce ID of the Opportunity (line item, in Drupal) that
   * this OrderLineItem represents.
   *
   * @var string
   */
  protected $opportunity_salesforce_record_id;

  /**
   * This is the ID of the Drupal Commerce order this line item belongs to.
   *
   * @var string
   */
  protected $commerce_order_id;

  /**
   * This is the ID of the line item in Drupal that this OrderLineItem
   * represents.
   *
   * @var string
   */
  protected $line_item_id;

  /**
   * The donation amount.
   *
   * @var string
   */
  protected $amount;

  /**
   * The type of donation (Project Donation, or Gratuity).
   *
   * @var string
   */
  protected $opportunity_type;

  /**
   * @param $opportunity_salesforce_record_id
   * @return $this
   */
  public function setOpportunitySalesforceRecordId($opportunity_salesforce_record_id) {
    $this->opportunity_salesforce_record_id = $opportunity_salesforce_record_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getOpportunitySalesforceRecordId() {
    return $this->opportunity_salesforce_record_id;
  }

  /**
   * @param $commerce_order_id
   * @return $this
   */
  public function setCommerceOrderId($commerce_order_id) {
    $this->commerce_order_id = $commerce_order_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getCommerceOrderId() {
    return $this->commerce_order_id;
  }

  /**
   * @param $line_item_id
   * @return $this
   */
  public function setLineItemId($line_item_id) {
    $this->line_item_id = $line_item_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getLineItemId() {
    return $this->line_item_id;
  }

  /**
   * @param $amount
   * @return $this
   */
  public function setAmount($amount) {
    $this->amount = $amount;
    return $this;
  }

  /**
   * @return string
   */
  public function getAmount() {
    return $this->amount;
  }

  /**
   * @param string $opportunity_type
   */
  public function setOpportunityType($opportunity_type) {
    $this->opportunity_type = $opportunity_type;
    return $this;
  }

  /**
   * @return string
   */
  public function getOpportunityType() {
    return $this->opportunity_type;
  }
}
