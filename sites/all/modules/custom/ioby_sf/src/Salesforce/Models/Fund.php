<?php

namespace Drupal\ioby_sf\Salesforce\Models;

class Fund extends SalesforceObjectBase
{
  const TYPE_MATCHING_FUND = 'Matching Fund';
  const TYPE_CATEGORY = 'Category';

  /**
   * @var int
   */
  protected $campaign_nid;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var int
   */
  protected $sponsor_nid;

  /**
   * @var string
   */
  protected $account_sf_id;

  /**
   * @var int
   */
  protected $total_value;

  /**
   * @var string
   */
  protected $description;

  /**
   * @var string
   */
  protected $fund_type = self::TYPE_CATEGORY;

  /**
   * @var int[]
   */
  protected $projects = array();

  /**
   * @param int $campaign_nid
   */
  public function setCampaignNid($campaign_nid) {
    $this->campaign_nid = $campaign_nid;
    return $this;
  }

  /**
   * @return int
   */
  public function getCampaignNid() {
    return $this->campaign_nid;
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
   * @return string
   */
  public function getFundType() {
    return $this->fund_type;
  }

  /**
   * @param string $description
   */
  public function setDescription($description) {
    $this->description = isset($description) ? trim($description) : $description;
    return $this;
  }

  /**
   * @return string
   */
  public function getDescription() {
    return $this->description;
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

  /**
   * @param string $account_sf_id
   *
   * @return $this
   */
  public function setAccountSfId($account_sf_id) {
    $this->account_sf_id = isset($account_sf_id) ? trim($account_sf_id) : $account_sf_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getAccountSfId() {
    return $this->account_sf_id;
  }

  /**
   * @param int $total_value
   */
  public function setTotalValue($total_value) {
    $this->total_value = $total_value;
    $this->fund_type = ($total_value > 0) ? self::TYPE_MATCHING_FUND : self::TYPE_CATEGORY;

    return $this;
  }

  /**
   * @return int
   */
  public function getTotalValue() {
    return $this->total_value;
  }

  /**
   * @param \int[] $projects
   */
  public function setProjects($projects) {
    $this->projects = $projects;
    return $this;
  }

  /**
   * @return \int[]
   */
  public function getProjects() {
    return $this->projects;
  }
}
