<?php
// $Id: node.tpl.php,v 1.1.2.5 2010/06/24 23:15:09 grendzy Exp $

/**
 * @file
 * Default theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: An array of node items. Use render($content) to print them all,
 *   or print a subset such as render($content['field_example']). Use
 *   hide($content['field_example']) to temporarily suppress the printing of a
 *   given element.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct url of the current node.
 * - $display_submitted: whether submission information should be displayed.
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type, i.e., "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $view_mode: View mode, e.g. 'full', 'teaser'...
 * - $teaser: Flag for the teaser state (shortcut for $view_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * Field variables: for each field instance attached to the node a corresponding
 * variable is defined, e.g. $node->body becomes $body. When needing to access
 * a field's raw values, developers/themers are strongly encouraged to use these
 * variables. Otherwise they will have to explicitly specify the desired field
 * language, e.g. $node->body['en'], thus overriding any language negotiation
 * rule that was previously applied.
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see template_process()
 */

?>

<?php if($page): ?>
  <?php if (!empty($sponsors)): ?>
    <div class="sponsor-ribbon">
      <div class="full">
        <?php foreach($sponsors as $campaign_nid => $sponsor_info) : ?>
          <?php if($sponsor_info['sponsor_message']) : ?>
              <?php
                $campaign = node_view(node_load($campaign_nid));

                $campaign_button_text = (!empty($campaign['#node']->field_view_campaign_button_text[LANGUAGE_NONE][0]['value'])) ?
                    $campaign['#node']->field_view_campaign_button_text[LANGUAGE_NONE][0]['value'] :
                    t('View the Campaign');
              ?>

              <a class="sponsor-info" href="<?php print url('node/' . $campaign_nid) ?>">
                <?php print render($campaign['field_campaign_logo']); ?>
                <div class="sponsor-body">
                <p><?php print $sponsor_info['sponsor_message']; ?></p>
                <br />
                <button><?php print $campaign_button_text; ?></button>
                </div>
              </a>

          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <header>

    <?php if (!$page): ?>
    <?php print render($title_prefix); ?>
      <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
    <?php print render($title_suffix); ?>
    <?php endif; ?>

    <?php if ($display_submitted && $is_admin): ?>
      <p class="submitted">
        <?php
          print t('Submitted by !username on !datetime',
            array('!username' => $name, '!datetime' => $date));
        ?>
      </p>
    <?php endif; ?>
  </header>

  <nav>
    <ul class="tabs project-tabs clearfix">
      <li class="active"><a href="#overview">Overview</a></li>
      <li><a href="#budget">Budget</a></li>
      <li><a href="#updates">Updates</a></li>
      <!-- li><a href="#media">Photos</a></li -->
      <li><a href="#contributors">Donors</a></li>
      <?php if ($donations_access): ?><li><a href="#donations">Donor List Detail</a></li><?php endif; ?>
      <li class="last"><a href="#nearby">Nearby Projects</a></li>
    </ul>
  </nav>

  <div class="content"<?php print $content_attributes; ?>>

  <div class="pane" id="overview">

    <div class="clearfix intro">
      <div class="photo"><?php //print render($content['field_project_photo']); ?></div>
      <?php print $project_gallery; ?>
      <dl class="specs">
        <dt>project leader</dt>
        <dd><?php print $name ?></dd>
        <dt>location</dt>
        <dd>
          <?php
            print render($content['field_project_address']);
            print render($content['field_project_boroughs']);
            print render($content['field_project_neighborhood']);
          ?>
        </dd>
        <dt>latest update <a href="/<?php print str_replace("%", $node->nid, $project_update_rss_path) ?>" class="icon-small rss">rss</a></dt>
        <dd><?php //like magic!
          print views_embed_view('project_updates','ctools_context_1',$node->nid);
          ?>
        </dd>
      </dl>
    </div>


    <h2>the project</h2>
      <?php print render($content['body']); ?>


    <h2>the steps</h2>
      <?php print render($content['field_project_desc']); ?>

    <h2>why we're doing it</h2>
      <?php print render($content['field_project_issue']); ?>

    <?php print render($content['comments']); ?>
    <?php if( !$logged_in) print render($content['links']); //only shows register/login to comment link, right? ?>
  </div>

  <div class="pane" id="budget">
    <h2>budget</h2>
    <?php print render($content['field_project_budget']); ?>
  </div>

  <div class="pane" id="updates">
    <h2>updates</h2>
    <?php print $project_update_link; ?>
    <?php //like magic!
      print views_embed_view('project_updates','page',$node->nid);
    ?>
  </div>

  <div class="pane" id="media">
    <h2>photos</h2>
    This is where photos will go once we build flickr integration
  </div>

  <div class="pane" id="contributors">
    <h2>donors</h2>
    <?php
      print views_embed_view('project_donors','block_1',$node->nid);
    ?>

  </div>

  <?php if ($donations_access): ?>
  <div class="pane" id="donations">
    <h2>donor list detail</h2>
    <?php print views_embed_view('project_donations','block_1',$node->nid); ?>
  </div>
  <?php endif; ?>

  <div class="pane" id="nearby">
    <h2>nearby projects</h2>
    <?php
      print views_embed_view('micro_projects','block',$node->nid);
    ?>
  </div>

  </div> <!-- /.content -->

