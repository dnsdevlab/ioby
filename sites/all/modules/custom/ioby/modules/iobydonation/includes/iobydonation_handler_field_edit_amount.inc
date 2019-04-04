<?php

/**
 * @file
 * Field handler to present a form field to change quantity of a line item. It's
 * a dummy handler, most part of the implementation is done via post render
 * hook.
 */

/**
 * Field handler to present a field to change quantity of a line item.
 */
class iobydonation_handler_field_edit_amount extends views_handler_field {

  function construct() {
    parent::construct();
    $this->additional_fields['line_item_id'] = 'line_item_id';
    $this->additional_fields['amount'] = 'amount';

    // Set real_field in order to make it generate a field_alias.
    $this->real_field = 'amount';
  }
	
	function query() {
	}

  function render($values) {
		$return = '<!--form-item-' . $this->options['id'] . '--' . $this->view->row_index . '-->';
    return $return;
  }

  /**
   * Returns the form which replaces the placeholder from render().
   */
  function views_form(&$form, &$form_state) {
    // The view is empty, abort.
    if (empty($this->view->result)) {
      return;
    }

    $form[$this->options['id']] = array(
      '#tree' => TRUE,
    );
    // At this point, the query has already been run, so we can access the results
    // in order to get the base key value (for example, nid for nodes).
    foreach ($this->view->result as $row_id => $row) {
			$line_item_id = $row->commerce_line_item_field_data_commerce_line_items_line_item_;
			$amount = $row->field_commerce_unit_price[0]['raw'];
			$value = commerce_currency_amount_to_decimal($amount['amount'], 'USD');

      $form[$this->options['id']][$row_id] = array(
        '#type' => 'textfield',
        '#default_value' => number_format($value, 2),
        '#size' => 8,
				'#maxlength' => 20,
				'#prefix' => '$',
        '#line_item_id' => $line_item_id,
				'#currency_code' => $amount['currency_code'],
      );
    }
  }

  function views_form_validate($form, &$form_state) {
    $field_name = $this->options['id'];
    foreach (element_children($form[$field_name]) as $row_id) {
      // Ensure the quantity is actually a numeric value.
      $line_item_id = $form[$field_name][$row_id]['#line_item_id'];
			$value = $form_state['values'][$field_name][$row_id];
			$value = str_replace(',', '', $value);
      if (!is_numeric($value) || $value < 0) {
        form_set_error($field_name . '][' . $row_id, t('You must specify a positive amount'));
      }
    }
  }

  function views_form_submit($form, &$form_state) {
    $field_name = $this->options['id'];
    foreach (element_children($form[$field_name]) as $row_id) {
			$element = $form[$field_name][$row_id];
      $line_item_id = $element['#line_item_id'];
			$currency_code = $element['#currency_code'];
			
      // If the line item hasn't been deleted...
      if ($line_item = commerce_line_item_load($line_item_id)) {
				// make sure it's a product
				if($line_item->type != 'product') {
					continue;
				}
				$line_item_wrapper = entity_metadata_wrapper('commerce_line_item', $line_item);
				
				$price = $line_item_wrapper->commerce_product->commerce_price->amount->value();
				
				$entered_amount = $form_state['values'][$field_name][$row_id];
				$entered_amount = str_replace(',', '', $entered_amount);
				$amount = commerce_currency_decimal_to_amount($entered_amount, $currency_code);
				$product = $line_item_wrapper->commerce_product->value();
				
				if($amount != $price) {
					$product->commerce_price[LANGUAGE_NONE][0]['amount'] = $amount;
					
					// modify gratuity to allow over-ride
					if($product->type == 'gratuity') {
						$product->field_override[LANGUAGE_NONE][0]['value'] = 1;
					}
					commerce_product_save($product);
					
					// refresh the line item
          entity_get_controller('commerce_line_item')->resetCache(array($line_item->line_item_id));
				}
      }
    }
  }
}
