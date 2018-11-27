<?php

class iobydonation_handler_filter_product_title extends views_handler_filter {

function operators() {
    $operators = parent::operators();
    // We won't be using regex in our example
    // unset($operators['regular_expression']);
    return $operators;
  }

  /**
   * Options form subform for setting exposed filter options.
   */
  function value_form(&$form, &$form_state) {
    parent::value_form($form, $form_state);

    // get list of project_2 nids from database
    $project_nodes = db_select('node', 'n')
     ->fields('n')
     ->condition('type', 'project_2', '=')
     ->execute()
     ->fetchCol();

    // Prepare All option for field.
    $projects = array(
      '0' => t('All')
    );

    foreach ($project_nodes as $pn) {
      $projects[$pn] = _get_node_title($pn);
    }

    // create form element with options retrieved from database
    $form['value']['project_title'] = array(
      '#type' => 'select',
      '#options' => $projects,
      '#default_value' => end($projects),
    );
  }



  /**
   * Alters Views query when filter is used.
   */
  function query() {
    // make sure base table is included in the query
   $this->ensure_my_table();
    $this->add_additional_fields();
  }

}
