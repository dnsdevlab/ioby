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
