<?php

/*
function devel_mail_entity_property_info() {
  $info = array();
  $properties = &$info['devel_mail_log']['properties'];

  $properties['message_sent'] = array(
    'label' => t('Sent'),
    'type' => 'date',
    'description' => t('The Unix timestamp when the e-mail was sent (saved).'),
    'setter callback' => 'entity_property_verbatim_set',
    'setter permission' => 'administer mail log',
    'schema field' => 'message_sent',
  );
  return $info;
}
*/

function devel_mail_entity_property_info_alter(&$info) {
  $test = 1;
  $info['devel_mail_log']['properties']['message_sent']['type'] = 'date';
}