<?php
/*
  $Id: shop_by_price.php,v DW-1.1 Exp $
  
  Contribution edit by David van der Wel
  http//www.xlwel.nl
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include_once __DIR__ . '/includes/application_top.php';

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOP_BY_PRICE);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOP_BY_PRICE, '', 'NONSSL'));



  $content = CONTENT_SHOP_BY_PRICE;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
