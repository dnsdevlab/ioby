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
<section class="hero">
  <div class="hero__image">
    <figure>
      <picture>
        <source srcset="<?php print $hero_image_large; ?>" media="(min-width: 700px)" />
        <source srcset="<?php print $hero_image_medium; ?>" media="(min-width: 440px)" />
        <img srcset="<?php print $hero_image_small; ?>"/>
      </picture>
    </figure>
  </div><!--/.hero__image-->

  <div class="hero__text"  data-words="<?php print $animated_text_data; ?>">
    <h2><?php print $hero_static_text; ?>  <br/><span class="active-text-and-cursor"><span class="active-text"></span></span></h2>
  </div><!--/.hero__text-->
  </div><!--/.hero__inside-->
  <div class="hero__inside">
</section><!--/.hero-->
<div class="home-wrapper-outline-bg">
  <section class="impact-list">
    <div class="impact-list__inside">
      <header class="impact-list__header">
        <h2 class="impact-list__title"><?php print $impact_title_line_1; ?><strong><?php print $impact_title_line_2; ?></strong></h2>
      </header>
      <div class="impact-list__body">
        <?php /* foreach($impact_blocks as $key => $impact_block) {
          switch ($key) {
          case 0:
            $impact_figure_start = '<figure><svg width="278" height="333" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <defs>
                    <mask id="image-mask-green">
                      <path fill="#ffffff" d="M3.59 0l272.439 10.59 1.396 174.62a55.14 55.14 0 0 1-6.895 27.15l-45.372 81.98c-15.666 28.299-47.893 43.16-79.598 36.706l-17.57-3.579a89.664 89.664 0 0 0-21.981-1.71l-55.498 2.52 3.224-50.482a118.452 118.452 0 0 0-4.423-40.467L3.685 79.565a93.624 93.624 0 0 1-3.448-32.67L3.59 0z"/>
                    </mask>
                  </defs>
                  <g mask="url(#image-mask-green)">
                    <image x="0" y="0" width="380" height="380" transform="translate(-51,-24)" xlink:href="';
            $impact_figure_end = '"/>
                    <rect fill="#84BF41" opacity=".6" width="100%" height="100%"/>
                  </g>
                </svg>
              </figure>';
            $impact_image = $impact_block;
          break;
          }
        }
        */ ?>
        <?php if (!empty($impact[0]['image'])): ?>
        <div class="impact-list__item impact-list__item--green">
          <article class="impact">
            <div class="impact__image">
              <figure>
                <svg width="278" height="333" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <defs>
                    <mask id="image-mask-green">
                      <path fill="#ffffff" d="M3.59 0l272.439 10.59 1.396 174.62a55.14 55.14 0 0 1-6.895 27.15l-45.372 81.98c-15.666 28.299-47.893 43.16-79.598 36.706l-17.57-3.579a89.664 89.664 0 0 0-21.981-1.71l-55.498 2.52 3.224-50.482a118.452 118.452 0 0 0-4.423-40.467L3.685 79.565a93.624 93.624 0 0 1-3.448-32.67L3.59 0z"/>
                    </mask>
                  </defs>
                  <g mask="url(#image-mask-green)">
                    <image x="0" y="0" width="380" height="380" transform="translate(-51,-24)" xlink:href="<?php print $impact[0]['image']; ?>"/>
                    <rect fill="#84BF41" opacity=".6" width="100%" height="100%"/>
                  </g>
                </svg>
              </figure>
            </div>
            <?php print render($impact_blocks[0]); ?>
          </article>
        </div><!--/.impact-list__item-->
        <?php endif; ?>
        <?php if (!empty($impact[1]['image'])): ?>
        <div class="impact-list__item impact-list__item--orange">
          <article class="impact">
            <div class="impact__image">
              <figure>
                <svg width="333" height="315" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <defs>
                    <mask id="image-mask-orange">
                      <path fill="#ffffff" d="M98.407 26.716L245.045.754c10.894-1.927 22.02-.148 32.001 5.19 18.2 9.752 39.279 19.751 45.6 37.246 3.618 9.999 7.162 58.43 9.173 90.455 1.172 18.68-3.37 37.13-12.832 52.467l-21.021 34.001c-21.426 34.66-52.993 59.486-89.045 70.012L123.724 315 48.67 298.971c-24.29-5.172-42.924-27.51-46.28-55.383L.624 228.795c-2.243-18.763 1.606-37.79 10.821-53.538l86.962-148.54z"/>
                    </mask>
                  </defs>
                  <g mask="url(#image-mask-orange)">
                    <image x="0" y="0" width="380" height="380" transform="translate(-24, -32)" xlink:href="<?php print $impact[1]['image']; ?>"/>
                    <rect fill="#E88124" opacity=".6" width="100%" height="100%"/>
                  </g>
                </svg>
              </figure>
            </div>
            <?php print render($impact_blocks[1]); ?>
          </article>
        </div><!--/.impact-list__item-->
        <?php endif; ?>
        <?php if (!empty($impact[2]['image'])): ?>
        <div class="impact-list__item impact-list__item--purple">
          <article class="impact">
            <div class="impact__image">
              <figure>
                <svg width="380" height="315" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <defs>
                    <mask id="image-mask-purple">
                      <path fill="#ffffff" d="M6.264 0L380 59.632 376.868 315l-220.793-7.801c-23.758-.832-47.122-7.747-68.639-20.285L0 235.931 6.264 0z"/>
                    </mask>
                  </defs>
                  <g mask="url(#image-mask-purple)">
                    <image x="0" y="0" width="380" height="380" transform="translate(0, -32)" xlink:href="<?php print $impact[2]['image']; ?>"/>
                    <rect fill="#8241D7" opacity=".6" width="100%" height="100%"/>
                  </g>
                </svg>
              </figure>
            </div>
            <?php print render($impact_blocks[2]); ?>
          </article>
        </div><!--/.impact-list__item-->
        <?php endif; ?>
      </div><!--/.impact-list__body-->
      <footer class="impact-list__footer">
        <a href="<?php print $impact_link_url; ?>"><?php print $impact_link_title; ?></a>
      </footer>
    </div><!--/.impact-list__inside-->
  </section><!--/.impact-list-->
