<?php

function iobytheme_text_format_wrapper($variables) {
  $element = $variables['element'];
  $output = '<div class="text-format-wrapper">';
  $output .= $element['#children'];

  if (!empty($element['#description'])) {
    // The string in the HTML to insert the description after
    $sstr = '<div class="form-textarea-wrapper resizable">';
    // The string to insert
    $rstr = $sstr . '<div class="description">' . $element['#description'] . '</div>';
    $output = str_replace($sstr, $rstr, $output);
  }
  $output .= "</div>\n";

  return $output;
}


function iobytheme_form_element($variables) {
  $element = &$variables['element'];
  // This is also used in the installer, pre-database setup.
  $t = get_t();

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array(
    '#title_display' => 'before',
  );

  // Add element #id for #type 'item'.
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  // Add element's #type and #name as class to aid with JS/CSS selectors.
  $attributes['class'] = array('form-item');
  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }
  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
  }
  // Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';
   $element_description = "";
  if (!empty($element['#description'])) {
    $element_description = '<div class="description">' . $element['#description'] . "</div>\n";
  }
  switch ($element['#title_display']) {
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= $element_description;
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";

      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= $element_description;
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }
  $output .= "</div>\n";

  return $output;
}


function iobytheme_form_alter(&$form, &$form_state, $form_id) {

  if ($form_id == 'search_block_form') {
    $form['actions']['submit']['#value'] = "Go";
    $form['search_block_form']['#title'] = "Search ioby.org";
    $form['search_block_form']['#title_display'] = "before";
  }

  if ($form_id == 'comment_node_project_2_form') {
    //dprint_r($form);

    //comment subjects and author info are lame
    $form['subject']["#access"] = false;
    $form['author']["#access"] = false;
    $form['actions']['preview']['#access'] = false;
    $form['actions']['submit']['#access'] = true;
    //and 'save' should be 'post'
    $form['actions']['submit']['#value'] = 'Post';
    //suppress titles n such
    $form['comment_body']['#title_display'] = 'invisible';
    $form['comment_body']['und'][0]['#title_display'] = 'invisible';

  }

}

function iobytheme_links__system_secondary_menu(&$vars) {

  //chunk out the links
  $links = $vars['links'];
  if (empty($links) or $links==null) return;
  $end_links = array_splice( $links, count($links)-2, 2 );
  $num_links = count($links);
  if(count($links)) {
    $link_cols = array_chunk( $links, intval(ceil($num_links/2)), true);
    //output the links
    ob_start();
    printf('<nav id="%s" class="%s" ><ul class="first-col" role="navigation">',$vars['attributes']['id'], implode(" ",$vars['attributes']['class'] ) );
    iobytheme_loop_links( array_shift($link_cols) );
    print '<li class="null">';
    iobytheme_loop_links($end_links);
    print '</ul><ul class="second-col">';
    iobytheme_loop_links( array_shift($link_cols) );
    print "</ul></nav>\n";

    ob_end_flush();
  }
}


function iobytheme_loop_links( $links ) {
  if (!is_array($links)) return;
  foreach( $links as $id => $link) {
    print '<li class="'.$id.'">' . l($link['title'],$link['href']) . "</li>\n";
  }
}

/*
 * Fields for projects -- faster here than tpl files
 */

function iobytheme_field__project_2($variables) {

  $field = $variables['element']['#field_name'];

  switch($field) {
    case "field_project_neighborhood":
    return iobytheme_field_neighborhood($variables);
    case "field_project_boroughs":
    case "field_project_facet":
    case "field_project_impact_areas":
    return iobytheme_field_commas($variables);
    case "field_project_address":
    return iobytheme_field_street($variables);
    case "field_project_cost":
    return iobytheme_field_cost($variables);
    case "field_project_status":
    return iobytheme_field_simple($variables);
    default:
    return iobytheme_field_default($variables);
  }
}


