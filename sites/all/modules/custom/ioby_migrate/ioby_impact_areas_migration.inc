<?php

/**
 * @author Russom Woldezghi
 */

  class ImpactAreasMigration extends Migration {
    // Setting up soruces, destination, mapping
    public function __construct($arguments) {
      parent::__construct($arguments);

    // For CSV migrations let PHP handle platform-specific line-endings
    ini_set('auto_detect_line_endings', TRUE);

    // Migration description
    $this->description = t('Impact Areas new taxonomy terms.');

       // Team members
    $this->team = array(
      new MigrateTeamMember('Maryam Gunja', 'maryam@ioby.org', t('Client')),
      new MigrateTeamMember('Sara Kaplow', 'kaplow@echo.co', t('Project Manager')),
      new MigrateTeamMember('Russom Woldezghi', 'russom@echo.co', t('Developer')),
      );

      // Pre-built link to JIRA for Ioby
    $this->issuePattern = 'https://echoditto.atlassian.net/browse/IOBY-:id:';

      // The path to the csv file
    $this->path = drupal_get_path('module', 'ioby_migrate') . '/data/impact_areas_list.csv';

    $columns = array(
     0 => array('impact_areas_terms_no', 'impact_term_number'),
     1 => array('impact_areas_terms', 'impact_term')
     );
      // Build the source using MigrateSourceCSV() function
    $this->source = new MigrateSourceCSV($this->path, $columns, array('delimiter' => ',', 'header_rows' => 1));

    $this->destination = new MigrateDestinationTerm('impact_areas');

      $this->map = new MigrateSQLMap(
      $this->machineName,
      array(
        'impact_areas_terms_no' => array(
          'type' => 'varchar',
          'length' => 255,
          'not null' => TRUE,
          'description' => 'Terms',
          'alias' => 't',
          )
        ),
        MigrateDestinationTerm::getKeySchema()
      );
      // Mappings
      $this->addFieldMapping('name', 'impact_areas_terms');

      // Do not map term fields
      $this->addUnmigratedDestinations(array('description', 'parent', 'parent_name',
        'format', 'weight', 'path'));

      // Ignore from source
      $this->addUnmigratedSources(array(
        'impact_areas_terms_no'
        )
      );
    }
  }
?>
