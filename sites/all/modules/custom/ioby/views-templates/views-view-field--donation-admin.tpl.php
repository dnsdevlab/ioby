<?php

/**
 * @file
 * This template is used to print a single field in a view.
 *
 * It is not actually used in default Views, as this is registered as a theme
 * function which has better performance. For single overrides, the template is
 * perfectly okay.
 *
 * Variables available:
 * - $view: The view object
 * - $field: The field handler object that can process the input
 * - $row: The raw SQL result that can be used
 * - $output: The processed output that will normally be used.
 *
 * When fetching output from the $row, this construct should be used:
 * $data = $row->{$field->field_alias}
 *
 * The above will guarantee that you'll always get the correct data,
 * regardless of any changes in the aliasing that might happen if
 * the view is modified.
 */

if ($field->field == 'field_share_donor_email' || $field->field == 'field_hide_donor_info') {
  if (isset($output)) {
    $output = ($output == 1) ? 'Yes' : 'No';
  }
  else {
    $output = ($field->field == 'field_share_donor_email') ? 'Yes' : 'No';
  }
}

if ($field->field == 'field_donor_display_name') {
  if (empty($output)) {
    if (empty($row->field_field_hide_donor_info) || (!empty($row->field_field_hide_donor_info) && $row->field_field_hide_donor_info[0]['raw']['value'] == 0)) {
      $output = $row->commerce_customer_profile_field_data_commerce_customer_billi . ' ' . substr($row->commerce_customer_profile_field_data_commerce_customer_billi_1, 0, 1) . '.';
      $name_parts = explode(' ', $output);

      $name_parts = array_map('drupal_ucfirst', $name_parts);

      $output = implode(' ', $name_parts);
    }
    else {
      $output = 'Anonymous';
    }
  }
  else {
    if ($row->field_field_hide_donor_info[0]['raw']['value'] == 1) {
      $output = 'Anonymous';
    }
  }
}

?>

<?php print $output; ?>
