<?php $amt_raised = 50.00; // Example amount for testing
$amt_needed = $content['field_project_cost']['#items'][0]['value'];
$amt_to_go = $amt_needed - $amt_raised;
?>
<header>
  <h5><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h5>
</header>
<div class="project-needs">
  NEEDS: <span id="amt-remaining" class="project-amt">$<?php echo $amt_to_go;?></span>
  of <?php print render($content['field_project_cost']);?>
  <?php echo $volunteers != 1 ? '' : '+ <span id="needs-volunteers">Volunteers</span>';?>
</div>
<div class="extra-info">
  Organizer: <?php print $name;?></div>
</div>