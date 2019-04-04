<?php

/**
 * @file
 * Drush integration for kint.
 */

/**
 * Implements hook_drush_command().
 *
 * In this hook, you specify which commands your
 * drush module makes available, what it does and
 * description.
 *
 * Notice how this structure closely resembles how
 * you define menu hooks.
 *
 * See `drush topic docs-commands` for a list of recognized keys.
 *
 * @return array
 *   An associative array describing your command(s).
 */
function kint_drush_command() {
  $items = array();

  // the key in the $items array is the name of the command.
  $items['kint-download'] = array(
    'callback'    => 'drush_kint_download',
    'description' => dt("Downloads the Kint library."),
    'bootstrap'   => DRUSH_BOOTSTRAP_DRUPAL_SITE,
    'arguments'   => array(
      'path' => dt('Optional. A path where to install the Kint library. If omitted Drush will use the default location.'),
    ),
    'aliases'     => array('kintplugin'),
  );

  return $items;
}

/**
 * Implements hook_drush_help().
 *
 * This function is called whenever a drush user calls
 * 'drush help <name-of-your-command>'
 *
 * @param
 *   A string with the help section (prepend with 'drush:')
 *
 * @return null|string
 *   A string with the help text for your command.
 *
 * @see hook_drush_help()
 */
function kint_drush_help($section) {
  switch ($section) {
    case 'drush:kint-download':
      return dt("Downloads the Kint library, default location is sites/all/libraries.");
  }
}

/**
 * Command to download the Kint library.
 */
function drush_kint_download() {
  if (drush_confirm(dt('Do you want to download Kint library?'))) {
    $args = func_get_args();
    if (!empty($args[0])) {
      $path = $args[0];
    }
    else {
      $path = 'sites/all/libraries';
    }

    // Create the path if it does not exist.
    if (!is_dir($path)) {
      drush_op('mkdir', $path);
      drush_log(dt('Directory @path was created', array('@path' => $path)), 'notice');
    }

    // Set the directory to the download location.
    $olddir = getcwd();
    chdir($path);

    // Download the zip archive
    $filepath = drush_download_file(KINT_DOWNLOAD_ARCHIVE);

    if ($filepath) {
      $filename = basename($filepath);
      $dirname = 'kint-' . basename($filepath, '.zip');

      // Remove any existing Kint library directory
      if (is_dir($dirname) || is_dir('kint')) {
        drush_delete_dir($dirname, TRUE);
        drush_delete_dir('kint', TRUE);
        drush_log(dt('A existing Kint library was deleted from @path', array('@path' => $path)), 'notice');
      }

      // Decompress the zip archive
      drush_tarball_extract($filename);

      // Change the directory name to "kint" if needed.
      if ($dirname != 'kint') {
        drush_move_dir($dirname, 'kint', TRUE);
        $dirname = 'kint';
      }
    }

    if (is_dir($dirname)) {
      drush_log(dt('Kint library has been uploaded to @path', array('@path' => $path)), 'success');
    }
    else {
      drush_log(dt('Drush was unable to upload the Kint library to @path', array('@path' => $path)), 'error');
    }

    // Set working directory back to the previous working directory.
    chdir($olddir);
  }
}

/**
 * Take action after a project has been downloaded.
 */
function drush_kint_post_pm_enable() {
  $args = func_get_args();

  // reflect only on enabling of Kint module
  if (in_array('kint', $args)) {
    drush_kint_download();
  }
}