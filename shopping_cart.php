<?php

/*
  $Id: shopping_cart.php,v 1.2 2003/09/24 14:33:16 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  Shoppe Enhancement Controller - Copyright (c) 2003 WebMakers.com
  Linda McGrath - osCommerce@WebMakers.com
 */

/* @var $cart \shoppingCart */


include_once __DIR__ . '/includes/application_top.php';
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

if(array_key_exists('reset', $_GET))
{
    $cart->reset();
}

$r_mycode = filter_input(INPUT_POST, 'gv_redeem_code');

require(DIR_WS_CLASSES . 'order.php');
/* @var $order \order */
$order = new order;

require(DIR_WS_CLASSES . 'order_total.php');
/* @var $order_total_modules \order_total */
$order_total_modules = new order_total;
$order_totals = $order_total_modules->process();

if(MODULE_ORDER_TOTAL_COUPON_STATUS == 'true')
{
    // Start - CREDIT CLASS Gift Voucher Contribution
    if($credit_covers)
    {
        $paymentMethod = 'credit_covers';
    }
    unset($_POST['gv_redeem_code']);
    unset($HTTP_POST_VARS['gv_redeem_code']);
    $order_total_modules->collect_posts();
    $order_total_modules->pre_confirmation_check();
    // End - CREDIT CLASS Gift Voucher Contribution
}

