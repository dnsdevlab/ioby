<?php
/**
 * @file
 * home_page.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function home_page_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_3up_promo|node|home_page|form';
  $field_group->group_name = 'group_3up_promo';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'home_page';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => '3-Up Promo',
    'weight' => '44',
    'children' => array(
      0 => 'field_promo_blocks',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-3up-promo field-group-fieldset',
        'required_fields' => 1,
        'id' => '',
      ),
    ),
  );
  $field_groups['group_3up_promo|node|home_page|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_brand_hero|node|home_page|form';
  $field_group->group_name = 'group_brand_hero';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'home_page';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Brand Hero',
    'weight' => '4',
    'children' => array(
      0 => 'field_hero_image',
      1 => 'field_static_text',
      2 => 'field_animated_text',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-brand-hero field-group-fieldset',
        'required_fields' => 1,
        'id' => '',
      ),
    ),
  );
  $field_groups['group_brand_hero|node|home_page|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_browse_cities|node|home_page|form';
  $field_group->group_name = 'group_browse_cities';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'home_page';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Browse Cities',
    'weight' => '40',
    'children' => array(
      0 => 'field_browse_cities_title',
      1 => 'field_city_link',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-browse-cities field-group-fieldset',
        'required_fields' => 1,
        'id' => '',
      ),
    ),
  );
  $field_groups['group_browse_cities|node|home_page|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_email_cta|node|home_page|form';
  $field_group->group_name = 'group_email_cta';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'home_page';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Email CTA',
    'weight' => '6',
    'children' => array(
      0 => 'field_email_cta_title_line_one',
      1 => 'field_email_cta_title_line_two',
      2 => 'field_form_supertitle',
      3 => 'field_social_supertitle',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-email-cta field-group-fieldset',
        'required_fields' => 1,
        'id' => '',
      ),
    ),
  );
  $field_groups['group_email_cta|node|home_page|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_ioby_impact|node|home_page|form';
  $field_group->group_name = 'group_ioby_impact';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'home_page';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'ioby Impact',
    'weight' => '5',
    'children' => array(
      0 => 'field_title_line_one',
      1 => 'field_title_line_two',
      2 => 'field_impact_blocks',
      3 => 'field_link',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-ioby-impact field-group-fieldset',
        'required_fields' => 1,
        'id' => '',
      ),
    ),
  );
  $field_groups['group_ioby_impact|node|home_page|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_ioby_updates|node|home_page|form';
  $field_group->group_name = 'group_ioby_updates';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'home_page';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'ioby Updates',
    'weight' => '42',
    'children' => array(
      0 => 'field_ioby_update_blocks',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-ioby-updates field-group-fieldset',
        'required_fields' => 1,
        'id' => '',
      ),
    ),
  );
  $field_groups['group_ioby_updates|node|home_page|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_overrides|bean|project_feature|form';
  $field_group->group_name = 'group_overrides';
  $field_group->entity_type = 'bean';
  $field_group->bundle = 'project_feature';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Overrides',
    'weight' => '7',
    'children' => array(
      0 => 'field_image',
      1 => 'field_project_title',
      2 => 'field_city',
      3 => 'field_state',
      4 => 'field_summary',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Overrides',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-overrides field-group-fieldset',
        'description' => 'By default, the values for these fields are pulled from the Project node referenced above. You can override any of these values by entering a new value below. The overrides only affect the Project Feature section of the site, and won\'t change the Project node itself.',
      ),
      'formatter' => 'collapsible',
    ),
  );
  $field_groups['group_overrides|bean|project_feature|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_project_feature|node|home_page|form';
  $field_group->group_name = 'group_project_feature';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'home_page';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Project Feature',
    'weight' => '36',
    'children' => array(
      0 => 'field_project_feature_title',
      1 => 'field_project_features_blocks',
      2 => 'field_start_a_project_cta_text',
      3 => 'field_start_a_project_cta_link',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-project-feature field-group-fieldset',
        'required_fields' => 1,
        'id' => '',
      ),
    ),
  );
  $field_groups['group_project_feature|node|home_page|form'] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('3-Up Promo');
  t('Brand Hero');
  t('Browse Cities');
  t('Email CTA');
  t('Overrides');
  t('Project Feature');
  t('ioby Impact');
  t('ioby Updates');

  return $field_groups;
}
