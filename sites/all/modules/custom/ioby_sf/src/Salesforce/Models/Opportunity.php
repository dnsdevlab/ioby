<?php

namespace Drupal\ioby_sf\Salesforce\Models;

class Opportunity extends SalesforceObjectBase
{
  const STAGE_POSTED = 'Posted';
  const STAGE_REFUNDED = 'Refunded';

  const TYPE_DONATION = 'Project Donation';
  const TYPE_GRATUITY = 'Gratuity';
  const TYPE_MATCH = 'Fund Donation';
  const TYPE_COUPON = 'Coupon Redemption';

  /**
   * @var int
   */
  protected $opportunity_id;

  /**
   * @var int
   */
  protected $nid;

  /**
   * @var string
   */
  protected $name;

  /**
   * @var string
   */
  protected $description;

  /**
   * @var string
   */
  protected $entry_type;

  /**
   * @var string
   */
  protected $opportunity_type;

  /**
   * @var int
   */
  protected $project_nid;

  /**
   * @var string
   */
  protected $campaign_sf_id;

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
  protected $order_id;

  /**
   * @var int
   */
  protected $line_item_id;

  /**
   * @var int
   */
  protected $amount;

  /**
   * @var int
   */
  protected $closed_date;

  /**
   * @var string
   */
  protected $stage;

  /**
   * @var int
   */
  protected $campaign_nid;

  /**
   * @var string
   */
  protected $fund_sf_id;

  /**
   * @var string
   */
  protected $contact_email;

  /**
   * @var string
   */
  protected $contact_sf_id;

  /**
   * @var Contact
   */
  protected $contact;

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
   * @param int $opportunity_id
   */
  public function setOpportunityId($opportunity_id) {
    $this->opportunity_id = $opportunity_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getOpportunityId() {
    return $this->opportunity_id;
  }

  /**
   * @param int $nid
   */
  public function setNid($nid) {
    $this->nid = $nid;
    return $this;
  }

  /**
   * @return int
   */
  public function getNid() {
    return $this->nid;
  }

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
   * @param int $sponsor_nid
   *
   * @return $this
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
   * @param int $amount
   */
  public function setAmount($amount) {
    $this->amount = $amount;
    return $this;
  }

  /**
   * @return int
   */
  public function getAmount() {
    return $this->amount;
  }

  /**
   * @param int $closed_date
   */
  public function setClosedDate($closed_date) {
    $this->closed_date = $closed_date;
    return $this;
  }

  /**
   * @return int
   */
  public function getClosedDate() {
    return $this->closed_date;
  }

  /**
   * @param string $entry_type
   */
  public function setEntryType($entry_type) {
    $this->entry_type = $entry_type;
    return $this;
  }

  /**
   * @return string
   */
  public function getEntryType() {
    return $this->entry_type;
  }

  /**
   * @param string $opportunity_type
   */
  public function setOpportunityType($opportunity_type) {
    $this->opportunity_type = $opportunity_type;
    return $this;
  }

  /**
   * @return string
   */
  public function getOpportunityType() {
    return $this->opportunity_type;
  }

  /**
   * @param string $contact_email
   */
  public function setContactEmail($contact_email) {
    $this->contact_email = isset($contact_email) ? drupal_strtolower(trim($contact_email)) : $contact_email;
    return $this;
  }

  /**
   * @return string
   */
  public function getContactEmail() {
    return $this->contact_email;
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
   * @param string $stage
   */
  public function setStage($stage) {
    $this->stage = $stage;
    return $this;
  }

  /**
   * @return string
   */
  public function getStage() {
    return $this->stage;
  }

  /**
   * @param int $line_item_id
   */
  public function setLineItemId($line_item_id) {
    $this->line_item_id = $line_item_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getLineItemId() {
    return $this->line_item_id;
  }

  /**
   * @param int $order_id
   */
  public function setOrderId($order_id) {
    $this->order_id = $order_id;
    return $this;
  }

  /**
   * @return int
   */
  public function getOrderId() {
    return $this->order_id;
  }

  /**
   * @param Contact $contact
   *
   * @return $this
   */
  public function setContact($contact) {
    $this->contact = $contact;
    return $this;
  }

  /**
   * @return Contact
   */
  public function getContact() {
    return $this->contact;
  }

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
}
