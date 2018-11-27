<?php
// $Id: node.tpl.php,v 1.1.2.5 2010/06/24 23:15:09 grendzy Exp $

/**
 * @file
 * Theme implementation to display an About ioby section node.
 *
 */
?>
<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

<?php if ($user_picture || !$page || $display_submitted): ?>
  <header>
    <?php print $user_picture; ?>

    <?php print render($title_prefix); ?>
    <?php if (!$page): ?>
      <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
    <?php endif; ?>
    <?php print render($title_suffix); ?>

    <?php if ($display_submitted && $is_admin): ?>
      <p class="submitted">
        <?php
          print t('Submitted by !username on !datetime',
            array('!username' => $name, '!datetime' => $date));
        ?>
      </p>
    <?php endif; ?>
  </header>
<?php endif; ?>
  <nav>
    <?php print render($content['navigation']);?>
  </nav>


<div class="content"<?php print $content_attributes; ?>>
  <?php
    // We hide the comments, tags and links now so that we can render them later.
    hide($content['comments']);
    hide($content['links']);
    hide($content['field_tags']);
    print render($content);
  ?>
</div> <!-- /.content -->

<?php if (!empty($content['field_tags']) || !empty($content['links'])): ?>
  <footer>
    <?php print render($content['field_tags']); ?>
    <?php print render($content['links']); ?>
  </footer>
<?php endif; ?>

<?php print render($content['comments']); ?>

</article> <!-- /.node -->