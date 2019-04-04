<?php

  /**
   * multistep: fix php 5.3 incompatibility
   * empty() can't be called on anything besides a variable below php 5.5
   *
   * @patch multistep-php55-2017-02-17.patch
   * @rolled-against 7.x-2.x-dev (datestamp 1486587785)
   * @author Peter Sax, Paul Reny
   *
   * usage: (from webroot)
   * git apply sites/all/hacks/multistep-php55-2017-02-17.patch
   */

  /**
   * CORE: Allow email addresses with a '+' in them.
   *
   * @patch drupal-allow-plus-in-usernames-2790923-3.patch
   * @issue https://www.drupal.org/node/2790923
   * @rolled-against 7.44
   * @author Paul Venuti
   *
   * Current version of Drupal throws an error about illegal characters when
   * saving an email address with a '+' on the user/edit form. This patch
   * allows that character.
   *
   * Once this patch has made it into core (i.e., never), we can remove it.
   */
