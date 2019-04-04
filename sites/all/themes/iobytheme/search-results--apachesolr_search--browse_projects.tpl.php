<?php
$keywords = ($query->getParam('q')) ? '/' . $query->getParam('q') : '';
?>
<div class="view view-project-view view-id-project_view">
  <div class="view-content">
    <ul class="tabs">
      <li><a href="<?php print url('projects/browse' . $keywords, array('query' => drupal_get_query_parameters())); ?>" class="browse-switch grid current">Grid view <span></span></a></li>
      <li><a href="<?php print url('projects/map' . $keywords, array('query' => drupal_get_query_parameters())); ?>" class="browse-switch map">Map view <span></span></a></li>
    </ul>
    <?php print $search_results; ?>
  </div>
  <?php print $pager; ?>
</div>
