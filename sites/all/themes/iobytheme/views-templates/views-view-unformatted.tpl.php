<?php
/**
 * @file views-view-unformatted.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 *
 * Changed from being <div>s to <article>s for HTML5 goodness.
 */
?>
<?php if (!empty($title)): ?>
  <h3><?php print $title; ?></h3>
<?php endif; ?>
<?php foreach ($rows as $id => $row): ?>
  <article class="<?php print $classes_array[$id]; ?>">
    <?php print $row; ?>
  </article>
<?php endforeach; ?>

