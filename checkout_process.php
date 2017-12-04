<?php

/*
  $Id: checkout_process.php,v 1.2 2003/09/24 15:34:25 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */
if($_GET['payment_not_trevial'] == 1)
{
    //Если проплата была произведена через liqpay или приват24
    include('includes/application_top.php');
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id=' . $_GET['order_id'], 'SSL'));
}
require('includes/configure.php'); // include server parameters
require(DIR_WS_INCLUDES . 'spider_configure.php'); // Spiderkiller 
/* include(DIR_WS_CLASSES . 'language.php');
  $lng = new language();

  if (isset($_GET['language']) && tep_not_null($_GET['language'])) {
  $lng->set_language($_GET['language']);
  } else {
  $lng->get_browser_language();
  }

  $language = $lng->language['directory']; */
global $language;
//  include('includes/application_top.php');
/* One Page Checkout - BEGIN */
if(ONEPAGE_LOGIN_REQUIRED == 'true')
{
    if(!tep_session_is_registered('customer_id'))
    {
        exit('11111111111111111111');
        tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
    }
}
/* One Page Checkout - END */

if(empty($_SESSION['sendto']))
{
    exit('2222222222222');
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
}

if((tep_not_null(MODULE_PAYMENT_INSTALLED)) && (!tep_session_is_registered('payment')))
{
    exit('33333333333333333');
    tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL'));
}

// avoid hack attempts during the checkout procedure by checking the internal cartID
if(isset($cart->cartID) && tep_session_is_registered('cartID'))
{
    if($cart->cartID != $cartID)
    {
        exit('44444444444444444');
        tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
    }
}

include(DIR_WS_LANGUAGES . $language . '/checkout_process.php');


/* One Page Checkout - BEGIN */
if(ONEPAGE_CHECKOUT_ENABLED == 'True')
{
//      require('includes/classes/onepage_checkout.php');
    $onePageCheckout = new osC_onePageCheckout();
}
/* One Page Checkout - END */

// load selected payment module
//  require(DIR_WS_CLASSES . 'payment.php');
//  if ($credit_covers) $payment=''; //ICW added for CREDIT CLASS
//  $payment_modules = new payment($payment);
// load the selected shipping module
//  require(DIR_WS_CLASSES . 'shipping.php');
//  $shipping_modules = new shipping($shipping);
//  require(DIR_WS_CLASSES . 'order.php');
//  $order = new order;

/* One Page Checkout - BEGIN */
if(ONEPAGE_CHECKOUT_ENABLED == 'True')
{
    $onePageCheckout->loadSessionVars();
}
/* One Page Checkout - END */
if(empty($payment_modules) || !($payment_modules instanceof payment))
{
    $payment_modules = new payment($_SESSION['payment']);
}
else
{
    $payment_modules->selected_module = $_SESSION['payment'];
}

//$random_addition = rand(7,15);
//$last_order_id = tep_db_query("select orders_id from " . TABLE_ORDERS . " o order by o.orders_id desc limit 1");
//$old_order_id = tep_db_fetch_array($last_order_id);
//$ordernum = ($old_order_id['orders_id'] + $random_addition);
// load the before_process function from the payment modules
$payment_modules->before_process();

//  require(DIR_WS_CLASSES . 'order_total.php');
// $order_total_modules = new order_total;
//    print_r($order_total_modules->process());
//$order_totals = $order_total_modules->process();
$order_totals = $_SESSION['order_total'];

// BOF: WebMakers.com Added: Downloads Controller

