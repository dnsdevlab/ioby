                                    Kint for Drupal module

WHAT IS IT?
-----------
Kint for PHP is a tool designed to present your debugging data in the absolutely best way possible.

In other words, it's var_dump() and debug_backtrace() on steroids. Easy to use, but powerful and customizable.
An essential addition to your development toolbox.


INSTALLATION
------------
Download Kint module and Libraries module
Download Kint library (https://github.com/fat763/kint/tree/1.0.0-wip)
Put it in libraries folder (usualy it is in sites/all/libraries folder)
Rename folder to "kint" if needed.
So you should have this path: sites/all/libraries/kint/Kint.class.php
Enable module

OR
--
Use 'drush pm-enable' ('drush en') command for enable module: after enabling of module Drush will prompts you about
downloading of Kint library.

If you just want to download or redownload library - use 'drush kint-download' command.


USAGE
-----
This module allows to use functions
    kint($data1, $data2, $data3, ...);
    kint_trace();
Also you can use all of Kint class power!
Learn more about Kint: http://raveren.github.io/kint/


CONTACTS
--------
Module author:
    Alexander Danilenko
    danilenko.dn@gmail.com
    https://drupal.org/user/1072104

Kint author:
    Rokas Šleinius
    raveren@gmail.com
    https://github.com/raveren