</div><!--/.home-wrapper-outline-bg-->
<section class="connect">
  <div class="connect__inside">
    <header class="connect__header">
      <h3 class="connect__title"><?php if (!empty($email_title_line_1)) { print $email_title_line_1; } ?><span><?php print $email_title_line_2; ?></span></h3>
    </header>
    <div class="connect__body">
      <div class="connect__newsletter">
        <h4 class="connect__newsletter-title"><?php print $email_form_supertitle; ?></h4>

        <div class="connect__newsletter-form-item"> <!--id="mc_embed_signup">-->
          <!-- Begin MailChimp Signup Form -->
<!--            <div id="mc_embed_signup">-->
            <form action="//ioby.us1.list-manage.com/subscribe/post?u=f3c712aa320de5a6d109211a6&amp;id=71207383ff" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
              <div class="mc-field-group">
                <label for="connect-email" class="visually-hidden">Email address</label>
                <input type="email" value="" name="EMAIL" class="required email" id="connect-email">
              </div>
              <input type="hidden" value="<?php $path = drupal_get_destination(); print htmlentities(filter_xss($path['destination'])); ?>" name="MMERGE27" class="required" id="connect-email">
              <input type="submit" value="Sign up" name="subscribe" placeholder="Email address">
            </form>
<!--            </div>-->
          <!--End mc_embed_signup -->
