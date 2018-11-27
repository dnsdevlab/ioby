<?php

/**
 * @file
 * Coupon Batch forms for Drupal Commerce
 *
 * This file contains form data for...
 */

/**
 * Generates the commerce coupon editing form.
 */
function commerce_coupon_batch_form($form, &$form_state, $commerce_coupon) {
  $form['batch_size'] = array(
    '#title' => t('Batch Size'),
    '#type' => 'textfield',
    '#description' => t('Enter the number of coupons which should be generated.'),
    '#size' => 30,
  );

  $form['coupon_code'] = array(
    '#type' => 'fieldset',
    '#title' => t('Coupon code'),
    '#tree' => TRUE,
    'prefix' => array(
      '#type' => 'textfield',
      '#title' => t('Prefix'),
      '#size' => 10,
      '#description' => t('Prefix for generated coupon codes.'),
    ),
    'suffix' => array(
      '#type' => 'textfield',
      '#title' => t('Suffix'),
      '#size' => 10,
      '#description' => t('Suffix for generated coupon codes.'),
    ),
  );

  $form['is_active'] = array(
    '#title' => t('Active'),
    '#type' => 'checkbox',
    '#default_value' => $commerce_coupon->is_active,
    '#description' => t('Indicates if the coupon can be used or not.'),
    '#size' => 30,
  );

  // Add the field related form elements.
  $form_state['commerce_coupon'] = $commerce_coupon;
  field_attach_form('commerce_coupon', $commerce_coupon, $form, $form_state);

  // Hide the coupon code field:
  $form['commerce_coupon_code']['#type'] = 'hidden';

  $form['data']['#tree'] = TRUE;

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save coupon'),
    '#weight' => 40,
  );

  return $form;
}

function commerce_coupon_batch_form_validate(&$form, &$form_state) {
  $v = &$form_state['values'];

  # strip invalid chars -- @see commerce_coupon_generate_coupon_code().
  $v['coupon_code']['suffix'] = preg_replace('/[^a-z0-9]*/i', '', $v['coupon_code']['suffix']);
  $v['coupon_code']['prefix'] = preg_replace('/[^a-z0-9-]*/i', '', $v['coupon_code']['prefix']);

  # get prefix + suffix string length
  $code_length = 0;
  $code_length += strlen($v['coupon_code']['prefix']);
  $code_length += strlen($v['coupon_code']['suffix']);

  if ($code_length) {
    $limit_length = variable_get('commerce_coupon_default_code_size', 8);

    # we need 3 spaces free for code generating
    if ($limit_length - $code_length < 3) {
      $message = 'Coupon code length set to %limit. You need to shorten your';
      $message .= ' coupon code prefix/suffix that we have 3 free space for';
      $message .= ' generating coupon code.';
      $message = t($message, array('%limit' => $limit_length));

      if (!empty($v['coupon_code']['prefix'])) {
        form_set_error('prefix', $message);
      }
      else {
        form_set_error('suffix', $message);
      }
    }
  }
}

/**
 * Form API submit callback for the type form.
 */
function commerce_coupon_batch_form_submit(&$form, &$form_state) {
  $v = &$form_state['values'];
  $size = (int) $v['batch_size'];

  if ($size > 0) {
    // Base commerce coupon object for cloning.
    $commerce_coupon = $form_state['commerce_coupon'];

    // Notify field widgets.
    field_attach_submit('commerce_coupon', $commerce_coupon, $form, $form_state);

    // Coupon code prefix/suffix.
    $prefix = trim($v['coupon_code']['prefix']);
    $suffix = trim($v['coupon_code']['suffix']);

    // Coupon length.
    $length = variable_get('commerce_coupon_default_code_size', 8);
    $length -= strlen($prefix);
    $length -= strlen($suffix);

    for ($i = 0; $i < $size; $i++) {
      $new_commerce_coupon = clone $commerce_coupon;
      $code = commerce_coupon_generate_coupon_code($length);
      $new_commerce_coupon->commerce_coupon_code[LANGUAGE_NONE][0]['value'] = "{$prefix}{$code}{$suffix}";
      commerce_coupon_save($new_commerce_coupon);
    }
    drupal_set_message(format_plural($size,
            'Generated 1 coupon in batch mode.',
            'Generated @count coupons in batch mode.'
    ));
  }
  else {
    drupal_set_message(t('No coupons to generate.'));
  }



  $form_state['redirect'] = 'admin/commerce/coupons';
}

