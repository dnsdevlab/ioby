<?php
/**
 * @file
 * Administration page callback for the About ioby module.
 */

/**
 * Form builder. Configure About ioby section.
 *
 * @ingroup forms
 * @see system_settings_form().
 */
function aboutioby_admin_setting() {
  $form['aboutioby_title'] = array(
    '#type' => 'textfield',
    '#title' => t('Section Title'),
    '#default_value' => variable_get('aboutioby_title', t('About')),
    '#description' => t('Page title'),
  );
	
	$form['aboutioby_block_title'] = array(
		'#type' => 'textfield',
		'#title' => t('Block title'),
		'#default_value' => variable_get('aboutioby_block_title', 'By the Numbers'),
		'#description' => 'Block title',
	);
	
	$msg = '<p>Use the following options to populate the sidebar on the about pages<br/>';
	$msg .= '<i>If either label or value are blank, the entire field will not be shown.</i></p>';
	$form['aboutioby_instructions'] = array(
		'#markup' => $msg,
	);
	for($i = 1; $i <= 15; $i++) {
		$field = 'aboutioby_option'. $i;
		$form[$field] = array(		
			'#type' => 'fieldset',
			'#title' => 'Option ' . $i,
			$field . '_value' => array(
				'#type' => 'textfield',
				'#title' => t('Content'),
				'#default_value' => variable_get($field . '_value'),
			),
			$field . '_label' => array(
				'#type' => 'textfield',
				'#title' => t('Label'),
				'#default_value' => variable_get($field . '_label'),
			),
		);
	}
  return system_settings_form($form);
}
