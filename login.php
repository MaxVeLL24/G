<?php

/*
  $Id: login.php,v 1.2 2003/09/24 14:33:16 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

include_once __DIR__ . '/includes/application_top.php';

// Guest account start - reset to false
$guest_account = false;
// Guest account end
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled (or the session has not started)
if($session_started == false)
{
    tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);

$error = false;
if(isset($_GET['action']) && ($_GET['action'] == 'process'))
{
    $email_address = tep_db_prepare_input($_POST['email_address']);
    $password = tep_db_prepare_input($_POST['password']);

    //TotalB2B start
    $check_customer_query = tep_db_query("select customers_id, customers_firstname, customers_password, customers_groups_id, customers_email_address, customers_default_address_id, customers_status from " . TABLE_CUSTOMERS . " where customers_status = '1' and customers_email_address = '" . tep_db_input($email_address) . "'");
    if(!tep_db_num_rows($check_customer_query))
    {
        $error = true;
    }
    else
    {
        $check_customer = tep_db_fetch_array($check_customer_query);
        //TotalB2B end
// Check that password is good
        if(!tep_validate_password($password, $check_customer['customers_password']))
        {
            $error = true;
        }
        else
        {
            if(SESSION_RECREATE == 'True')
            {
                tep_session_recreate();
            }

            $check_country_query = tep_db_query("select entry_country_id, entry_zone_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int) $check_customer['customers_id'] . "' and address_book_id = '" . (int) $check_customer['customers_default_address_id'] . "'");
            $check_country = tep_db_fetch_array($check_country_query);

            $customer_id = $check_customer['customers_id'];
            $customer_default_address_id = $check_customer['customers_default_address_id'];
            $customer_first_name = $check_customer['customers_firstname'];
            $customer_country_id = $check_country['entry_country_id'];
            $customer_zone_id = $check_country['entry_zone_id'];

// add for SPPC shipment and payment module start 		
            $sppc_customers_groups_id = $check_customer['customers_groups_id'];

//add for SPPC shipment and payment module end

            tep_session_register('customer_id');
            tep_session_register('customer_default_address_id');
            tep_session_register('customer_first_name');
            tep_session_register('customer_country_id');
            tep_session_register('customer_zone_id');

// HMCS: Begin Autologon	**********************************************************
            $cookie_url_array = parse_url((ENABLE_SSL == true ? HTTPS_SERVER : HTTP_SERVER) . substr(DIR_WS_CATALOG, 0, -1));
            $cookie_path = $cookie_url_array['path'];

            if((ALLOW_AUTOLOGONLOGON == 'false') || ($_POST['remember_me'] == ''))
            {
                setcookie("email_address", "", time() - 3600, $cookie_path);   // Delete email_address cookie
                setcookie("password", "", time() - 3600, $cookie_path);        // Delete password cookie
            }
            else
            {
                setcookie('email_address', $email_address, time() + (365 * 24 * 3600), $cookie_path, '', ((getenv('HTTPS') == 'on') ? 1 : 0));
                setcookie('password', $check_customer['customers_password'], time() + (365 * 24 * 3600), $cookie_path, '', ((getenv('HTTPS') == 'on') ? 1 : 0));
            }
// HMCS: End Autologon		**********************************************************
//add for SPPC shipment and payment module start 		
            tep_session_register('sppc_customers_groups_id');

//add for SPPC shipment and payment module end 	

            tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_of_last_logon = now(), customers_info_number_of_logons = customers_info_number_of_logons+1 where customers_info_id = '" . (int) $customer_id . "'");

// restore cart contents
            $cart->restore_contents();

// restore wishlist to sesssion
            if(!empty($wishList) && is_object($wishList))
            {
                $wishList->restore_wishlist();
            }

            if(sizeof($navigation->snapshot) > 0)
            {
                $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
                $navigation->clear_snapshot();
                tep_redirect($origin_href);
            }
            else
            {
                tep_redirect(tep_href_link(FILENAME_DEFAULT));
            }
        }
    }
}

if($error == true)
{
    $messageStack->add('login', TEXT_LOGIN_ERROR);
}

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_LOGIN, '', 'SSL'));

$content = CONTENT_LOGIN;
$javascript = $content . '.js';

$body_class = 'login-page';

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_WS_INCLUDES . 'application_bottom.php');