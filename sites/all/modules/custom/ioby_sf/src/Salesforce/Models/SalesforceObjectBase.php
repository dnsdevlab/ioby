<?php

namespace Drupal\ioby_sf\Salesforce\Models;


abstract class SalesforceObjectBase
{
  /**
   * @var string
   */
  protected $salesforce_record_id;

  /**
   * @var int
   */
  protected $created;

  /**
   * @var int
   */
  protected $changed;

  /**
   * @var bool
   */
  protected $needs_update;

  /**
   * @param string $salesforce_record_id
   */
  public function setSalesforceRecordId($salesforce_record_id) {
    $this->salesforce_record_id = isset($salesforce_record_id) ? trim($salesforce_record_id) : $salesforce_record_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getSalesforceRecordId() {
    return $this->salesforce_record_id;
  }

  /**
   * @param int $created
   */
  public function setCreated($created) {
    $this->created = $created;
    return $this;
  }

  /**
   * @return int
   */
  public function getCreated() {
    return $this->created;
  }

  /**
   * @param int $changed
   */
  public function setChanged($changed) {
    $this->changed = $changed;
    return $this;
  }

  /**
   * @return int
   */
  public function getChanged() {
    return $this->changed;
  }

  /**
   * @param boolean $needs_update
   */
  public function setNeedsUpdate($needs_update) {
    $this->needs_update = $needs_update;
    return $this;
  }

  /**
   * @return boolean
   */
  public function getNeedsUpdate() {
    return $this->needs_update;
  }
}
