<?php

namespace Drupal\ioby_sf\Salesforce\Models;


class ProjectParticipation
{
  /**
   * @var int
   */
  protected $project_participation_id;

  /**
   * @var int
   */
  protected $account_id;

  /**
   * @var string
   */
  protected $account_sf_id;

  /**
   * @var int
   */
  protected $project_nid;

  /**
   * @var string
   */
  protected $campaign_sf_id;

  /**
   * @var string
   */
  protected $salesforce_record_id;

  /**
   * @param int $project_participation_id
   *
   * @return $this
   */
  public function setProjectParticipationId($project_participation_id) {
    $this->project_participation_id = $project_participation_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getProjectParticipationId() {
    return $this->project_participation_id;
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
