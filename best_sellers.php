<?php
/*
  $Id: best_sellers.php,v 1.7 2002/12/02

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce
  Copyright (c) 2002 HMCservices

  Released under the GNU General Public License
*/

  include_once __DIR__ . '/includes/application_top.php';
  include(DIR_WS_LANGUAGES . $language . '/best_sellers.php');

// Set number of columns in listing
define ('NR_COLUMNS', 1);
//
  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('best_sellers.php', '', 'NONSSL'));

  $content = best_sellers;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
