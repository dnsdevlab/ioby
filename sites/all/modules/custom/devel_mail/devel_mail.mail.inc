<?php

/**
 * @file
 * Devel Mail implementations of MailSystemInterface.
 */

/**
 * The default Drupal mail backend using PHP's mail function.
 */
class DevelMailSystem extends DefaultMailSystem implements MailSystemInterface {
  
  /**
   * Send an e-mail message, using Drupal variables and default settings.
   *
   * @see http://php.net/manual/en/function.mail.php
   * @see drupal_mail()
   *
   * @param $message
   *   A message array, as described in hook_mail_alter().
   * @return
   *   TRUE if the mail was successfully accepted, otherwise FALSE.
   */
  public function mail(array $message) {
    if (variable_get('devel_mail_log_enabled', 0)) {
      $line_endings = variable_get('mail_line_endings', MAIL_LINE_ENDINGS);
      $mail_body = preg_replace('@\r?\n@', $line_endings, $message['body']);

      $log_item = new DevelMailLogEntity();
      $log_item->message_id = $message['id'];
      $log_item->message_module = $message['module'];
      $log_item->message_key = $message['key'];
      $log_item->message_to = $message['to'];
      $log_item->message_from = $message['from'];
      $log_item->message_subject = $message['subject'];
      $log_item->message_body = $mail_body;
      $log_item->message_headers = $message['headers'];
      $log_item->message_params = $message['params'];
      $log_item->message_language = $message['language'];
      $log_item->message = $message;
      
      $log_item->save();
      $return = TRUE;

      if (variable_get('devel_mail_log_send_enabled', 0)) {
        // Pass through to Drupal's DefaultMailSystem
        $return = parent::mail($message);
      }
      return $return;
    }
    else {
      // Pass through to Drupal's DefaultMailSystem
      return parent::mail($message);  
    }
    
  }  
}
