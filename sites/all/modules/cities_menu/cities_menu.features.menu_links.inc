<?php
/**
 * @file
 * cities_menu.features.menu_links.inc
 */

/**
 * Implements hook_menu_default_menu_links().
 */
function cities_menu_menu_default_menu_links() {
  $menu_links = array();

  // Exported menu link: main-menu_about:node/170.
  $menu_links['main-menu_about:node/170'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'node/170',
    'router_path' => 'node/%',
    'link_title' => 'about',
    'options' => array(
      'attributes' => array(
        'title' => 'Who we are and why we do what we do',
      ),
      'identifier' => 'main-menu_about:node/170',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -47,
    'customized' => 1,
  );
  // Exported menu link: main-menu_add-a-project:project/add.
  $menu_links['main-menu_add-a-project:project/add'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'project/add',
    'router_path' => 'project/add',
    'link_title' => 'Add a project',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'main-menu_add-a-project:project/add',
    ),
    'module' => 'menu',
    'hidden' => 1,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -50,
    'customized' => 1,
  );
  // Exported menu link: main-menu_blog:blog.
  $menu_links['main-menu_blog:blog'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'blog',
    'router_path' => 'blog',
    'link_title' => 'blog',
    'options' => array(
      'attributes' => array(
        'title' => 'The latest',
      ),
      'identifier' => 'main-menu_blog:blog',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -46,
    'customized' => 1,
  );
  // Exported menu link: main-menu_cities:<nolink>.
  $menu_links['main-menu_cities:<nolink>'] = array(
    'menu_name' => 'main-menu',
    'link_path' => '<nolink>',
    'router_path' => '<nolink>',
    'link_title' => 'cities',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'main-menu_cities:<nolink>',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 1,
    'expanded' => 1,
    'weight' => -48,
    'customized' => 1,
  );
  // Exported menu link: main-menu_cleveland:node/31742/.
  $menu_links['main-menu_cleveland:node/31742/'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'node/31742/',
    'router_path' => 'node/%',
    'link_title' => 'Cleveland',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'main-menu_cleveland:node/31742/',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -50,
    'customized' => 1,
    'parent_identifier' => 'main-menu_cities:<nolink>',
  );
  // Exported menu link: main-menu_companies-the-care:http://ioby.org/companies-care.
  $menu_links['main-menu_companies-the-care:http://ioby.org/companies-care'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'http://ioby.org/companies-care',
    'router_path' => '',
    'link_title' => 'Companies the Care',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'main-menu_companies-the-care:http://ioby.org/companies-care',
    ),
    'module' => 'menu',
    'hidden' => 1,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 1,
    'weight' => -49,
    'customized' => 1,
  );
  // Exported menu link: main-menu_detroit:node/31830.
  $menu_links['main-menu_detroit:node/31830'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'node/31830',
    'router_path' => 'node/%',
    'link_title' => 'Detroit',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'main-menu_detroit:node/31830',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 1,
    'weight' => -49,
    'customized' => 1,
    'parent_identifier' => 'main-menu_cities:<nolink>',
  );
  // Exported menu link: main-menu_help:help.
  $menu_links['main-menu_help:help'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'help',
    'router_path' => 'help',
    'link_title' => 'help',
    'options' => array(
      'attributes' => array(
        'title' => 'ioby\'s help section',
      ),
      'identifier' => 'main-menu_help:help',
    ),
    'module' => 'system',
    'hidden' => 1,
    'external' => 0,
    'has_children' => 1,
    'expanded' => 0,
    'weight' => -45,
    'customized' => 1,
  );
  // Exported menu link: main-menu_memphis:node/12090.
  $menu_links['main-menu_memphis:node/12090'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'node/12090',
    'router_path' => 'node/%',
    'link_title' => 'Memphis',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'main-menu_memphis:node/12090',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 1,
    'weight' => 0,
    'customized' => 1,
    'parent_identifier' => 'main-menu_cities:<nolink>',
  );
  // Exported menu link: main-menu_pittsburgh:node/37312.
  $menu_links['main-menu_pittsburgh:node/37312'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'node/37312',
    'router_path' => 'node/%',
    'link_title' => 'Pittsburgh',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'main-menu_pittsburgh:node/37312',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 1,
    'weight' => 0,
    'customized' => 1,
    'parent_identifier' => 'main-menu_cities:<nolink>',
  );
  // Exported menu link: main-menu_resources:node/13017.
  $menu_links['main-menu_resources:node/13017'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'node/13017',
    'router_path' => 'node/%',
    'link_title' => 'resources',
    'options' => array(
      'attributes' => array(
        'title' => '',
      ),
      'identifier' => 'main-menu_resources:node/13017',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 1,
    'weight' => -44,
    'customized' => 1,
  );
  // Exported menu link: main-menu_support:http://support.ioby.org.
  $menu_links['main-menu_support:http://support.ioby.org'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'http://support.ioby.org',
    'router_path' => '',
    'link_title' => 'support',
    'options' => array(
      'attributes' => array(
        'title' => 'ioby\'s Support Center',
      ),
      'identifier' => 'main-menu_support:http://support.ioby.org',
    ),
    'module' => 'menu',
    'hidden' => 0,
    'external' => 1,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -43,
    'customized' => 1,
  );

  // Translatables
  // Included for use with string extractors like potx.
  t('Add a project');
  t('Cleveland');
  t('Companies the Care');
  t('Detroit');
  t('Memphis');
  t('Pittsburgh');
  t('about');
  t('blog');
  t('cities');
  t('help');
  t('resources');
  t('support');

  return $menu_links;
}