<!--            <label class="visually-hidden" for="connect-email">Email address</label>-->
<!--            <input type="email" name="email" id="connect-email" placeholder="Email address"/>-->
<!--            <input type="submit" value="Sign up"/>-->
        </div><!--/.connect__newsletter-form-item-->
      </div><!--/.connect__newsletter-->
      <div class="connect__social">
        <h4 class="connect__social-title"><?php print $email_social_supertitle; ?></h4>
        <div class="connect__social-list">
          <a href="http://twitter.com/ioby" class="twitter" ><svg width="40" height="32" xmlns="http://www.w3.org/2000/svg"><path d="M40 3.8a17.04 17.04 0 0 1-4.112 4.175c.017.233.026.583.026 1.05 0 2.167-.322 4.33-.965 6.488-.643 2.158-1.62 4.229-2.931 6.212a24.538 24.538 0 0 1-4.683 5.263c-1.81 1.525-3.993 2.741-6.548 3.65-2.555.908-5.288 1.362-8.198 1.362C8.003 32 3.807 30.792 0 28.375c.592.067 1.252.1 1.98.1 3.807 0 7.2-1.15 10.177-3.45-1.776-.033-3.367-.57-4.771-1.612-1.405-1.042-2.37-2.371-2.894-3.988a10.54 10.54 0 0 0 1.549.125c.727 0 1.446-.092 2.157-.275-1.895-.383-3.464-1.312-4.708-2.787-1.244-1.476-1.866-3.188-1.866-5.138v-.1a8.227 8.227 0 0 0 3.706 1.025A8.112 8.112 0 0 1 2.665 9.4a7.775 7.775 0 0 1-.99-3.85c0-1.467.372-2.825 1.117-4.075 2.047 2.483 4.539 4.47 7.475 5.962 2.935 1.492 6.078 2.321 9.428 2.488a8.84 8.84 0 0 1-.203-1.85c0-2.233.8-4.137 2.399-5.713C23.49.787 25.423 0 27.69 0c2.368 0 4.365.85 5.99 2.55 1.844-.35 3.578-1 5.202-1.95-.626 1.917-1.827 3.4-3.604 4.45 1.574-.167 3.147-.583 4.721-1.25z" fill="#FFF" fill-rule="evenodd"/></svg>
          </a>
          <a href="http://facebook.com/ioby.org" class="facebook" ><svg width="36" height="36" xmlns="http://www.w3.org/2000/svg"><path d="M34.008 0c.547 0 1.015.195 1.406.586.39.39.586.86.586 1.406v32.016c0 .547-.195 1.015-.586 1.406-.39.39-.86.586-1.406.586h-9.164V22.055h4.664l.703-5.438h-5.367v-3.469c0-.875.183-1.53.55-1.968.368-.438 1.083-.657 2.145-.657l2.86-.023V5.648c-.985-.14-2.376-.21-4.172-.21-2.125 0-3.825.624-5.098 1.874-1.274 1.25-1.91 3.016-1.91 5.297v4.008H14.53v5.438h4.688V36H1.992a1.918 1.918 0 0 1-1.406-.586c-.39-.39-.586-.86-.586-1.406V1.992C0 1.445.195.977.586.586.976.196 1.446 0 1.992 0h32.016z" fill="#FFF" fill-rule="evenodd"/></svg>
          </a>
          <a href="http://instagram.com/inourbackyards/" class="instagram" ><svg width="38" height="38" xmlns="http://www.w3.org/2000/svg"><path d="M12.668 19a6.333 6.333 0 1 1 12.667 0 6.334 6.334 0 0 1-12.667 0m-3.424 0c0 5.388 4.368 9.756 9.757 9.756 5.389 0 9.757-4.368 9.757-9.756S24.389 9.244 19 9.244c-5.389 0-9.757 4.368-9.757 9.756m17.62-10.143a2.281 2.281 0 0 0 4.561 0 2.28 2.28 0 0 0-2.28-2.279 2.28 2.28 0 0 0-2.282 2.279m-15.54 25.608c-1.852-.084-2.859-.393-3.528-.653A5.911 5.911 0 0 1 5.61 32.39a5.873 5.873 0 0 1-1.421-2.184c-.261-.669-.57-1.675-.654-3.528-.092-2.003-.11-2.604-.11-7.678s.02-5.674.11-7.678c.084-1.853.394-2.858.654-3.529a5.911 5.911 0 0 1 1.42-2.185 5.867 5.867 0 0 1 2.186-1.421c.67-.261 1.676-.57 3.529-.654 2.003-.092 2.604-.11 7.677-.11 5.074 0 5.674.02 7.678.11 1.853.085 2.858.395 3.529.654.887.344 1.52.757 2.185 1.42.665.666 1.076 1.3 1.422 2.186.26.67.569 1.676.653 3.529.092 2.004.11 2.604.11 7.678 0 5.072-.018 5.674-.11 7.678-.084 1.852-.394 2.86-.653 3.528a5.894 5.894 0 0 1-1.422 2.184c-.664.665-1.298 1.076-2.185 1.422-.67.26-1.676.569-3.529.653-2.002.092-2.604.11-7.678.11-5.073 0-5.674-.018-7.677-.11M11.167.115C9.144.207 7.763.528 6.555.998A9.326 9.326 0 0 0 3.19 3.189 9.298 9.298 0 0 0 .998 6.555c-.47 1.208-.79 2.589-.883 4.612C.021 13.193 0 13.84 0 19s.021 5.807.115 7.833c.092 2.023.413 3.404.883 4.612a9.292 9.292 0 0 0 2.191 3.366 9.32 9.32 0 0 0 3.366 2.191c1.21.47 2.59.79 4.612.883 2.028.092 2.674.115 7.834.115 5.161 0 5.807-.021 7.833-.115 2.023-.092 3.405-.413 4.612-.883a9.346 9.346 0 0 0 3.366-2.191 9.331 9.331 0 0 0 2.192-3.366c.47-1.208.792-2.589.882-4.612C37.98 24.806 38 24.16 38 19s-.021-5.807-.114-7.833c-.092-2.023-.412-3.404-.882-4.612a9.345 9.345 0 0 0-2.192-3.366A9.32 9.32 0 0 0 31.448.998c-1.21-.47-2.59-.792-4.612-.883C24.81.023 24.162 0 19.002 0c-5.161 0-5.807.022-7.835.115" fill="#FFF" fill-rule="nonzero"/></svg>
          </a>
        </div>
      </div><!--/.connect__social-->
    </div><!--/.connect__body-->
  </div><!--/.connect__inside-->
