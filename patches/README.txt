Place documentation about applied patches in here and make sure to remove these
if they no longer apply.

maxlength-count_not_updating-1554952.patch
  Fixed @count not being updated in text. http://drupal.org/node/1554952

location-fix_wrong_location_for_node.patch
  Fixes issue with location information loading on nodes that have the same nid
  as a user's uid causing a random user's address to be displayed for the node.
  This is a quick fix until this issue is addressed: https://drupal.org/node/1856224

gmap-marker-path-issue.patch
  Fixes url path issue with the marker popup that was introduced in gmap-7.x-2.9.

location-php-warning-fix.patch
  Fixed php warning on nodes that do not have a location.

unique-field-permissions-per-bundle-1993074.patch
  Make sure field permissions are unique per-bundle. This is Jay's fix to:
  https://www.drupal.org/node/1993074.

og-cant-save-entity-as-group-1538658-63.patch
  Fix for incorrectly not allowing a user to create more than one project (og group) where
  they become the administrator for that group. Patch is from this issue:
  https://www.drupal.org/node/1538658

commerce_authnet-undefined_indexes-1512130-18.patch
  Fix for PHP warning of undefined indexes for address fields when they aren't
  configured to display: https://www.drupal.org/node/1512130

apache_solr-page-title-fix.patch
  Fix issue on custom solr search pages where the page title is hard-coded to
  "Search Results" when viewing the page without performing a search.

drupal-1958538-9.patch
  Fix to improve and add new features to the math expression engine in the ctools
  module: https://www.drupal.org/node/1958538

states-duplicate-required-markers-1592688-55.patch
  Fix issue with FAPI states that causes multiple required markers to display on
  a field in some cases. See https://www.drupal.org/node/1592688. I ran into the
  problem for conditional states fields on the project form after a mathfield
  triggers an ajax refresh of calculated fields.
