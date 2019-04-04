/* $Id: README.txt,v 1.1 2011/01/22 02:30:55 goodman Exp $ */

-- SUMMARY --

Pages module allows you to create pages from static text files.

It's a simple module, and useful when working with installation profiles.


-- INSTALLATION --

* Install as usual, see http://drupal.org/node/895232 for further information.


-- USAGE --

Pages grab files from two locations:
 - files/pages directory
 - modules/pages/pages directory

You can put the files inside folders to organize them, e.g.:
 - files/pages/about/about.txt (The title of the page will be About)
 - files/pages/about/team.txt
 - files/pages/privacy/policy.txt
 - files/pages/terms/terms.txt
 - files/pages/work/work_decoration.txt (The title of the page will be
 Work Decoration)


Pages can create menu items for these pages (default main menu). @todo a way to
change this menu


Pages can also works with Translation & Title project (still very expermintal), e.g.:
 - files/pages/about/about-en.txt
 - files/pages/about/about-ar.txt


To get your pages you can either:
 - Enable the module and it'll create them
 - Call this function:
    pages_create();
   or
    pages_create(array('menu' => 'secondary-menu'));


-- CONTACT --

Current maintainer:
* Khaled Alhourani (good_man) - http://drupal.org/user/265439

 
