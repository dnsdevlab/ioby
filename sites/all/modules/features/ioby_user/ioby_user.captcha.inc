<?php
/**
 * @file
 * ioby_user.captcha.inc
 */

/**
 * Implements hook_captcha_default_points().
 */
function ioby_user_captcha_default_points() {
  $export = array();

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_about_ioby_section_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_about_ioby_section_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_blog_feed_form';
  $captcha->module = '';
  $captcha->captcha_type = 'default';
  $export['comment_node_blog_feed_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_blog_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_blog_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_campaign_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_campaign_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_donation_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_donation_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_donation_match_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_donation_match_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_faq_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_faq_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_feed_form';
  $captcha->module = '';
  $captcha->captcha_type = 'default';
  $export['comment_node_feed_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_feed_item_form';
  $captcha->module = '';
  $captcha->captcha_type = 'default';
  $export['comment_node_feed_item_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_footer_press_image_form';
  $captcha->module = '';
  $captcha->captcha_type = 'default';
  $export['comment_node_footer_press_image_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_page_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_page_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_panel_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_panel_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_project_2_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_project_2_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_project_blog_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_project_blog_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_slide_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_slide_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_sponsor_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_sponsor_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_sponsorship_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_sponsorship_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'comment_node_testimonial_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['comment_node_testimonial_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'contact_personal_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['contact_personal_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'contact_site_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['contact_site_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'forum_node_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['forum_node_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'ioby_user_register_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['ioby_user_register_form'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'user_login';
  $captcha->module = '';
  $captcha->captcha_type = '';
  $export['user_login'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'user_login_block';
  $captcha->module = '';
  $captcha->captcha_type = '';
  $export['user_login_block'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'user_pass';
  $captcha->module = '';
  $captcha->captcha_type = '';
  $export['user_pass'] = $captcha;

  $captcha = new stdClass();
  $captcha->disabled = FALSE; /* Edit this to true to make a default captcha disabled initially */
  $captcha->api_version = 1;
  $captcha->form_id = 'user_register_form';
  $captcha->module = 'recaptcha';
  $captcha->captcha_type = 'reCAPTCHA';
  $export['user_register_form'] = $captcha;

  return $export;
}
