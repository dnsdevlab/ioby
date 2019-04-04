<?php

/**
 * @author Russom Woldezghi
 */

const SOURCE = 1;
const DESTINATION = 2;

class ProjectMigration extends Migration {
  // Needed to update instead of create new nodes
  protected $systemOfRecord = Migration::SOURCE;

  // Setting up soruces, destination, mapping
  public function __construct($arguments) {
    parent::__construct($arguments);

      // For CSV migrations let PHP handle platform-specific line-endings
    ini_set('auto_detect_line_endings', TRUE);

    $this->description = t('Update Project Nodes with new taxonomy terms.');

      // Team members
    $this->team = array(
      new MigrateTeamMember('Maryam Gunja', 'maryam@ioby.org', t('Client')),
      new MigrateTeamMember('Sara Kaplow', 'kaplow@echo.co', t('Project Manager')),
      new MigrateTeamMember('Russom Woldezghi', 'russom@echo.co', t('Developer')),
      );

      // Pre-built link to JIRA for Ioby
    $this->issuePattern = 'https://echoditto.atlassian.net/browse/IOBY-:id:';

      // The path to the csv file
    $this->path = drupal_get_path('module', 'ioby_migrate') . '/data/project_list_import.csv';

    $columns = array(
     0 => array('TITLE', 'csv_title'),
     1 => array('NODE_ID', 'csv_nid'),
     2 => array('impact_areas', 'csv_impact_areas')
     );
      // Build the source using MigrateSourceCSV() function
    $this->source = new MigrateSourceCSV(
      $this->path,
      $columns,
      array(
        'delimiter' => ',',
        'header_rows' => 1
        )
      );

      // Imported data will got the node
    $this->destination = new MigrateDestinationNode('project_2');

    // Dependencies
    $this->dependencies = array('ImpactAreas');

      // Defines primary key, mapping key
    $this->map = new MigrateSQLMap(
      $this->machineName,
      array(
        'NODE_ID' => array(
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
          'description' => 'Node ID',
          'alias' => 'n',
          )
        ),
      MigrateDestinationNode::getKeySchema()
      );

    // Destination Update: Set to update or add records in destination database
    $this->addFieldMapping('nid', 'NODE_ID')
    ->issueNumber(28);

    // Title, added because it was overridding title
    // If problems arise, load from db instead of CSV file
    $this->addFieldMapping('title', 'TITLE');

    $this->addFieldMapping('field_project_impact_areas', 'impact_areas')
    ->separator(',');

    $this->addFieldMapping('field_project_impact_areas:source_type')
    ->defaultValue('name');

    $this->addFieldMapping('field_project_impact_areas:create_term')
    ->defaultValue(TRUE);

    $this->addFieldMapping('field_project_impact_areas:ignore_case')
    ->defaultValue(TRUE);

    // Node and node revisions module complaining about uid
    $this->addFieldMapping('uid', 'NODE_ID')
    ->callbacks(array($this, 'addNodeUid'))
    ->defaultValue(1);

    // Revision Node User ID
    $this->addFieldMapping('revision_uid')
    ->defaultValue(1);

      // Ignore fields from project_2 node type
    $this->addUnmigratedDestinations(array('log','created','changed','status',
      'promote','sticky','revision','language','tnid','translate',
      'is_new','body','body:summary','body:format','field_project_address',
      'field_project_address:name','field_project_address:street',
      'field_project_address:additional','field_project_address:city',
      'field_project_address:province','field_project_address:postal_code',
      'field_project_address:country','field_project_address:latitude',
      'field_project_address:longitude','field_project_address:source',
      'field_project_address:is_primary','field_project_boroughs',
      'field_project_boroughs:source_type','field_project_boroughs:create_term',
      'field_project_boroughs:ignore_case','field_project_photo',
      'field_project_photo:file_class','field_project_photo:preserve_files',
      'field_project_photo:destination_dir','field_project_photo:destination_file',
      'field_project_photo:file_replace','field_project_photo:source_dir',
      'field_project_photo:urlencode','field_project_photo:alt',
      'field_project_photo:title','field_contact_first','field_contact_last',
      'field_project_contact_email','field_project_contact_phone',
      'field_contact_birth','field_contact_birth:timezone',
      'field_contact_birth:rrule','field_contact_birth:to',
      'field_sponsor_name','field_sponsor_email','field_sponsor_phone',
      'field_with_group','field_group_name','field_group_website',
      'field_group_503','field_group_tax_fiscal','field_group_tax_fisc_spon',
      'field_group_tax_fisc_ioby','field_group_annual','field_group_partners',
      'field_contact_address','field_contact_address:name',
      'field_contact_address:street','field_contact_address:additional',
      'field_contact_address:city','field_contact_address:province',
      'field_contact_address:postal_code','field_contact_address:country',
      'field_contact_address:latitude','field_contact_address:longitude',
      'field_contact_address:source','field_contact_address:is_primary',
      'field_project_desc','field_project_desc:format','field_project_issue',
      'field_project_issue:format','field_project_start_date',
      'field_project_start_date:timezone','field_project_start_date:rrule',
      'field_project_start_date:to','field_project_neighborhood',
      'field_project_results','field_project_results:format',
      'field_demo_age','field_demo_ethnicity','field_demo_other',
      'field_project_anythingelse','field_project_anythingelse:format',
      'field_project_cost','field_project_budget','field_project_budget:format',
      'field_project_volunteers','field_project_vol_reason',
      'field_project_vol_reason:format','field_project_facet',
      'field_project_facet:source_type','field_project_facet:create_term',
      'field_project_facet:ignore_case','field_project_inbrief','field_project_inbrief:format',
      'field_project_status','group_group','field_hide_project_map','field_incentives_instructions',
      'field_incentives','field_incentives:revision_id','field_deadline','field_deadline:timezone',
      'field_deadline:rrule','field_deadline:to','field_project_video','field_project_video:title',
      'field_project_video:attributes','field_project_video:language','path','comment',
      'field_extras_flickr'));

    // Ignore from source
$this->addUnmigratedSources(array(
      // 'TITLE',
  'NODE_ID'
  )
);
}

protected function addNodeUid($row) {
  $node = node_load($row);
  $uid = $node->uid;
  return $uid;
}

public function prepareRow($row) {
  // debug
 // drush_print_r($row);

    // Check row
  // if (parent::prepareRow($row) === FALSE) {
  //   return FALSE;
  // }

    // Check to make sure our node id is a number
  if($row->NODE_ID == '#N/A'){
        // return FALSE;
        // drush_print_r($row->NODE_ID);
   $result = db_query("SELECT n.nid FROM {node} n WHERE n.title = :title AND n.type = :type", array(
    ":title" => $row->title,
    ":type" => 'project_2')
   );
   $nid = $result->fetchField();
   // dpm($nid);
   if (empty($nid)) {
     watchdog('Ioby Project Migration', t('Could not find Node id of '.$row->title.' during migration.'), array(), WATCHDOG_ERROR);
      return FALSE;
   }
 }

}


