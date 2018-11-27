ECHO FRONT-END: New Build Process for Ioby

The Patternlab is now the source of truth for all CSS and JS related assets. Drupal will load all of its styles (not including CMS styles) from the Patternlab's output. No CSS and JS should be loaded from anywhere else. CMS styles have been captured and included in the patterlab to make sure they're taken into account, but the compiled cms.min.css should NOT be included by Drupal.

cd Sites/ioby/sites/all/themes/iobytheme/patternlab
nvm use
npm install
npm install -g gulp
gulp

Questions for Bill:
why moving src files to public: feels like plab is conflicting with gulp stuff

Questions for Neal
Footer links: jobs? no find a project like header?

@TODO
dropdown for signup/login
search form
skip cart popout, but be aware of it
remove scrollbars, period. flag neal

@TODO 
fix img json structure
placeholder imgs
header responsive approach
flag neal about font probs

@TODO:
new image from neal in email
icons from neal in new sketch?
responsive!
nav menu!
cart badge!
main drop downs

