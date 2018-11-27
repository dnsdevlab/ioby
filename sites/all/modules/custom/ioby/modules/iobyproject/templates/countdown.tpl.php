<?php
/**
 * @file
 * Default theme implementation for displaying the project deadline countdown.
 *
 * Available variables:
 * - $deadline: A DateTime object that represents the set deadline for the project.
 * - $days_left: An integer representing the number of days left until the project deadline.
 *    A value of -1 means that the deadline has passed.
 */
?>
<div class="project--countdown-widget">

  <div class="countdown-widget__header">
    Project Deadline: <br />
    <span><?php print $deadline->format('F j, Y'); ?></span>
  </div>

  <?php if ($days_left != -1): ?>
    <?php if ($days_left == 0): ?>
      <div class="countdown-widget__alttext">Last Day</div>
    <?php else: ?>
      <div class="countdown-widget__counter">
        <div class="countdown-widget__daysleft">
          <?php print $days_left; ?>
        </div>
        <div class="countdown-widget__text">
          <?php print format_plural($days_left, 'Day Left', 'Days Left'); ?>
        </div>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</div>
