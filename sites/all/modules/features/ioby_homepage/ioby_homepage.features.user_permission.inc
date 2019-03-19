<?php
/**
 * @file
 * ioby_homepage.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function ioby_homepage_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'create any impact_bean bean'.
  $permissions['create any impact_bean bean'] = array(
    'name' => 'create any impact_bean bean',
    'roles' => array(),
    'module' => 'bean',
  );

  // Exported permission: 'create any ioby_update bean'.
  $permissions['create any ioby_update bean'] = array(
    'name' => 'create any ioby_update bean',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'create any project_feature bean'.
  $permissions['create any project_feature bean'] = array(
    'name' => 'create any project_feature bean',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'create any promo bean'.
  $permissions['create any promo bean'] = array(
    'name' => 'create any promo bean',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'delete any impact_bean bean'.
  $permissions['delete any impact_bean bean'] = array(
    'name' => 'delete any impact_bean bean',
    'roles' => array(),
    'module' => 'bean',
  );

  // Exported permission: 'delete any ioby_update bean'.
  $permissions['delete any ioby_update bean'] = array(
    'name' => 'delete any ioby_update bean',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'delete any project_feature bean'.
  $permissions['delete any project_feature bean'] = array(
    'name' => 'delete any project_feature bean',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'delete any promo bean'.
  $permissions['delete any promo bean'] = array(
    'name' => 'delete any promo bean',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'edit any impact_bean bean'.
  $permissions['edit any impact_bean bean'] = array(
    'name' => 'edit any impact_bean bean',
    'roles' => array(),
    'module' => 'bean',
  );

  // Exported permission: 'edit any ioby_update bean'.
  $permissions['edit any ioby_update bean'] = array(
    'name' => 'edit any ioby_update bean',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'edit any project_feature bean'.
  $permissions['edit any project_feature bean'] = array(
    'name' => 'edit any project_feature bean',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'edit any promo bean'.
  $permissions['edit any promo bean'] = array(
    'name' => 'edit any promo bean',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'view any impact_bean bean'.
  $permissions['view any impact_bean bean'] = array(
    'name' => 'view any impact_bean bean',
    'roles' => array(
      'anonymous user' => 'anonymous user',
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'view any ioby_update bean'.
  $permissions['view any ioby_update bean'] = array(
    'name' => 'view any ioby_update bean',
    'roles' => array(
      'admin' => 'admin',
      'anonymous user' => 'anonymous user',
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'view any project_feature bean'.
  $permissions['view any project_feature bean'] = array(
    'name' => 'view any project_feature bean',
    'roles' => array(
      'admin' => 'admin',
      'anonymous user' => 'anonymous user',
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'view any promo bean'.
  $permissions['view any promo bean'] = array(
    'name' => 'view any promo bean',
    'roles' => array(
      'admin' => 'admin',
      'anonymous user' => 'anonymous user',
      'authenticated user' => 'authenticated user',
    ),
    'module' => 'bean',
  );

  return $permissions;
}
