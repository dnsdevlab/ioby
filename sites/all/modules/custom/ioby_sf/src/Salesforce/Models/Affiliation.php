<?php

namespace Drupal\ioby_sf\Salesforce\Models;


class Affiliation
{
  /**
   * @var int
   */
  protected $affiliation_id;

  /**
   * @var string
   */
  protected $email;

  /**
   * @var string
   */
  protected $contact_sf_id;

  /**
   * @var int
   */
  protected $account_id;

  /**
   * @var string
   */
  protected $account_sf_id;

  /**
   * @var string
   */
  protected $salesforce_record_id;

  /**
   * @param int $affiliation_id
   *
   * @return $this
   */
  public function setAffiliationId($affiliation_id) {
    $this->affiliation_id = $affiliation_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getAffiliationId() {
    return $this->affiliation_id;
  }

  /**
   * @param string $email
   *
   * @return $this
   */
  public function setEmail($email) {
    $this->email = $email;
    return $this;
  }

  /**
   * @return string
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @param string $contact_sf_id
   *
   * @return $this
   */
  public function setContactSfId($contact_sf_id) {
    $this->contact_sf_id = $contact_sf_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getContactSfId() {
    return $this->contact_sf_id;
  }

  /**
   * @param int $account_id
   *
   * @return $this
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
   * @param string $account_sf_id
   *
   * @return $this
   */
  public function setAccountSfId($account_sf_id) {
    $this->account_sf_id = $account_sf_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getAccountSfId() {
    return $this->account_sf_id;
  }

  /**
   * @param string $salesforce_record_id
   *
   * @return $this
   */
  public function setSalesforceRecordId($salesforce_record_id) {
    $this->salesforce_record_id = $salesforce_record_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getSalesforceRecordId() {
    return $this->salesforce_record_id;
  }
}
