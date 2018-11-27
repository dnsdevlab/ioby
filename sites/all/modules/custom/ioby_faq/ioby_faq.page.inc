<?php
/**
 * @file sets up faq pages for ioby
 */
 
/**
 * Internal function to sort categories
 */
function _ioby_faq_sort_categories($at, $bt) {
  $a = (int) $at->weight;
  $b = (int) $bt->weight;
  return $a - $b;
}

/**
 * The page handler for the FAQ help page.
 */
function ioby_faq_page($category_arg='') {
  
  $path = variable_get('ioby_faq_path', 'help');
  
  // categories
  $default_category = variable_get('ioby_faq_default_category', 15);
  $category = $default_category;
  $vocabulary = variable_get('ioby_faq_vocabulary', '');
  
  if (!empty($vocabulary)) {
    $term_tree = taxonomy_get_tree($vocabulary);
    
    // Sort the terms based on the weight
    if (count($term_tree)>0 && isset($term_tree[0]->weight)) {
      uasort($term_tree, '_ioby_faq_sort_categories');
    }
    
    $categories = array();
    $category_links = array();
    
    foreach ($term_tree as $term) {
      $categories[$term->tid] = $term;
      $slug = preg_replace('/\W+/', '-', strtolower($term->name));
      $category_links[$term->tid] = array(
        'href' => $path . '/' . $slug,
        'title' => $term->name,
      );
      
      // The default should go to the root level of the FAQ
      if ($term->tid==$default_category) {
        $category_links[$term->tid]['href'] = $path;
      }
      
      // Check if term is the current
      if (!empty($category_arg) && $slug==$category_arg) {
        $category = $term->tid;
      }
    }
    
  }
  
  // Handle the breadcrumbs and title for sub-pages
  if ((int) $category != (int) $default_category) {
    $breadcrumbs = drupal_get_breadcrumb();
    $breadcrumbs[] = l(variable_get('ioby_faq_title', t('Help')), $path);
    drupal_set_breadcrumb($breadcrumbs);
    
    drupal_set_title($categories[$category]->name);
  }
  
  
  // View
  $view = views_get_view('faqs');
  $view->set_display('default_display');
  $args = array($category);
  $view->set_arguments($args);
  $view_display = $view->render();
  
  return theme('ioby_faq_page', array(
    'view' => $view, 
    'view_display' => $view_display, 
    'categories' => $categories,
    'category_links' => $category_links,
  ));
}