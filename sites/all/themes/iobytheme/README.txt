iobytheme is a Drupal 7 theme

In 2018 a 'refresh' was done in collaboration between ioby staff and Echo&Co.
The refresh included an update to the global site look and feel, with a brand new
homepage, global header and footer.

During this refresh we also updated front-end tools within the theme, including
integration with Pattern Lab, http://patternlab.io/

Here are some tips for getting started and doing common front-end-y tasks within this theme.

----------------

I. Getting Started with the Pattern Library.

  1. cd into 'patternlab'
  2. run 'composer install' (accept all defaults to prompts)
  3. run 'php core/console --generate' (this is the command to generate the plab)

II. Getting Started with Gulp (required to make CSS or JS changes)

  1. Install Node Version Manager (NVM) https://github.com/creationix/nvm
  2. Run 'nvm use'
  4. Run 'nvm install-latest-npm'
  3. Run 'npm install'
  4. Run 'npm install -g gulp-cli'

III. Compile all CSS and JS

  1. Complete steps in item II above
  2. run 'gulp build'
