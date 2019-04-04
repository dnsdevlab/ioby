<?php

/**
 * Implements of hook_default_rules_configuration().
 */
function iobyproject_default_rules_configuration() {
  $items = array();

  $items['ioby_project_funded_notification'] = rules_import(
    '{ "ioby_project_funded_notification" : {
        "LABEL" : "IOBY - Project Funded Notification",
        "PLUGIN" : "reaction rule",
        "OWNER" : "rules",
        "TAGS" : [ "ioby emails" ],
        "REQUIRES" : [ "rules", "iobyproject" ],
        "ON" : { "iobyproject_project_funded" : [] },
        "DO" : [
          { "mail" : {
              "to" : "notifications@ioby.org",
              "subject" : "Project Fully Funded: [node:title]",
              "message" : "Dear Ioby Team,\r\n\r\nThe project: [node:title]  has reached it\u0027s funding goal. You can edit the project here: [node:edit-url].\r\n\r\nThanks,\r\nThe ioby server",
              "from" : "[site:mail]",
              "language" : [ "" ]
            }
          }
        ]
      }
    }'
  );

  $items['ioby_send_project_percentage_complete'] = rules_import(
    '{ "ioby_send_project_percentage_complete" : {
        "LABEL" : "IOBY - Project Completion Percentage Notification",
        "PLUGIN" : "reaction rule",
        "OWNER" : "rules",
        "TAGS" : [ "ioby emails" ],
        "REQUIRES" : [ "rules", "iobyproject" ],
        "ON" : { "iobyproject_project_funding_threshold_reached" : [] },
        "DO" : [
          { "mail" : {
              "to" : "notifications@ioby.org",
              "subject" : "Dear Ioby Team,\r\n\r\nThe project: [node:title]  has reached [funding-threshold:value]% of it\u0027s funding goal! You can edit the project here: [node:edit-url].\r\n\r\nThanks,\r\nThe ioby server\r\n",
              "message" : "Project: [node:title] has reached [funding-threshold:value]% of it\u0027s funding goal!",
              "from" : "[site:mail]",
              "language" : [ "" ]
            }
          }
        ]
      }
    }'
  );
  return $items;
}
