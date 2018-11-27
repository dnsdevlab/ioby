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