$sql_data_array = array(
    'customers_id' => ($customer_id != 0) ? $customer_id : $order->customer['customer_id'],
    'customers_name' => $order->customer['firstname'] . ' ' . $order->customer['lastname'],
    'customers_company' => $order->customer['company'],
    'customers_street_address' => $order->customer['street_address'],
    'customers_suburb' => $order->customer['suburb'],
    'customers_city' => $order->customer['city'],
    'customers_postcode' => $order->customer['postcode'],
    'customers_state' => $order->customer['state'],
    'customers_country' => $order->customer['country']['title'],
    'customers_telephone' => $order->customer['telephone'],
    'customers_fax' => $order->customer['fax'],
    'customers_email_address' => $order->customer['email_address'],
    'customers_address_format_id' => $order->customer['format_id'],
    'delivery_name' => $order->delivery['firstname'] . ' ' . $order->delivery['lastname'],
    'delivery_company' => $order->delivery['company'],
    'delivery_street_address' => $order->delivery['street_address'],
    'delivery_suburb' => $order->delivery['suburb'],
    'delivery_city' => $order->delivery['city'],
    'delivery_postcode' => $order->delivery['postcode'],
    'delivery_state' => $order->delivery['state'],
    'delivery_country' => $order->delivery['country']['title'],
    'delivery_address_format_id' => $order->delivery['format_id'],
    'billing_name' => $order->billing['firstname'] . ' ' . $order->billing['lastname'],
    'billing_company' => $order->billing['company'],
    'billing_street_address' => $order->billing['street_address'],
    'billing_suburb' => $order->billing['suburb'],
    'billing_city' => $order->billing['city'],
    'billing_postcode' => $order->billing['postcode'],
    'billing_state' => $order->billing['state'],
    'billing_country' => $order->billing['country']['title'],
    'billing_address_format_id' => $order->billing['format_id'],
    'payment_method' => $order->info['payment_method'],
// BOF: Lango Added for print order mod 
    'payment_info' => $GLOBALS['payment_info'],
// EOF: Lango Added for print order mod 
    'cc_type' => $order->info['cc_type'],
    'cc_owner' => $order->info['cc_owner'],
    'cc_number' => $order->info['cc_number'],
    'cc_expires' => $order->info['cc_expires'],
    'date_purchased' => 'now()',
    'last_modified' => 'now()',
    'orders_status' => $order->info['order_status'],
    'currency' => $order->info['currency'],
    'currency_value' => $order->info['currency_value'],
    'customers_referer_url' => $referer_url);

// EOF: WebMakers.com Added: Downloads Controller
tep_db_perform(TABLE_ORDERS, $sql_data_array);

$insert_id = tep_db_insert_id();

for($i = 0, $n = sizeof($order_totals); $i < $n; $i++)
{
    $sql_data_array = array('orders_id' => $insert_id,
        'title' => $order_totals[$i]['title'],
        'text' => $order_totals[$i]['text'],
        'value' => $order_totals[$i]['value'],
        'class' => $order_totals[$i]['code'],
        'sort_order' => $order_totals[$i]['sort_order']);
    tep_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
}

$customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
$sql_data_array = array('orders_id' => $insert_id,
    'orders_status_id' => $order->info['order_status'],
    'date_added' => 'now()',
    'customer_notified' => $customer_notification,
    'comments' => $order->info['comments']);
tep_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);

// initialized for the email confirmation
$products_ordered = '';
$subtotal = 0;
$total_tax = 0;

