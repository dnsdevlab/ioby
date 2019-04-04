<?php
/**
 * @file
 * project_form_steps.context.inc
 */

/**
 * Implements hook_context_default_contexts().
 */
function project_form_steps_context_default_contexts() {
  $export = array();

  $context = new stdClass();
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'hide_project_progress_bar';
  $context->description = 'Hides displays progress of project form steps, notifications of idea and project forms';
  $context->tag = '';
  $context->conditions = array(
    'node' => array(
      'values' => array(
        'project_2' => 'project_2',
      ),
      'options' => array(
        'node_form' => '2',
      ),
    ),
    'path' => array(
      'values' => array(
        'node/39632' => 'node/39632',
        'node/39634' => 'node/39634',
        'cart' => 'cart',
        'checkout' => 'checkout',
        'checkout/*' => 'checkout/*',
      ),
    ),
  );
  $context->reactions = array();
  $context->condition_mode = 0;

  // Translatables
  // Included for use with string extractors like potx.
  t('Hides displays progress of project form steps, notifications of idea and project forms');
  $export['hide_project_progress_bar'] = $context;

  return $export;
}