function iobytheme_field_simple($variables) {

  $output = '';
  if (!$variables['label_hidden']) $output .= iobytheme_field_label($variables, 'span');

  foreach ($variables['items'] as $delta => $item) {
    $output .=  drupal_render($item);
  }

  $output = iobytheme_field_wrapper($variables, $output, 'span');
  return $output;
}


function iobytheme_field_commas($variables) {

  $output = '';
  if (!$variables['label_hidden']) $output .= iobytheme_field_label($variables, 'span');

  foreach ($variables['items'] as $delta => $item) {
    $output .=  drupal_render($item) .", ";
  }

  $output = rtrim($output,", ");

  $output = iobytheme_field_wrapper($variables, $output, 'span');
  return $output;
}


function iobytheme_field_default($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= iobytheme_field_label($variables, 'div');
  }

  // Render the items.
  $output .= '<div class="field-items"' . $variables['content_attributes'] . '>';
  foreach ($variables['items'] as $delta => $item) {
    $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
    $output .= '<div class="' . $classes . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</div>';
  }
  $output .= '</div>';

  // Render the top-level DIV.
  $output = iobytheme_field_wrapper($variables, $output, 'div');

  return $output;
}


function iobytheme_field_label($variables, $tag = 'span') {
  return '<'.$tag.' class="field-label"' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</'.$tag.'>';
}


function iobytheme_field_wrapper($variables, $output, $tag = 'div') {
  return '<'.$tag.' class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</'.$tag.'>';
}


function iobytheme_field_street($variables) {

  $output = '';
  if (!$variables['label_hidden']) {
    $output .= iobytheme_field_label($variables, 'span');
  }

  $output .= '<span class="street-only">'.$variables['items'][0]['#location']['street'] . '</span>';
  $output = iobytheme_field_wrapper($variables, $output, 'div');
  return $output;
}


function iobytheme_field_neighborhood($variables) {

  $output = '';
  if (!$variables['label_hidden']) {
    $output .= iobytheme_field_label($variables, 'span');
  }

  foreach ($variables['items'] as $delta => $item) {
    $output .=  " (".drupal_render($item).")";
  }
  $output = iobytheme_field_wrapper($variables, $output, 'span');
  return $output;
}


/*
 * Display the project cost amount as a span
 */
function iobytheme_field_cost($vars) {
  $prefix = '<span class="project-amt ' . $vars['classes'].'">';
  $suffix = '</span>';
  $content = '';
  foreach($vars['items'] as $item) {
    $content .= '$' . render($item);
  }
  return $prefix . $content . $suffix;
}


/*
 * Display the boroughs as a span -- will this get called?
 */
function iobytheme_field__field_project_boroughs($vars) {
  $prefix = '<span class="' . $vars['classes'].'">';
  $suffix = '</span>';
  $content = '';
  foreach($vars['items'] as $item) {
    $content .= render($item);
  }
  return $prefix . $content . $suffix;
}

/**
 * Implements hook_preprocess_entity().
 *
 * @author Paul Venuti
 */
function iobytheme_preprocess_entity(&$vars) {
  if ($vars['elements']['#entity_type'] == 'bean') {
    // Define per-bundle functions for Bean types.
    $view_mode = $vars['elements']['#view_mode'];
    $bundle = $vars['elements']['#bundle'];
    $function = 'iobytheme_preprocess_' . $bundle . '_bundle';
    if (function_exists($function)) {
      $function($vars, $bundle, $view_mode);
    }
  }
}

/**
 * Implements hook_preprocess_node().
 *
 * @author Paul Venuti
 */
function iobytheme_preprocess_node(&$vars) {
  // Define per-bundle functions.
  $view_mode = $vars['view_mode'];
  $bundle = $vars['type'];
  $node = $vars['node'];
  $function = 'iobytheme_preprocess_' . $bundle . '_bundle';
  if (function_exists($function)) {
    $function($vars, $node, $bundle, $view_mode);
  }
}

