<?php
  include_once __DIR__ . '/includes/application_top.php';
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ACCOUNT);
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

  $first_name = iconv('UTF-8', 'windows-1251', $_GET['first_name']);
  $last_name = iconv('UTF-8', 'windows-1251', $_GET['last_name']);
  $photo = $_GET['photo'];
  $email = $_GET['email'];
  $city = $_GET['city'];
  $id_social = $_GET['id'];
  $country = STORE_COUNTRY;

  $guest_pass = tep_create_random_value(ENTRY_PASSWORD_MIN_LENGTH, 'mixed');
  if($_GET['password']=='') $password = tep_db_prepare_input($guest_pass);
  else $password = $_GET['password'];

 if($_GET['password']!='') { // если не с социалки то обычный логин - process
  $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_groups_id, customers_email_address, customers_default_address_id, customers_status, customers_fax from " . TABLE_CUSTOMERS . " where customers_status = '1' and customers_email_address = '" . tep_db_input($email) . "'");
    if (!tep_db_num_rows($check_customer_query)) {
      echo 'Нет такого пользователя!';
    } else {
      $check_social = tep_db_fetch_array($check_customer_query);

      if (!tep_validate_password($password, $check_social['customers_password'])) {
         echo 'Неверный пароль!';
      } else {
        $first_name = $check_social['customers_firstname'];
        $photo = '';
        $id_social = $check_social['customers_fax'];
        $country = STORE_COUNTRY;

        require('soc_login.php');
      }
    }
  } elseif($id_social!='') {

  $check_social_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_groups_id, customers_email_address, customers_default_address_id, customers_status, customers_fax from " . TABLE_CUSTOMERS . " where customers_fax = '" . $id_social . "' and guest_flag != '1'");
  $check_social = tep_db_fetch_array($check_social_query);
  if($check_social['customers_fax']!='') {  // если есть айди соцсети
    if($email=='') $email = $check_social['customers_email_address'];
    require('soc_login.php');
  } else {
      $check_email_query = tep_db_query("select count(*) as total from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $email . "' and guest_flag != '1'");
      $check_email = tep_db_fetch_array($check_email_query);
      if ($check_email['total'] > 0) {
        $check_soc_query = tep_db_query("select customers_fax from " . TABLE_CUSTOMERS . " where customers_email_address = '" . $email . "' and guest_flag != '1'");
        $check_soc = tep_db_fetch_array($check_soc_query);
        $social_array = explode('_',$id_social);
        if ($social_array['0']=='vk') $social_name = 'Вконтакте';
        elseif ($social_array['0']=='fb') $social_name = 'Facebook';
        elseif ($social_array['0']=='tw') $social_name = 'Twitter';

        $social_array2 = explode('_',$check_soc['customers_fax']);
        if ($social_array2['0']=='vk') $social_name2 = 'Вконтакте';
        elseif ($social_array2['0']=='fb') $social_name2 = 'Facebook';
        elseif ($social_array2['0']=='tw') $social_name2 = 'Twitter';
        elseif ($social_array2['0']=='') $social_name2 = 'сайта';

        echo iconv('UTF-8', 'windows-1251', '<div style="text-align:left;padding:20px 10px 0 10px;">Зайдите из другой соц. сети или под своим логином-паролем.<br />'.
        'Сейчас вы пытаетесь зайти из <b>'.$social_name.'</b><br />'.
        'Вы регистрировались из <b>'.$social_name2.'</b></div>');
      } else {
// РЕГИСТРАЦИЯ -----------------
          require('soc_create.php');
// РЕГИСТРАЦИЯ END-----------------
      }
  }
 } else {  // если не с социалки то обычный логин - form
     echo '
                <table align="center" border="0" width="300" cellspacing="0" cellpadding="2" style="padding:5px;color:#333;text-align:left;">
                  <tr>
                    <td class="main" style="padding-top:9px;font-size:17px;">'.ENTRY_EMAIL_ADDRESS.'</td>
                    <td class="main" align="right">'.tep_draw_input_field('email','','class="green_input" id="pop_email"','','',false).'</td>
                  </tr>
                  <tr>
                    <td class="main" style="padding-top:4px;font-size:17px;">'.ENTRY_PASSWORD.'</td>
                    <td class="main" align="right">'.tep_draw_password_field('password','','class="green_input" id="pop_pass" style="margin-top:5px;"').'</td>
                  </tr>
                  <tr>
                    <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td align="right" style="padding-top:8px;">
                          <a class="a_normal" href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">Забыли пароль?</a><br />
                          <a class="a_normal" style="color:#3CA029;" href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">Зарегистрироваться</a>
                        </td>
                        <td align="right" width="70">
                          <a href=javascript:checkLoginvk("","","","",document.getElementById(\'pop_email\').value,"",document.getElementById(\'pop_pass\').value); class="green_button" >Войти</a>
                        </td>
                      </tr>
                    </table></td>
                  </tr>
                </table>';
 }
?>