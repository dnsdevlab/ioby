<?php

namespace Drupal\ioby_sf\Salesforce\Models;


class FundCampaignGrouping
{
  /**
   * @var int
   */
  protected $fund_grouping_id;

  /**
   * @var int
   */
  protected $campaign_nid;

  /**
   * @var string
   */
  protected $campaign_sf_id;

  /**
   * @var int
   */
  protected $project_nid;

  /**
   * @var string
   */
  protected $fund_sf_id;

  /**
   * @var string
   */
  protected $salesforce_record_id;

  /**
   * @param int $fund_grouping_id
   *
   * @return $this
   */
  public function setFundGroupingId($fund_grouping_id) {
    $this->fund_grouping_id = $fund_grouping_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getFundGroupingId() {
    return $this->fund_grouping_id;
  }

  /**
   * @param int $campaign_nid
   *
   * @return $this
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
   * @param string $campaign_sf_id
   *
   * @return $this
   */
  public function setCampaignSfId($campaign_sf_id) {
    $this->campaign_sf_id = $campaign_sf_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getCampaignSfId() {
    return $this->campaign_sf_id;
  }

  /**
   * @param string $fund_sf_id
   *
   * @return $this
   */
  public function setFundSfId($fund_sf_id) {
    $this->fund_sf_id = $fund_sf_id;
    return $this;
  }

  /**
   * @return string
   */
  public function getFundSfId() {
    return $this->fund_sf_id;
  }

  /**
   * @param int $project_nid
   *
   * @return $this
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
