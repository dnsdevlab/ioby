<?php

/**
 * View template to display a list as a carousel
 * with previous and next items visible and pager.
 */
?>
<ul id="rotator" class="<?php print $classes; ?>">
  <?php foreach ($rows as $id => $row): ?>
    <li class="<?php print $classes_array[$id]; ?>"><?php print $row; ?></li>
  <?php endforeach; ?>
</ul>
<span class="feature-overlay-left"></span>
<span class="feature-overlay-right"></span>