/*
 * delivery of pages without page.tpl, for ajax
 */

function iobytheme_preprocess_page(&$vars) {

  if ((isset($_GET['ajax']) && $_GET['ajax'] == 1) || arg(0) == 'volunteerform' || arg(0) == 'popup') {
    $vars['theme_hook_suggestions'][] = 'page__ajax';
  }

  // Additional CSS and Javascript for the front page rotator
  if (drupal_is_front_page()) {
    $options = [
      'every_page' => FALSE,
      'preprocess' => FALSE,
      'group' => CSS_DEFAULT,
      //Add the default galleryview CSS before iobytheme-specific CSS files
    ];
    //drupal_add_css(path_to_theme() . '/js/galleryview/css/jquery.galleryview.css', $options);
    drupal_add_css(path_to_theme() . '/patternlab/public/css/plugins.min.css', $options);
    $vars['styles'] = drupal_get_css();

    $options['group'] = JS_THEME;
    //drupal_add_js(path_to_theme() . '/js/galleryview/js/jquery.easing.js', $options);
    //drupal_add_js(path_to_theme() . '/js/galleryview/js/jquery.timers.js', $options);
    //drupal_add_js(path_to_theme() . '/js/galleryview/js/jquery.galleryview-ioby.js', $options);
    //drupal_add_js(path_to_theme() . '/js/galleryview/js/jquery.ioby-rotator.js', $options);
    $vars['script'] = drupal_get_js();
  }

  drupal_add_js(path_to_theme() . '/patternlab/public/js/plugins.min.js', $options);

  // Footer Brand Image stuff, adds ~6 logos to footer based on 'Footer Brand Logo' content type
  // Looks like nodequeue neglected some of their internal API methods so part of it is dup'd here.
  $footer_brand_subqueue_id = 3;
  $vars['footer_logos'] = [];
  foreach (nodequeue_load_nodes($footer_brand_subqueue_id, FALSE, 0, 6) as $node) {
    $vars['footer_logos'][] = node_view($node, 'teaser');
  }

  $main_menu = drupal_render(menu_tree_output(menu_tree_all_data('main-menu')));
  $vars['main_menu'] = preg_replace('/(leaf|collapsed)/','',$main_menu);

  if (!empty($vars['node']) && $vars['node']->nid == '34783') {
    if (user_is_logged_in()) {
      global $user;
      $user_wrapper = entity_metadata_wrapper('user', $user);
      if ($user_wrapper->field_first_name->value() || $user_wrapper->field_last_name->value()) {
        $vars['user_first_name'] = $user_wrapper->field_first_name->value();
        $vars['user_last_name'] = $user_wrapper->field_last_name->value();
      }
      else {
        $vars['user_first_name'] = $user_wrapper->field_user_fullname->value();
      }
      $vars['user_email'] = $user_wrapper->mail->value();
    }
  }

  if (!empty($vars['node']) && !empty($vars['node']->type)) {
    $vars['bundle'] = $vars['node']->type;
  }

  // load cart items
  if (user_is_logged_in()) {
    // get cart items
    global $user;
    if ($order = commerce_cart_order_load($user->uid)) {
      // Count the number of product line items on the order.
      $wrapper = entity_metadata_wrapper('commerce_order', $order);
      $quantity = commerce_line_items_quantity($wrapper->commerce_line_items, commerce_product_line_item_types());
      $vars['cart_items'] = $quantity;
    }
  }

}

