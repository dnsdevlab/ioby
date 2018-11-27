<style>
  body { background-color:#fff; }
</style>
<?php print render($title_prefix); ?>
<h2 id="pagetitle"><?php print $title; ?></h2>
<?php 
  print render($title_suffix);
  
  print $messages;
  print render($page['content']); 
?>