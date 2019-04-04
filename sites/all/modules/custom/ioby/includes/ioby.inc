<?php
/**
 * @file
 * IOBY include file, extends ioby module.
 *
 * Most of the content is pertaining to the checkout flow
 */

/**
 * Extends commerce checkout pane
 */
function ioby_sponsor_info_pane_checkout_form($form, &$form_state, $checkout_pane, $order) {
  $sponsor_text = '';
  $order_wrapper = entity_metadata_wrapper('commerce_order', $order);
  foreach ($order_wrapper->commerce_line_items as $line_item_wrapper) {
    // do one thing for coupons..
    if (preg_match('/coupon/', $line_item_wrapper->type->value())) {
      $sponsor_msg = ioby_sponsor_coupon_sponsor($line_item_wrapper);
      if($sponsor_msg) {
        $sponsor_text .= '<div class="sponsored coupon">' . $sponsor_msg . '</div>';
      }
    }

    if(isset($line_item_wrapper->commerce_product) && $line_item_wrapper->commerce_product->type->value() == 'project') {
      $sponsors = ioby_sponsor_check_match($line_item_wrapper);
      if ($sponsors) {
        foreach ($sponsors as $sponsor) {
          $sponsor_text .= '<div class="sponsored">' . $sponsor . '</div>';
        }
      }
    }
  }

  if ($sponsor_text !='') {
    $pane_form = array();
    $pane_form['match_sponsor'] = array(
      '#markup' => $sponsor_text,
    );
    return $pane_form;
  }
}

/**
 * Extends commerce review pane
 * note: This is ignored since commerce is set to go straight to checkout.
 */
function ioby_sponsor_review_pane_checkout_form($form, &$form_state, $checkout_pane, $order) {
  $sponsor_text = '';
  $order_wrapper = entity_metadata_wrapper('commerce_order', $order);

  foreach ($order_wrapper->commerce_line_items as $line_item_wrapper) {
    // do one thing for coupons..
    if (preg_match('/coupon/', $line_item_wrapper->type->value())) {
      $sponsor_msg = ioby_sponsor_coupon_sponsor($line_item_wrapper);
      if($sponsor_msg) {
        $sponsor_text .= '<div class="sponsored coupon">' . $sponsor_msg . '</div>';
      }
    }

    if(isset($line_item_wrapper->commerce_product) && $line_item_wrapper->commerce_product->type->value() == 'project') {
      $sponsors = ioby_sponsor_check_match($line_item_wrapper);
      if ($sponsors) {
        foreach ($sponsors as $sponsor) {
          $sponsor_text .= '<div class="sponsored">' . $sponsor . '</div>';
        }
      }
    }
  }

  if ($sponsor_text !='') {
    $pane_form = array();
    $pane_form['match_sponsor'] = array(
      '#markup' => $sponsor_text,
    );
    return $pane_form;
  }
}

/**
 * Extends commerce thank you page
 */
function ioby_sponsor_thanks_pane_checkout_form($form, &$form_state, $checkout_pane, $order) {
  $sponsor_text = '';
  $order_wrapper = entity_metadata_wrapper('commerce_order', $order);

  foreach ($order_wrapper->commerce_line_items as $line_item_wrapper) {

    if (preg_match('/coupon/', $line_item_wrapper->type->value())) {
      $sponsor_msg = ioby_sponsor_coupon_sponsor($line_item_wrapper);
      if($sponsor_msg) {
        $sponsor_text .= '<div class="sponsored coupon">' . $sponsor_msg . '</div>';
      }
    }
    else {
      $product = $line_item_wrapper->commerce_product->value();
      if($product->type == 'project') {
        $li = $line_item_wrapper->value();
        $messages = ioby_sponsor_show_sponsor($li->line_item_id);
        ioby_sponsor_finalize_match($li->line_item_id);
        foreach ($messages as $message) {
          $sponsor_text .= '<div class="sponsored">' . $message . '</p>';
        }

      }
    }
  }
  if ($sponsor_text!='') {
    $pane_form = array();
    $pane_form['match_sponsor'][] = array('#markup' => $sponsor_text);
    return $pane_form;
  }
}

/**
 * Checks to see if a campaign or sponsor exists for a project.
 * If campaign is valid and there is money left, it creates donation_match nodes
 *
 * @param entityWrapper $line_item_wrapper
 * @return array
 */
