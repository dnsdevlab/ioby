<?php

/**
 * @file
 * Default theme implementation to display the basic html structure of a single
 * Drupal page.
 *
 * Variables:
 * - $css: An array of CSS files for the current page.
 * - $language: (object) The language the site is being displayed in.
 *   $language->language contains its textual representation.
 *   $language->dir contains the language direction. It will either be 'ltr' or 'rtl'.
 * - $rdf_namespaces: All the RDF namespace prefixes used in the HTML document.
 * - $grddl_profile: A GRDDL profile allowing agents to extract the RDF data.
 * - $head_title: A modified version of the page title, for use in the TITLE tag.
 * - $head: Markup for the HEAD section (including meta tags, keyword tags, and
 *   so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *   for the page.
 * - $page_top: Initial markup from any modules that have altered the
 *   page. This variable should always be output first, before all other dynamic
 *   content.
 * - $page: The rendered page content.
 * - $page_bottom: Final closing markup from any modules that have altered the
 *   page. This variable should always be output last, after all other dynamic
 *   content.
 * - $classes String of classes that can be used to style contextually through
 *   CSS.
 *
 * @see template_preprocess()
 * @see template_preprocess_html()
 * @see template_process()
 */
?><?php print $doctype; ?>
<html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>" <?php print $rdf->version . $rdf->namespaces; ?>>
<head<?php print $rdf->profile; ?>>

  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <?php print $scripts; ?>

  <link href="//ioby.tfaforms.net/form-builder/4.1.0/css/wforms-layout.css" rel="stylesheet" type="text/css" />
  <!--[if IE 8]>
  <link href="//ioby.tfaforms.net/form-builder/4.1.0/css/wforms-layout-ie8.css" rel="stylesheet" type="text/css" />
  <![endif]-->
  <!--[if IE 7]>
  <link href="//ioby.tfaforms.net/form-builder/4.1.0/css/wforms-layout-ie7.css" rel="stylesheet" type="text/css" />
  <![endif]-->
  <!--[if IE 6]>
  <link href="//ioby.tfaforms.net/form-builder/4.1.0/css/wforms-layout-ie6.css" rel="stylesheet" type="text/css" />
  <![endif]-->

  <link href="//ioby.tfaforms.net/themes/get/5" rel="stylesheet" type="text/css" />
  <link href="//ioby.tfaforms.net/form-builder/4.1.0/css/wforms-jsonly.css" rel="alternate stylesheet" title="This stylesheet activated by javascript" type="text/css" />
  <script type="text/javascript" src="//ioby.tfaforms.net/wForms/3.7/js/wforms.js"></script>
  <script type="text/javascript">
    wFORMS.behaviors.prefill.skip = false;
  </script>
  <link rel="stylesheet" type="text/css" href="//ioby.tfaforms.net/form-builder/4.1.0/css/wforms_calendar.css" />
  <script type="text/javascript" src="//ioby.tfaforms.net/js/yui/yui-min.js" ></script>
  <script type="text/javascript" src="//ioby.tfaforms.net/wForms/3.7/js/wforms_calendar.js"></script>
  <script type="text/javascript" src="//ioby.tfaforms.net/wForms/3.7/js/localization-en_US.js"></script>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>

  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>

</body>
</html>
