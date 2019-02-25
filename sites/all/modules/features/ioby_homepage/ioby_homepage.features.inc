<?php
/**
 * @file
 * ioby_homepage.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function ioby_homepage_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "bean_admin_ui" && $api == "bean") {
    return array("version" => "5");
  }
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_image_default_styles().
 */
function ioby_homepage_image_default_styles() {
  $styles = array();

  // Exported image style: 1400x787.
  $styles['1400x787'] = array(
    'label' => '1400x787',
    'effects' => array(
      12 => array(
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 1400,
          'height' => 787,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: 380x380.
  $styles['380x380'] = array(
    'label' => '380x380',
    'effects' => array(
      11 => array(
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 380,
          'height' => 380,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: 440x216.
  $styles['440x216'] = array(
    'label' => '440x216',
    'effects' => array(
      14 => array(
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 440,
          'height' => 216,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: 530_wide.
  $styles['530_wide'] = array(
    'label' => '530 wide',
    'effects' => array(
      10 => array(
        'name' => 'image_scale',
        'data' => array(
          'width' => 530,
          'height' => '',
          'upscale' => 0,
        ),
        'weight' => 1,
      ),
    ),
  );

  // Exported image style: 631x420.
  $styles['631x420'] = array(
    'label' => '631x420',
    'effects' => array(),
  );

  // Exported image style: 700x394.
  $styles['700x394'] = array(
    'label' => '700x394',
    'effects' => array(
      13 => array(
        'name' => 'image_scale_and_crop',
        'data' => array(
          'width' => 700,
          'height' => 394,
        ),
        'weight' => 1,
      ),
    ),
  );

  return $styles;
}

/**
 * Implements hook_node_info().
 */
function ioby_homepage_node_info() {
  $items = array(
    'homepage' => array(
      'name' => t('Homepage'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
