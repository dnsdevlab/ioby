<div>

  <!-- Brand Hero -->
  <h3>Brand Hero</h3>
  <?php if (isset($hero_image)): ?>
    <?php print render($hero_image); ?>
  <?php endif; ?>

  <?php if (isset($static_text)): ?>
    <p><?php print $static_text; ?></p>
  <?php endif; ?>

  <?php if (isset($animated_text)): ?>
    <ul>
      <?php foreach ($animated_text as $text): ?>
        <li><?php print $text; ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  <!-- Impact Statements -->
  <h3>Impact Statements</h3>
  <?php if (isset($impact_blocks)): ?>
    <?php print render($impact_blocks); ?>
  <?php endif; ?>

  <!-- Browse Cities -->
  <h3>Browse Cities</h3>
  <?php if (isset($browse_cities_tile)): ?>
    <p><?php print $browse_cities_tile; ?></p>
  <?php endif; ?>

  <?php if (isset($city_links)): ?>
    <ul>
      <?php foreach($city_links as $city_link): ?>
        <li><a href="<?php print $city_link['url']; ?>"><?php print $city_link['title']; ?></a></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

</div>
