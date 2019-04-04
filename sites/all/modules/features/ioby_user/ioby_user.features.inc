<?php
/**
 * @file
 * ioby_user.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function ioby_user_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "captcha" && $api == "captcha") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