function ioby_sponsor_check_match($line_item_wrapper) {
  $sponsors = array();

  $line_item_id = $line_item_wrapper->line_item_id->value();
  // bail on non-product lines (coupons) or non-project products (gratuities)
  if($line_item_wrapper->type->value() != 'product' && $line_item_wrapper->commerce_product->type->value() != 'project') {
    return $sponsors;
  }
  $project = $line_item_wrapper->commerce_product->value();
  $project = $project->field_project_node[LANGUAGE_NONE][0]['nid'];
  if(!$project) {
    return $sponsors;
  }
  $project = node_load($project);

  // get amount from product
  $donation_amount = commerce_currency_amount_to_decimal(
    $line_item_wrapper->commerce_product->commerce_price->amount->value(),
    $line_item_wrapper->commerce_product->commerce_price->currency_code->value()
  );

  // CHECK TO SEE IF A CAMPAIGN EXISTS FOR THE PROJECT
  $query = new EntityFieldQuery();
  $campaigns = $query->entityCondition('entity_type', 'node')
                    ->entityCondition('bundle', 'campaign')
                    ->fieldCondition('field_campaign_project', 'nid', $project->nid)
                    ->execute();

  if(!empty($campaigns['node'])) {
    foreach (array_keys($campaigns['node']) as $campaign_id) {
      $campaign_wrapper = entity_metadata_wrapper('node', node_load($campaign_id));

      // skip over completed campaigns.
      if ($campaign_wrapper->field_campaign_complete->value()) {
        continue;
      }

      $sponsor_title = $campaign_wrapper->field_campaign_sponsor->title->value();

      // Default message (this should probably be an admin setting with TOKENS)
      $msg = $sponsor_title . ' is proud to sponsor this project!';

      // check campaign status
      if(ioby_campaign_is_active($campaign_wrapper)) {
        // CHECK TO SEE IF WE NEED TO SET UP A DONATION_MATCH
        $campaign_total_amount = $campaign_wrapper->field_campaign_amount->value();
        if($campaign_total_amount > 0 ) {
          // SET UP MATCH
          $match_percent = $campaign_wrapper->field_percent_match->value();
          $maximum_match = $campaign_wrapper->field_campaign_max->value();
          $match_amount = $donation_amount * ($match_percent/100);

          if (!isset($maximum_match)) {
            $maximum_match = $match_amount;
          }

          $applied_matches_amount = iobycampaign_get_match_totals($campaign_id, $line_item_id);
          $applied_project_matches_amount = iobycampaign_get_match_total_for_project($campaign_id, $project->nid, $line_item_id);

          // COMPARE AMOUNTS..
          if ($campaign_total_amount > $applied_matches_amount) {
            // Make sure the match amount is less that the maximum allowed
            $adjusted_match = ($maximum_match >= $match_amount) ? $match_amount : $maximum_match;

            // Check that the adjusted match does not exceed a project-specific cap
            $project_cap_amount = iobycampaign_get_cap_amount_for_project($campaign_id, $project->nid);
            if ($project_cap_amount > 0 && ($applied_project_matches_amount + $adjusted_match) > $project_cap_amount) {
              $adjusted_match = $project_cap_amount - $applied_project_matches_amount;
            }

            // Adjust the match if the sum of it and applied matches exceeds the total limit for the campaign
            if (($applied_matches_amount + $adjusted_match) > $campaign_total_amount) {
              $adjusted_match = $campaign_total_amount - $applied_matches_amount;
            }

            if ($adjusted_match > 0) {
              $match_id = ioby_sponsor_create_match($campaign_id, $project, $adjusted_match, $line_item_id);
              if ($match_id) {
                $msg = t('Your donation to %project will be matched with $%amount from %sponsor. Thanks for your participation!',
                  array(
                    '%project' => $project->title,
                    '%sponsor' => $sponsor_title,
                    '%amount' => sprintf("%01.2f", $adjusted_match)));
              }
            }
          }
        }

        // CHECK FOR PRE-DETERMINED MESSAGE. This overwrites the match text!
        $sponsor_message = ioby_sponsor_get_sponsor_message($campaign_wrapper, 'cart');
        if($sponsor_message) {
          $msg = $sponsor_message;
        }
      }
      $sponsors[$campaign_id] = $msg;
    }
  }
  return $sponsors;
}


/**
 * Creates donation_match node for a campaign/project
 * @global User $user
 * @param Int $campaign_nid
 * @param entityNode $project
 * @param Int $amount
 * @param Int $line_item_id
 * @return Int created node id.
 */
