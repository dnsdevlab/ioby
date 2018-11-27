<?php

namespace Drupal\ioby_sf\Salesforce\Repository;


use Drupal\ioby_sf\Salesforce\Models\Account;
use Drupal\ioby_sf\Salesforce\Models\Fund;

class FundRepository
{
  /**
   * @return Fund[]
   */
  public function getNewFunds() {
    $query = db_select('node', 'n')
      ->fields('n', array('nid'))
      ->condition('n.type', IOBY_NODE_TYPE_CAMPAIGN, '=')
      ->isNull('f.campaign_nid')
      ->orderBy('n.nid');
    $query->leftJoin('ioby_sf_funds', 'f', 'n.nid = f.campaign_nid');

    $nids = $query->execute()->fetchCol();

    if (empty($nids)) {
      return array();
    }

    $campaigns = node_load_multiple($nids);
    $funds = array();

    foreach ($campaigns as $campaign) {
      $funds[] = $this->getFundFromNode($campaign);
    }

    return $funds;
  }

  /**
   * @param \stdClass $campaign_node
   *
   * @return Fund
   * @throws \InvalidArgumentException
   */
  public function getFundFromNode($campaign_node) {
    if (empty($campaign_node) || $campaign_node->type != IOBY_NODE_TYPE_CAMPAIGN) {
      throw new \InvalidArgumentException("The node that was passed in is not a campaign.");
    }

    $fund = new Fund();
    $fund->setCampaignNid($campaign_node->nid)
      ->setName($campaign_node->title)
      ->setSponsorNid(isset($campaign_node->field_campaign_sponsor[LANGUAGE_NONE][0]['nid']) ? $campaign_node->field_campaign_sponsor[LANGUAGE_NONE][0]['nid'] : NULL)
      ->setTotalValue(isset($campaign_node->field_campaign_amount[LANGUAGE_NONE][0]['value']) ? $campaign_node->field_campaign_amount[LANGUAGE_NONE][0]['value'] : 0)
      ->setCreated(REQUEST_TIME)
      ->setChanged(REQUEST_TIME);

    if (!empty($campaign_node->field_campaign_project[LANGUAGE_NONE])) {
      $projects = array();
      foreach ($campaign_node->field_campaign_project[LANGUAGE_NONE] as $project) {
        if (isset($project['nid']) && !in_array($project['nid'], $projects)) {
          $projects[] = $project['nid'];
        }
      }
      $fund->setProjects($projects);
    }

    return $fund;
  }

  /**
   * @return Fund[]
   */
  public function getModifiedFunds() {
    $query = db_select('node', 'n')
      ->fields('n', array('nid'))
      ->condition('n.type', IOBY_NODE_TYPE_CAMPAIGN, '=')
      ->where('n.changed > f.changed')
      ->orderBy('n.nid');
    $query->innerJoin('ioby_sf_funds', 'f', 'n.nid = f.campaign_nid');

    $nids = $query->execute()->fetchCol();

    if (empty($nids)) {
      return array();
    }

    $campaigns = node_load_multiple($nids);
    $funds = array();

    foreach ($campaigns as $campaign) {
      $funds[] = $this->getFundFromNode($campaign);
    }

    return $funds;
  }

  /**
   * @param int $campaign_nid
   *
   * @return bool|Fund
   */
  public function getFundFromTable($campaign_nid) {
    $fund_record = db_select('ioby_sf_funds', 'f')
      ->fields('f', array())
      ->condition('f.campaign_nid', $campaign_nid, '=')
      ->execute()->fetch();

    if (!$fund_record) {
      return FALSE;
    }

    $fund = new Fund();
    $fund->setCampaignNid($fund_record->campaign_nid)
      ->setName($fund_record->name)
      ->setSponsorNid($fund_record->sponsor_nid)
      ->setTotalValue($fund_record->total_value)
      ->setDescription($fund_record->description)
      ->setSalesforceRecordId($fund_record->salesforce_record_id)
      ->setCreated($fund_record->created)
      ->setChanged($fund_record->changed)
      ->setNeedsUpdate($fund_record->needs_update);

    $projects = db_select('ioby_sf_fund_groupings', 'fg')
      ->fields('fg', array('project_nid'))
      ->condition('fg.campaign_nid', $fund->getCampaignNid(), '=')
      ->execute()->fetchCol();

    $fund->setProjects($projects);

    return $fund;
  }

  /**
   * @param $campaign_nid
   *
   * @return int[]
   */
  public function getFundCampaignsFromTable($campaign_nid) {
    $project_nids = db_select('ioby_sf_fund_groupings', 'fg')
      ->fields('fg', array('project_nid'))
      ->condition('fg.campaign_nid', $campaign_nid, '=')
      ->execute()->fetchCol();

    return $project_nids;
  }

  public function getAccountForFund(Fund $fund) {
    if ($fund->getSponsorNid() == NULL) {
      return FALSE;
    }

    if ($sponsorship = node_load($fund->getSponsorNid())) {
      $account = new Account();
      $account->setSponsorNid($sponsorship->nid)
        ->setAccountName($sponsorship->title)
        ->setCreated(REQUEST_TIME)
        ->setChanged(REQUEST_TIME);

      return $account;
    }

    return FALSE;
  }
}
