<li class="updates__item">
  <article class="update">
    <a href="<?php print $link_url; ?>">
      <div class="update__image">
        <figure>
          <?php print $image; ?>
        </figure>
      </div><!--/.update__image-->
      <div class="update__text">
        <?php if (!empty($date)): ?>
          <div class="update__date"><?php print $date; ?></div>
          <?php print render($content['field_updates_date']); ?>
        <?php endif; ?>
        <h3 class="update__title"><?php print $title; ?></h3>
        <div class="update__description"><p><?php print $summary; ?></p></div>
        <?php if ($link_style == 'Button'): ?><div class="update__button"><?php endif; ?>
          <span><?php print $link_title; ?></span>
        <?php if ($link_style == 'Button'): ?></div><?php endif; ?>
      </div><!--/.update__text-->
    </a>
  </article><!--/.update-->
</li>

<!--
<?php if (isset($image)): ?>
  <p><?php print $image; ?></p>
<?php endif; ?>

<?php if (isset($subtitle)): ?>
  <p><?php print $subtitle; ?></p>
<?php endif; ?>

<?php if (isset($summary)): ?>
  <p><?php print $summary; ?></p>
<?php endif; ?>
--?