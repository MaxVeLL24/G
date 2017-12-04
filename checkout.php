<?php

/* @var $cart \shoppingCart */
/* @var $currencies \currencies */

/*
  raid
 */

include_once('includes/application_top.php');
require('includes/classes/http_client.php');
// debug($_POST,1);die();
define('CHARSET', 'UTF-8');

/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

if(ONEPAGE_LOGIN_REQUIRED == 'true')
{
    if(!tep_session_is_registered('customer_id'))
    {
        $navigation->set_snapshot(array('mode' => 'SSL', 'page' => FILENAME_CHECKOUT));
        tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
}

if(isset($_GET['rType']))
{
    header('content-type: text/html; charset=utf-8');
}

//if(isset($_REQUEST['gv_redeem_code']) && tep_not_null($_REQUEST['gv_redeem_code']) && $_REQUEST['gv_redeem_code'] == 'redeem code'){
if(isset($_REQUEST['gv_redeem_code']) && tep_not_null($_REQUEST['gv_redeem_code']))
{
    $_REQUEST['gv_redeem_code'] = '';
    $_POST['gv_redeem_code'] = '';
}


if(isset($_REQUEST['coupon']) && tep_not_null($_REQUEST['coupon']) && $_REQUEST['coupon'] == 'redeem code')
{
    $_REQUEST['coupon'] = '';
    $_POST['coupon'] = '';
}

require('includes/classes/onepage_checkout.php');
$onePageCheckout = new osC_onePageCheckout();

if(!isset($_GET['rType']) && !isset($_GET['action']) && !isset($_POST['action']) && !isset($_GET['error_message']) && !isset($_GET['payment_error']))
{
    $onePageCheckout->init();
}
//BOF KGT
if(MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true')
{
    if(isset($_POST['code']))
    {
        if(!tep_session_is_registered('coupon'))
        {
            tep_session_register('coupon');
        }
        $coupon = $_POST['code'];
    }
}
//EOF KGT
require(DIR_WS_CLASSES . 'order.php');
$order = new order;

$onePageCheckout->loadSessionVars();

//  print_r($order);
// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
if(!tep_session_is_registered('cartID'))
{
    tep_session_register('cartID');
}
$cartID = $cart->cartID;

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed

if(!isset($_GET['action']) && !isset($_POST['action']))
{
    // Start - CREDIT CLASS Gift Voucher Contribution
    //  if ($order->content_type == 'virtual') {
    if($order->content_type == 'virtual' || $order->content_type == 'virtual_weight')
    {
        // End - CREDIT CLASS Gift Voucher Contribution
        $shipping = false;
        $sendto = false;
    }
}
else
{
    // if there is nothing in the customers cart, redirect them to the shopping cart page
    if($cart->count_contents() < 1)
    {
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }

    // avoid hack attempts during the checkout procedure by checking the internal cartID
    if(isset($cart->cartID) && tep_session_is_registered('cartID'))
    {
        if($cart->cartID != $cartID)
        {
            tep_redirect(tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'));
        }
    }
}

$total_weight = $cart->show_weight();
$total_count = $cart->count_contents();
if(method_exists($cart, 'count_contents_virtual'))
{
    // Start - CREDIT CLASS Gift Voucher Contribution
    $total_count = $cart->count_contents_virtual();
    // End - CREDIT CLASS Gift Voucher Contribution
}

// load all enabled shipping modules
require_once(DIR_WS_CLASSES . 'shipping.php');
$shipping_modules = new shipping;

// load all enabled payment modules
require_once(DIR_WS_CLASSES . 'payment.php');
$payment_modules = new payment;

require_once(DIR_WS_CLASSES . 'order_total.php');
$order_total_modules = new order_total;
//$order_total_modules->process();
$_SESSION['order_total'] = $order_total_modules->process();


require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT);
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_ONEPAGE);

$action = (isset($_POST['action']) ? $_POST['action'] : '');
if(isset($_POST['updateQuantities_x']))
{
    $action = 'updateQuantities';
}
if(isset($_GET['action']) && $_GET['action'] == 'process_confirm')
{
    $action = 'process_confirm';
}
if(tep_not_null($action))
{
    ob_start();
    if(isset($_POST) && is_array($_POST))
        $onePageCheckout->decode_post_vars();
    switch($action)
    {
        case 'process_confirm':
            //      echo $onePageCheckout->confirmCheckout();
            break;
        case 'process':
            echo $onePageCheckout->processCheckout();
            break;
        case 'countrySelect':
            //  echo $onePageCheckout->getAjaxStateField();
            break;
        case 'processLogin':
//			  echo $onePageCheckout->processAjaxLogin($_POST['email'], $_POST['pass']);
            break;
        case 'removeProduct':
//			  echo $onePageCheckout->removeProductFromCart($_POST['pID']);
            break;
        case 'updateQuantities':
            //		  echo $onePageCheckout->updateCartProducts($_POST['qty'], $_POST['id']);
            break;
        case 'setPaymentMethod':
            echo $onePageCheckout->setPaymentMethod($_POST['method']);
            break;
        case 'setGV':
//			  echo $onePageCheckout->setGiftVoucher($_POST['method']);
            break;
        case 'redeemPoints':
//			  echo $onePageCheckout->redeemPoints($_POST['points']);
            break;
        case 'clearPoints':
//			  echo $onePageCheckout->clearPoints();
            break;
        case 'setShippingMethod':
            echo $onePageCheckout->setShippingMethod($_POST['method']);
            break;
        case 'setSendTo':
        case 'setBillTo':
            echo $onePageCheckout->setCheckoutAddress($action);
            break;
        case 'checkEmailAddress':
            echo $onePageCheckout->checkEmailAddress($_POST['emailAddress'], \EShopmakers\Http\Request::isAjax());
            break;
        case 'saveAddress':
        case 'addNewAddress':
            echo $onePageCheckout->saveAddress($action);
            break;
        case 'selectAddress':
            echo $onePageCheckout->setAddress($_POST['address_type'], $_POST['address']);
            break;
        case 'redeemVoucher':
            //		  echo $onePageCheckout->redeemCoupon($_POST['code']);
            break;
        case 'setMembershipPlan':
            //	  echo $onePageCheckout->setMembershipPlan($_POST['planID']);
            break;
        case 'updateCartView':
            \EShopmakers\Http\Response::noCache();
            \EShopmakers\Http\Response::noIndexFollow();
            include(DIR_WS_INCLUDES . 'checkout/checkout_cart.php');
            exit();
            break;
        case 'updatePoints':
        case 'updateShippingMethods':
            include(DIR_WS_INCLUDES . 'checkout/shipping_method.php');
            break;
        case 'updatePaymentMethods':
            include(DIR_WS_INCLUDES . 'checkout/payment_method.php');
            break;
        case 'getOrderTotals':
            if(MODULE_ORDER_TOTAL_INSTALLED)
            {
                exit($order_total_modules->output());
            }
            break;
        case 'updateRadiosforTotal':
            $order_total_modules->output();
            echo $order->info['total'];
            break;
        case 'getProductsFinal':
            include(DIR_WS_INCLUDES . 'checkout/products_final.php');
            break;
        case 'getNewAddressForm':
        case 'getAddressBook':
            $addresses_count = tep_count_customer_address_book_entries();
            if($action == 'getAddressBook')
            {
                $addressType = $_POST['addressType'];
                include(DIR_WS_INCLUDES . 'checkout/address_book.php');
            }
            else
            {
                include(DIR_WS_INCLUDES . 'checkout/new_address.php');
            }
            break;
        case 'getEditAddressForm':
            $aID = tep_db_prepare_input($_POST['addressID']);
            $Qaddress = tep_db_query('select * from ' . TABLE_ADDRESS_BOOK . ' where customers_id = "' . $customer_id . '" and address_book_id = "' . $aID . '"');
            $address = tep_db_fetch_array($Qaddress);
            include(DIR_WS_INCLUDES . 'checkout/edit_address.php');
            break;
        case 'getBillingAddress':
            include(DIR_WS_INCLUDES . 'checkout/billing_address.php');
            break;
        case 'getShippingAddress':
            include(DIR_WS_INCLUDES . 'checkout/shipping_address.php');
            break;
    }

    $content = ob_get_contents();
    ob_end_clean();
    if($action == 'process')
    {
        echo $content;
    }
    else
    {
        echo $content;
    }
    tep_session_close();
    tep_exit();
}

//  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_CHECKOUT, '', $request_type));

if(!function_exists('buildInfobox'))
{
    function buildInfobox($header, $contents)
    {
        global $action;
        $info_box_contents = array();
        if(isset($action) && tep_not_null($action))
        //	$info_box_contents[] = array('text' => utf8_encode($header));
        {
            $info_box_contents[] = array('text' => ($header));
        }
        else
        {
            $info_box_contents[] = array('text' => ($header));
        }
        new infoBoxHeading($info_box_contents, false, false);

        $info_box_contents = array();

        if(isset($action) && tep_not_null($action))
        //		$info_box_contents[] = array('text' => utf8_encode($contents));
        {
            $info_box_contents[] = array('text' => ($contents));
        }
        else
        {
            $info_box_contents[] = array('text' => ($contents));
        }
        new contentBox($info_box_contents);
    }
}

if(!function_exists('fixSeoLink'))
{
    function fixSeoLink($url)
    {
        return str_replace('&amp;', '&', $url);
    }
}

if(!$cart->count_contents())
{
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
}

$breadcrumb->add(NAVBAR_TITLE);

$content = CONTENT_CHECKOUT_ONEPAGE;
$javascript = 'onepagecheckout.js.php';

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_WS_INCLUDES . 'application_bottom.php');