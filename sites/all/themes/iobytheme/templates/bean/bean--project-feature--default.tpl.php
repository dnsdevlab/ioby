<div class="featured-projects__item">
  <article class="featured-project">
    <a href="<?php print $url; ?>">
      <div class="featured-project__image">
        <figure>
          <?php print $image; ?>
        </figure>
      </div>
      <div class="featured-project__text">
        <h3 class="featured-project__title"><?php print $project_title; ?></h3>

        <div class="featured-project__location-and-description">
          <?php if (!empty($city) && !empty($state)): ?>
            <div class="featured-project__location"><?php print $city . ', ' . $state; ?></div>
          <?php endif; ?>
          <div class="featured-project__description"><?php print $summary; ?></div>
        </div>
        <?php if (!empty($percent)): ?>
        <div class="featured-project__progress">
        <span class="featured-project__progress-stat">
          <span data-percent="<?php print $percent; ?>" class="featured-project__progress-number"><?php print $percent; ?></span><span class="progress-bar__percent">%</span>
        </span>
          <span class="featured-project__progress-funded-text">funded</span>
          <div class="featured-project__progress-bar">
            <div class="featured-project__progress-bar-inside" style="width: <?php print $width; ?>%;"></div>
          </div><!--/.featured-project__progress-bar-->
          <div class="featured-project__progress-bar-meta">
            <?php if ($percent !== 100): ?>
              <span class="featured-project__progress-bar-amount">$<?php print $amount; ?> still needed</span>
              <?php if (!empty($countdown)) : ?>
              <span class="featured-project__progress-bar-days"><?php print $countdown; ?></span>
              <?php endif; ?>
            <?php endif; ?>
          </div><!--/.featured-project__progress-bar-meta-->
          <?php endif; ?>
        </div>
      </div>
    </a>
  </article><!--/.featured-project-->
</div><!--/.featured-projects__item-->


