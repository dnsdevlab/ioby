<?php

/*
* Implements hook_migrate_api()
* @author Russom Woldezghi
*/
function ioby_migrate_migrate_api(){
  // Source: https://www.drupal.org/node/2136601
  // Build array of hooks to disable during migration, and the module(s) responsible
  $disable_hooks = array(
    'node_insert' => array(
      'ioby_sf',
      ),
    'node_update' => array(
      'ioby_sf',
      'apachesolr'
      ),
    'node_update_index' => array(
      'apachesolr',
      )
    );

  // Query rules_config table for active rules to disable during migration
  $rules_query = db_query('SELECT * FROM rules_config WHERE active = 1');
  $result = $rules_query->fetchAll();

  $active_rules_list = array();
  foreach ($result as $record) {
    $active_rules_list[] = $record->name;
  }

  $api = array(
    'api' => 2,

    // Grouping infos of Migration
    'groups' => array(
      'projectUpdate' => array(
        'title' => t('Impact Areas Update'),
        'disable_rules' => $active_rules_list,
        'disable_hooks' => $disable_hooks,
        ),
      ),
    // Project Nodes update and terms import
    'migrations' => array(
      // Project nodes
      'project' => array(
        'class_name' => 'ProjectMigration',
        'group_name' => 'projectUpdate',
        // Needs to be run before nodes
        // 'dependencies' => array(
        //   'ImpactAreas',
        //   ),
        ),
      // Term import
      'ImpactAreas' => array(
        'class_name' => 'ImpactAreasMigration',
        'group_name' => 'projectUpdate'
        )
      ),
    );
  return $api;
}
