<?php

/**
 * @file
 * Ioby rules
 */

/**
 * Implements hook_rules_action_info()
 */
function ioby_rules_action_info() {
  $items = array();

  $items['ioby_coupon_is_expired'] = array(
    'label' => t('Check expiration date of coupon'),
    'group' => t('IOBY Coupon'),
    'parameter' => array(
      'commerce_coupon' => array(
        'type' => 'commerce_coupon',
        'label' => t('Commerce Coupon'),
      ),
    ),
  );

  $items['ioby_check_coupon_amount'] = array(
    'label' => t('Validate coupon amount to ignore/remove gratuity and add extra to first item'),
    'group' => t('IOBY Coupon'),
    'parameter' => array(
      'commerce_order' => array(
        'type' => 'commerce_order',
        'label' => t('commerce order'),
        'save' => TRUE,
      ),
      'commerce_coupon' => array(
        'type' => 'commerce_coupon',
        'label' => t('Coupon'),
      )
    ),
  );

  $items['ioby_remove_gratuities_from_cart'] = array(
    'label' => t('Remove all gratuities from an order'),
    'parameter' => array(
      'commerce_order' => array(
        'type' => 'commerce_order',
        'label' => t('Order to empty'),
      ),
    ),
    'group' => t('Commerce Cart'),
    'callbacks' => array(
      'execute' => 'ioby_remove_gratuities_from_cart',
    ),
  );

  return $items;
}

/**
 * Checks to see if coupon is expired.
 */
function ioby_coupon_is_expired($commerce_coupon) {
  global $commerce_coupon_validation_error_message;
  $date = NULL;
  if(isset($commerce_coupon->field_coupon_expires[LANGUAGE_NONE])) {
    $date = $commerce_coupon->field_coupon_expires[LANGUAGE_NONE][0]['value'];
  }
  if(!$date) {
    return;
  }
  $date = preg_replace('/00:00:00/','23:59:59', $date);
  $date = strtotime($date);
  if(time() > $date){
    $commerce_coupon_validation_error_message = 'We apologize, however your coupon has expired';
    commerce_coupon_action_is_invalid_coupon();
  }
}

/**
 * Checks coupon amount and applies left over amount to first line item,
 * excluding gratuity!
 */
function ioby_check_coupon_amount($order, $coupon) {

  $products_total = 0;
  $order_wrapper = entity_metadata_wrapper('commerce_order', $order);
  $coupon_wrapper = entity_metadata_wrapper('commerce_coupon', $coupon);

  $first_line = null;
  $gratuity_line = null;
  foreach($order_wrapper->commerce_line_items as $line_item_wrapper) {
    // breakout if coupon is in order..
    if($line_item_wrapper->type->value() == 'commerce_coupon') {
      continue;
    }
    // ignore gratuities
    if($line_item_wrapper->commerce_product->type->value() == 'gratuity') {
      $gratuity_line_id = $line_item_wrapper->line_item_id->value();
      continue;
    }

    if(!$first_line) {
      $first_line = $line_item_wrapper;
    }
    $products_total += $line_item_wrapper->commerce_product->commerce_price->amount->value();
  }

  $amount = $coupon_wrapper->commerce_coupon_fixed_amount->amount->value();

  if(!$first_line) {
    $commerce_coupon_validation_error_message = "You do not have a valid product.";
    commerce_coupon_action_is_invalid_coupon();
  }

  // if cart total (less gratuity) is less the coupon amount
  if($products_total <= $amount) {
    // remove gratuity line item;
    commerce_cart_order_product_line_item_delete($order, $gratuity_line_id);

    // add left over to first line.
    $left_over = $amount - $products_total;

    $product_amount = $first_line->commerce_product->commerce_price->amount->value();
    $adjusted_price = $product_amount + $left_over;

    $first_line->commerce_unit_price->amount = $adjusted_price;
    $first_line->commerce_total->amount = $adjusted_price;

    $first_line->commerce_product->commerce_price->amount = $adjusted_price;
    commerce_product_save($first_line->commerce_product->value());

    $order_wrapper->save();

    drupal_set_message(t('Adding left over coupon amount to first donation. Thank you'));
  }
}

function ioby_remove_gratuities_from_cart($order) {
  $gratuity_removed = FALSE;

  // Loop through line items and look for gratuities
  foreach($order->commerce_line_items[LANGUAGE_NONE] as $li) {
    $line_item = commerce_line_item_load($li['line_item_id']);

    // ignore coupon lines
    if(preg_match('/coupon/', $line_item->type)) {
      continue;
    }

    $product = commerce_product_load($line_item->commerce_product[LANGUAGE_NONE][0]['product_id']);
    if($product->type == 'gratuity') {
      commerce_cart_order_product_line_item_delete($order, $line_item->line_item_id, TRUE);
      $gratuity_removed = TRUE;

      // Delete the gratuity product as well
      commerce_product_delete($product->product_id);
    }
  }

  if ($gratuity_removed) {
    // Save our changes
    commerce_order_save($order);
  }
}