function iobytheme_preprocess_html(&$vars) {
  $logo_colors = array("blue","orange","active","plum","grey");
  $vars['classes_array'][] = 'logo-'. $logo_colors[ array_rand($logo_colors) ];

  drupal_add_css('https://fonts.googleapis.com/css?family=Barlow:400,600,700|Lato:400,400i,700,700i', array('type' => 'external'));

  drupal_add_css(drupal_get_path('theme','iobytheme').'/patternlab/public/css/app.min.css');

  $contexts = context_active_contexts();
  if (array_key_exists('idea_css', $contexts)){
    $vars['theme_hook_suggestions'][] = 'html__idea_css';
  }

  // IOBY-36, Adds Mailchimp CSS file
  if (array_key_exists('mailchimp_css', $contexts)){
    drupal_add_css(drupal_get_path('theme', 'iobytheme') . '/css/mailchimp.css');
  }

  // IOBY-71, Adds FormAssembly assets files
  if (array_key_exists('form_assembly', $contexts)){
    $vars['theme_hook_suggestions'][] = 'html__form_assembly';
  }

  // IOBY-121
  $script = array(
    '#tag' => 'script',
    '#attributes' => array('type' => 'text/javascript'),
    '#value' => "(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push(
                {'gtm.start': new Date().getTime(),event:'gtm.js'}
                );var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
                })(window,document,'script','dataLayer','GTM-KTTPQD');",
   );
   drupal_add_html_head($script, 'script');

   // $vars['google_tag_body'] .= "<!-- Google Tag Manager (noscript) -->
   //              <noscript><iframe src='https://www.googletagmanager.com/ns.html?id=GTM-KTTPQD'
   //              height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>
   //              <!-- End Google Tag Manager (noscript) -->";
  $viewport = array(
    '#tag' => 'meta',
    '#attributes' => array(
    'name' => 'viewport',
    'content' => 'width=device-width, initial-scale=1, maximum-scale=1',
  ),
 );
  drupal_add_html_head($viewport, 'viewport');

  if ($node = menu_get_object()) {
    if ($node->type == 'homepage') {
      $vars['classes_array'][] = 'refresh-home';
    }
  }
}

function iobytheme_preprocess(&$vars) {
  if (arg(0) == "blog") drupal_set_title("ioby blog");
  drupal_add_library('system', 'ui.dialog');
}

/**
 * Implements template_preprocess_block().
 */

function iobytheme_theme(&$existing, $type, $theme, $path) {
  $hooks['user_login_block'] = array(
    'template' => 'templates/user-login-block',
    'render element' => 'form',
  );
  return $hooks;
}

function iobytheme_preprocess_user_login_block(&$vars) {
  $vars['name'] = render($vars['form']['name']);
  $vars['pass'] = render($vars['form']['pass']);
  $vars['submit'] = render($vars['form']['actions']['submit']);
  $vars['rendered'] = drupal_render_children($vars['form']);
}

/**
 * Preprocess the homepage node.
 */
