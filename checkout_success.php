<?php

/*
  $Id: checkout_success.php,v 1.2 2003/09/24 15:34:26 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

include_once __DIR__ . '/includes/application_top.php';

/* One Page Checkout - BEGIN */
if(ONEPAGE_CHECKOUT_ENABLED == 'True')
{
    if(!tep_session_is_registered('onepage'))
    {
        if(!tep_session_is_registered('customer_id'))
        {
            tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
        }
    }
    else
    {
        require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT);
        require_once('includes/functions/password_funcs.php');
        require('includes/classes/onepage_checkout.php');
        $onePageCheckout = new osC_onePageCheckout();
        $onePageCheckout->createCustomerAccount();
    }
}
else
{
    if(!tep_session_is_registered('customer_id'))
    {
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
}
/* One Page Checkout - END */

if(isset($_GET['action']) && ($_GET['action'] == 'update'))
{
//    $notify_string = 'action=notify&';
//    $notify = $_POST['notify'];
//    if (!is_array($notify)) $notify = array($notify);
//    for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
//      $notify_string .= 'notify[]=' . $notify[$i] . '&';
//    }
//    if (strlen($notify_string) > 0) $notify_string = substr($notify_string, 0, -1);

    tep_redirect(tep_href_link(FILENAME_DEFAULT));
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SUCCESS);

$breadcrumb->add(NAVBAR_TITLE_1);

$global_query = tep_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int) $customer_id . "'");
$global = tep_db_fetch_array($global_query);

/* One Page Checkout - BEGIN */
if(tep_session_is_registered('customer_id'))
{
//  echo 'yes!!!!!!!!!!!!!!!!';
    /* One Page Checkout - END */
//    $sql_data_array = array('customers_id' => (int)$customer_id);
//    tep_db_perform('orders', $sql_data_array, 'update', "orders_id='".$_GET['order_id']."'");
}
if(tep_session_is_registered('customers_id'))
{

    if($global['global_product_notifications'] != '1')
    {
        $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int) $customer_id . "' order by date_purchased desc limit 1");
        $orders = tep_db_fetch_array($orders_query);

        $products_array = array();
        $products_query = tep_db_query("select products_id, products_name from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int) $orders['orders_id'] . "' order by products_name");
        while($products = tep_db_fetch_array($products_query))
        {
            $products_array[] = array('id' => $products['products_id'],
                'text' => $products['products_name']);
        }
    }

    /* One Page Checkout - BEGIN */
}
/* One Page Checkout - END */


$content = CONTENT_CHECKOUT_SUCCESS;
$javascript = 'popup_window_print.js';
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_WS_INCLUDES . 'application_bottom.php');