function ioby_sponsor_create_match($campaign_nid, $project, $amount, $line_item_id) {
  global $user;
  // CHECK FOR PREVIOUS MATCHES ON THIS LINE ITEM.
  $dm_query = new EntityFieldQuery();
  $dm_count = $dm_query->entityCondition('entity_type', 'node')
                       ->entityCondition('bundle', 'donation_match')
                       ->fieldCondition('field_donation_match_line_item', 'line_item_id', $line_item_id)
                       ->fieldCondition('field_donation_match_campaign', 'nid', $campaign_nid)
                       ->fieldCondition('field_donation_match_project', 'nid', $project->nid)
                       ->addTag('DANGEROUS_ACCESS_CHECK_OPT_OUT')
                       ->execute();

  $campaign = node_load($campaign_nid);

  if ($dm_count && array_key_exists('node', $dm_count)) {
    $nodes = array_keys($dm_count['node']);
    $node = node_load($nodes[0]);

    if ($amount != $node->field_donation_match_amount[LANGUAGE_NONE][0]['value']) {
      // Update match amount in case the donation was changed in the cart after the match creation
      $node->field_donation_match_amount[LANGUAGE_NONE][0]['value'] = $amount;
      $node->title = 'Donation match of $' . number_format($amount, 2) . ' to ' . $project->title . ' for ' . $campaign->title;
      node_save($node);
    }
  }
  else {
    $node = new stdClass();
    $node->type = 'donation_match';
    $node->language = LANGUAGE_NONE;
    $node->uid = $user->uid;
    node_object_prepare($node);
    $node->title = 'Donation match of $' . number_format($amount, 2) . ' to ' . $project->title . ' for ' . $campaign->title;
    $node->field_donation_match_amount[LANGUAGE_NONE][0]['value'] = $amount;
    $node->field_donation_match_campaign[LANGUAGE_NONE][0]['nid'] = $campaign_nid;
    $node->field_entry_type[LANGUAGE_NONE][0]['value'] = 'website';
    $node->field_donation_match_line_item[LANGUAGE_NONE][0]['line_item_id'] = $line_item_id;
    $node->field_donation_match_project[LANGUAGE_NONE][0]['nid'] = $project->nid;
    node_save($node);
  }
  return $node->nid;
}

/**
 * Gets all donation matches from line item as an entity wrapper
 * @param int $line_item_id
 * @return array of entityWrappers
 */
function ioby_donation_match_info($line_item_id) {
  $out = array();
  $query = new EntityFieldQuery();
  $dm_entities = $query->entityCondition('entity_type', 'node')
                       ->entityCondition('bundle', 'donation_match')
                       ->fieldCondition('field_donation_match_line_item', 'line_item_id', $line_item_id)
                       ->addTag('DANGEROUS_ACCESS_CHECK_OPT_OUT')
                       ->execute();

  if(array_key_exists('node', $dm_entities)) {
    $nodes = node_load_multiple(array_keys($dm_entities['node']));
    foreach($nodes as $node) {
      $dm_wrapper = entity_metadata_wrapper('node', $node);
      $out[] = $dm_wrapper;
    }
  }

  return $out;
}

/**
 * Checks line item for sponsored project and returns thank you message.
 * @param int $line_item_id
 * @param string $type
 * @return array
 */
function ioby_sponsor_find_sponsor($line_item_id, $type = 'thanks') {
  $out = array();
  // load line item
  $line_item = entity_load_single('commerce_line_item', $line_item_id);
  $line_item_wrapper = entity_metadata_wrapper('commerce_line_item', $line_item);
  // skip if it's a gratuity
  if($line_item_wrapper->commerce_product->type->value() == 'gratuity') {
    return $out;
  }
  // get project_id to search
  $project = $line_item_wrapper->commerce_product->value();
  $project_id = $project->field_project_node[LANGUAGE_NONE][0]['nid'];

  // CHECK TO SEE IF A CAMPAIGN EXISTS FOR THE PROJECT
  $query = new EntityFieldQuery();
  $entities = $query->entityCondition('entity_type', 'node')
                    ->entityCondition('bundle', 'campaign')
                    ->fieldCondition('field_campaign_project', 'nid', $project_id)
                    ->execute();

  if(array_key_exists('node', $entities)) {
    foreach (array_keys($entities['node']) as $campaign_id) {
      $campaign_wrapper = entity_metadata_wrapper('node', node_load($campaign_id));
      $msg = ioby_sponsor_get_sponsor_message($campaign_wrapper, $type);
      if($msg) {
        $out[] = $msg;
      }
    }
  }

  return $out;
}

/**
 * Gets all matches from line item.
 * @param int $line_item_id
 * @return array of sponsor messages.
 */
