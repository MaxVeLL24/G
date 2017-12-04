<?php
/*
  $Id: create_account_success.php,v 1.2 2003/09/24 15:34:26 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include_once __DIR__ . '/includes/application_top.php';

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  //TotalB2B start
  tep_session_register('customer_id');
  tep_session_register('customer_default_address_id');
  tep_session_register('customer_first_name');
  tep_session_register('customer_country_id');
  tep_session_register('customer_zone_id');
  tep_session_register('comments');
//  $cart->reset();
  //TotalB2B end

  if (sizeof($navigation->snapshot) > 0) {
    $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
    $navigation->clear_snapshot();
  } else {
    $origin_href = tep_href_link(FILENAME_DEFAULT);
  }

  $content = CONTENT_CREATE_ACCOUNT_SUCCESS;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
  
  //UNISENDER begin
  
  // Ваш ключ доступа к API (из Личного Кабинета)
  
    require('admin/unisender_api.php');   
    $uni_query = tep_db_query('select customers_firstname, customers_lastname,	customers_email_address,customers_telephone from customers where customers_id="'.$_SESSION['customer_id'].'"');
    $uni_q=tep_db_fetch_array($uni_query);
    $CONFIG;
    $path='admin/uniscfg.php';
    $CONFIG=unserialize(file_get_contents($path)); 
    $api_key=$CONFIG['api'];  
 
  // Создаём POST-запрос
    $POST = array (
      'api_key' => $api_key,
      'field_names[0]' => 'email',
      'field_names[1]' => 'Name',
      'field_names[2]' => 'phone',
      'field_names[3]' => 'email_list_ids', 
      'field_names[4]' => 'phone_list_ids'
      );
    $POST['data[0][0]'] = $uni_q['customers_email_address'];
    $POST['data[0][1]'] = iconv('cp1251','utf-8',$uni_q['customers_firstname']).' '.iconv('cp1251','utf-8',$uni_q['customers_lastname']);
    $POST['data[0][2]'] = $uni_q['customers_telephone'];
    $POST['data[0][3]'] = $list;
    $POST['data[0][4]'] = $list;
     
    $uniapi= new UniSenderApi($api_key);
	  $result=$uniapi->ImportContacts($POST);
      
  //UNISENDER end
?>
