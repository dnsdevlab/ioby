<?php

namespace Drupal\ioby_sf\Salesforce\Models;

class Account extends SalesforceObjectBase
{
  /**
   * @var int
   */
  protected $account_id;

  /**
   * @var string
   */
  protected $account_name;

  /**
   * @var string
   */
  protected $billing_street;

  /**
   * @var string
   */
  protected $billing_city;

  /**
   * @var string
   */
  protected $billing_state;

  /**
   * @var string
   */
  protected $billing_zip;

  /**
   * @var string
   */
  protected $billing_country;

  /**
   * @var int
   */
  protected $sponsor_nid;

  /**
   * @param int $account_id
   */
  public function setAccountId($account_id) {
    $this->account_id = $account_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getAccountId() {
    return $this->account_id;
  }

  /**
   * @param string $account_name
   */
  public function setAccountName($account_name) {
    $this->account_name = isset($account_name) ? trim($account_name) : $account_name;
    return $this;
  }

  /**
   * @return string
   */
  public function getAccountName() {
    return $this->account_name;
  }

  /**
   * @param string $billing_city
   */
  public function setBillingCity($billing_city) {
    $this->billing_city = isset($billing_city) ? trim($billing_city) : $billing_city;
    return $this;
  }

  /**
   * @return string
   */
  public function getBillingCity() {
    return $this->billing_city;
  }

  /**
   * @param string $billing_country
   */
  public function setBillingCountry($billing_country) {
    $this->billing_country = isset($billing_country) ? trim($billing_country) : $billing_country;
    return $this;
  }

  /**
   * @return string
   */
  public function getBillingCountry() {
    return $this->billing_country;
  }

  /**
   * @param string $billing_state
   */
  public function setBillingState($billing_state) {
    $this->billing_state = isset($billing_state) ? trim($billing_state) : $billing_state;
    return $this;
  }

  /**
   * @return string
   */
  public function getBillingState() {
    return $this->billing_state;
  }

  /**
   * @param string $billing_street
   */
  public function setBillingStreet($billing_street) {
    $this->billing_street = isset($billing_street) ? trim($billing_street) : $billing_street;
    return $this;
  }

  /**
   * @return string
   */
  public function getBillingStreet() {
    return $this->billing_street;
  }

  /**
   * @param string $billing_zip
   */
  public function setBillingZip($billing_zip) {
    $this->billing_zip = isset($billing_zip) ? trim($billing_zip) : $billing_zip;
    return $this;
  }

  /**
   * @param int $length
   *
   * @return string
   */
  public function getBillingZip($length = NULL) {
    if ($length) {
      return drupal_substr($this->billing_zip, 0, $length);
    }
    return $this->billing_zip;
  }

  /**
   * @param int $sponsor_nid
   */
  public function setSponsorNid($sponsor_nid) {
    $this->sponsor_nid = $sponsor_nid;
    return $this;
  }

  /**
   * @return int
   */
  public function getSponsorNid() {
    return $this->sponsor_nid;
  }
}
