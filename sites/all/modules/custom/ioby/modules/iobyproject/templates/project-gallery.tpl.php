<div class="project-gallery">
  <?php if (!empty($gallery_items)): ?>
  <ul class="project-gallery__list">
    <?php foreach($gallery_items as $item): ?>
      <li><?php print $item; ?></li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</div>
