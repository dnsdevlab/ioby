<?php

namespace Drupal\ioby_sf\Salesforce\Models;


class ModelLoader
{
  public static function loadOpportunityRowData($opportunity_row) {
    $opportunity = new Opportunity();
    $opportunity->setOpportunityId(isset($opportunity_row->opportunity_id) ? $opportunity_row->opportunity_id : NULL)
      ->setNid(isset($opportunity_row->nid) ? $opportunity_row->nid : NULL)
      ->setName(isset($opportunity_row->name) ? $opportunity_row->name : NULL)
      ->setDescription(isset($opportunity_row->description) ? $opportunity_row->description : NULL)
      ->setEntryType(isset($opportunity_row->entry_type) ? $opportunity_row->entry_type : NULL)
      ->setOpportunityType(isset($opportunity_row->opportunity_type) ? $opportunity_row->opportunity_type : NULL)
      ->setProjectNid(isset($opportunity_row->project_nid) ? $opportunity_row->project_nid : NULL)
      ->setCampaignSfId(isset($opportunity_row->campaign_sf_id) ? $opportunity_row->campaign_sf_id : NULL)
      ->setSponsorNid(isset($opportunity_row->sponsor_nid) ? $opportunity_row->sponsor_nid : NULL)
      ->setAccountSfId(isset($opportunity_row->account_sf_id) ? $opportunity_row->account_sf_id : NULL)
      ->setOrderId(isset($opportunity_row->order_id) ? $opportunity_row->order_id : NULL)
      ->setLineItemId(isset($opportunity_row->line_item_id) ? $opportunity_row->line_item_id : NULL)
      ->setAmount(isset($opportunity_row->amount) ? $opportunity_row->amount : NULL)
      ->setClosedDate(isset($opportunity_row->closed_date) ? $opportunity_row->closed_date : NULL)
      ->setStage(isset($opportunity_row->stage) ? $opportunity_row->stage : NULL)
      ->setCampaignNid(isset($opportunity_row->campaign_nid) ? $opportunity_row->campaign_nid : NULL)
      ->setFundSfId(isset($opportunity_row->fund_sf_id) ? $opportunity_row->fund_sf_id : NULL)
      ->setContactEmail(isset($opportunity_row->contact_email) ? $opportunity_row->contact_email : NULL)
      ->setContactSfId(isset($opportunity_row->contact_sf_id) ? $opportunity_row->contact_sf_id : NULL)
      ->setSalesforceRecordId(isset($opportunity_row->salesforce_record_id) ? $opportunity_row->salesforce_record_id : NULL)
      ->setCreated(isset($opportunity_row->created) ? $opportunity_row->created : NULL)
      ->setChanged(isset($opportunity_row->changed) ? $opportunity_row->changed : NULL)
      ->setNeedsUpdate(isset($opportunity_row->needs_update) ? $opportunity_row->needs_update : NULL);

    return $opportunity;
  }

  public static function loadOrderRowData($order_row) {
    $order = new Order();
    $order->setCommerceOrderId(isset($order_row->commerce_order_id) ? $order_row->commerce_order_id : NULL)
      ->setCreated(isset($order_row->created) ? $order_row->created : NULL);

    return $order;
  }

  public static function loadOrderLineItemRowData($order_row) {
    $order_line_item = new OrderLineItem();
    $order_line_item->setSalesforceRecordId(isset($order_row->salesforce_record_id) ? $order_row->salesforce_record_id : NULL)
      ->setOpportunitySalesforceRecordId(isset($order_row->opportunity_salesforce_record_id) ? $order_row->opportunity_salesforce_record_id : NULL)
      ->setLineItemId(isset($order_row->line_item_id) ? $order_row->line_item_id : NULL)
      ->setAmount(isset($order_row->amount) ? $order_row->amount : NULL)
      ->setOpportunityType(isset($order_row->opportunity_type) ? $order_row->opportunity_type : NULL)
      ->setCommerceOrderId(isset($order_row->commerce_order_id) ? $order_row->commerce_order_id : NULL)
      ->setCreated(isset($order_row->created) ? $order_row->created : NULL);

    return $order_line_item;
  }
}
