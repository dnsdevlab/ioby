<?php

namespace Drupal\ioby_sf\Salesforce\Models;

/**
 * Class Order
 *
 * Note: This is a custom Order object, not the built-in SF Order object.
 *
 * @package Drupal\ioby_sf\Salesforce\Models
 */
class Order extends SalesforceObjectBase {
  
  /**
   * This is the ID of the Drupal Commerce order this Order represents..
   *
   * @var string
   */
  protected $commerceOrderId;

  /**
   * @param string $commerceOrderId
   * @return $this
   */
  public function setCommerceOrderId($commerceOrderId) {
    $this->commerceOrderId = $commerceOrderId;
    return $this;
  }

  /**
   * @return string
   */
  public function getCommerceOrderId() {
    return $this->commerceOrderId;
  }
}