  /**
   * Runs before an import starts.
   * @author Russom Woldezghi
   *
   * Used to disable any rules which could cause problems during
   * the import.
   *
   * @see https://www.drupal.org/node/1327812
   * Migrate Extras's (hook_init) didn't help
   *
   */
  public function preImport() {
    parent::preImport();
    // Disable rules before import begins
    if (!empty($this->arguments['disable_rules']) && module_exists('rules')) {
      if (!is_array($this->arguments['disable_rules'])) {
        $this->arguments['disable_rules'] = array($this->arguments['disable_rules']);
      }

      $rules = db_select('rules_config', 'rc')
      ->fields('rc', array('name'))
      ->condition('name', $this->arguments['disable_rules'], 'IN')
      ->condition('active', 1)
      ->execute()
      ->fetchAll();
      $this->arguments['disable_rules'] = array();
      foreach ($rules as $rule) {
        $this->arguments['disable_rules'][] = $rule->name;
      }

      // Check again to ensure we didn't just eliminate everything.
      if (!empty($this->arguments['disable_rules'])) {
        db_update('rules_config')
        ->fields(array('active' => 0))
        ->condition('name', $this->arguments['disable_rules'], 'IN')
        ->execute();
        rules_clear_cache(TRUE);
      }
    }
  }

  /**
   * Ran after completion of a migration.
   *
   * Used to turn any rules that were disabled back on.
   */
  public function postImport() {
    parent::postImport();

    if (!empty($this->arguments['disable_rules']) && module_exists('rules')) {
      db_update('rules_config')
      ->fields(array('active' => 1))
      ->condition('name', $this->arguments['disable_rules'], 'IN')
      ->execute();
      rules_clear_cache(TRUE);
    }
  }
}

