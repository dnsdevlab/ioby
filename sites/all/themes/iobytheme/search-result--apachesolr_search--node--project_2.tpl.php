<?php

$node_output = node_view(node_load($result['node']->entity_id), 'teaser');
print drupal_render($node_output);
