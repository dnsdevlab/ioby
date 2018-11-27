<?php
/**
 * @file
 * home_page.bean.inc
 */

/**
 * Implements hook_bean_admin_ui_types().
 */
function home_page_bean_admin_ui_types() {
  $export = array();

  $bean_type = new stdClass();
  $bean_type->disabled = FALSE; /* Edit this to true to make a default bean_type disabled initially */
  $bean_type->api_version = 5;
  $bean_type->name = 'impact';
  $bean_type->label = 'Impact';
  $bean_type->options = '';
  $bean_type->description = '';
  $export['impact'] = $bean_type;

  $bean_type = new stdClass();
  $bean_type->disabled = FALSE; /* Edit this to true to make a default bean_type disabled initially */
  $bean_type->api_version = 5;
  $bean_type->name = 'ioby_update';
  $bean_type->label = 'ioby Update';
  $bean_type->options = '';
  $bean_type->description = '';
  $export['ioby_update'] = $bean_type;

  $bean_type = new stdClass();
  $bean_type->disabled = FALSE; /* Edit this to true to make a default bean_type disabled initially */
  $bean_type->api_version = 5;
  $bean_type->name = 'project_feature';
  $bean_type->label = 'Project Feature';
  $bean_type->options = '';
  $bean_type->description = '';
  $export['project_feature'] = $bean_type;

  $bean_type = new stdClass();
  $bean_type->disabled = FALSE; /* Edit this to true to make a default bean_type disabled initially */
  $bean_type->api_version = 5;
  $bean_type->name = 'promo';
  $bean_type->label = 'Promo';
  $bean_type->options = '';
  $bean_type->description = 'One of the 3-Up promos that appear on the home page.';
  $export['promo'] = $bean_type;

  return $export;
}
