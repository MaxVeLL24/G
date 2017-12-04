<?php
          $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$check_social['customers_id'] . "' and address_book_id = '" . (int)$check_social['customers_default_address_id'] . "'");
          $check_country = tep_db_fetch_array($check_country_query);

          $customer_id = $check_social['customers_id'];
          $customer_default_address_id = $check_social['customers_default_address_id'];
          $customer_first_name = $check_social['customers_firstname'];
          $customer_country_id = $check_country['entry_country_id'];
          $customer_zone_id = $check_country['entry_zone_id'];
          $sppc_customers_groups_id = $check_social['customers_groups_id'];

          tep_session_register('customer_id');
          tep_session_register('customer_default_address_id');
          tep_session_register('customer_first_name');
          tep_session_register('customer_country_id');
          tep_session_register('customer_zone_id');

// HMCS: Begin Autologon  **********************************************************
    $cookie_url_array = parse_url((ENABLE_SSL == true ? HTTPS_SERVER : HTTP_SERVER) . substr(DIR_WS_CATALOG, 0, -1));
    $cookie_path = $cookie_url_array['path'];

            if ((ALLOW_AUTOLOGONLOGON == 'false') || ($_POST['remember_me'] == '')) {
              setcookie("email_address", "", time() - 3600, $cookie_path);   // Delete email_address cookie
              setcookie("password", "", time() - 3600, $cookie_path);        // Delete password cookie
    }
            else {
              setcookie('email_address', $email, time()+ (365 * 24 * 3600), $cookie_path, '', ((getenv('HTTPS') == 'on') ? 1 : 0));
              setcookie('password', $check_customer['customers_password'], time()+ (365 * 24 * 3600), $cookie_path, '', ((getenv('HTTPS') == 'on') ? 1 : 0));
    }
// HMCS: End Autologon    **********************************************************
          tep_session_register('sppc_customers_groups_id');
          tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int)$customer_id . "'");

          // переводим в группу 5% если логинится через соц. сеть
          tep_db_query("update " . TABLE_CUSTOMERS . " set customers_groups_id = 7 where customers_id = '" . (int)$customer_id . "'");

// restore cart contents
        $cart->restore_contents();

// restore wishlist to sesssion
//        $wishList->restore_wishlist();

  //      echo '<script type="text/javascript">change_login();</script>';
      //    echo '<script type="text/javascript">
      //            document.getElementById(\'kabinet\').innerHTML = "&nbsp;&nbsp;<a href=account.php>кабинет</a>&nbsp;&nbsp;<a href=logoff.php>выход</a>";
      //          </script>';
$text2 = '';
$text2 .= '<div style="padding:15px;">';
  $text2 .= '<div class="left"><img style="border-radius:4px;" src="'.$photo.'" /></div>';
  $text2 .= '<div class="left" style="width:300px;padding-left:10px;text-align:left;">Добро пожаловать,  <b>'.$first_name.'</b>!
        <br />';

  if($city!='')
    $text2 .= '<br />Ваш город: <b>'.$city.'</b>';

  if($email!='') {
    $text2 .= '<br />Ваш логин(e-mail): <b>'.$email.'</b><br />';
  }
  $text2 .= '<br /><b style="color:#37AE22">Спасибо, вы успешно вошли!</b>';
  $text2 .= '</div><div class="clear"></div>';
$text2 .= '</div>';

// $text2 = iconv('UTF-8', 'windows-1251', $text2);
echo $text2;
?>