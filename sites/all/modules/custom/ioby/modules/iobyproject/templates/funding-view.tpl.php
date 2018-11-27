<!--
    $funding is a variable which contains all values coming from the module.
    $donate_form is a variable which contains the donate form.
  -->

  <?php
// NOT THE BEST solution, this might need more work done the road
  if($funding['node-page'] == 1) { ?>
  <div class="funding-view">
    <div id="total_funding_needed">Total Funding Needed: <span>$<?php print number_format($funding['total']); ?></span></div>
    <div id="general_info">
      <div class="bar">
        <div class="inner-bar" style="width: <?php echo sprintf("%2.2f%%", ($funding['pct'])); ?>">&nbsp;</div>
      </div>
        <div class="amounts clearfix">
          <div class="money needed">
              <span class="big">$<?php echo number_format($funding['needed']); ?></span>
              <span class="small">still needed</span>
          </div>
          <div class="money raised">
              <span class="big">$<?php echo number_format($funding['raised']); ?></span>
              <span class="small">raised so far</span>
          </div>
        </div>
    </div>
    <div id="donate">
      <?php print $funding['form']; ?>
    </div>

    <!-- Will be implemented... later
    <div id="stats">
        <ul class="donation_list">
            <?php foreach($funding['list'] as $key => $element) : ?>
            <li><span class="number"><?php echo $element['number']; ?></span> people donated $<?php echo $element['value']; ?> or more</li>
            <?php endforeach; ?>
        </ul>
        <?php if (!$funding['suppress']) : ?>
        <div class="message"><a href="/page/donate-offline?width=500&height=500&iframe=true&ajax=1" class="colorbox-load">prefer to give by mail or phone?</a></div>
        <?php endif; ?>
    </div>
   -->
</div>
<?php } else { ?>
<?php
  // prints class instead of div above
  if ($funding['suppress'] == TRUE) {
    print 'funded-project';
  }else {
    return '';
  }
 ?>
<?php } ?>