</section><!--/.connect-->
<section class="featured-projects">
  <div class="featured-projects__inside">
    <header class="featured-projects__header">
      <h2 class="featured-projects__title"><?php print $project_title; ?></h2>
    </header>
    <div class="featured-projects__body">
      <?php foreach($project_features as $project_feature) { print render($project_feature); } ?>
      <?php /* <div class="featured-projects__item">
        <article class="featured-project">
          <a href=".">
            <div class="featured-project__image">
              <figure>
                <img src="/sites/all/themes/iobytheme/patternlab/public/images/site/featured-project_1.jpg" alt="image description" title="Crop width and height" />
              </figure>
            </div>
            <div class="featured-project__text">
              <h3 class="featured-project__title">The Bronx is Blooming</h3>
              <div class="featured-project__location-and-description">
                <div class="featured-project__location">New York City, NY</div>
                <div class="featured-project__description">Empowering Bronx students to mentor their communities in the revitalization of local parks.</div>
              </div>
              <div class="featured-project__progress">
      <span class="featured-project__progress-stat">
        <span data-percent="44" class="featured-project__progress-number">44</span><span class="progress-bar__percent">%</span>
      </span>
                <span class="featured-project__progress-funded-text">funded</span>
                <div class="featured-project__progress-bar">
                  <div class="featured-project__progress-bar-inside" style="width: 56%;"></div>
                </div><!--/.featured-project__progress-bar-->
                <div class="featured-project__progress-bar-meta">
                  <span class="featured-project__progress-bar-amount">$2,453 still needed</span>

                  <span class="featured-project__progress-bar-days">12 days to go</span>
                </div><!--/.featured-project__progress-bar-meta-->
              </div>
            </div>
          </a>
        </article><!--/.featured-project-->
      </div><!--/.featured-projects__item-->
      <div class="featured-projects__item">
        <article class="featured-project">
          <a href=".">
            <div class="featured-project__image">
              <figure>
                <img src="/sites/all/themes/iobytheme/patternlab/public/images/site/featured-project_2.jpg" alt="image description" title="Crop width and height" />
              </figure>
            </div>
            <div class="featured-project__text">
              <h3 class="featured-project__title">South Side Pittsburgh Community Murals</h3>
              <div class="featured-project__location-and-description">
                <div class="featured-project__location">Pittsburgh, PA</div>
                <div class="featured-project__description">Enriching the South Side with public art  that expresses artistic visions of past, present, and future.</div>
              </div>
              <div class="featured-project__progress">
      <span class="featured-project__progress-stat">
        <span data-percent="93" class="featured-project__progress-number">93</span><span class="progress-bar__percent">%</span>
      </span>
                <span class="featured-project__progress-funded-text">funded</span>
                <div class="featured-project__progress-bar">
                  <div class="featured-project__progress-bar-inside" style="width: 7%;"></div>
                </div><!--/.featured-project__progress-bar-->
                <div class="featured-project__progress-bar-meta">
                  <span class="featured-project__progress-bar-amount">$14,648 still needed</span>
                  <span> + Volunteers </span>

                  <span class="featured-project__progress-bar-days">16 days to go</span>
                </div><!--/.featured-project__progress-bar-meta-->
              </div>
            </div>
          </a>
        </article><!--/.featured-project-->
      </div><!--/.featured-projects__item-->
      <div class="featured-projects__item">
        <article class="featured-project">
          <a href=".">
            <div class="featured-project__image">
              <figure>
                <img src="/sites/all/themes/iobytheme/patternlab/public/images/site/featured-project_3.jpg" alt="image description" title="Crop width and height" />
              </figure>
            </div>
            <div class="featured-project__text">
              <h3 class="featured-project__title">Children’s Organic Food Patch Workshops</h3>
              <div class="featured-project__location-and-description">
                <div class="featured-project__location">Memphis, TN</div>
                <div class="featured-project__description">New opportunities to learn about the benefits of hands on experience with food production.</div>
              </div>
              <div class="featured-project__progress">
      <span class="featured-project__progress-stat">
        <span data-percent="67" class="featured-project__progress-number">67</span><span class="progress-bar__percent">%</span>
      </span>
                <span class="featured-project__progress-funded-text">funded</span>
                <div class="featured-project__progress-bar">
                  <div class="featured-project__progress-bar-inside" style="width: 33%;"></div>
                </div><!--/.featured-project__progress-bar-->
                <div class="featured-project__progress-bar-meta">
                  <span class="featured-project__progress-bar-amount">$1,376 still needed</span>

                </div><!--/.featured-project__progress-bar-meta-->
              </div>
            </div>
          </a>
        </article><!--/.featured-project-->
      </div><!--/.featured-projects__item-->
*/ ?>
    </div><!--/.featured-projects__body-->
    <footer class="featured-projects__footer">
      <div class="featured-projects__footer-text"><?php print $project_cta_text; ?></div>
      <a class="featured-projects__footer-button" href="<?php print $project_link_url; ?>"><?php print $project_link_title; ?></a>
    </footer>
  </div><!--/.featured-projects__inside-->