function iobytheme_preprocess_homepage_bundle(&$vars, $node, $bundle, $view_mode) {

  // Brand hero.
  if (!empty($node->field_hero_image) && !empty($node->field_hero_image[LANGUAGE_NONE]['0'])) {
    $vars['hero_image_large'] = image_style_url('1400x787', $node->field_hero_image[LANGUAGE_NONE]['0']['uri']);
    $vars['hero_image_medium'] = image_style_url('700x394', $node->field_hero_image[LANGUAGE_NONE]['0']['uri']);
    $vars['hero_image_small'] = image_style_url('440x216', $node->field_hero_image[LANGUAGE_NONE]['0']['uri']);
  }

  // Text fields
  $text_fields = [
    'hero_static_text',
    'impact_title_line_1',
    'impact_title_line_2',
    'email_title_line_1',
    'email_title_line_2',
    'email_form_supertitle',
    'email_social_supertitle',
    'project_title',
    'project_cta_text',
    'cities_title',
  ];
  foreach ($text_fields as $key => $name) {
    $field_name = 'field_' . $name;
    if ($field = field_get_items('node', $node, $field_name)) {
      $vars[$name] = $field[0]['safe_value'];
    }
  }

  if ($animated_text = field_get_items('node', $node, 'field_hero_animated_text')) {
    foreach ($animated_text as $text) {
      $vars['animated_text'][] = $text['safe_value'];
    }
    $vars['animated_text_data'] = implode(',', $vars['animated_text']);
  }


  // Load the Impact beans.
  if ($impact_blocks = field_get_items('node', $node, 'field_impact_blocks')) {
    foreach ($impact_blocks as $key => $impact_block) {
      if ($delta = $impact_block['entity']->identifier()) {
        $bean = bean_load($delta);
        $vars['impact_blocks'][] = iobytheme_get_block('bean', $delta);
        if (!empty($bean->field_bean_image) && !empty($bean->field_bean_image[LANGUAGE_NONE]['0'])) {
          $vars['impact'][$key]['image'] = image_style_url('380x380', $bean->field_bean_image[LANGUAGE_NONE]['0']['uri']);
        }
      }
    }
  }

  if ($impact_link = field_get_items('node', $node, 'field_impact_link')) {
    $vars['impact_link_url'] = $impact_link[0]['display_url'];
    $vars['impact_link_title'] = $impact_link[0]['title'];
  }


  // Load the Project Feature beans.
  if ($project_features = field_get_items('node', $node, 'field_project_feature_blocks')) {
    foreach ($project_features as $project_feature) {
      if ($delta = $project_feature['entity']->identifier()) {
        $vars['project_features'][] = iobytheme_get_block('bean', $delta);
      }
    }
  }

  if ($project_link = field_get_items('node', $node, 'field_project_cta_link')) {
    $vars['project_link_url'] = $project_link[0]['display_url'];
    $vars['project_link_title'] = $project_link[0]['title'];
  }

  // Browse Cities
  if ($field_browse_cities_title = field_get_items('node', $node, 'field_cities_title')) {
    $vars['browse_cities_title'] = $field_browse_cities_title[0]['safe_value'];
  }

  if ($field_city_link = field_get_items('node', $node, 'field_cities_link')) {
    foreach ($field_city_link as $city_link) {
      $vars['city_links'][] = array(
        'url' => $city_link['display_url'],
        'title' => $city_link['title'],
      );
    }
  }

  // Load the Ioby Updates beans.
  if ($ioby_updates = field_get_items('node', $node, 'field_ioby_updates_blocks')) {
    foreach ($ioby_updates as $ioby_update) {
      if ($delta = $ioby_update['entity']->identifier()) {
        $vars['ioby_updates'][] = iobytheme_get_block('bean', $delta);
      }
    }
  }

  if ($promo_blocks = field_get_items('node', $node, 'field_promos')) {
    foreach ($promo_blocks as $key => $impact_block) {
      if ($delta = $impact_block['entity']->identifier()) {
        $bean = bean_load($delta);
        $vars['promo_blocks'][] = iobytheme_get_block('bean', $delta);
        if (!empty($bean->field_bean_image) && !empty($bean->field_bean_image[LANGUAGE_NONE]['0'])) {
          $vars['promo'][$key]['image'] = image_style_url('gallery_feature', $bean->field_bean_image[LANGUAGE_NONE]['0']['uri']);
        }
      }
    }
  }

}

/**
 * Preprocess Impact beans.
 *
 * @author Paul Venuti
 */
function iobytheme_preprocess_impact_bean_bundle(&$vars, $bundle, $view_mode) {
  $bean = $vars['bean'];

  // Subtitle.
  $field_subtitle = field_get_items('bean', $bean, 'field_subtitle');
  if (!empty($field_subtitle)) {
    $vars['subtitle'] = $field_subtitle[0]['safe_value'];
  }

  // Summary.
  $field_summary = field_get_items('bean', $bean, 'field_summary');
  if (!empty($field_summary)) {
    $vars['summary'] = $field_summary[0]['safe_value'];
  }
}


