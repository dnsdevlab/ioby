<?php

/**
 * Implementation of hook_views_handlers().
 */

/**
 * Implements hook_views_data().
 */
function iobyproject_views_data() {
  $data = array();

  $data['iobyproject_totals_cache']['table'] = array(
    'group' => t('Ioby Project'),
    'field' => 'nid',
    'title' => t('Project'),

    'join' => array(
      'node' => array(
        'left_field' => 'nid',
        'field' => 'nid',
        'type' => 'LEFT',
      )
    ),
  );

  $data['iobyproject_totals_cache']['total'] = array(
    'title' => t('Project Total'),
    'help' => t('The amount raised so far for a project.'),
    'field' => array(
      'handler' => 'views_handler_field_numeric',
      'click sortable' => TRUE,
      'float' => TRUE,
    ),
    'argument' => array(
      'handler' => 'views_handler_argument_numeric',
    ),
    'filter' => array(
      'title' => t('Duration'),
      'handler' => 'views_handler_filter_numeric',
    ),
    'sort' => array(
      'handler' => 'views_handler_sort',
    ),
  );

  return $data;
}

/**
 * Implements hook_views_data_alter().
 */
function iobyproject_views_data_alter(&$data) {
  if (isset($data['node'])) {
    $data['node']['field_data_field_project_address'] = array(
      'title' => 'Project Address',
      'help' => 'The project address (location)',
      'relationship' => array(
        'handler' => 'views_handler_relationship',
        'base' => 'field_data_field_project_address',
        'base field' => 'revision_id',
        'field' => 'vid',
      ),
    );
  }
  if (isset($data['location'])) {
    $data['location']['table']['join']['field_data_field_project_address'] = array(
      'left_field' => 'field_project_address_lid',
      'field' => 'lid',
    );
  }
}