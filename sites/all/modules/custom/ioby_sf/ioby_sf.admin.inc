<?php

function ioby_sf_manual_sync() {
  $sync_form = drupal_get_form('ioby_sf_manual_sync_form');
  return drupal_render($sync_form);
}

function ioby_sf_manual_sync_form() {
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Sync Salesforce records')
  );

  return $form;
}

function ioby_sf_manual_sync_form_submit() {
  try {
    ioby_sf_process_everything();
    drupal_set_message("Salesforce record sync complete.");
  }
  catch (Exception $e) {
    drupal_set_message('A problem occurred while trying to sync records. Check the error log for details.', 'error');
  }
}

/**
 * Autocomplete for the salesforce_record_id field.
 *
 * @author Paul Venuti
 */
function ioby_sf_id_lookup_autocomplete($title) {
  $matches = array();
  $args = array(
    ':title' => '%' . $title . '%',
  );
  $result = db_query_range("SELECT salesforce_record_id, title FROM {ioby_sf_potential_projects} WHERE title LIKE :title AND nid IS NULL ORDER BY title", 0, 10, $args);

  foreach ($result as $potential_project) {
    $matches[$potential_project->salesforce_record_id] = check_plain($potential_project->title);
  }

  return drupal_json_output($matches);
}