function iobytheme_preprocess_project_feature_bundle(&$vars, $bundle, $view_mode) {
  $bean = $vars['bean'];

  if ($project = field_get_items('bean', $bean, 'field_project')) {
    $project_node = $project[0]['entity'];
  }

  $vars['url'] = url('node/' . $project_node->nid, array('absolute' => TRUE));;

  if ($image = field_get_items('bean', $bean, 'field_featured_project_image')) {
    $vars['image'] = iobytheme_get_image_style($image[0], '631x420');
  } elseif ($image = field_get_items('node', $project_node, 'field_project_photo')) {
    $vars['image'] = iobytheme_get_image_style($image[0], '631x420');
  }

  if ($project_title = field_get_items('bean', $bean, 'field_featured_project_title')) {
    $vars['project_title'] = $project_title[0]['safe_value'];
  } elseif ($project_title = $project_node->title) {
    $vars['project_title'] = $project_title;
  }


  $vars['countdown'] = iobyproject_countdown($project_node, TRUE);
  $raised = iobyproject_amount_raised($project_node->nid);
  if (!empty($raised)) {
    $vars['percent'] = $raised['pct_done'];
    $vars['width'] = abs(100 - $raised['pct_done']);
    $vars['amount'] = number_format($raised['amount_needed'], 0, '.', ',');
  }


  if ($city = field_get_items('bean', $bean, 'field_featured_project_city')) {
    $vars['city'] = $city[0]['safe_value'];
  }
  if ($state = field_get_items('bean', $bean, 'field_featured_project_state')) {
    $vars['state'] = $state[0]['value'];
  }
  // fallback to project city/state if not provided.
  if (empty($vars['city']) || empty($vars['state'])) {
    if ($address = field_get_items('node', $project_node, 'field_project_address')) {
      $vars['city'] = $address[0]['city'];
      $vars['state'] = $address[0]['province'];
    }
  }

  // fallback to project tagling if not provided
  if ($summary = field_get_items('bean', $bean, 'field_featured_project_summary')) {
    $vars['summary'] = $summary[0]['safe_value'];
  } elseif ($tagline = field_get_items('node', $project_node, 'field_project_inbrief')) {
    $vars['summary'] = strip_tags($tagline[0]['safe_value']);
  }

}

function iobytheme_preprocess_promo_bundle(&$vars, $bundle, $view_mode) {
  $bean = $vars['bean'];

  if ($field_promo_text = field_get_items('bean', $bean, 'field_promo_text')) {
    $vars['promo_text'] = $field_promo_text[0]['safe_value'];
  }
}

function iobytheme_preprocess_ioby_update_bundle(&$vars, $bundle, $view_mode) {
  $bean = $vars['bean'];

  $field_summary = field_get_items('bean', $bean, 'field_summary');
  if (!empty($field_summary)) {
    $vars['summary'] = $field_summary[0]['safe_value'];
  }

  if ($image = field_get_items('bean', $bean, 'field_bean_image')) {
    $vars['image'] = iobytheme_get_image_style($image[0], '530_wide');
  }

  if ($link = field_get_items('bean', $bean, 'field_updates_link')) {
    $vars['link_url'] = $link[0]['display_url'];
    $vars['link_title'] = $link[0]['title'];
  }
  if ($link_style = field_get_items('bean', $bean, 'field_updates_link_style')) {
    $vars['link_style'] = $link_style[0]['value'];
  }
}

/*
 * Ouput the homepage rotator fields without wrappers
 */
function iobytheme_field__slide($vars) {
  $output = '';
  foreach ($vars['items'] as $item) {
    $output .= render($item);
  }
  return trim($output);
}

/*
 * Use tab link style for the About ioby menu
 */
function iobytheme_menu_tree__menu_about_ioby_menu($vars) {
  return '<ul class="tabs">' . $vars['tree'] . '</ul>';
}

/*
 * Use tab link style for the Resources menu
 */
function iobytheme_menu_tree__menu_resources_menu($vars) {
  return '<ul class="tabs">' . $vars['tree'] . '</ul>';
}

/*
 * Use tab link style for the Tips menu
 */
