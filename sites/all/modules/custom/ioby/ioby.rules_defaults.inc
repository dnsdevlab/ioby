<?php

/**
 * @file
 * Provides default rules definitions for the ioby module.
 */

/**
 * Implements of hook_default_rules_configuration().
 */
function ioby_default_rules_configuration() {
  $items = array();

  $items['ioby_commerce_checkout_new_account'] = rules_import(
    '{ "ioby_commerce_checkout_new_account" : {
        "LABEL" : "IOBY - Create a new account for an anonymous order",
        "PLUGIN" : "reaction rule",
        "WEIGHT" : "2",
        "OWNER" : "rules",
        "REQUIRES" : [ "rules", "commerce", "commerce_checkout" ],
        "ON" : { "commerce_checkout_complete" : [] },
        "IF" : [
          { "data_is" : { "data" : [ "commerce-order:uid" ], "value" : "0" } },
          { "NOT entity_exists" : {
              "type" : "user",
              "property" : "mail",
              "value" : [ "commerce-order:mail" ]
            }
          },
          { "data_is" : { "data" : [ "commerce-order:type" ], "value" : "commerce_order" } }
        ],
        "DO" : [
          { "entity_create" : {
              "USING" : {
                "type" : "user",
                "param_field_user_type" : "individual",
                "param_name" : [ "commerce-order:mail-username" ],
                "param_mail" : [ "commerce-order:mail" ]
              },
              "PROVIDE" : { "entity_created" : { "account_created" : "Created account" } }
            }
          },
          { "data_set" : { "data" : [ "account-created:status" ], "value" : 1 } },
          { "entity_save" : { "data" : [ "account-created" ], "immediate" : 1 } },
          { "entity_query" : {
              "USING" : {
                "type" : "user",
                "property" : "mail",
                "value" : [ "commerce-order:mail" ],
                "limit" : 1
              },
              "PROVIDE" : { "entity_fetched" : { "account_fetched" : "Fetched account" } }
            }
          },
          { "LOOP" : {
              "USING" : { "list" : [ "account-fetched" ] },
              "ITEM" : { "list_item" : "Current list item" },
              "DO" : [
                { "data_set" : { "data" : [ "commerce-order:uid" ], "value" : [ "list-item:uid" ] } },
                { "data_set" : {
                    "data" : [ "commerce-order:commerce-customer-billing:uid" ],
                    "value" : [ "list-item:uid" ]
                  }
                }
              ]
            }
          }
        ]
      }
    }'
  );

  $items['ioby_project_creation'] = rules_import(
    '{ "ioby_project_creation" : {
        "LABEL" : "IOBY - Project Creation Notification",
        "PLUGIN" : "reaction rule",
        "OWNER" : "rules",
        "TAGS" : [ "ioby emails" ],
        "REQUIRES" : [ "rules" ],
        "ON" : { "node_insert" : [] },
        "IF" : [
          { "node_is_of_type" : {
              "node" : [ "node" ],
              "type" : { "value" : { "project_2" : "project_2" } }
            }
          }
        ],
        "DO" : [
          { "mail" : {
              "to" : "notifications@ioby.org",
              "subject" : "New Project created!",
              "message" : "A new project called [node:title] was created by [node:author]. Please review it at [node:url]",
              "language" : [ "" ]
            }
          }
        ]
      }
    }'
  );

  $items['ioby_commerce_payment_authnet_aim'] = rules_import(
    '{ "ioby_commerce_payment_authnet_aim" : {
        "LABEL" : "IOBY - Authorize.Net AIM - Credit Card",
        "PLUGIN" : "reaction rule",
        "REQUIRES" : [ "commerce_payment" ],
        "ON" : [ "commerce_payment_methods" ],
        "DO" : [
          { "commerce_payment_enable_authnet_aim" : {
              "commerce_order" : [ "commerce-order" ],
              "payment_method" : { "value" : {
                  "method_id" : "authnet_aim",
                  "settings" : {
                    "login" : "5EKXd28jnjJ",
                    "tran_key" : "5jk9JD8jYEp265LJ",
                    "txn_mode" : "live",
                    "txn_type" : "auth_capture",
                    "email_customer" : 0,
                    "log" : { "request" : "request", "response" : "response" }
                  }
                }
              }
            }
          }
        ]
      }
    }'
  );

  $items['ioby_project_created_redirect'] = rules_import(
    '{ "ioby_project_created_redirect" : {
        "LABEL" : "IOBY - Project Created Redirect",
        "PLUGIN" : "reaction rule",
        "REQUIRES" : [ "rules" ],
        "ON" : [ "node_insert" ],
        "IF" : [
          { "node_is_of_type" : {
              "node" : [ "node" ],
              "type" : { "value" : { "project_2" : "project_2" } }
            }
          }
        ],
        "DO" : [ { "redirect" : { "url" : "add\/project\/step\/4" } } ]
      }
    }'
  );

  $items['ioby_checkout_payment_complete'] = rules_import(
    '{ "ioby_checkout_payment_complete" : {
        "LABEL" : "IOBY - Update the order status on checkout payment completion",
        "PLUGIN" : "reaction rule",
        "REQUIRES" : [ "commerce_payment", "commerce_order", "commerce_checkout" ],
        "ON" : [ "commerce_checkout_complete" ],
        "IF" : [
          { "commerce_payment_order_balance_comparison" : { "commerce_order" : [ "commerce_order" ], "value" : "0" } }
        ],
        "DO" : [
          { "commerce_order_update_state" : { "commerce_order" : [ "commerce-order" ], "order_state" : "completed" } }
        ]
      }
    }'
  );

  // Creates donation node after checkout is complete.
  $items['ioby_create_donation_node'] = rules_import(
    '{ "ioby_create_donation_node" : {
        "LABEL" : "IOBY - Create Donation Node",
        "PLUGIN" : "reaction rule",
        "WEIGHT" : "5",
        "TAGS" : [ "ioby" ],
        "REQUIRES" : [ "iobydonation", "commerce_checkout" ],
        "ON" : [ "commerce_checkout_complete" ],
        "DO" : [
          { "iobydonation_after_checkout_complete" : { "commerce_order" : [ "commerce_order" ] } }
        ]
      }
    }'
  );

  $items['ioby_email_new_comment_posted'] = rules_import(
    '{ "ioby_email_new_comment_posted" : {
        "LABEL" : "IOBY - Email on New Comment Posted",
        "PLUGIN" : "reaction rule",
        "TAGS" : [ "ioby emails" ],
        "REQUIRES" : [ "rules", "comment" ],
        "ON" : { "comment_insert" : [] },
        "DO" : [
          { "mail" : {
              "to" : "notifications@ioby.org",
              "subject" : "A new comment has been posted on [site:name]",
              "message" : "A new comment has been posted on [site:name] at [site:current-date:other_short_datetime].\r\n\r\n[comment:name] has commented on [comment:node:title] ([comment:node:url:absolute]).\r\n\r\nTo manage comments, visit: [site:url]admin\/content\/comment",
              "from" : "[site:mail]",
              "language" : [ "" ]
            }
          }
        ]
      }
    }'
  );

  $items['ioby_new_donation_order_notification'] = rules_import(
    '{ "ioby_new_donation_order_notification" : {
        "LABEL" : "IOBY - New Donation Order Notification",
        "PLUGIN" : "reaction rule",
        "WEIGHT" : "10",
        "OWNER" : "rules",
        "TAGS" : [ "ioby emails" ],
        "REQUIRES" : [ "rules", "commerce_checkout" ],
        "ON" : { "commerce_checkout_complete" : [] },
        "IF" : [
          { "data_is" : { "data" : [ "commerce-order:status" ], "value" : "completed" } }
        ],
        "DO" : [
          { "mail" : {
              "to" : "notifications@ioby.org",
              "subject" : "Donation Order Receipt",
              "message" : "Donation By: [commerce-order:commerce-customer-billing:commerce_customer_address:name-line]\r\nDate: [commerce-order:created]\r\nOrder: [commerce-order:order-id]\r\nTotal: $[commerce-order:commerce-order-total:amount_decimal]\r\n\r\nYou can view the full order details here: [commerce-order:admin-url]",
              "from" : "[site:mail]"
            }
          }
        ]
      }
    }'
  );

  $items['ioby_donation_node_created_notification'] = rules_import(
    '{ "ioby_donation_node_created_notification" : {
        "LABEL" : "IOBY - Donation Node Created Notification",
        "PLUGIN" : "reaction rule",
        "OWNER" : "rules",
        "TAGS" : [ "ioby emails" ],
        "REQUIRES" : [ "rules" ],
        "ON" : { "node_insert" : [] },
        "IF" : [
          { "node_is_of_type" : {
              "node" : [ "node" ],
              "type" : { "value" : { "donation" : "donation" } }
            }
          },
          { "data_is" : { "data" : [ "node:field-entry-type" ], "value" : "website" } }
        ],
        "DO" : [
          { "mail" : {
              "to" : "notifications@ioby.org",
              "subject" : "New Donation to [node:field-donation-project:title]",
              "message" : "A new donation of $[node:field-donation-amount] has been made to [node:field-donation-project] by [node:field-donation-user:mail]. Please review it at [node:url]",
              "from" : "[site:mail]",
              "language" : [ "" ]
            }
          }
        ]
      }
    }'
  );

  $items['ioby_project_update_created_notification'] = rules_import(
    '{ "ioby_project_update_created_notification" : {
        "LABEL" : "IOBY - Project Update Created Notification",
        "PLUGIN" : "reaction rule",
        "OWNER" : "rules",
        "TAGS" : [ "ioby emails" ],
        "REQUIRES" : [ "rules" ],
        "ON" : { "node_insert" : [] },
        "IF" : [
          { "node_is_of_type" : {
              "node" : [ "node" ],
              "type" : { "value" : { "project_blog" : "project_blog" } }
            }
          }
        ],
        "DO" : [
          { "mail" : {
              "to" : "notifications@ioby.org",
              "subject" : "New Project Update posted to [node:project-group:title]",
              "message" : "A new project update titled \u0022[node:title]\u0022 been posted to the project: [node:project-group:title].\r\n\r\nDate: [site:current-date:other_short_datetime]\r\nCreated By: [site:current-user] ([site:current-user:url])\r\nProject Update Edit URL: [node:edit-url]\r\nProject URL: [node:project-group:url]",
              "from" : "[site:mail]",
              "language" : [ "" ]
            }
          }
        ]
      }
    }'
  );

  $items['ioby_project_update_edited_notification'] = rules_import(
    '{ "ioby_project_update_edited_notification" : {
        "LABEL" : "IOBY - Project Update Edited Notification",
        "PLUGIN" : "reaction rule",
        "OWNER" : "rules",
        "TAGS" : [ "ioby emails" ],
        "REQUIRES" : [ "rules" ],
        "ON" : { "node_update--project_blog" : { "bundle" : "project_blog" } },
        "DO" : [
          { "mail" : {
              "to" : "notifications@ioby.org",
              "subject" : "The project update [node-unchanged:title] has been modified",
              "message" : "The project update [node-unchanged:title] has been modified. Here are the edits: [node:diff-markdown]\r\n\r\n",
              "from" : "[site:mail]",
              "language" : [ "" ]
            }
          }
        ]
      }
    }'
  );

  $items['ioby_project_update_deleted_notification'] = rules_import(
    '{ "ioby_project_update_deleted_notification" : {
        "LABEL" : "IOBY - Project Update Deleted Notification",
        "PLUGIN" : "reaction rule",
        "OWNER" : "rules",
        "TAGS" : [ "ioby emails" ],
        "REQUIRES" : [ "rules" ],
        "ON" : { "node_delete--project_blog" : { "bundle" : "project_blog" } },
        "DO" : [
          { "mail" : {
              "to" : "notifications@ioby.org",
              "subject" : "Project Update has been removed from [node:project-group:title]",
              "message" : "The project update titled \u0022[node:title]\u0022 has been removed from [node:project-group:title].\r\n\r\nDate: [site:current-date:other_short_datetime]\r\nDeleted By: [site:current-user] ([site:current-user:url])\r\nProject URL: [node:project-group:url]\r\n",
              "from" : "[site:mail]",
              "language" : [ "" ]
            }
          }
        ]
      }
    }'
  );

  $items['ioby_project_updated_notification'] = rules_import(
    '{ "ioby_project_updated_notification" : {
        "LABEL" : "IOBY - Project Updated Notification",
        "PLUGIN" : "reaction rule",
        "OWNER" : "rules",
        "TAGS" : [ "ioby emails" ],
        "REQUIRES" : [ "rules" ],
        "ON" : { "node_update--project_2" : { "bundle" : "project_2" } },
        "DO" : [
          { "mail" : {
              "to" : "notifications@ioby.org",
              "subject" : "The project [node-unchanged:title] has been modified",
              "message" : "The project [node-unchanged:title] has been modified. Here are the edits: [node:diff-markdown]\r\n\r\n",
              "from" : "[site:mail]",
              "language" : [ "" ]
            }
          }
        ]
      }
    }'
  );

  $items['ioby_coupon_validation_check_gratuity'] = rules_import(
    '{ "ioby_coupon_validation_check_gratuity" : {
        "LABEL" : "Ioby Coupon Validation: Check Gratuity",
        "PLUGIN" : "reaction rule",
        "OWNER" : "rules",
        "TAGS" : [ "ioby" ],
        "REQUIRES" : [ "commerce_order", "ioby", "commerce_coupon" ],
        "ON" : { "commerce_coupon_validate" : [] },
        "IF" : [
          { "commerce_order_contains_product_type" : {
              "commerce_order" : [ "commerce_order" ],
              "product_type" : { "value" : { "gratuity" : "gratuity" } },
              "operator" : "\u003E=",
              "value" : "1"
            }
          }
        ],
        "DO" : [
          { "ioby_remove_gratuities_from_cart" : { "commerce_order" : [ "commerce_order" ] } }
        ]
      }
    }'
  );

  $items['ioby_coupon_validation_check_multiple_donations'] = rules_import(
    '{ "ioby_coupon_validation_check_multiple_donations" : {
        "LABEL" : "Ioby Coupon Validation: Check Multiple Donations",
        "PLUGIN" : "reaction rule",
        "OWNER" : "rules",
        "TAGS" : [ "ioby" ],
        "REQUIRES" : [ "commerce_order", "commerce_coupon", "rules" ],
        "ON" : { "commerce_coupon_validate" : [] },
        "IF" : [
          { "commerce_order_contains_product_type" : {
              "commerce_order" : [ "commerce_order" ],
              "product_type" : { "value" : { "product" : "product", "project" : "project" } },
              "operator" : "\u003E",
              "value" : "1"
            }
          }
        ],
        "DO" : [
          { "commerce_coupon_action_is_invalid_coupon" : [] },
          { "drupal_message" : {
              "message" : "Giving Codes may only be applied to a single project donation at a time. Please return to your \u003Ca href=\u0022\/cart\u0022\u003Ecart\u003C\/a\u003E and remove any additional project donations. You\u2019ll need to start the donation process over to make donations to additional projects. We apologize for the inconvenience. If you have questions, please email \u003Ca href=\u0022mailto:donations@ioby.org\u0022\u003Edonations@ioby.org\u003C\/a\u003E or call (917) 464-4515. \r\n",
              "type" : "warning"
            }
          }
        ]
      }
    }'
  );

  return $items;
}
