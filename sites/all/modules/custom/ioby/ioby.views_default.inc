<?php
/**
 * @file
 * ioby.views_default.inc
 */

/**
 * Implements hook_views_default_views().
 */
function ioby_views_default_views() {
  $views = array();

  $files = file_scan_directory(drupal_get_path('module', 'ioby'). '/views', '/\.view/');
  foreach ($files as $absolute => $file) {
    require $absolute;
    if (isset($view)) {
      $views[$view->name] = $view;
    }   
  }
  return $views;
}