function iobytheme_menu_tree__menu_tips_menu($vars) {
  return '<ul class="tabs">' . $vars['tree'] . '</ul>';
}

/*
 * Use tab link style for the Tips menu
 */
function iobytheme_menu_tree__menu_idea($vars) {
  return '<ul class="tabs">' . $vars['tree'] . '</ul>';
}

/*
 * Use tab link style for the Campaign Essentials menu
 */
function iobytheme_menu_tree__menu_campaign_essentials($vars) {
  return '<ul class="tabs">' . $vars['tree'] . '</ul>';
}

/*
 * Use tab link style for the Trick Out My Trip Toolkit menu
 * @author Russom Woldezghi
 * IOBY-40
 */
function iobytheme_menu_tree__menu_trick_out_my_trip_toolkit($vars) {
  return '<ul class="tabs">' . $vars['tree'] . '</ul>';
}

/*
 * IOBY-48
 */

function iobytheme_menu_tree__menu_tabbed_page_menu($vars) {
  return '<ul class="tabs">' . $vars['tree'] . '</ul>';
}

//kill links to user profiles, at least for now
function iobytheme_username($variables) {
  // If project node type, use first and last name from node, not user, if available
  // IOBY-105
  // @author Russom Woldezghi
  if (isset($variables['account']->type) && $variables['account']->type == 'project_2') {

    // Get nid and load the node object
    $nid = $variables['account']->nid;
    $node = node_load($nid);

    // First and last name
    $project_leader_first_name = $node->field_contact_first['und']['0']['value'];
    $project_leader_last_name = $node->field_contact_last['und']['0']['value'];

    // Get only first name and first character of last name
    $project_leader_name = $project_leader_first_name.' '.substr($project_leader_last_name, 0, 1);

    // if null, return username stored on the node object
    if (is_null($project_leader_name)) {
      $project_leader_name = $variables['name'];
    }

    // Output for username project
    $output = '<span' . drupal_attributes($variables['attributes_array']) . '>' . $project_leader_name . $variables['extra'] . '</span>';
  }else{

    // Return username from node author info
    $output = '<span' . drupal_attributes($variables['attributes_array']) . '>' . $variables['name'] . $variables['extra'] . '</span>';
  }
  return $output;
}

function iobytheme_image_formatter($variables) {
  $item = $variables['item'];
  $image = array(
    'path' => $item['uri'],
    'alt' => $item['alt'],
    );

  /*
  if (isset($item['width']) && isset($item['height'])) {
    $image['width'] = $item['width'];
    $image['height'] = $item['height'];
  }
  */
  // Do not output an empty 'title' attribute.
  if (drupal_strlen($item['title']) > 0) {
    $image['title'] = $item['title'];
  }

  if ($variables['image_style']) {
    $image['style_name'] = $variables['image_style'];
    $output = theme('image_style', $image);
  }
  else {
    $output = theme('image', $image);
  }

  if (!empty($variables['path']['path'])) {
    $path = $variables['path']['path'];
    $options = $variables['path']['options'];
    // When displaying an image inside a link, the html option must be TRUE.
    $options['html'] = TRUE;
    $output = l($output, $path, $options);
  }

  return $output;
}

