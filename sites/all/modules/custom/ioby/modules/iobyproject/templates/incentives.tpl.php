<?php if (!empty($incentives)): ?>
  <div class="project-incentives">
    <?php foreach ($incentives as $incentive): ?>
      <div class="project-incentives-row">
        <div class="project-incentive__level"><?php print $incentive['incentive_level']; ?></div>
        <div class="project-incentive__description"><?php print $incentive['incentive_description']; ?></div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