for($i = 0, $n = sizeof($order->products); $i < $n; $i++)
{
// Stock Update - Joao Correia
    if(STOCK_LIMITED == 'true')
    {
        if(DOWNLOAD_ENABLED == 'true')
        {
            $stock_query_raw = "SELECT products_quantity, pad.products_attributes_filename
                            FROM " . TABLE_PRODUCTS . " p
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                             ON p.products_id=pa.products_id
                            LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                             ON pa.products_attributes_id=pad.products_attributes_id
                            WHERE p.products_id = '" . tep_get_prid($order->products[$i]['id']) . "'";
// Will work with only one option for downloadable products
// otherwise, we have to build the query dynamically with a loop
            $products_attributes = $order->products[$i]['attributes'];
            if(is_array($products_attributes))
            {
                $stock_query_raw .= " AND pa.options_id = '" . $products_attributes[0]['option_id'] . "' AND pa.options_values_id = '" . $products_attributes[0]['value_id'] . "'";
            }
            $stock_query = tep_db_query($stock_query_raw);
        }
        else
        {
            $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
        }
        if(tep_db_num_rows($stock_query) > 0)
        {
            $stock_values = tep_db_fetch_array($stock_query);
// do not decrement quantities if products_attributes_filename exists
            if((DOWNLOAD_ENABLED != 'true') || (!$stock_values['products_attributes_filename']))
            {
                $stock_left = $stock_values['products_quantity'] - $order->products[$i]['qty'];

// Version: 02-20-04 (BOF) 02/20/2004 - Low Stock Level Email Author: Emmett (yesUdo.com) and Jai (kynet.co.uk) 

                $warning_stock = STOCK_REORDER_LEVEL;
                $current_stock = $stock_left;

                $low_stock_email = LOW_STOCK_TEXT1 . $order->products[$i]['name'] . "\n" . LOW_STOCK_TEXT2 . $order->products[$i]['model'] . "\n" . LOW_STOCK_TEXT3 . $stock_left . "\n" . LOW_STOCK_TEXT4 . HTTP_SERVER . DIR_WS_CATALOG . 'product_info.php?products_id=' . $order->products[$i]['id'] . "\n\n" . LOW_STOCK_TEXT5 . $warning_stock;
                $low_stock_subject = LOW_STOCK_TEXT1 . $order->products[$i]['name'];

                if($current_stock <= $warning_stock)
                {
                    tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $low_stock_subject, $low_stock_email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
                }

                // (EOF) 02/20/2004 - Low Stock Level Email Author: Emmett (yesUdo.com) and Jai (kynet.co.uk)
            }
            else
            {
                $stock_left = $stock_values['products_quantity'];
            }
            tep_db_query("update " . TABLE_PRODUCTS . " set products_quantity = '" . $stock_left . "' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
            if(($stock_left < 1) && (STOCK_ALLOW_CHECKOUT == 'false'))
            {
                tep_db_query("update " . TABLE_PRODUCTS . " set products_status = '0' where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");
            }
        }
    }

// Update products_ordered (for bestsellers list)
    tep_db_query("update " . TABLE_PRODUCTS . " set products_ordered = products_ordered + " . sprintf('%d', $order->products[$i]['qty']) . " where products_id = '" . tep_get_prid($order->products[$i]['id']) . "'");

    $sql_data_array = array('orders_id' => $insert_id,
        'products_id' => tep_get_prid($order->products[$i]['id']),
        'products_model' => $order->products[$i]['model'],
        'products_name' => $order->products[$i]['name'],
        'products_price' => $order->products[$i]['price'],
        'final_price' => $order->products[$i]['final_price'],
        'products_tax' => $order->products[$i]['tax'],
        'products_quantity' => $order->products[$i]['qty']);
    tep_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
    $order_products_id = tep_db_insert_id();
    $order_total_modules->update_credit_account($i); //ICW ADDED FOR CREDIT CLASS SYSTEM
//------insert customer choosen option to order--------
    $attributes_exist = '0';
    $products_ordered_attributes = '';

    if(isset($order->products[$i]['attributes']))
    {
        $attributes_exist = '1';


        for($j = 0, $n2 = sizeof($order->products[$i]['attributes']); $j < $n2; $j++)
        {
            if(DOWNLOAD_ENABLED == 'true')
            {
                $attributes_query = "select popt.products_options_name, poval.products_options_values_id, poval.products_options_values_name, pa.options_values_price, pa.price_prefix, pad.products_attributes_maxdays, pad.products_attributes_maxcount , pad.products_attributes_filename 
                               from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa 
                               left join " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " pad
                                on pa.products_attributes_id=pad.products_attributes_id
                               where pa.products_id = '" . $order->products[$i]['id'] . "' 
                                and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' 
                                and pa.options_id = popt.products_options_id 
                                and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' 
                                and pa.options_values_id = poval.products_options_values_id 
                                and popt.language_id = '" . $languages_id . "' 
                                and poval.language_id = '" . $languages_id . "'";
                $attributes = tep_db_query($attributes_query);
            }
            else
            {

                $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.pa_qty, poval.products_options_values_id, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id = '" . $order->products[$i]['id'] . "' and pa.options_id = '" . $order->products[$i]['attributes'][$j]['option_id'] . "' and pa.options_id = popt.products_options_id and pa.options_values_id = '" . $order->products[$i]['attributes'][$j]['value_id'] . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . $languages_id . "' and poval.language_id = '" . $languages_id . "'");
            }
            $attributes_values = tep_db_fetch_array($attributes);

            if(STOCK_LIMITED == 'true' and isset($order->products[$i]['qty']) and ! empty($order->products[$i]['qty']))
            {

                tep_db_query('UPDATE products_attributes SET pa_qty = IF(pa_qty > 0, pa_qty - ' . $order->products[$i]['qty'] . ', 0) WHERE products_id = ' . tep_get_prid($order->products[$i]['id']) . ' and options_id = ' . $order->products[$i]['attributes'][$j]['option_id'] . ' and options_values_id = ' . $order->products[$i]['attributes'][$j]['value_id']);
            }

            $sql_data_array = array('orders_id' => $insert_id,
                'orders_products_id' => $order_products_id,
                'products_options' => $attributes_values['products_options_name'],
                'products_options_values' => $order->products[$i]['attributes'][$j]['value'],
//                                'products_options_values' => $attributes_values['products_options_values_name'],
                'options_values_price' => $attributes_values['options_values_price'],
                'price_prefix' => $attributes_values['price_prefix']);
            tep_db_perform(TABLE_ORDERS_PRODUCTS_ATTRIBUTES, $sql_data_array);



            if((DOWNLOAD_ENABLED == 'true') && isset($attributes_values['products_attributes_filename']) && tep_not_null($attributes_values['products_attributes_filename']))
            {
                $sql_data_array = array('orders_id' => $insert_id,
                    'orders_products_id' => $order_products_id,
                    'orders_products_filename' => $attributes_values['products_attributes_filename'],
                    'download_maxdays' => $attributes_values['products_attributes_maxdays'],
                    'download_count' => $attributes_values['products_attributes_maxcount']);
                tep_db_perform(TABLE_ORDERS_PRODUCTS_DOWNLOAD, $sql_data_array);
            }
// otf 1.71 changing to use values from $orders->products and adding call to tep_decode_specialchars()
            $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . tep_decode_specialchars($order->products[$i]['attributes'][$j]['value']);
//        $products_ordered_attributes .= "\n\t" . $attributes_values['products_options_name'] . ' ' . $attributes_values['products_options_values_name'];
        }
    }


//------insert customer choosen option eof ----
    $total_weight += ($order->products[$i]['qty'] * $order->products[$i]['weight']);
    $total_tax += tep_calculate_tax($total_products_price, $products_tax) * $order->products[$i]['qty'];
    $total_cost += $total_products_price;

//    $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
    //TotalB2B start
    $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' (' . $order->products[$i]['model'] . ') = ' . $currencies->display_price_nodiscount($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "<br>";
    //TotalB2B end
//    $products_ordered .= $order->products[$i]['qty'] . ' x ' . $order->products[$i]['name'] . ' = ' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . $products_ordered_attributes . "\n";
}
$order_total_modules->apply_credit(); //ICW ADDED FOR CREDIT CLASS SYSTEM
// lets start with the email confirmation
$email_order = "<b>" . STORE_NAME . "</b><br>" .
        EMAIL_SEPARATOR . "<br>" .
        EMAIL_TEXT_ORDER_NUMBER . ' ' . $insert_id . "<br>" .
        // ((tep_session_is_registered('customer_id') && $guest_account == false) ? EMAIL_TEXT_INVOICE_URL . ' ' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $insert_id, 'SSL', false) . "\n" : '') .
        EMAIL_TEXT_DATE_ORDERED . ' ' . strftime(DATE_FORMAT_LONG) . "<br>" .
        EMAIL_TEXT_CUSTOMER_NAME . ' ' . $order->customer['firstname'] . ' ' . $order->customer['lastname'] . "<br>" .
        EMAIL_TEXT_CUSTOMER_EMAIL_ADDRESS . ' ' . $order->customer['email_address'] . "<br>" .
        EMAIL_TEXT_CUSTOMER_TELEPHONE . ' ' . $order->customer['telephone'] . "<br><br>";
if($order->info['comments'])
{
    $email_order .= tep_db_output($order->info['comments']) . "<br><br>";
}
$email_order .= "<b>" . EMAIL_TEXT_PRODUCTS . "</b><br>" .
        EMAIL_SEPARATOR . "<br>" .
        $products_ordered .
        EMAIL_SEPARATOR . "<br>";

for($i = 0, $n = sizeof($order_totals); $i < $n; $i++)
{
    $email_order .= strip_tags($order_totals[$i]['title']) . ' ' . strip_tags($order_totals[$i]['text']) . "<br>";
}

/* One Page Checkout - BEGIN */
$sendToFormatted = tep_address_label($customer_id, $_SESSION['sendto'], 0, '', "<br>");
if(ONEPAGE_CHECKOUT_ENABLED == 'True')
{
    $sendToFormatted = $onePageCheckout->getAddressFormatted('sendto');
}

$billToFormatted = tep_address_label($customer_id, $billto, 0, '', "<br>");
if(ONEPAGE_CHECKOUT_ENABLED == 'True')
{
    $billToFormatted = $onePageCheckout->getAddressFormatted('billto');
}
/* One Page Checkout - END */

if($order->content_type != 'virtual')
{
    $email_order .= "<br><b>" . EMAIL_TEXT_BILLING_ADDRESS . "</b><br>" .
            EMAIL_SEPARATOR . "<br>" .
            $sendToFormatted . "<br><br>";
}

//  $email_order .= "\n" . EMAIL_TEXT_BILLING_ADDRESS . "\n" .
//                  EMAIL_SEPARATOR . "\n" .
//                  $billToFormatted . "\n";
//  $payment_modules->after_process();

if(is_object($GLOBALS[$paymentMethod]))
{
    $email_order .= "<b>" . EMAIL_TEXT_PAYMENT_METHOD . "</b><br>" .
            EMAIL_SEPARATOR . "<br>";
    $payment_class = $GLOBALS[$paymentMethod];
    $email_order .= $payment_class->title . "<br><br>";
    if($payment_class->email_footer)
    {
        $email_order .= $payment_class->email_footer . "<br><br>";
    }
}
else if($order->info['payment_method'])
{
    $email_order .= "<b>" . EMAIL_TEXT_PAYMENT_METHOD . "</b><br>" .
            EMAIL_SEPARATOR . "<br>";
    $email_order .= $order->info['payment_method'] . "<br><br>";
}

tep_mail($order->customer['firstname'] . ' ' . $order->customer['lastname'], $order->customer['email_address'], EMAIL_TEXT_SUBJECT . ' №' . $insert_id . ' - ' . strftime(DATE_FORMAT_LONG), nl2br($email_order), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');

// send emails to other people
if(SEND_EXTRA_ORDER_EMAILS_TO != '')
{
    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, EMAIL_TEXT_SUBJECT . ' №' . $insert_id . ' - ' . strftime(DATE_FORMAT_LONG), nl2br($email_order), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');
}

//---отправка смс по заказу
if(isset($insert_id))
{
    $entry_telephone_query = tep_db_query('SELECT customers_telephone FROM orders WHERE orders_id = ' . $insert_id);
    $entry_telephone = tep_db_fetch_array($entry_telephone_query);

    //begin SMS  
    if(SMS_ENABLE == 'true')
    {
        $enc_terminal = SMS_ENC;
        $client = new SoapClient('http://vipsms.net/api/soap.html');
        $res = $client->auth(SMS_LOGIN, SMS_PASSWORD);
        $sessid = $res->message;

        if(SMS_CUSTOMER_ENABLE == 'true')
        {
            $message_sms = EMAIL_TEXT_SMS_ORDER . $insert_id . EMAIL_TEXT_SMS_DONE . substr(SMS_OWNER_TEL, 3);
            $res = $client->sendSmsOne($sessid, $entry_telephone['customers_telephone'], SMS_SIGN, $message_sms);
        }
        if(SMS_OWNER_ENABLE == 'true')
        {
            $message_admin = EMAIL_TEXT_SMS_ADMIN . $insert_id;
            $res = $client->sendSmsOne($sessid, SMS_OWNER_TEL, SMS_SIGN, $message_admin);
        }
    }
    //end SMS
}

// Include OSC-AFFILIATE 
//  require(DIR_WS_INCLUDES . 'affiliate_checkout_process.php');
// remove items from wishlist if customer purchased them

if(is_object($wishList))
{
    $wishList->clear();
}

$cart->reset(true);

/* One Page Checkout - BEGIN */
if(ONEPAGE_CHECKOUT_ENABLED == 'True')
{
    $onepage['info']['order_id'] = $insert_id;
}
/* One Page Checkout - END */

// unregister session variables used during checkout
tep_session_unregister('sendto');
//  tep_session_unregister('billto');
tep_session_unregister('shipping');
tep_session_unregister('payment');
tep_session_unregister('comments');
//    if(tep_session_is_registered('credit_covers')) tep_session_unregister('credit_covers');
//  $order_total_modules->clear_posts();//ICW ADDED FOR CREDIT CLASS SYSTEM
// BOF: Lango added for print order mod

// load the after_process function from the payment modules
$payment_modules->after_process();

tep_redirect(tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'order_id=' . $insert_id, 'SSL'));

// EOF: Lango added for print order mod
require(DIR_WS_INCLUDES . 'application_bottom.php');