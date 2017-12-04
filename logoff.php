<?php
/*
  $Id: logoff.php,v 1.2 2003/09/24 14:33:16 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include_once __DIR__ . '/includes/application_top.php';

// HMCS: Begin Autologon	**************************************************************

  $cookie_url_array = parse_url((ENABLE_SSL == true ? HTTPS_SERVER : HTTP_SERVER) . substr(DIR_WS_CATALOG, 0, -1));
  $cookie_path = $cookie_url_array['path'];	
  setcookie('email_address', time() - 3600, $cookie_path); 
  setcookie('password', time() - 3600, $cookie_path);
  
// HMCS: End Autologon		**************************************************************

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGOFF);

  $breadcrumb->add(NAVBAR_TITLE);

  tep_session_unregister('customer_id');
  tep_session_unregister('customer_default_address_id');
  tep_session_unregister('customer_first_name');
  tep_session_unregister('customer_country_id');
  tep_session_unregister('customer_zone_id');
//  tep_session_unregister('comments');
//  tep_session_unregister('guest_account');
//ICW - logout -> unregister GIFT VOUCHER sessions - Thanks Fredrik
  tep_session_unregister('gv_id');
  tep_session_unregister('cc_id');
//ICW - logout -> unregister GIFT VOUCHER sessions  - Thanks Fredrik

  $cart->reset();
  
//  $wishList->reset();

 tep_session_destroy();

  $content = CONTENT_LOGOFF;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
