<?php

/**
 * Implements hook_views_data().
 */
function iobydonation_views_data() {
  $data = array();

  $data['iobydonation_custom']['table']['group'] = t('Ioby Campaign');
  $data['iobydonation_custom']['table']['join'] = array(
    // #global is a special flag which let's a table appear all the time.
    '#global' => array(),
    );

  $data['iobydonation_custom']['match_total'] = array(
    'title' => t('Campaign match total'),
    'help' => t('Shows calculated donation match total for a campaign.'),
    'field' => array(
      'handler' => 'iobydonation_handler_field_match_total',
      'click sortable' => TRUE,
      'float' => TRUE,
      ),
    );

  return $data;
}

/**
 * Implements hook_views_data();
 * Creates an editable field for amount.
 * @return array
 */
function iobydonation_views_data_alter(&$data) {

  // Adds Custom search by product title (project)
  $data['iobydonation_custom']['product_title'] = array(
    'group' => t('Ioby Commerce Custom'),
    'title' => t('Ioby Product title '),
    'help' => t('Filter Commerce product title'),
    'filter' => array(
      'handler' => 'iobydonation_handler_filter_product_title',
      ),
    );


	// Adds a textfield to edit line item amount on the view.
  $data['commerce_line_item']['edit_amount'] = array(
    'field' => array(
      'title' => t('Amount text field'),
      'help' => t('Adds a text field to edit the line item amount in the View.'),
      'handler' => 'iobydonation_handler_field_edit_amount',
      ),
    );

  $data['commerce_line_item']['edit_amount'] = array(
    'field' => array(
      'title' => t('Amount text field'),
      'help' => t('Adds a text field to edit the line item amount in the View.'),
      'handler' => 'iobydonation_handler_field_edit_amount',
      ),
    );

  return $data;
}

/**
 * Implementation of hook_views_pre_render().
 */
function iobydonation_views_pre_render(&$view) {
  if($view->name == 'clone_of_orders' && $view->current_display == 'admin_page'){
    foreach ($view->result as $key => $value) {

      $order = commerce_order_load($value->commerce_order_order_number);
      $line_items = $order->commerce_line_items['und'];

      $products = array();
      foreach ($line_items as $key => $li_value) {
        $products[] = commerce_product_load(commerce_line_item_load($li_value['line_item_id'])->commerce_product['und'][0]['product_id']);

        foreach ($products as $product) {
         // dpm($product->title);
        $value->field_commerce_line_items[0]['rendered']['#markup'] = $product->title;

        }
      }



    }
  }
}

function _get_node_title($nid) {
  return db_query('SELECT title FROM {node} WHERE nid = :nid', array(':nid' => $nid))->fetchField();
}
