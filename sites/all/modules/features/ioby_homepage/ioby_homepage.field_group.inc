<?php
/**
 * @file
 * ioby_homepage.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function ioby_homepage_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_3up_promo|node|homepage|form';
  $field_group->group_name = 'group_3up_promo';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'homepage';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_tabs';
  $field_group->data = array(
    'label' => '3-Up Promo',
    'weight' => '8',
    'children' => array(
      0 => 'field_promos',
    ),
    'format_type' => 'htab',
    'format_settings' => array(
      'formatter' => 'closed',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-3up-promo field-group-htab',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_3up_promo|node|homepage|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_brand_hero|node|homepage|form';
  $field_group->group_name = 'group_brand_hero';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'homepage';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_tabs';
  $field_group->data = array(
    'label' => 'Brand Hero',
    'weight' => '2',
    'children' => array(
      0 => 'field_hero_image',
      1 => 'field_hero_static_text',
      2 => 'field_hero_animated_text',
    ),
    'format_type' => 'htab',
    'format_settings' => array(
      'formatter' => 'closed',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-brand-hero field-group-htab',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_brand_hero|node|homepage|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_browse_cities|node|homepage|form';
  $field_group->group_name = 'group_browse_cities';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'homepage';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_tabs';
  $field_group->data = array(
    'label' => 'Browse Cities',
    'weight' => '6',
    'children' => array(
      0 => 'field_cities_title',
      1 => 'field_cities_link',
    ),
    'format_type' => 'htab',
    'format_settings' => array(
      'formatter' => 'closed',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-browse-cities field-group-htab',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_browse_cities|node|homepage|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_email_cta|node|homepage|form';
  $field_group->group_name = 'group_email_cta';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'homepage';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_tabs';
  $field_group->data = array(
    'label' => 'Email CTA',
    'weight' => '4',
    'children' => array(
      0 => 'field_email_title_line_1',
      1 => 'field_email_title_line_2',
      2 => 'field_email_form_supertitle',
      3 => 'field_email_social_supertitle',
    ),
    'format_type' => 'htab',
    'format_settings' => array(
      'formatter' => 'closed',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-email-cta field-group-htab',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_email_cta|node|homepage|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_ioby_impact|node|homepage|form';
  $field_group->group_name = 'group_ioby_impact';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'homepage';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_tabs';
  $field_group->data = array(
    'label' => 'Ioby Impact',
    'weight' => '3',
    'children' => array(
      0 => 'field_impact_blocks',
      1 => 'field_impact_title_line_1',
      2 => 'field_impact_title_line_2',
      3 => 'field_impact_link',
    ),
    'format_type' => 'htab',
    'format_settings' => array(
      'formatter' => 'closed',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-ioby-impact field-group-htab',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_ioby_impact|node|homepage|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_ioby_updates|node|homepage|form';
  $field_group->group_name = 'group_ioby_updates';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'homepage';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_tabs';
  $field_group->data = array(
    'label' => 'Ioby Updates',
    'weight' => '7',
    'children' => array(
      0 => 'field_ioby_updates_blocks',
    ),
    'format_type' => 'htab',
    'format_settings' => array(
      'formatter' => 'closed',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-ioby-updates field-group-htab',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_ioby_updates|node|homepage|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_project_feature|node|homepage|form';
  $field_group->group_name = 'group_project_feature';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'homepage';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_tabs';
  $field_group->data = array(
    'label' => 'Project Feature',
    'weight' => '5',
    'children' => array(
      0 => 'field_project_title',
      1 => 'field_project_feature_blocks',
      2 => 'field_project_cta_text',
      3 => 'field_project_cta_link',
    ),
    'format_type' => 'htab',
    'format_settings' => array(
      'formatter' => 'closed',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-project-feature field-group-htab',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_project_feature|node|homepage|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_tabs|node|homepage|form';
  $field_group->group_name = 'group_tabs';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'homepage';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Tabs',
    'weight' => '4',
    'children' => array(
      0 => 'group_brand_hero',
      1 => 'group_ioby_impact',
      2 => 'group_email_cta',
      3 => 'group_project_feature',
      4 => 'group_browse_cities',
      5 => 'group_ioby_updates',
      6 => 'group_3up_promo',
    ),
    'format_type' => 'htabs',
    'format_settings' => array(
      'formatter' => '',
      'instance_settings' => array(
        'classes' => 'group-tabs field-group-htabs',
        'id' => '',
      ),
    ),
  );
  $field_groups['group_tabs|node|homepage|form'] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('3-Up Promo');
  t('Brand Hero');
  t('Browse Cities');
  t('Email CTA');
  t('Ioby Impact');
  t('Ioby Updates');
  t('Project Feature');
  t('Tabs');

  return $field_groups;
}