</section><!--/.featured-projects-->
<section class="cities">
  <div class="cities__inside">
    <header class="cities__header">
      <h2 class="cities__title"><svg width="62" height="55" xmlns="http://www.w3.org/2000/svg"><g transform="translate(1 1)" stroke="#3599CC" stroke-width="2" fill="none" fill-rule="evenodd"><path d="M49 31.5v-4a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5V30"/><rect x="9" y="27" width="4" height="6" rx=".5"/><rect x="34" y="27" width="4" height="6" rx=".5"/><path d="M27 46v-7.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5V46"/><path d="M18 54V.5a.5.5 0 0 1 .5-.5h25a.5.5 0 0 1 .5.5V54M3 35V11.5a.5.5 0 0 1 .5-.5H15M24 49h14M24 53h14M54 52v2M7 52v2M59 32.5v-21a.5.5 0 0 0-.5-.5H47"/><rect x="24" y="27" width="4" height="6" rx=".5"/><rect x="49" y="16" width="4" height="6" rx=".5"/><rect x="9" y="16" width="4" height="6" rx=".5"/><rect x="34" y="16" width="4" height="6" rx=".5"/><rect x="24" y="16" width="4" height="6" rx=".5"/><rect x="34" y="5" width="4" height="6" rx=".5"/><rect x="24" y="5" width="4" height="6" rx=".5"/><path d="M57.59 47.373c.007.083.01.167.01.252C57.6 49.489 55.988 51 54 51s-3.6-1.511-3.6-3.375c0-.085.003-.169.01-.252C49.006 46.76 48 45.09 48 43.125c0-2.032 1.077-3.749 2.557-4.308A5.547 5.547 0 0 1 50.4 37.5c0-2.485 1.612-4.5 3.6-4.5s3.6 2.015 3.6 4.5c0 .458-.055.9-.157 1.317 1.48.559 2.557 2.276 2.557 4.308 0 1.964-1.006 3.634-2.41 4.248zM11.188 48.18c.008.064.012.13.012.195C11.2 49.825 9.32 51 7 51c-2.32 0-4.2-1.175-4.2-2.625 0-.066.004-.131.012-.196C1.174 47.701 0 46.403 0 44.875c0-1.58 1.257-2.916 2.983-3.35A2.965 2.965 0 0 1 2.8 40.5C2.8 38.567 4.68 37 7 37c2.32 0 4.2 1.567 4.2 3.5 0 .356-.064.7-.183 1.024 1.726.435 2.983 1.77 2.983 3.351 0 1.528-1.174 2.826-2.812 3.304z"/></g></svg> <?php print $cities_title; ?></h2>
    </header>
    <ul class="cities__body">
      <?php foreach ($city_links as $city_link): ?>
      <li class="cities__item">
        <div class="city">
          <a href="<?php print $city_link['url']; ?>"><?php print $city_link['title']; ?></a>
        </div><!--/.city-->
      </li>
      <?php endforeach; ?>
    </ul>
  </div><!--/.cities__inside-->
</section><!--/.cities-->

