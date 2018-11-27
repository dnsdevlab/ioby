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
	// Adds a textfield to edit line item amount on the view.
  $data['commerce_line_item']['edit_amount'] = array(
    'field' => array(
      'title' => t('Amount text field'),
      'help' => t('Adds a text field to edit the line item amount in the View.'),
      'handler' => 'iobydonation_handler_field_edit_amount',
    ),
  );
	
	return $data;
}