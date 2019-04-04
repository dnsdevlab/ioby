<?php
/**
 * @file
 * Ioby rules
 */

/**
 * Implements hook_rules_action_info()
 */
function iobydonation_rules_action_info() {
  $items = array();
  
  $items['iobydonation_after_checkout_complete'] = array(
    'label' => t('Creates donation item after checkout is complete'),
    'group' => t('IOBY Donation'),
    'parameter' => array(
      'commerce_order' => array(
        'type' => 'commerce_order',
        'label' => t('commerce order'),
        'save' => TRUE,
      ),
    ),
  );
	
	$items['iobydonation_mail_sender'] = array(
		'label' => t('Sends thank you mail based on the order.'),
		'group' => t('IOBY Donation'),
		'parameter' => array(
			'commerce_order' => array(
				'type' => 'commerce_order',
				'label' => t('Commerce Order'),
				'save' => TRUE,
			),
		),
	);

  return $items;
}
