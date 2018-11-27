<?php
/**
 * @file
 * project_form_steps.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function project_form_steps_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_about_step|node|project_2|form';
  $field_group->group_name = 'group_about_step';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'About You',
    'weight' => '2',
    'children' => array(
      0 => 'field_contact_first',
      1 => 'field_contact_last',
      2 => 'field_project_contact_email',
      3 => 'field_contact_birth',
      4 => 'field_with_group',
      5 => 'group_info',
      6 => 'group_sponsor',
    ),
    'format_type' => 'multistep',
    'format_settings' => array(
      'label' => 'About You',
      'instance_settings' => array(
        'description' => 'Provide us with sponsorship information so that you can collect tax-deductible donations on ioby! Birthdate and sponsor info will be kept private and are for our records only. ',
        'nav_label' => '',
      ),
    ),
  );
  $field_groups['group_about_step|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_additional_info|node|project_2|form';
  $field_group->group_name = 'group_additional_info';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_removed_items';
  $field_group->data = array(
    'label' => 'Additional Info',
    'weight' => '48',
    'children' => array(
      0 => 'field_project_results',
      1 => 'field_project_anythingelse',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'classes' => ' group-additional-info field-group-fieldset',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_additional_info|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_budget_results_step|node|project_2|form';
  $field_group->group_name = 'group_budget_results_step';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Project Budget & Fees',
    'weight' => '1',
    'children' => array(),
    'format_type' => 'multistep',
    'format_settings' => array(
      'label' => 'Project Budget & Fees',
      'instance_settings' => array(
        'description' => 'In this next section, lock down your budget plan and fiscal sponsorship information.',
        'nav_label' => 'Project Budget &<br/> Fees',
      ),
    ),
  );
  $field_groups['group_budget_results_step|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_contact_info|node|project_2|form';
  $field_group->group_name = 'group_contact_info';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_removed_items';
  $field_group->data = array(
    'label' => 'About yourself or your group',
    'weight' => '45',
    'children' => array(
      0 => 'field_project_contact_phone',
      1 => 'field_contact_address',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'classes' => ' group-contact-info field-group-fieldset',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_contact_info|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_demographics|node|project_2|form';
  $field_group->group_name = 'group_demographics';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_removed_items';
  $field_group->data = array(
    'label' => 'Relevant Population(s)',
    'weight' => '50',
    'children' => array(
      0 => 'field_demo_age',
      1 => 'field_demo_ethnicity',
      2 => 'field_demo_other',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Relevant Population(s)',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => ' group-demographics field-group-fieldset',
        'description' => 'Can you give us a sense of the population(s) you are going to be working with or serving through your project? Please mark any.',
      ),
      'formatter' => 'collapsible',
    ),
  );
  $field_groups['group_demographics|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_extras|node|project_2|form';
  $field_group->group_name = 'group_extras';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_removed_items';
  $field_group->data = array(
    'label' => 'Extras to make your project extra awesome',
    'weight' => '49',
    'children' => array(
      0 => 'field_extras_flickr',
      1 => 'field_project_facet',
      2 => 'group_group',
      3 => 'locations',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'formatter' => 'collapsible',
      'instance_settings' => array(
        'classes' => ' group-extras field-group-fieldset',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_extras|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_giving_level|node|project_2|form';
  $field_group->group_name = 'group_giving_level';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_volunteers_giving_step';
  $field_group->data = array(
    'label' => 'Giving Levels',
    'weight' => '8',
    'children' => array(
      0 => 'field_incentives_instructions',
      1 => 'field_incentives',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Giving Levels',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => 'group-giving-level field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $field_groups['group_giving_level|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_info|node|project_2|form';
  $field_group->group_name = 'group_info';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_about_step';
  $field_group->data = array(
    'label' => 'Fiscal Sponsorship Information',
    'weight' => '38',
    'children' => array(
      0 => 'field_group_name',
      1 => 'field_group_website',
      2 => 'field_group_503',
      3 => 'field_group_annual',
      4 => 'field_group_partners',
      5 => 'group_tax_info',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Fiscal Sponsorship Information',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => ' group-info field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_info|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_project_about_step|node|project_2|form';
  $field_group->group_name = 'group_project_about_step';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Project Information',
    'weight' => '0',
    'children' => array(
      0 => 'group_project_about',
    ),
    'format_type' => 'multistep',
    'format_settings' => array(
      'label' => 'Project Information',
      'instance_settings' => array(
        'description' => 'This is where your project comes to life! Your project page is the place you\'ll be able to share your goals, passion, and motivation with donors and supporters. At this stage, focus on submitting a rough version of the form instead of finalizing your info. As you walk through the multiple sections of the form remember to save along the way so that you can pick up where you left off. After you submit, ioby will work with you on the final polish before it goes live.',
        'nav_label' => 'Project<br/> Information',
      ),
    ),
  );
  $field_groups['group_project_about_step|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_project_about|node|project_2|form';
  $field_group->group_name = 'group_project_about';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_project_about_step';
  $field_group->data = array(
    'label' => '',
    'weight' => '3',
    'children' => array(
      0 => 'field_project_address',
      1 => 'field_project_neighborhood',
      2 => 'field_project_inbrief',
      3 => 'field_hide_project_map',
      4 => 'field_deadline',
      5 => 'title',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => '',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => ' group-project-about field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $field_groups['group_project_about|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_project_administration|node|project_2|form';
  $field_group->group_name = 'group_project_administration';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Project Administration',
    'weight' => '6',
    'children' => array(
      0 => 'field_project_status',
      1 => 'field_remove_salesforce_id',
      2 => 'salesforce_record_id',
      3 => 'create_new_sf_object',
      4 => 'path',
      5 => 'redirect',
    ),
    'format_type' => 'tab',
    'format_settings' => array(
      'formatter' => 'closed',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-project-administration field-group-tab',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_project_administration|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_project_budget_step|node|project_2|form';
  $field_group->group_name = 'group_project_budget_step';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Project Budget',
    'weight' => '1',
    'children' => array(
      0 => 'group_project_budget',
    ),
    'format_type' => 'multistep',
    'format_settings' => array(
      'label' => 'Project Budget',
      'instance_settings' => array(
        'description' => 'It\'s time to think through the budget required to make your project a success! This budget information will display publicly on your project page so that donors can learn more about the resources and support you need.',
        'nav_label' => 'Project<br/>Budget',
      ),
    ),
  );
  $field_groups['group_project_budget_step|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_project_budget|node|project_2|form';
  $field_group->group_name = 'group_project_budget';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_project_budget_step';
  $field_group->data = array(
    'label' => 'Project Budget',
    'weight' => '7',
    'children' => array(
      0 => 'field_project_start_date',
      1 => 'field_project_cost',
      2 => 'field_project_budget',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Project Budget',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => ' group-project-budget field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $field_groups['group_project_budget|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_project_story_step|node|project_2|form';
  $field_group->group_name = 'group_project_story_step';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Project Story',
    'weight' => '3',
    'children' => array(
      0 => 'body',
      1 => 'field_project_desc',
      2 => 'field_project_issue',
      3 => 'field_project_impact_areas',
    ),
    'format_type' => 'multistep',
    'format_settings' => array(
      'label' => 'Project Story',
      'instance_settings' => array(
        'description' => 'Now that we\'re through the nuts and bolts it\'s time for us to learn more about the story behind the project! Share background on what you\'re hoping to accomplish through this project and why it\'s important to you. The content below will display on your public facing project page so remember to be specific and direct.',
        'nav_label' => 'Project<br/> Story',
      ),
    ),
  );
  $field_groups['group_project_story_step|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_removed_items|node|project_2|form';
  $field_group->group_name = 'group_removed_items';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Removed Items',
    'weight' => '5',
    'children' => array(
      0 => 'field_project_boroughs',
      1 => 'field_sponsor_phone',
      2 => 'field_group_tax_fisc_ioby',
      3 => 'field_steps_complete',
      4 => 'group_additional_info',
      5 => 'group_contact_info',
      6 => 'group_demographics',
      7 => 'group_extras',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Removed Items',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => '',
        'description' => '',
      ),
      'formatter' => 'collapsible',
    ),
  );
  $field_groups['group_removed_items|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_sponsor|node|project_2|form';
  $field_group->group_name = 'group_sponsor';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_about_step';
  $field_group->data = array(
    'label' => 'Sponsor',
    'weight' => '36',
    'children' => array(
      0 => 'field_sponsor_name',
      1 => 'field_sponsor_email',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Sponsor',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => ' group-sponsor field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $field_groups['group_sponsor|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_tax_info|node|project_2|form';
  $field_group->group_name = 'group_tax_info';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_info';
  $field_group->data = array(
    'label' => 'Group Tax info',
    'weight' => '18',
    'children' => array(
      0 => 'field_group_tax_fiscal',
      1 => 'field_group_tax_fisc_spon',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Group Tax info',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => ' group-tax-info field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_tax_info|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_volunteer_info|node|project_2|form';
  $field_group->group_name = 'group_volunteer_info';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_volunteers';
  $field_group->data = array(
    'label' => 'Volunteer Info',
    'weight' => '18',
    'children' => array(
      0 => 'field_project_vol_reason',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Volunteer Info',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => ' group-volunteer-info field-group-fieldset',
        'description' => 'Great! We’ll allow people to sign up through our website and notify you by email when they do. They won’t have your contact information, but you’ll have theirs so you can communicate with them however you need to. If you have a public email address or phone number used to coordinate volunteers, you can include that here.',
      ),
      'formatter' => 'collapsed',
    ),
  );
  $field_groups['group_volunteer_info|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_volunteers_giving_step|node|project_2|form';
  $field_group->group_name = 'group_volunteers_giving_step';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Extras',
    'weight' => '4',
    'children' => array(
      0 => 'field_project_photo',
      1 => 'field_project_video',
      2 => 'group_giving_level',
      3 => 'group_volunteers',
    ),
    'format_type' => 'multistep',
    'format_settings' => array(
      'label' => 'Extras',
      'instance_settings' => array(
        'description' => 'You\'re at the home stretch! Take advantage of ioby extra features to spread the word about your project and compel your donors to give.',
        'nav_label' => 'Extras <br/>',
      ),
    ),
  );
  $field_groups['group_volunteers_giving_step|node|project_2|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_volunteers|node|project_2|form';
  $field_group->group_name = 'group_volunteers';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'project_2';
  $field_group->mode = 'form';
  $field_group->parent_name = 'group_volunteers_giving_step';
  $field_group->data = array(
    'label' => 'Volunteer Work',
    'weight' => '7',
    'children' => array(
      0 => 'field_project_volunteers',
      1 => 'group_volunteer_info',
    ),
    'format_type' => 'fieldset',
    'format_settings' => array(
      'label' => 'Volunteer Work',
      'instance_settings' => array(
        'required_fields' => 1,
        'id' => '',
        'classes' => ' group-volunteers field-group-fieldset',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $field_groups['group_volunteers|node|project_2|form'] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('About You');
  t('About yourself or your group');
  t('Additional Info');
  t('Extras');
  t('Extras to make your project extra awesome');
  t('Fiscal Sponsorship Information');
  t('Giving Levels');
  t('Group Tax info');
  t('Project Administration');
  t('Project Budget');
  t('Project Budget & Fees');
  t('Project Information');
  t('Project Story');
  t('Relevant Population(s)');
  t('Removed Items');
  t('Sponsor');
  t('Volunteer Info');
  t('Volunteer Work');

  return $field_groups;
}
