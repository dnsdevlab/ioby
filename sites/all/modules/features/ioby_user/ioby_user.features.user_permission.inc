<?php
/**
 * @file
 * ioby_user.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function ioby_user_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'administer CAPTCHA settings'.
  $permissions['administer CAPTCHA settings'] = array(
    'name' => 'administer CAPTCHA settings',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'captcha',
  );

  // Exported permission: 'administer recaptcha'.
  $permissions['administer recaptcha'] = array(
    'name' => 'administer recaptcha',
    'roles' => array(),
    'module' => 'recaptcha',
  );

  // Exported permission: 'skip CAPTCHA'.
  $permissions['skip CAPTCHA'] = array(
    'name' => 'skip CAPTCHA',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'captcha',
  );

  return $permissions;
}
