<?php

/**
 * Implements hook_rules_event_info().
 */
function iobyproject_rules_event_info() {
  $items = array();

  $items['iobyproject_project_funded'] = array(
    'label' => t('Project is fully funded'),
    'group' => t('Ioby Project'),
    'variables' => array(
      'node' => array('type' => 'node', 'label' => t('Projects'))
    ),
  );

  $items['iobyproject_project_funding_threshold_reached'] = array(
    'label' => t('Project has reached funding threshold'),
    'group' => t('Ioby Project'),
    'variables' => array(
      'node' => array(
        'label' => t('Projects'),
        'type' => 'node',
      ),
      'funding_threshold' => array(
        'label' => t('Percent Funded'),
        'type' => 'integer',
        'description' => 'blah, blah'
      )
    ),
  );

  return $items;
}
