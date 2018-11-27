<?php
/**
 * @file
 * home_page.features.user_permission.inc
 */

/**
 * Implements hook_user_default_permissions().
 */
function home_page_user_default_permissions() {
  $permissions = array();

  // Exported permission: 'access bean overview'.
  $permissions['access bean overview'] = array(
    'name' => 'access bean overview',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'administer bean settings'.
  $permissions['administer bean settings'] = array(
    'name' => 'administer bean settings',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'administer bean types'.
  $permissions['administer bean types'] = array(
    'name' => 'administer bean types',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'administer beans'.
  $permissions['administer beans'] = array(
    'name' => 'administer beans',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'create any impact bean'.
  $permissions['create any impact bean'] = array(
    'name' => 'create any impact bean',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
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

  // Exported permission: 'delete any impact bean'.
  $permissions['delete any impact bean'] = array(
    'name' => 'delete any impact bean',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
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

  // Exported permission: 'edit any impact bean'.
  $permissions['edit any impact bean'] = array(
    'name' => 'edit any impact bean',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
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

  // Exported permission: 'edit bean view mode'.
  $permissions['edit bean view mode'] = array(
    'name' => 'edit bean view mode',
    'roles' => array(
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'view any impact bean'.
  $permissions['view any impact bean'] = array(
    'name' => 'view any impact bean',
    'roles' => array(
      'admin' => 'admin',
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

  // Exported permission: 'view bean page'.
  $permissions['view bean page'] = array(
    'name' => 'view bean page',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  // Exported permission: 'view bean revisions'.
  $permissions['view bean revisions'] = array(
    'name' => 'view bean revisions',
    'roles' => array(
      'Content Admin' => 'Content Admin',
      'admin' => 'admin',
    ),
    'module' => 'bean',
  );

  return $permissions;
}