<div class="more-projects">
  <div class="more-projects__inside">
    <a href="/projects/browse?f[0]=sm_field_project_status%3A1">or, browse all projects</a>
  </div><!--/.more-projects-->
</div><!--/.more-projects-->

<div class="home-wrapper-outline-bg">
  <?php if (!empty($ioby_updates)): ?>
  <section class="updates">
    <div class="updates__inside">
      <ul class="updates__body">
        <?php foreach ($ioby_updates as $update) { print render($update); } ?>
      </ul>
    </div><!--/.updates__inside-->
  </section><!--/.updates-->
  <?php endif; ?>
  <section class="promo-list">
    <div class="promo-list__inside">
      <ul class="promo-list__body">
        <?php if (!empty($promo[0]['image'])): ?><li class="promo-list__item">
          <article class="promo ">
            <div class="promo__image">
              <figure>
                <svg width="299" height="235" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <defs>
                    <mask id="promo-mask-orange">
                      <path fill="#ffffff" d="M297.068 9.139l1.208 220.639L102.646 235 55.5 165.837c-13.535-19.865-23.62-42.248-29.755-66.02L0 0l67.47 17.985a128.35 128.35 0 0 0 41.938 4.053l187.66-12.9z"/>
                    </mask>
                  </defs>
                  <g mask="url(#promo-mask-orange)">
                    <image x="0" y="0" width="380" height="380" transform="translate(-40,-72)" xlink:href="<?php print $promo[0]['image']; ?>"/>
                    <rect fill="#E88124" opacity=".6" width="100%" height="100%"/>
                  </g>
                </svg>
              </figure>
            </div>
            <div class="promo__text">
              <?php print render($promo_blocks[0]); ?>
            </div><!--/.promo__text-->
          </article>
        </li><?php endif; ?>
        <?php if (!empty($promo[1]['image'])): ?><li class="promo-list__item">
          <article class="promo promo--blue">
            <div class="promo__image">
              <figure>
                <svg width="318" height="273" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <defs>
                    <mask id="promo-mask-blue">
                      <path fill="#ffffff" d="M126.523 0L317.92 48.512l-30.158 98.621a112.616 112.616 0 0 0-4.403 22.009l-9.461 95.518c-30.153 10.494-63.782 10.434-100.887-.177C135.907 253.87 78.236 237.082 0 214.115L117.604 35.042a35.381 35.381 0 0 0 7.847-19.827L126.523 0z"/>
                    </mask>
                  </defs>
                  <g mask="url(#promo-mask-blue)">
                    <image x="0" y="0" width="380" height="380" transform="translate(-32,-54)" xlink:href="<?php print $promo[1]['image']; ?>"/>
                    <rect fill="#49A3D1" opacity=".6" width="100%" height="100%"/>
                  </g>
                </svg>
              </figure>
            </div>
            <div class="promo__text">
              <?php print render($promo_blocks[1]); ?>
            </div><!--/.promo__text-->
          </article>
        </li><?php endif; ?>
        <?php if (!empty($promo[2]['image'])): ?><li class="promo-list__item">
          <article class="promo promo--purple">
            <div class="promo__image">
              <figure>
                <svg width="315" height="247" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                  <defs>
                    <mask id="promo-mask-purple">
                      <path fill="#ffffff" d="M287.155 32.936L314 243.508s-42.433 3.817-45.046 3.47c-1.62-.221-15.507-9.5-25.835-16.522-7.458-5.072-16.051-7.685-24.749-7.533l-28.08.49c-2.72.05-5.442-.211-8.128-.765l-49.54-10.223a114.22 114.22 0 0 0-36.274-1.62l-70.364 8.192L0 .863 27.488.356C54.83-.762 77.974-.09 103.778 15.134l22.793 13.772c15.499 9.51 29.717 15.702 47.387 14.127l113.197-10.097z"/>
                    </mask>
                  </defs>
                  <g mask="url(#promo-mask-purple)">
                    <image x="0" y="0" width="380" height="380" transform="translate(-32,-66)" xlink:href="<?php print $promo[2]['image']; ?>"/>
                    <rect fill="#8241D7" opacity=".6" width="100%" height="100%"/>
                  </g>
                </svg>
              </figure>
            </div>
            <div class="promo__text">
              <?php print render($promo_blocks[2]); ?>
            </div><!--/.promo__text-->
          </article>
        </li><?php endif; ?>
      </ul><!--/.promo-list__body-->
    </div><!--/.promo-list__inside-->
  </section><!--/.promo-list-->
</div><!--/.home-wrapper-outline-bg-->


