<?php

namespace Drupal\ioby_sf\Salesforce\Models;

class Campaign extends SalesforceObjectBase
{
  /**
   * @var int
   */
  protected $project_nid;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $project_street;

  /**
   * @var string
   */
  protected $project_street2;

  /**
   * @var string
   */
  protected $project_city;

  /**
   * @var string
   */
  protected $project_state;

  /**
   * @var string
   */
  protected $project_zip;

  /**
   * @var string
   */
  protected $status;

  /**
   * @var string
   */
  protected $start_date;

  /**
   * @var string
   */
  protected $deadline_date;

  /**
   * @var int
   */
  protected $expected_revenue;

  /**
   * @var bool
   */
  protected $volunteers_accepted;

  /**
   * @var string
   */
  protected $volunteers_description;

  /**
   * @var bool
   */
  protected $is_new = FALSE;

  public $status_map = array(
    0 => 'Submitted',
    1 => 'Open',
    2 => 'Underway',
    3 => 'Completed',
    4 => 'Canceled',
    5 => 'Funded',
  );

  /**
   * @param int $project_nid
   */
  public function setProjectNid($project_nid) {
    $this->project_nid = $project_nid;
    return $this;
  }

  /**
   * @return int
   */
  public function getProjectNid() {
    return $this->project_nid;
  }

  /**
   * @param string $name
   */
  public function setName($name) {
    $this->name = isset($name) ? trim($name) : $name;
    return $this;
  }

  /**
   * @param int $length
   *
   * @return string
   */
  public function getName($length = NULL) {
    if ($length) {
      return  drupal_substr($this->name, 0, $length);
    }
    return $this->name;
  }

  /**
   * @param string $project_street
   *
   * @return $this
   */
  public function setProjectStreet($project_street) {
    $this->project_street = isset($project_street) ? drupal_substr($project_street, 0, 150) : NULL;
    return $this;
  }

  /**
   * @return string
   */
  public function getProjectStreet() {
    return $this->project_street;
  }

  /**
   * @param string $project_street2
   *
   * @return $this
   */
  public function setProjectStreet2($project_street2) {
    $this->project_street2 = isset($project_street2) ? drupal_substr($project_street2, 0, 150) : NULL;
    return $this;
  }

  /**
   * @return string
   */
  public function getProjectStreet2() {
    return $this->project_street2;
  }

  /**
   * @param string $project_city
   *
   * @return $this
   */
  public function setProjectCity($project_city) {
    $this->project_city = isset($project_city) ? drupal_substr($project_city, 0, 120) : NULL;
    return $this;
  }

  /**
   * @return string
   */
  public function getProjectCity() {
    return $this->project_city;
  }

  /**
   * @param string $project_state
   *
   * @return $this
   */
  public function setProjectState($project_state) {
    $this->project_state = isset($project_state) ? drupal_substr($project_state, 0, 2) : NULL;
    return $this;
  }

  /**
   * @return string
   */
  public function getProjectState() {
    return $this->project_state;
  }

  /**
   * @param string $project_zip
   *
   * @return $this
   */
  public function setProjectZip($project_zip) {
    $this->project_zip = isset($project_zip) ? drupal_substr($project_zip, 0, 10) : NULL;
    return $this;
  }

  /**
   * @return string
   */
  public function getProjectZip() {
    return $this->project_zip;
  }

  /**
   * @param int $expected_revenue
   */
  public function setExpectedRevenue($expected_revenue) {
    $this->expected_revenue = $expected_revenue;
    return $this;
  }

  /**
   * @return int
   */
  public function getExpectedRevenue() {
    return $this->expected_revenue;
  }

  /**
   * @param string $status
   */
  public function setStatus($status) {
    $this->status = isset($status) ? trim($status) : $status;
    return $this;
  }

  /**
   * @return string
   */
  public function getStatus() {
    return $this->status;
  }

  /**
   * @param string $start_date
   */
  public function setStartDate($start_date) {
    $this->start_date = $start_date;
    return $this;
  }

  /**
   * @return string
   */
  public function getStartDate() {
    return $this->start_date;
  }

  /**
   * @param string $deadline_date
   */
  public function setDeadlineDate($deadline_date) {
    $this->deadline_date = !empty($deadline_date) ? $deadline_date : NULL;
    return $this;
  }

  /**
   * @return string
   */
  public function getDeadlineDate() {
    return $this->deadline_date;
  }

  /**
   * @param boolean $volunteers_accepted
   */
  public function setVolunteersAccepted($volunteers_accepted) {
    $this->volunteers_accepted = $volunteers_accepted;
    return $this;
  }

  /**
   * @return boolean
   */
  public function getVolunteersAccepted() {
    return $this->volunteers_accepted;
  }

  /**
   * @param string $volunteers_description
   */
  public function setVolunteersDescription($volunteers_description) {
    $this->volunteers_description = isset($volunteers_description) ? trim($volunteers_description) : $volunteers_description;
    return $this;
  }

  /**
   * @param bool $strip_tags
   *    If html should be stripped from the returned text.
   *
   * @return string
   */
  public function getVolunteersDescription($strip_tags = FALSE) {
    return ($strip_tags && !empty($this->volunteers_description)) ? strip_tags(preg_replace("/&#?[a-z0-9]{2,8};/i", "", $this->volunteers_description)) : $this->volunteers_description;
  }

  /**
   * @return boolean
   */
  public function isNew() {
    return $this->is_new;
  }

  /**
   * @param boolean $is_new
   *
   * @return $this
   */
  public function setIsNew($is_new) {
    $this->is_new = $is_new;
    return $this;
  }
}
