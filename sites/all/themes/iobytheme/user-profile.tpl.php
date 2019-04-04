<?php
// $Id: user-profile.tpl.php,v 1.13 2010/01/08 07:04:09 webchick Exp $

/**
 * @file
 * Default theme implementation to present all user profile data.
 *
 * This template is used when viewing a registered member's profile page,
 * e.g., example.com/user/123. 123 being the users ID.
 *
 * Use render($user_profile) to print all profile items, or print a subset
 * such as render($content['field_example']). Always call render($user_profile)
 * at the end in order to print all remaining items. If the item is a category,
 * it will contain all its profile items. By default, $user_profile['summary']
 * is provided which contains data on the user's history. Other data can be
 * included by modules. $user_profile['user_picture'] is available
 * for showing the account picture.
 *
 * Available variables:
 *   - $user_profile: An array of profile items. Use render() to print them.
 *   - Field variables: for each field instance attached to the user a
 *     corresponding variable is defined; e.g., $user->field_example has a
 *     variable $field_example defined. When needing to access a field's raw
 *     values, developers/themers are strongly encouraged to use these
 *     variables. Otherwise they will have to explicitly specify the desired
 *     field language, e.g. $user->field_example['en'], thus overriding any
 *     language negotiation rule that was previously applied.
 *
 * @see user-profile-category.tpl.php
 *   Where the html is handled for the group.
 * @see user-profile-item.tpl.php
 *   Where the html is handled for each item in the group.
 * @see template_preprocess_user_profile()
 */
 
 //field_user_bio, field_user_reasons, field_user_websites, field_user_type
 
 $field_labels = array(
  "individual" => array("Full Name","Biography","I Participate Because"),
  "group" =>array("Organization Name","About Us","We Participate Because")
 );
// dprint_r(  );
  
 $labels = $field_labels[ $user_profile['field_user_type']['#items'][0]['value'] ];
?>
<article class="profile"<?php print $attributes; ?>>
  <?php print render($user_profile['user_picture']) ?>

  <dl>
    <dt><?php print $labels[0]?></dt>
    <dd><?php print render($user_profile['field_user_fullname']) ?> &nbsp;</dd>
  
    <dt><?php print $labels[1]?></dt>
    <dd><?php print render($user_profile['field_user_bio']) ?></dd>

    <dt><?php print $labels[2]?></dt>
    <dd><?php print render($user_profile['field_user_reasons']) ?></dd>

    <dt>Website(s)</dt>
    <dd><?php print render($user_profile['field_user_websites']) ?></dd>  
  </dl>

  <?php 
  hide($user_profile['field_user_fullname']);
  hide($user_profile['field_user_bio']);
  hide($user_profile['field_user_reasons']);
  hide($user_profile['field_user_websites']);
    
  //print render($user_profile); 
  //ultimately we should also pull the projects this user has submitted via a view...
  
  ?>
</article>
