<?php
$keywords = ($query->getParam('q')) ? '/' . $query->getParam('q') : '';
?>
<div class="view view-project-view view-id-project_view">
  <div class="view-content">
    <ul class="tabs">
      <li><a href="<?php print url('projects/browse' . $keywords, array('query' => drupal_get_query_parameters())); ?>" class="browse-switch grid">Grid view <span></span></a></li>
      <li><a href="<?php print url('projects/map' . $keywords, array('query' => drupal_get_query_parameters())); ?>" class="browse-switch map current">Map view <span></span></a></li>
    </ul>
    <?php if (!empty($projects_map)) { print $projects_map; } ?>
  </div>
  <?php print $pager; ?>
</div>
