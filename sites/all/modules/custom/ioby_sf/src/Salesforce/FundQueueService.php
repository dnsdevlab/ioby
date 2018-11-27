<?php

namespace Drupal\ioby_sf\Salesforce;

use Drupal\ioby_sf\Salesforce\Models\Fund;
use Drupal\ioby_sf\Salesforce\Repository\FundRepository;

class FundQueueService
{

  /**
   * @var FundRepository
   */
  protected $repository;

  function __construct(FundRepository $repository = NULL) {
    if (!isset($repository)) {
      $repository = new FundRepository();
    }
    $this->repository = $repository;
  }

  public function queueNewFunds() {
    $funds = $this->repository->getNewFunds();

    foreach ($funds as $fund) {
      $this->populateFundTables($fund);
    }
  }

  public function queueModifiedFunds() {
    $funds = $this->repository->getModifiedFunds();

    if ($funds) {
      foreach ($funds as $fund) {
        $this->updateFund($fund);
      }
    }
  }

  private function populateFundTables(Fund $fund) {
    try {
      db_insert('ioby_sf_funds')
        ->fields(
          array(
            'campaign_nid' => $fund->getCampaignNid(),
            'name' => $fund->getName(),
            'sponsor_nid' => $fund->getSponsorNid(),
            'total_value' => $fund->getTotalValue(),
            'fund_type' => $fund->getFundType(),
            'created' => $fund->getCreated(),
            'changed' => $fund->getChanged(),
          )
        )->execute();
    }
    catch (\Exception $e) {
      throw new \Exception("There was a problem inserting the fund.", 0, $e);
    }

    if ($account = $this->repository->getAccountForFund($fund)) {
      try {
        db_merge('ioby_sf_accounts')
          ->key(array('sponsor_nid' => $account->getSponsorNid()))
          ->insertFields(
            array(
              'sponsor_nid' => $account->getSponsorNid(),
              'account_name' => $account->getAccountName(),
              'created' => REQUEST_TIME,
              'changed' => REQUEST_TIME
            ))
          ->updateFields(array('changed' => REQUEST_TIME))
          ->execute();
      }
      catch (\Exception $e) {
        throw new \Exception("There was a problem inserting the fund account.", 0, $e);
      }
    }

    if ($projects = $fund->getProjects()) {
      $values = array();
      foreach ($projects as $project_nid) {
        $values[$project_nid] = array(
          'campaign_nid' => $fund->getCampaignNid(),
          'project_nid' => $project_nid,
        );
      }

      try {
        $query = db_insert('ioby_sf_fund_groupings')->fields(array(
          'campaign_nid',
          'project_nid'
        ));
        foreach ($values as $record) {
          $query->values($record);
        }
        $query->execute();
      }
      catch (\Exception $e) {
        throw new \Exception("There was a problem inserting the funds campaigns records.", 0, $e);
      }
    }
  }

  /**
   * @param Fund $fund
   *
   * @throws \Exception
   */
  private function updateFund(Fund $fund) {
    $fund_changes = $this->getFundChanges($fund);
    if (!empty($fund_changes)) {
      try {
        db_update('ioby_sf_funds')
          ->fields($fund_changes)
          ->condition('campaign_nid', $fund->getCampaignNid(), '=')
          ->execute();
      }
      catch (\Exception $e) {
        throw new \Exception("There was a problem updating the fund.", 0, $e);
      }
    }

    $existing_projects = $this->repository->getFundCampaignsFromTable($fund->getCampaignNid());
    $new_projects = array_diff($fund->getProjects(), $existing_projects);
    foreach ($new_projects as $project_nid) {
      try {
        db_insert('ioby_sf_fund_groupings')
          ->fields(
            array(
              'campaign_nid' => $fund->getCampaignNid(),
              'project_nid' => $project_nid,
            )
          )->execute();
      }
      catch (\Exception $e) {
        throw new \Exception("There was a problem inserting the funds campaigns records.", 0, $e);
      }
    }
  }

  /**
   * @param Fund $fund
   *
   * @return array
   */
  private function getFundChanges(Fund $fund) {
    $changes = array();

    if ($existing_data = $this->repository->getFundFromTable($fund->getCampaignNid())) {
      if ($fund->getTotalValue() != $existing_data->getTotalValue()) {
        $changes['total_value'] = $fund->getTotalValue();
      }
      if ($fund->getFundType() != $existing_data->getFundType()) {
        $changes['fund_type'] = $fund->getFundType();
      }

      if (!empty($changes)) {
        $changes['changed'] = REQUEST_TIME;
        $changes['needs_update'] = TRUE;
      }
    }

    return $changes;
  }
}
