<?php
/**
 * @file
 * Administration page callback for the IOBY FAQ module.
 */

/**
 * Form builder. Configure IOBY FAQ.
 *
 * @ingroup forms
 * @see system_settings_form().
 */
function ioby_faq_admin_setting() {
  
  // Select the content type for 
  $content_types = node_type_get_types();
  
  $content_type_options = array();
  foreach ($content_types as $content_type) {
    $content_type_options[$content_type->type] = $content_type->name;
  }
  
  $form['ioby_faq_content_type'] = array(
    '#type' => 'select',
    '#title' => t('Content type for FAQ items'),
    '#description' => t('Select the content type for the FAQ items'),
    '#options' => $content_type_options,
    '#default_value' => variable_get('ioby_faq_content_type', 'faq'),
  );
  
  
  // Select the vocabulary
  $vocabularies = taxonomy_get_vocabularies();
  
  $vocabularies_options = array();
  foreach ($vocabularies as $vocabulary) {
    $vocabularies_options[$vocabulary->vid] = $vocabulary->name;
  }
  
  $form['ioby_faq_vocabulary'] = array(
    '#type' => 'select',
    '#title' => t('Vocabulary for organizing FAQ items'),
    '#description' => t('Select the vocabulary for the FAQ items'),
    '#options' => $vocabularies_options,
    '#default_value' => variable_get('ioby_faq_vocabulary', ''),
  );
  
  
  // Default term
  $vocabulary = variable_get('ioby_faq_vocabulary', '');
  if (!empty($vocabulary)) {
    $term_tree = taxonomy_get_tree($vocabulary);
    $term_options = array();
    foreach ($term_tree as $term) {
      $term_options[$term->tid] = $term->name;
    }
    
    $form['ioby_faq_default_term'] = array(
      '#type' => 'select',
      '#options' => $term_options,
      '#default_value' => variable_get('ioby_faq_default_term', ''),
    );
  }
  
  
  // Set the path for the FAQs
  $form['ioby_faq_path'] = array(
    '#type' => 'textfield',
    '#title' => t('URL path'),
    '#default_value' => variable_get('ioby_faq_path', 'help'),
    '#description' => t('The URL path for the FAQ section'),
  );
  
  
  // Set the title for the FAQs
  $form['ioby_faq_title'] = array(
    '#type' => 'textfield',
    '#title' => t('Page title'),
    '#default_value' => variable_get('ioby_faq_title', 'Help'),
    '#description' => t('The title for the FAQ section'),
  );
  
  
  
  
  return system_settings_form($form);
}