function iobytheme_views_mini_pager__campaign_tabs($vars) {
  global $pager_page_array, $pager_total;

  $tags = $vars['tags'];
  $element = $vars['element'];
  $parameters = $vars['parameters'];

  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  if ($pager_total[$element] > 1) {

    $li_previous = theme('pager_previous',
      array(
        'text' => (isset($tags[1]) ? $tags[1] : t('‹‹')),
        'element' => $element,
        'interval' => 1,
        'parameters' => $parameters,
        )
      );
    if (empty($li_previous)) {
      $li_previous = "&nbsp;";
    }

    $li_next = theme('pager_next',
      array(
        'text' => (isset($tags[3]) ? $tags[3] : t('››')),
        'element' => $element,
        'interval' => 1,
        'parameters' => $parameters,
        )
      );

    if (empty($li_next)) {
      $li_next = "&nbsp;";
    }

    $items[] = array(
      'class' => array('pager-previous'),
      'data' => $li_previous,
      );

    $items[] = array(
      'class' => array('pager-current'),
      'data' => t('@current of @max', array('@current' => $pager_current, '@max' => $pager_max)),
      );

    $items[] = array(
      'class' => array('pager-next'),
      'data' => $li_next,
      );

    $items[] = array(
      'class' => array('view-all'),
      'data' => l('view all', '/', array('query' => array('items_per_page' => 'all'))),
      );

    return theme('item_list',
      array(
        'items' => $items,
        'title' => NULL,
        'type' => 'ul',
        'attributes' => array('class' => array('pager')),
        )
      );
  }
}

function iobytheme_table($variables) {
  if (isset($variables['attributes']['id']) && $variables['attributes']['id'] == 'field-incentives-values') {
    // Don't display the label for the incentives field
    $variables['header'][0]['data'] = '';
  }

  return theme_table($variables);
}

/**
 * Implements template_preprocess_search_result
 * @param type $vars
 */
function iobytheme_preprocess_search_result(&$vars) {
  if($vars['module'] == 'user'){
     // Removes email within () in search result
     // @see IOBY-99
    $title = $vars['title'];
    $vars['title'] = preg_replace("/\([^)]+\)/","",$title);

    // Load user object by mail
    $user = user_load_by_mail($vars['title']);

    // If user has fullname, show it
    if(!is_null($user->field_user_fullname)){
      $vars['title'] = $user->field_user_fullname['und'][0]['safe_value'];
    }
    if($user->field_user_fullname = NULL) {
      $vars['title'] = $user->name;
    }
  }
}

/**
 * Gets the render array for a block.
 *
 * @param string $module
 *    The module responsible for the block.
 * @param string $delta
 *    The block's delta.
 *
 * @return mixed $block
 *    The render array for the block, or FALSE if the block doesn't exist.
 *
 * @author Paul Venuti
 */
function iobytheme_get_block($module, $delta) {
  $block = block_load($module, $delta);
  if (isset($block->bid)) {
    return _block_get_renderable_array(_block_render_blocks(array($block)));
  }
  return FALSE;
}

/**
 * Runs an image through theme_image_style().
 *
 * @param array $image
 *    The loaded image, as an array.
 * @param string $style_name
 *    The image style to use.
 *
 * @author Paul Venuti
 */
function iobytheme_get_image_style($image, $style_name, $attributes = FALSE) {
  $image = is_array($image) ? $image : (array) $image;
  $data = iobytheme_get_width_height_from_style($style_name);

  $image_vars = array(
    'style_name' => $style_name,
    'path' => $image['uri'],
    'width' => $data['width'],
    'height' => $data['height'],
    'alt' => $image['alt'],
  );

  if ($attributes) {
    $image_vars['attributes'] = $attributes;
  }

  return theme_image_style($image_vars);
}

/**
 * Gets the width and height of the given image style, so long as that image
 * style uses a "Scale and crop" effect.
 *
 * @param string $style_name
 *    The machine name of the image style.
 * @return array $data
 *    An array with two elements, width and height. Empty if the style was not
 *    found, or if the style doesn't have a "Scale and crop" effect.
 *
 * @author Paul Venuti
 */
function iobytheme_get_width_height_from_style($style_name) {
  $styles = image_styles();
  $style_data = $styles[$style_name];
  $effects = $style_data['effects'];
  foreach ($effects as $key => $effect) {
    if ($effect['effect callback'] == 'image_scale_and_crop_effect' ||
      $effect['effect callback'] == 'image_scale_effect') {
      return array(
        'width' => $effect['data']['width'],
        'height' => $effect['data']['height'],
      );
    }
  }
  return array(
    'width' => '',
    'height' => '',
  );
}
