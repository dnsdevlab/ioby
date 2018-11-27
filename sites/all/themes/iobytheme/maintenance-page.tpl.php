<?php
// $Id: page.tpl.php,v 1.1.2.5 2010/08/13 20:29:13 spaceninja Exp $

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/garland.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 */
 
?>
<!DOCTYPE html>
<html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">
<head>

<?php print $head; ?>
<title><?php print $head_title; ?></title>

<script src="<?php print '/' . path_to_theme() . '/js/html5.js'; ?>" type="text/javascript"></script>
<?php print $styles; ?>

<?php print $scripts; ?>

</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
<div id="page-wrapper">

<div id="page">

  <header id="siteheader" role="banner">

    <?php if ($logo): ?>
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo"><?php print $site_name; ?></a>
        <h6 class="locale"><?php print t('new york city')?></h6>
        
    <?php endif; ?>

    <?php if ($site_slogan): ?>
      <div id="intro">
        <p>
          <?php print $site_slogan; ?>
        </p>
        <div id="greeting">
          
        </div>
      </div>
    <?php endif; ?>
  </header>


  <header id="pageheader">
    <div class="full">
      
      <?php print render($title_prefix); ?>
      <h1 id="pagetitle"><?php print $title; ?></h1>
      <?php print render($title_suffix); ?>
      <?php print render($page['header']); ?>
    </div>
  </header>


<div id="main-wrapper">

  <div id="main" class="clearfix">
    <?php print render($page['subhead']); ?>
    <div id="content" class="column" role="main">
      <?php print $messages; ?>
      
      <?php if ($tabs): ?>
        <div class="tabs"><?php print render($tabs); ?></div>
      <?php endif; ?>
      <?php print $content; ?>
    </div> 
    <!-- /.section, /#content -->

</div></div> <!-- /#main, /#main-wrapper -->

<footer id="footer" role="contentinfo">
  <div id="bottom">
    <div class="full">
      
    </div>    
  </div>  

  <div class="section full clearfix">
    <!-- /#navigation -->

    <?php print render($page['footer']); ?>

  <section class="credits">
    copyright &copy; <?php print date("Y"); ?> ioby, a 501(c)(3) nonprofit<br/>
    site by <a href="http://www.newsignature.com" target="_blank">New Signature</a>
  </section>
</div></footer>
 <!-- /.section, /#footer -->

</div></div> <!-- /#page, /#page-wrapper -->
</body>
</html>