<?php

function devel_mail_log_page_view(DevelMailLogEntity $devel_mail_log) {
  $output = '';
  $output .= '<b>From: </b>' . check_plain($devel_mail_log->message_from) . '<br />';
  $output .= '<b>To: </b>' . check_plain($devel_mail_log->message_to) . '<br />';
  $output .= '<b>Subject: </b>' . check_plain($devel_mail_log->message_subject) . '<br />';
  $output .= check_markup($devel_mail_log->message_body, 'filtered_html') . '<br />';

  return $output;
}

/**
 * Form bulder; Asks for confirmation of devel_mail_log deletion.
 */
function devel_mail_log_confirm($form, &$form_state, DevelMailLogEntity $devel_mail_log) {
  $form['#devel_mail_log'] = $devel_mail_log;
  // Always provide entity id in the same form key as in the entity edit form.
  $form['mail_log_id'] = array('#type' => 'value', '#value' => $devel_mail_log->mail_log_id);
  return confirm_form($form,
    t('Are you sure you want to delete %message_id?', array('%message_id' => $devel_mail_log->message_id)),
    entity_class_uri($devel_mail_log),
    t('This action cannot be undone.'),
    t('Delete'),
    t('Cancel')
  );
}

/**
 * Executes devel_mail_log deletion.
 */
function devel_mail_log_confirm_submit($form, &$form_state) {
  if ($form_state['values']['confirm']) {
    $devel_mail_log = devel_mail_log_load($form_state['values']['mail_log_id']);
    $devel_mail_log->delete();
    watchdog('devel_mail', 'Mail log item #!mail_log_id has been deleted.', array('!mail_log_id' => $devel_mail_log->mail_log_id));

    drupal_set_message(t('Mail log item #!mail_log_id has been deleted.', array('!mail_log_id' => $devel_mail_log->mail_log_id)));
  }

  //$form_state['redirect'] = '<front>'; // @todo Change this path to something else?
}

function devel_mail_settings_page() {
  $items = array(
    'clear_form' => drupal_get_form('devel_mail_clear_form'),
    'settings_form' => drupal_get_form('devel_mail_settings_form'),
  );
  return $items;
}

function devel_mail_settings_form($form, &$form_state) {
  $form['devel_mail_settings'] = array(
    '#type' => 'fieldset',
    '#title' => t('Mail log settings'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );

  if (in_array('DevelMailSystem', variable_get('mail_system', array('default-system' => 'DefaultMailSystem')), TRUE)) {
    $form['devel_mail_settings']['devel_mail_log_enabled'] = array(
      '#title' => t('Mail log'),
      '#description' => t('When enabled, all outgoing mail will be logged to the database instead of being sent.'),
      '#type' => 'radios',
      '#options' => array(1 => t('Enabled'), 0 => t('Disabled')),
      '#default_value' => variable_get('devel_mail_log_enabled', 0),
    );
    $form['devel_mail_settings']['devel_mail_log_send_enabled'] = array(
      '#title' => t('Also send email'),
      '#description' => t('When enabled, logged emails will also be sent.'),
      '#type' => 'radios',
      '#options' => array(1 => t('Enabled'), 0 => t('Disabled')),
      '#default_value' => variable_get('devel_mail_log_send_enabled', 0),
    );
  }
  else {
    $form['devel_mail_settings']['devel_mail_intro'] = array(
    '#markup' => t('<p><em>In order to override Drupal\'s default mail system with Mail log, you must place the following code in your settings.php file:</em> <blockquote>$conf[\'mail_system\'] = array(\'default-system\' => \'DevelMailSystem\');</blockquote></p>'),
  );
  }
  return system_settings_form($form);
}

function devel_mail_clear_form() {
  $form['devel_mail_clear'] = array(
    '#type' => 'fieldset',
    '#title' => t('Clear logged emails'),
    '#description' => t('Clicking this will remove all logged emails from the database.'),
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
  );
  $form['devel_mail_clear']['submit'] = array(
    '#prefix' => '<br />',
    '#type' => 'submit',
    '#value' => t('Clear Log'),
  );

  return $form;
}

/**
 * Form submission handler for reindex button on search_admin_settings_form().
 */
function devel_mail_clear_form_submit($form, &$form_state) {
  // send the user to the confirmation page
  $form_state['redirect'] = 'admin/config/devel-mail/clear';
}

/**
 * Menu callback: confirm clearing of the mail log.
 */
function devel_mail_clear_confirm_form() {
  return confirm_form(
    array(), 
    t('Are you sure you want to clear the mail log?'),
    !empty($_GET['destination']) ? $_GET['destination'] : 'admin/config/devel-mail', 
    t('This action cannot be undone.'), 
    t('Clear mail log'), 
    t('Cancel')
  );
}

/**
 * Handler for clear confirmation
 */
function devel_mail_clear_confirm_form_submit(&$form, &$form_state) {
  if ($form['confirm']) {
    db_query("TRUNCATE TABLE {devel_mail_log}")->execute();
    drupal_set_message(t('The mail log has been cleared.'));
    $form_state['redirect'] = 'admin/config/devel-mail';
    return;
  }
}

/**
 * A page callback to resend a message
 * 
 * @param DevelMailLogEntity $devel_mail_log
 */
function devel_mail_log_resend(DevelMailLogEntity $devel_mail_log) {
  if (variable_get('devel_mail_log_send_enabled', 0)) {
    $system = new DefaultMailSystem();
    $result = $system->mail($devel_mail_log->message);
    drupal_set_message(t('Resent email to %to with subject "%subject": %status.', array(
      '%to' => $devel_mail_log->message_to,
      '%subject' => $devel_mail_log->message_subject,
      '%status' => $result ? 'success' : 'failed',
    )));
    drupal_goto('admin/reports/devel-mail');
  }
  else {
    drupal_set_message(t('Sending emails is currently disabled.'));
  }
}