if($r_mycode)
{
    /**
     * Применяет код купона
     * 
     * @global int $customer_id
     * @global order $order
     * @global boolean $credit_covers
     * @global order_total $order_total_modules
     * @global string $cc_id
     * @global \messageStack $messageStack
     * @param string $code
     * @return boolean
     */
    function redeemCoupon($code)
    {
        //BOF KGT
        if(MODULE_ORDER_TOTAL_COUPON_STATUS == 'true')
        {
            //EOF KGT
            global $customer_id, $order, $credit_covers, $messageStack;
            $error = false;
            if($code)
            {
                // get some info from the coupon table
                $coupon_query = tep_db_query("select coupon_id, coupon_amount, coupon_type, coupon_minimum_order,uses_per_coupon, uses_per_user, restrict_to_products,restrict_to_categories from " . TABLE_COUPONS . " where coupon_code='" . tep_db_input($code) . "' and coupon_active='Y'");
                $coupon_result = tep_db_fetch_array($coupon_query);
                if($coupon_result['coupon_type'] != 'G')
                {
                    if(tep_db_num_rows($coupon_query) == 0)
                    {
                        $error = true;
                        $errMsg = ERROR_NO_INVALID_REDEEM_COUPON;
                    }

                    $date_query = tep_db_query("select coupon_start_date from " . TABLE_COUPONS . " where coupon_start_date <= now() and coupon_code='" . tep_db_input($code) . "'");
                    if(tep_db_num_rows($date_query) == 0)
                    {
                        $error = true;
                        $errMsg = ERROR_INVALID_STARTDATE_COUPON;
                    }

                    $date_query = tep_db_query("select coupon_expire_date from " . TABLE_COUPONS . " where coupon_expire_date >= now() and coupon_code='" . tep_db_input($code) . "'");
                    if(tep_db_num_rows($date_query) == 0)
                    {
                        $error = true;
                        $errMsg = ERROR_INVALID_FINISDATE_COUPON;
                    }

                    $coupon_count = tep_db_query("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $coupon_result['coupon_id'] . "'");
                    $coupon_count_customer = tep_db_query("select coupon_id from " . TABLE_COUPON_REDEEM_TRACK . " where coupon_id = '" . $coupon_result['coupon_id'] . "' and customer_id = '" . $customer_id . "' and customer_id>0");
                    if(tep_db_num_rows($coupon_count) >= $coupon_result['uses_per_coupon'] && $coupon_result['uses_per_coupon'] > 0)
                    {
                        $error = true;
                        $errMsg = ERROR_INVALID_USES_COUPON . $coupon_result['uses_per_coupon'] . TIMES;
                    }

                    if(tep_db_num_rows($coupon_count_customer) >= $coupon_result['uses_per_user'] && $coupon_result['uses_per_user'] > 0)
                    {
                        $error = true;
                        $errMsg = ERROR_INVALID_USES_USER_COUPON . $coupon_result['uses_per_user'] . TIMES;
                    }

                    if($error === false)
                    {
                        $messageStack->add_session(CONTENT_SHOPPING_CART, TEXT_COUPON_WAS_SUCCESSFULLY_APPLIED, 'success');
                        global $order_total_modules, $cc_id;
                        $cc_id = $coupon_result['coupon_id'];
                        if(!tep_session_is_registered('cc_id'))
                        {
                            tep_session_register('cc_id');
                        }
                        $order_total_modules->pre_confirmation_check();
                        if(!tep_session_is_registered('credit_covers'))
                        {
                            tep_session_register('credit_covers');
                            $credit_covers = true;
                        }
                        return true;
                    }
                    else
                    {
                        $messageStack->add_session(CONTENT_SHOPPING_CART, $errMsg);
                        if(tep_session_is_registered('credit_covers'))
                            tep_session_unregister('credit_covers');
                    }
                }
            }
            //BOF KGT
        }
        elseif(MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true')
        {
            global $customer_id, $order;
            $check_code_query = tep_db_query($sql = "SELECT dc.*
                                                  FROM " . TABLE_DISCOUNT_COUPONS . " dc
                                                  WHERE coupons_id = '" . tep_db_input($code) . "'
                                                    AND ( coupons_date_start <= CURDATE() OR coupons_date_start IS NULL )
                                                    AND ( coupons_date_end >= CURDATE() OR coupons_date_end IS NULL )");
            if(tep_db_num_rows($check_code_query) != 1)
            {
                // if no rows are returned, then they haven't entered a valid code
                $message = ENTRY_DISCOUNT_COUPON_ERROR; //display the error message
                return false;
            }
            else
            {
                if(tep_session_is_registered('customer_id') && (int) $customer_id > 0)
                {
                    //customer_exclusions
                    $check_user_query = tep_db_query($sql = 'SELECT dc2u.customers_id
                                                      FROM ' . TABLE_DISCOUNT_COUPONS_TO_CUSTOMERS . ' dc2u
                                                      WHERE customers_id=' . (int) $customer_id . '
                                                        AND coupons_id="' . tep_db_input($code) . '"');
                    if(tep_db_num_rows($check_user_query) > 0)
                    {
                        $message = ENTRY_DISCOUNT_COUPON_ERROR; //display the error message
                        //use this to debug exclusions:
                        //$this->message( 'Customer exclusion check failed' );
                        return true;
                    }
                }
                //shipping zone exclusions
                $delivery = $order->delivery;
                $check_user_query = tep_db_query($sql = 'SELECT dc2z.geo_zone_id
                                                    FROM ' . TABLE_DISCOUNT_COUPONS_TO_ZONES . ' dc2z
                                                    LEFT JOIN ' . TABLE_ZONES_TO_GEO_ZONES . ' z2g
                                                      USING( geo_zone_id )
                                                    WHERE ( z2g.zone_id=' . (int) $delivery['zone_id'] . ' or z2g.zone_id = 0 or z2g.zone_id IS NULL )
                                                      AND ( z2g.zone_country_id=' . (int) $delivery['country_id'] . ' or z2g.zone_country_id = 0 )
                                                      AND dc2z.coupons_id="' . tep_db_input($code) . '"');

                if(tep_db_num_rows($check_user_query) > 0)
                {
                    $message = ENTRY_DISCOUNT_COUPON_ERROR; //display the error message
                    //use this to debug exclusions:
                    //$this->message( 'Shipping Zones exclusion check failed' );
                    return false;
                }
                //end shipping zone exclusions
                $row = tep_db_fetch_array($check_code_query); //since there is one record, we have a valid code
                $order->coupon = $row;
                return true;
            }
        }
        //EOF KGT
        return false;
    }

    $status = redeemCoupon($r_mycode);
    if(\EShopmakers\Http\Request::isAjax())
    {
        \EShopmakers\Http\Response::sendJSON(array(
            'status' => $status
        ));
    }
    else
    {
        tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
    }
}


$breadcrumb->add(NAVBAR_TITLE);
$content = CONTENT_SHOPPING_CART;
$body_class = 'shopping-cart-page';

$options_names = array();
$options_values_names = array();

$products = $cart->get_products();
if($products)
{
    $options_ids = array();
    $options_values_ids = array();
    foreach($products as $product)
    {
        if($product['attributes'])
        {
            foreach($product['attributes'] as $option_id => $option_value_id)
            {
                $options_ids[] = $option_id;
                $options_values_ids[] = $option_value_id;
            }
        }
    }
    $options_ids = array_unique($options_ids);
    $options_values_ids = array_unique(array_filter($options_values_ids));
    // Выгрузить названия атрибутов
    if($options_ids)
    {
        $options_ids = implode(', ', $options_ids);
        $query = tep_db_query("SELECT products_options_id, products_options_name FROM products_options WHERE products_options_id IN ({$options_ids}) AND language_id = {$_SESSION['languages_id']}");
        if(tep_db_num_rows($query))
        {
            while(($row = tep_db_fetch_array($query)) !== false)
            {
                $options_names[$row['products_options_id']] = $row['products_options_name'];
            }
        }
    }
    // Выгрузить названия значений атрибутов
    if($options_values_ids)
    {
        $options_values_ids = implode(', ', $options_values_ids);
        $query = tep_db_query("SELECT products_options_values_id, products_options_values_name FROM products_options_values WHERE products_options_values_id IN ({$options_values_ids}) AND language_id = {$_SESSION['languages_id']}");
        if(tep_db_num_rows($query))
        {
            while(($row = tep_db_fetch_array($query)) !== false)
            {
                $options_values_names[$row['products_options_values_id']] = $row['products_options_values_name'];
            }
        }
    }
}

// Если корзина подгружается AJAX-ом, то рендерим только шаблон корзины
if(\EShopmakers\Http\Request::isAjax())
{
    \EShopmakers\Http\Response::noCache();
    \EShopmakers\Http\Response::noIndexNoFollow();
    require(DIR_WS_CONTENT . $content . '.tpl.php');
    exit();
}

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');