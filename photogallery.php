<?php
/*
  $Id: product_info.php,v 1.2 2003/09/24 14:33:16 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include_once __DIR__ . '/includes/application_top.php';
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);  
   
  $breadcrumb->add(PHOTOGALLERY);

  $content = CONTENT_PHOTOGALLERY;
   
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>