function ioby_sponsor_show_sponsor($line_item_id) {
  $out = array();
  $donation_matches = ioby_donation_match_info($line_item_id);

  if(count($donation_matches)) {

    foreach($donation_matches as $dm_wrapper) {
      $campaign_wrapper = $dm_wrapper->field_donation_match_campaign;
      $message = ioby_sponsor_get_sponsor_message($campaign_wrapper, 'thanks');
      if(!$message) {
        $project_title = $dm_wrapper->field_donation_match_project->title->value();
        $amount = $dm_wrapper->field_donation_match_amount->value();
        $amount = commerce_currency_format($amount, 'USD', NULL, FALSE);
        $sponsor_title = $campaign_wrapper->field_campaign_sponsor->title->value();

        $message = t('%sponsor thanks you for your donation of @amount to %project', array(
          '%sponsor' => $sponsor_title,
          '@amount' => $amount,
          '%project' => $project_title,
        ));
      }
      $out[] = $message;
    }

  }
  else {
    // no matches but there may be a sponsor..
    $sponsors = ioby_sponsor_find_sponsor($line_item_id);
    foreach($sponsors as $sponsor){
      $out[] = $sponsor;
    }
  }
  return $out;
}

/**
 * Commits donation match
 * @param int $line_item_id
 */
function ioby_sponsor_finalize_match($line_item_id) {
  $donation_matches = ioby_donation_match_info($line_item_id);

  foreach($donation_matches as $match) {
    $donation_match_wrapper = entity_metadata_wrapper('node', $match->nid->value());
    $donation_match_wrapper->field_donation_match_status->set(DONATION_MATCH_STATUS_COMPLETE);
    $donation_match_wrapper->save();
  }
}

/**
 * Returns the sponsor message if a campaign is active.
 * @param EntityWrapper $campaign_wrapper
 * @param String $type defaults to thank you cart message.
 * @return string
 */
function ioby_sponsor_get_sponsor_message($campaign_wrapper, $type = 'thanks') {
  $sponsor_message = '';
  if ($campaign_wrapper->field_campaign_complete->value()) {
    return $sponsor_message;
  }

  switch ($type) {
    case 'thanks':
    case 'email' : // right now these are the same.
      $sponsor_message = $campaign_wrapper->field_message_cart_thanks->value();
      break;

    case 'cart':
      $sponsor_message = $campaign_wrapper->field_message_cart->value();
      break;
  }

  return $sponsor_message;
}

/**
 * Checks for coupon thank you message.
 * @param entityWrapper $line_item_wrapper
 * @return string
 */
function ioby_sponsor_coupon_sponsor($line_item_wrapper) {
  $sponsor_coupon_message = '';

  $coupon_wrapper = $line_item_wrapper->commerce_coupon_reference;
  if($coupon_wrapper->field_campaign_sponsor->value()) {
    if($msg = $coupon_wrapper->field_coupon_sponsor_message->value()) {
      $sponsor_coupon_message = $msg;
    }
    else {
      $sponsor_title = $coupon_wrapper->field_campaign_sponsor->title->value();
      $sponsor_coupon_message = $sponsor_title .' thanks you for using their coupon.';
    }
  }
  return $sponsor_coupon_message;
}

//! CAMPAIGN FUNCTIONS
/**
 * Checks to see if a campaign is still active status
 * and updates status if it is out of date range
 *
 * @param entityWrapper $campaign_wrapper
 * @return bool
 */
function ioby_campaign_is_active(&$campaign_wrapper) {
  // if it is not set to inactive, doublecheck dates...
  if(!$campaign_wrapper->field_campaign_complete->value()) {
    // Are we in the campaign dates?
    $campaign_start = $campaign_wrapper->field_campaign_dates->value->value();
    $campaign_end = $campaign_wrapper->field_campaign_dates->value2->value();

    // make sure we are at the end of the day of the end date.
    $campaign_end = strtotime(date('Y-m-d\T23:59:59', $campaign_end));

    if((int)$campaign_start > REQUEST_TIME || (int)$campaign_end < REQUEST_TIME) {
      ioby_campaign_end($campaign_wrapper->value());
    }
    else {
      return TRUE;
    }
  }

  // There was a value, so it's over.. Return inactive.
  return FALSE;
}

/**
 * Sets a campaign to complete and saves it.
 * @param nodeObject $campaign
 */
function ioby_campaign_end(&$campaign) {
  $campaign->field_campaign_complete[LANGUAGE_NONE][0]['value'] = format_date(REQUEST_TIME, 'custom', 'Y-m-d\T00:00:00');
  node_save($campaign);
}
