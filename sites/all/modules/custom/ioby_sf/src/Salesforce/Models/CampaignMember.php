<?php

namespace Drupal\ioby_sf\Salesforce\Models;


class CampaignMember
{
  /**
   * @var int
   */
  protected $campaign_member_id;

  /**
   * @var string
   */
  protected $email;

  /**
   * @var int
   */
  protected $project_nid;

  /**
   * @var string
   */
  protected $salesforce_record_id;

  /**
   * @var bool
   */
  protected $project_leader;

  /**
   * @var bool
   */
  protected $project_donor;

  /**
   * @var bool
   */
  protected $needs_update;

  /**
   * @var string
   */
  protected $contact_sf_id;

  /**
   * @var string
   */
  protected $campaign_sf_id;

  /**
   * @param int $campaign_member_id
   *
   * @return $this
   */
  public function setCampaignMemberId($campaign_member_id) {
    $this->campaign_member_id = $campaign_member_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getCampaignMemberId() {
    return $this->campaign_member_id;
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
   * @return int
   */
  public function getCampaignSfId() {
    return $this->campaign_sf_id;
  }

  /**
   * @param bool $project_leader
   *
   * @return $this
   */
  public function setProjectLeader($project_leader) {
    $this->project_leader = $project_leader;
    return $this;
  }

  /**
   * @return bool
   */
  public function getProjectLeader() {
    return $this->project_leader;
  }

  /**
   * @param bool $project_donor
   *
   * @return $this
   */
  public function setProjectDonor($project_donor) {
    $this->project_donor = $project_donor;
    return $this;
  }

  /**
   * @return bool
   */
  public function getProjectDonor() {
    return $this->project_donor;
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

  /**
   * @param bool $needs_update
   *
   * @return $this
   */
  public function setNeedsUpdate($needs_update) {
    $this->needs_update = $needs_update;
    return $this;
  }

  /**
   * @return bool
   */
  public function getNeedsUpdate() {
    return $this->needs_update;
  }
}
