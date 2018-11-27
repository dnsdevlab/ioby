<?php

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


/*
 * delivery of pages without page.tpl, for ajax
 */

function iobytheme_preprocess_page(&$vars) {
  if ( (isset($_GET['ajax']) && $_GET['ajax'] == 1) || arg(0) == 'volunteerform' || arg(0) == 'popup') {
    $vars['theme_hook_suggestions'][] = 'page__ajax';
  }

  // Additional CSS and Javascript for the front page rotator
  if (drupal_is_front_page ()) {
    $options = array(
      'every_page' => FALSE,
      'preprocess' => FALSE,
      'group' => CSS_DEFAULT, //Add the default galleryview CSS before iobytheme-specific CSS files
    );
    drupal_add_css(path_to_theme() . '/js/galleryview/css/jquery.galleryview.css', $options);
    $vars['styles'] = drupal_get_css();

    $options['group'] = JS_THEME;
    drupal_add_js(path_to_theme() . '/js/galleryview/js/jquery.easing.js', $options);
    drupal_add_js(path_to_theme() . '/js/galleryview/js/jquery.timers.js', $options);
    drupal_add_js(path_to_theme() . '/js/galleryview/js/jquery.galleryview-ioby.js', $options);
    drupal_add_js(path_to_theme() . '/js/galleryview/js/jquery.ioby-rotator.js', $options);
    $vars['script'] = drupal_get_js();
  }

  // Footer Brand Image stuff, adds ~6 logos to footer based on 'Footer Brand Logo' content type
  // Looks like nodequeue neglected some of their internal API methods so part of it is dup'd here.
  $footer_brand_subqueue_id = 3;
  $vars['footer_logos'] = array();
  foreach (nodequeue_load_nodes($footer_brand_subqueue_id, FALSE, 0, 6) as $node) {
    $vars['footer_logos'][] = node_view($node, 'teaser');
  }
}


function iobytheme_preprocess_html(&$vars) {
  $logo_colors = array("blue","orange","active","plum","grey");
  $vars['classes_array'][] = 'logo-'. $logo_colors[ array_rand($logo_colors) ];

  $contexts = context_active_contexts();
  if (array_key_exists('idea_css', $contexts)){
    $vars['theme_hook_suggestions'][] = 'html__idea_css';
  }
}

function iobytheme_preprocess(&$vars) {
  if (arg(0) == "blog") drupal_set_title("ioby blog");
  drupal_add_library('system', 'ui.dialog');
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

//kill links to user profiles, at least for now
function iobytheme_username($variables) {
  $output = '<span' . drupal_attributes($variables['attributes_array']) . '>' . $variables['name'] . $variables['extra'] . '</span>';
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
