<?php

namespace Drupal\ioby_sf\Salesforce\Models;

class Contact extends SalesforceObjectBase
{
  /**
   * @var int
   */
  protected $uid;

  /**
   * @var string
   */
  protected $first_name;

  /**
   * @var string
   */
  protected $last_name;

  /**
   * @var string
   */
  protected $birth_date;

  /**
   * @var string
   */
  protected $mailing_street;

  /**
   * @var string
   */
  protected $mailing_city;

  /**
   * @var string
   */
  protected $mailing_state;

  /**
   * @var string
   */
  protected $mailing_zip;

  /**
   * @var string
   */
  protected $mailing_country;

  /**
   * @var string
   */
  protected $other_street;

  /**
   * @var string
   */
  protected $other_city;

  /**
   * @var string
   */
  protected $other_state;

  /**
   * @var string
   */
  protected $other_zip;

  /**
   * @var string
   */
  protected $other_country;

  /**
   * @var string
   */
  protected $email;

  /**
   * @var string
   */
  protected $alternate_email;

  /**
   * @var string
   */
  protected $phone;

  /**
   * @var string
   */
  protected $other_phone;

  /**
   * @var bool
   */
  protected $update_mailing_address;

  /**
   * @var bool
   */
  protected $update_other_address;


  /**
   * @param int $uid
   */
  public function setUid($uid) {
    $this->uid = $uid;
    return $this;
  }

  /**
   * @return int
   */
  public function getUid() {
    return $this->uid;
  }

  /**
   * @param string $first_name
   */
  public function setFirstName($first_name) {
    $this->first_name = isset($first_name) ? trim($first_name) : $first_name;
    return $this;
  }

  /**
   * @return string
   */
  public function getFirstName() {
    return $this->first_name;
  }

  /**
   * @param string $last_name
   */
  public function setLastName($last_name) {
    $this->last_name = isset($last_name) ? trim($last_name) : $last_name;
    return $this;
  }

  /**
   * @return string
   */
  public function getLastName() {
    return $this->last_name;
  }

  /**
   * @param string $birth_date
   *
   * @return $this
   */
  public function setBirthDate($birth_date) {
    $this->birth_date = $birth_date;
    return $this;
  }

  /**
   * @return string
   */
  public function getBirthDate() {
    return $this->birth_date;
  }

  /**
   * @param string $mailing_street
   */
  public function setMailingStreet($mailing_street) {
    $this->mailing_street = isset($mailing_street) ? trim($mailing_street) : $mailing_street;
    return $this;
  }

  /**
   * @return string
   */
  public function getMailingStreet() {
    return $this->mailing_street;
  }

  /**
   * @param string $mailing_city
   */
  public function setMailingCity($mailing_city) {
    $this->mailing_city = isset($mailing_city) ? trim($mailing_city) : $mailing_city;
    return $this;
  }

  /**
   * @return string
   */
  public function getMailingCity() {
    return $this->mailing_city;
  }

  /**
   * @param string $mailing_state
   */
  public function setMailingState($mailing_state) {
    $this->mailing_state = isset($mailing_state) ? trim($mailing_state) : $mailing_state;
    return $this;
  }

  /**
   * @return string
   */
  public function getMailingState() {
    return $this->mailing_state;
  }

  /**
   * @param string $mailing_zip
   */
  public function setMailingZip($mailing_zip) {
    $this->mailing_zip = isset($mailing_zip) ? trim($mailing_zip) : $mailing_zip;
    return $this;
  }

  /**
   * @param int $length
   *
   * @return string
   */
  public function getMailingZip($length = NULL) {
    if ($length) {
      return drupal_substr($this->mailing_zip, 0, $length);
    }
    return $this->mailing_zip;
  }

  /**
   * @param string $mailing_country
   */
  public function setMailingCountry($mailing_country) {
    $this->mailing_country = isset($mailing_country) ? trim($mailing_country) : $mailing_country;
    return $this;
  }

  /**
   * @return string
   */
  public function getMailingCountry() {
    return $this->mailing_country;
  }

  /**
   * @param string $other_street
   */
  public function setOtherStreet($other_street) {
    $this->other_street = isset($other_street) ? trim($other_street) : $other_street;
    return $this;
  }

  /**
   * @return string
   */
  public function getOtherStreet() {
    return $this->other_street;
  }

  /**
   * @param string $other_city
   */
  public function setOtherCity($other_city) {
    $this->other_city = isset($other_city) ? trim($other_city) : $other_city;
    return $this;
  }

  /**
   * @return string
   */
  public function getOtherCity() {
    return $this->other_city;
  }

  /**
   * @param string $other_state
   */
  public function setOtherState($other_state) {
    $this->other_state = isset($other_state) ? trim($other_state) : $other_state;
    return $this;
  }

  /**
   * @return string
   */
  public function getOtherState() {
    return $this->other_state;
  }

  /**
   * @param string $other_zip
   */
  public function setOtherZip($other_zip) {
    $this->other_zip = isset($other_zip) ? trim($other_zip) : $other_zip;
    return $this;
  }

  /**
   * @param int $length
   *
   * @return string
   */
  public function getOtherZip($length = NULL) {
    if ($length) {
      return drupal_substr($this->other_zip, 0, $length);
    }
    return $this->other_zip;
  }

  /**
   * @param string $other_country
   */
  public function setOtherCountry($other_country) {
    $this->other_country = isset($other_country) ? trim($other_country) : $other_country;
    return $this;
  }

  /**
   * @return string
   */
  public function getOtherCountry() {
    return $this->other_country;
  }

  /**
   * @param string $email
   */
  public function setEmail($email) {
    $this->email = isset($email) ? drupal_strtolower(trim($email)) : $email;
    return $this;
  }

  /**
   * @return string
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @param string $alternate_email
   */
  public function setAlternateEmail($alternate_email) {
    $this->alternate_email = isset($alternate_email) ? drupal_strtolower(trim($alternate_email)) : $alternate_email;
    return $this;
  }

  /**
   * @return string
   */
  public function getAlternateEmail() {
    return $this->alternate_email;
  }

  /**
   * @param string $phone
   */
  public function setPhone($phone) {
    $this->phone = isset($phone) ? trim($phone) : $phone;
    return $this;
  }

  /**
   * @param int $length
   *
   * @return string
   */
  public function getPhone($length = NULL) {
    if ($length) {
      return drupal_substr($this->phone, 0, $length);
    }
    return $this->phone;
  }

  /**
   * @param string $other_phone
   */
  public function setOtherPhone($other_phone) {
    $this->other_phone = isset($other_phone) ? trim($other_phone) : $other_phone;
    return $this;
  }

  /**
   * @param int $length
   *
   * @return string
   */
  public function getOtherPhone($length = NULL) {
    if ($length) {
      return drupal_substr($this->other_phone, 0, $length);
    }
    return $this->other_phone;
  }

  /**
   * @return string
   */
  public function getContactRecordType() {
    return $this->contact_record_type;
  }

  /**
   * @param boolean $update_mailing_address
   *
   * @return $this
   */
  public function setUpdateMailingAddress($update_mailing_address) {
    $this->update_mailing_address = $update_mailing_address;
    return $this;
  }

  /**
   * @return boolean
   */
  public function getUpdateMailingAddress() {
    return $this->update_mailing_address;
  }

  /**
   * @param boolean $update_other_address
   *
   * @return $this
   */
  public function setUpdateOtherAddress($update_other_address) {
    $this->update_other_address = $update_other_address;
    return $this;
  }

  /**
   * @return boolean
   */
  public function getUpdateOtherAddress() {
    return $this->update_other_address;
  }
}