<?php if (!empty($content['field_tags']) || !empty($content['links'])): ?>
  <footer>
    <?php print render($content['field_tags']); ?>
    <?php //print render($content['links']); ?>
  </footer>
<?php endif; ?>


</article> <!-- /.node -->

<?php

  //if this is a view or somesuch....
  elseif ($teaser):

  $funding = iobyproject_amount_raised( $node->nid );
  foreach ($funding as $key => $value) {
    $$key = $value;
  }

  if(!empty($content['field_project_volunteers'])) {
    $volunteers = $content['field_project_volunteers']['#items'][0]['value'];
  } else {
    $volunteers = 0;
  }
?>
<article id="node-<?php print $node->nid; ?>" class="project-miniview <?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <div class="main-info">
    <?php if(!empty($sponsors)> 0) : ?>
      <div class="sponsor-ribbon ribbon-small">
      <?php foreach ($sponsors as $sponsor_info) :?>
        <?php if ($sponsor_info['sponsor_teaser']): ?>
         <span><?php print $sponsor_info['sponsor_teaser']; ?></span>
        <?php endif; ?>
      <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <div class="project-photo"><a href="<?php print $node_url; ?>"><?php print render($content['field_project_photo'][0]);?></a></div>
    <header>
      <h3><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h3>
    </header>
    <div class="project-summary">
      <?php
      if (!empty($content['field_project_inbrief']['#items'][0]['safe_value'])) {
        print render($content['field_project_inbrief']);
      } else {
        print render($content['body']);
      }
      ?>
    </div>
    <div class="project-needs">
      NEEDS: <span class="project-amt">$<?php print number_format($amount_to_go);?></span>
      of <?php print number_format($content['field_project_cost'][0]['#markup']);?>
      <?php echo $volunteers != 1 ? '' : '+ <span class="needs-volunteers">Volunteers</span>';?>
    </div>
    <div class="project-meter">
      <div class="progress-bar" style="height:<?php echo $pct_done;?>%"></div>
    </div>
  </div>
</article>
<?php
//this should be for project submission preview only
else: ?>

        <dl class="preview-specs clearfix">
        <dt>Project Name</dt>
        <dd><?php print $title ?></dd>
        <dt>Location</dt>
        <dd>
          <?php
            print render($content['field_project_address']);
            print render($content['field_project_boroughs']);
            print render($content['field_project_neighborhood']);
          ?>
        </dd>
        <dt>Impact Areas</dt>
        <dd>
          <?php render($content['field_project_impact_areas']); ?>
        </dd>
        <dt>Overview</dt>
        <dd><?php
          print render($content['body']);
        ?></dd>
        <dt>Photo</dt>
        <dd><?php
          print render($content['field_project_photo']);
        ?></dd>
        <dt>Funds Needed</dt>
        <dd><?php
          print str_replace("$$", "$",render($content['field_project_cost']));
        ?></dd>

      </dl>

<?php endif;?>
