<?php

class iobydonation_handler_field_match_total extends views_handler_field_numeric {

  function __construct() {
    parent::construct();
  }

  function query() {
   // Do nothing
  }

  function get_value($values, $field = NULL) {
    $match_total = get_campaign_match_total($values->nid);
    return $match_total;
  }
}
