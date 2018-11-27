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

<div id="page-wrapper">
  <div id="top">
    <div class="full">
      <?php
        print render($page['cart']);
      ?>

      <div class="tools">
        <ul>
          <li>
            <?php if ($logged_in):
              print l('My Account','user');
            else:
              print l('Sign Up','user/register' );
            endif;
            ?>
          </li>
          <li>
            <?php if ($logged_in):
              print l('Log Out','user/logout');
            else:
              print l('Log In','user', array('attributes'=>array('class'=>array('login'))));
              print render($page['loginpop']);
            endif;
            ?>
          </li>
          <li id="search">
            <?php print render($page['search']); ?>
          </li>
          <?php if (!empty($user_email) || !empty($user_first_name) || !empty($user_last_name)): ?>
            <span id="user_data" style="display:none;"
              <?php if (!empty($user_email)) { ?>
                data-email="<?php print $user_email ?>" <?php }
                    if (!empty($user_first_name)) { ?>
                data-first-name="<?php print $user_first_name; ?>" <?php }
                    if (!empty($user_last_name)) { ?>
                data-last-name="<?php print $user_last_name; ?>" <?php } ?>
            ></span>
          <?php endif; ?>
        </ul>
      </div>

    <?php if ($main_menu): ?>
      <nav id="navigation" role="navigation">
        <?php $block = module_invoke('nice_menus', 'block_view', '1');
        print render($block['content']); ?>
      </nav> <!-- /.section, /#navigation -->
    <?php endif; //main menu ?>

    </div>
  </div><!-- /#top -->


<div id="page">

  <header id="siteheader" role="banner">

    <?php if ($logo): ?>
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo"><?php print $site_name; ?></a>


    <?php endif; ?>

    <?php if ($site_slogan): ?>
      <div id="intro">
        <p>
          <?php print $site_slogan; ?>
        </p>
        <div id="greeting">
          <?php
          print l('start a project', 'idea', array('attributes' => array('class' => array('button'))));
          print l('find a project »', 'projects/browse', array(
            'attributes' => array(
              'class' => array(
                'button',
                'button-blue'
              )
            ),
            'query' => array('f' => array('sm_field_project_status:1')),
          ));
          ?>
        </div>
      </div>
    <?php endif; ?>
  </header>


  <header id="pageheader">
    <div class="full">
      <?php if ($breadcrumb && $is_admin): ?>
        <div id="breadcrumb"><?php print $breadcrumb; ?></div>
      <?php endif; ?>

      <?php print render($title_prefix); ?>
      <h1 id="pagetitle"><?php print $title; ?></h1>
      <?php print render($title_suffix); ?>
      <?php print render($page['header']); ?>

      <?php if (isset($node) && $node->type == 'project_2') :
      // Don't show on edit form
      if(arg(0) == 'node' && arg(2) !== 'edit'){
        if (isset($node->field_project_inbrief['und'][0]['safe_value'])) {
          print '<p>'.text_summary($node->field_project_inbrief['und'][0]['safe_value'],null,230).'</p>';
        }
        elseif (isset($node->body['und'][0]['safe_value'])) {
          print '<p>'.text_summary($node->body['und'][0]['safe_value'],null,230).'</p>';
        }
      }
      endif; ?>
    </div>
  </header>


<div id="main-wrapper">

  <?php $pageClasses ="";
    if($page['sidebar']) $pageClasses .= " with-sidebar";
    if($page['search_facets']) $pageClasses .= " with-sidebar-left";

  ?>

  <div id="main" class="clearfix<?php print $pageClasses ?>">
    <?php print render($page['subhead']); ?>

    <?php if ($page['search_facets']): ?>
      <div id="current-search">
        <?php print render($page['current_search']) ?>
      </div>
    <?php endif; ?>

    <div id="content" class="column" role="main">
      <?php if ($page['highlighted']): ?>
        <div id="highlighted"><?php print render($page['highlighted']); ?></div>
      <?php endif; ?>

      <?php print $messages; ?>

      <?php if ($tabs): ?>
        <div class="tabs"><?php print render($tabs); ?></div>
      <?php endif; ?>
      <?php print render($page['help']); ?>
      <?php if ($action_links): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>
      <?php print render($page['content']); ?>
      <?php print $feed_icons; ?>
    </div>
    <!-- /.section, /#content -->

    <?php if ($page['search_facets']): ?>
      <div id="sidr-facets">
        <?php print render($page['search_facets']); ?>
      </div>
    <?php endif; ?>


    <?php if ($page['sidebar']): ?>
    <div id="rail" class="column">
      <?php print render($page['sidebar']); ?>
    </div>
    <?php endif; ?>


</div></div> <!-- /#main, /#main-wrapper -->

<footer id="footer" role="contentinfo">
  <div id="bottom">
    <div class="full">
      <ul>
        <li class="title"><?php print t("get our newsletter") ?></li>
        <li class="newsletter">
          <!-- Begin MailChimp Signup Form -->
          <div id="mc_embed_signup">
            <form action="//ioby.us1.list-manage.com/subscribe/post?u=f3c712aa320de5a6d109211a6&amp;id=71207383ff" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
              <div class="mc-field-group">
                <label for="mce-EMAIL">email address</label>
                <input type="text" value="" name="EMAIL" class="required email" id="mce-EMAIL">
              </div>
              <input type="hidden" value="<?php $path = drupal_get_destination(); print htmlentities(filter_xss($path['destination'])); ?>" name="MMERGE27" class="required" id="mce-MMERGE27">
              <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn">
            </form>
          </div>
          <!--End mc_embed_signup-->
        </li>
      </ul>
    </div>
  </div>

  <div class="section full clearfix">
    <?php print theme('links__system_secondary_menu',
    array('links' => $secondary_menu, //aka "user menu"
          'attributes' => array('id' => 'secondary-menu',
                                'class' => array('links', 'clearfix')),
                                'heading' => t('Secondary menu')
                                )); ?>
    <!-- /#navigation -->

    <?php print render($page['footer']); ?>

  <div class="clearfix">&nbsp;</div>

  <section class="footer-logos">
    <?php print render($footer_logos); ?>
  </section>

  <section class="credits">
    copyright &copy; <?php print date("Y"); ?> ioby, a 501(c)(3) nonprofit<br/>
    site by <a href="http://www.newsignature.com" target="_blank">New Signature</a>
  </section>
</div></footer>
 <!-- /.section, /#footer -->

</div></div> <!-- /#page, /#page-wrapper -->

