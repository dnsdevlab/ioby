<?php
if (!empty($row->field_field_donor_display_name[0]['raw']['safe_value'])) {
  print $row->field_field_donor_display_name[0]['raw']['safe_value'];
}
elseif (!empty($row->field_field_user_fullname[0]['raw']['value']) && substr_count(drupal_strtolower($row->field_field_user_fullname[0]['raw']['value']), 'anonymous') > 0) {
  print 'Anonymous';
}
else {
  if (trim($output) == '.') {
    print 'Anonymous';
  }
  else {
    $name_parts = explode(' ', $output);
    $name_parts = array_map('drupal_ucfirst', $name_parts);
    print implode(' ', $name_parts);
  }
}
