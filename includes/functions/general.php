<?php

/*
  $Id: general.php,v 1.1.1.1 2003/09/18 19:05:10 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

function json_fixcp1251($data)
{
    return iconv('cp1251', 'utf-8', $data);
}

// Вивід інфо сторінок
function renderInfopage($id, $fields = array('pages_description'))
{
    global $languages_id;
    $page_info_query = tep_db_query("select p.pages_id, pd.pages_name, pd.pages_description, p.pages_image, p.pages_date_added from " . TABLE_PAGES . " p, " . TABLE_PAGES_DESCRIPTION . " pd where p.pages_status = '1' and p.pages_id = '" . $id . "' and pd.pages_id = p.pages_id and pd.language_id = '" . $languages_id . "'");
    $page_info = tep_db_fetch_array($page_info_query);
    foreach($fields as $key => $filed)
    {
        echo $page_info[$filed];
    }
    // return var_dump($page_info);
}

////
// Stop from parsing any further PHP code
function tep_exit()
{
    tep_session_close();
    exit();
}

/*
 *   Возвращает масив аттрибутов товаров, по заданому ид товара
 *   @pid - Product_id
 *   @fields - список идшников аттрибутов в условии array(1,2,3)
 */

function get_products_attributes($pid, $fields = array())
{
    global $languages_id;
    $result = array();
    if(count($fields) > 0)
    {
        $fields_where .= ' (';
        foreach($fields as $id)
        {
            $fields_where .= 'pa.options_id = ' . $id . ' or ';
        }
        $fields_where = substr($fields_where, 0, -3);
        $fields_where .= ') and ';
    }

    $products_options_query = tep_db_query("select
      pov.products_options_values_id,
      pov.products_options_values_name,
      pa.pa_qty,
      popt.products_options_name
      from
      " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " .
            TABLE_PRODUCTS_OPTIONS . " popt, " .
            TABLE_PRODUCTS_OPTIONS_VALUES . " pov
      where " . $fields_where . "
                pa.products_id = '" . $pid . "'
      and pa.options_id = popt.products_options_id
      and pa.options_values_id = pov.products_options_values_id
      and pov.language_id = '" . (int) $languages_id . "'
      and popt.language_id = '" . (int) $languages_id . "'
      order by pa.products_options_sort_order");

    while($products_options = tep_db_fetch_array($products_options_query))
    {
        $result[$pid][$products_options['products_options_name']][] = array(
            'id' => $products_options['products_options_values_id'],
            'text' => $products_options['products_options_values_name'],
            'qty' => $products_options['pa_qty']);
    }

    if(count($result))
    {
        return $result;
    }
    else
    {
        return false;
    }
}

// Redirect to another page or site
function tep_redirect($url)
{
    global $HTTP_GET_VARS, $PHP_SELF, $_RESULT;
    if(strpos(basename($PHP_SELF), 'ajax_shopping_cart.php') !== FALSE)
    {
        if($url == tep_href_link(FILENAME_SSL_CHECK) ||
                $url == tep_href_link(FILENAME_LOGIN) ||
                $url == tep_href_link(FILENAME_COOKIE_USAGE) ||
                ( $HTTP_GET_VARS['action'] === 'buy_now' && tep_has_product_attributes($HTTP_GET_VARS['products_id']) )
        )
        {
            $_RESULT['ajax_redirect'] = $url;
            tep_exit();
        }
        return;
    }
// AJAX Addto shopping_cart - End

    if((strstr($url, "\n") != false) || (strstr($url, "\r") != false))
    {
        tep_redirect(tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false));
    }

    if((ENABLE_SSL == true) && (getenv('HTTPS') == 'on'))
    { // We are loading an SSL page
        if(substr($url, 0, strlen(HTTP_SERVER)) == HTTP_SERVER)
        { // NONSSL url
            $url = HTTPS_SERVER . substr($url, strlen(HTTP_SERVER)); // Change it to SSL
        }
    }

    header('Location: ' . $url);

    tep_exit();
}

// -------------сливаем два массива и удаляем повторяющиеся элементы
function array_merge_recursive_unique($array1, $array2)
{
    if(empty($array1))
        return $array2; //optimize the base case

    foreach($array2 as $key => $value)
    {
        if(is_array($value) && is_array(@$array1[$key]))
        {
            $value = array_merge_recursive_unique($array1[$key], $value);
        }
        $array1[$key] = $value;
    }
    return $array1;
}

// ---------------------------------------------------------
////
// Parse the data used in the html tags to ensure the tags will not break
function tep_parse_input_field_data($data, $parse)
{
    return strtr(trim($data), $parse);
}

function tep_output_string($string, $translate = false, $protected = false)
{
    if($protected == true)
    {
        return htmlspecialchars($string);
    }
    else
    {
        if($translate == false)
        {
            return tep_parse_input_field_data($string, array('"' => '&quot;'));
        }
        else
        {
            return tep_parse_input_field_data($string, $translate);
        }
    }
}

function tep_output_string_protected($string)
{
    return tep_output_string($string, false, true);
}

function tep_sanitize_string($string)
{
    $string = preg_replace('/ +/', ' ', trim($string));

    return preg_replace("/[<>]/", '_', $string);
}

////
// Return a random row from a database query
function tep_random_select($query)
{
    $random_product = '';
    $random_query = tep_db_query($query);
    $num_rows = tep_db_num_rows($random_query);
    if($num_rows > 0)
    {
        $random_row = tep_rand(0, ($num_rows - 1));
        tep_db_data_seek($random_query, $random_row);
        $random_product = tep_db_fetch_array($random_query);
    }

    return $random_product;
}

////
// Return a product's name
// TABLES: products
function tep_get_products_name($product_id, $language = '')
{
    global $languages_id;

    if(empty($language))
        $language = $languages_id;

    $product_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int) $product_id . "' and language_id = '" . (int) $language . "'");
    $product = tep_db_fetch_array($product_query);

    return $product['products_name'];
}

////
// Return a product's special price (returns nothing if there is no offer)
// TABLES: products
function tep_get_customers_groups_id()
{
    static $customers_groups_id;
    if(!isset($customers_groups_id))
    {
        if(empty($_SESSION['customer_id']))
        {
            $customers_groups_id = 0;
        }
        else
        {
            $customers_groups_query = tep_db_query("select customers_groups_id from " . TABLE_CUSTOMERS . " where customers_id =  '" . $_SESSION['customer_id'] . "'");
            if(tep_db_num_rows($customers_groups_query))
            {
                $customers_groups_id = tep_db_fetch_array($customers_groups_query);
                $customers_groups_id = $customers_groups_id['customers_groups_id'];
            }
            else
            {
                $customers_groups_id = 0;
            }
        }
    }
    return $customers_groups_id;
}

function tep_get_products_special_price($product_id)
{
    $product_query = tep_db_query("select products_price, products_model from " . TABLE_PRODUCTS . " where products_id = '" . $product_id . "'");
    if(tep_db_num_rows($product_query))
    {
        $product = tep_db_fetch_array($product_query);
        $product_price = $product['products_price'];
// BOF FlyOpenair: Extra Product Price
        $product_price = extra_product_price($product_price);
// EOF FlyOpenair: Extra Product Price
    }
    else
    {
        return false;
    }

    $specials_query = tep_db_query("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $product_id . "' and status = 1 and (expires_date is null or expires_date = '0000-00-00 00:00:00' or expires_date > now())");
    if(tep_db_num_rows($specials_query))
    {
        $special = tep_db_fetch_array($specials_query);
        $special_price = $special['specials_new_products_price'];
// BOF FlyOpenair: Extra Product Price
        $special_price = extra_product_price($special_price);
// EOF FlyOpenair: Extra Product Price
    }
    else
    {
        $special_price = false;
    }

    if(substr($product['products_model'], 0, 4) == 'GIFT')
    {    //Never apply a salededuction to Ian Wilson's Giftvouchers
        return $special_price;
    }

    $product_to_categories_query = tep_db_query("select categories_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . $product_id . "'");
    $product_to_categories = tep_db_fetch_array($product_to_categories_query);
    $category = $product_to_categories['categories_id'];

    $sale_query = tep_db_query("select sale_specials_condition, sale_deduction_value, sale_deduction_type from " . TABLE_SALEMAKER_SALES . " where sale_categories_all like '%," . $category . ",%' and sale_status = '1' and (sale_date_start <= now() or sale_date_start = '0000-00-00') and (sale_date_end >= now() or sale_date_end = '0000-00-00') and (sale_pricerange_from <= '" . $product_price . "' or sale_pricerange_from = '0') and (sale_pricerange_to >= '" . $product_price . "' or sale_pricerange_to = '0')");
    if(tep_db_num_rows($sale_query))
    {
        $sale = tep_db_fetch_array($sale_query);
    }
    else
    {
        return $special_price;
    }

    if(!$special_price)
    {
        $tmp_special_price = $product_price;
    }
    else
    {
        $tmp_special_price = $special_price;
    }

    switch($sale['sale_deduction_type'])
    {
        case 0:
            $sale_product_price = $product_price - $sale['sale_deduction_value'];
            $sale_special_price = $tmp_special_price - $sale['sale_deduction_value'];
            break;
        case 1:
            $sale_product_price = $product_price - (($product_price * $sale['sale_deduction_value']) / 100);
            $sale_special_price = $tmp_special_price - (($tmp_special_price * $sale['sale_deduction_value']) / 100);
// BOF FlyOpenair: Extra Product Price
            $sale_special_price = extra_product_price($sale_special_price);
// EOF FlyOpenair: Extra Product Price
            break;
        case 2:
            $sale_product_price = $sale['sale_deduction_value'];
            $sale_special_price = $sale['sale_deduction_value'];
            break;
        default:
            return $special_price;
    }

    if($sale_product_price < 0)
    {
        $sale_product_price = 0;
    }

    if($sale_special_price < 0)
    {
        $sale_special_price = 0;
    }

    if(!$special_price)
    {
        return number_format($sale_product_price, 4, '.', '');
    }
    else
    {
        switch($sale['sale_specials_condition'])
        {
            case 0:
                return number_format($sale_product_price, 4, '.', '');
                break;
            case 1:
                return number_format($special_price, 4, '.', '');
                break;
            case 2:
                return number_format($sale_special_price, 4, '.', '');
                break;
            default:
                return number_format($special_price, 4, '.', '');
        }
    }
}

////
// Return a product's stock
// TABLES: products
function tep_get_products_stock($products_id)
{
    $products_id = tep_get_prid($products_id);
    $stock_query = tep_db_query("select products_quantity from " . TABLE_PRODUCTS . " where products_id = '" . (int) $products_id . "'");
    $stock_values = tep_db_fetch_array($stock_query);

    return $stock_values['products_quantity'];
}

////
// Check if the required stock is available
// If insufficent stock is available return an out of stock message
function tep_check_stock($products_id, $products_quantity)
{
    $stock_left = tep_get_products_stock($products_id) - $products_quantity;
    $out_of_stock = '';

    if($stock_left < 0)
    {
        $out_of_stock = '<span class="markProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
    }

    return $out_of_stock;
}

////
// Break a word in a string if it is longer than a specified length ($len)
function tep_break_string($string, $len, $break_char = '-')
{
    $l = 0;
    $output = '';
    for($i = 0, $n = strlen($string); $i < $n; $i++)
    {
        $char = substr($string, $i, 1);
        if($char != ' ')
        {
            $l++;
        }
        else
        {
            $l = 0;
        }
        if($l > $len)
        {
            $l = 1;
            $output .= $break_char;
        }
        $output .= $char;
    }

    return $output;
}

////
// Return all HTTP GET variables, except those passed as a parameter
function tep_get_all_get_params($exclude_array = '')
{
    global $_GET;

    if(!is_array($exclude_array))
        $exclude_array = array();

    $get_url = '';
    if(is_array($_GET) && (sizeof($_GET) > 0))
    {
        reset($_GET);
        while(list($key, $value) = each($_GET))
        {
            if((strlen($value) > 0) && ($key != tep_session_name()) && ($key != 'error') && (!in_array($key, $exclude_array)) && ($key != 'x') && ($key != 'y'))
            {
                $get_url .= $key . '=' . rawurlencode(stripslashes($value)) . '&';
            }
        }
    }

    return $get_url;
}

////
// Returns an array with countries
// TABLES: countries
function tep_get_countries($countries_id = '', $with_iso_codes = false)
{
    $countries_array = array();
    if(tep_not_null($countries_id))
    {
        if($with_iso_codes == true)
        {
            $countries = tep_db_query("select countries_name, countries_iso_code_2, countries_iso_code_3 from " . TABLE_COUNTRIES . " where countries_id = '" . (int) $countries_id . "' order by countries_name");
            $countries_values = tep_db_fetch_array($countries);
            $countries_array = array('countries_name' => $countries_values['countries_name'],
                'countries_iso_code_2' => $countries_values['countries_iso_code_2'],
                'countries_iso_code_3' => $countries_values['countries_iso_code_3']);
        }
        else
        {
            $countries = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int) $countries_id . "'");
            $countries_values = tep_db_fetch_array($countries);
            $countries_array = array('countries_name' => $countries_values['countries_name']);
        }
    }
    else
    {
        $countries = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " order by countries_name");
        while($countries_values = tep_db_fetch_array($countries))
        {
            $countries_array[] = array('countries_id' => $countries_values['countries_id'],
                'countries_name' => $countries_values['countries_name']);
        }
    }

    return $countries_array;
}

////
// Alias function to tep_get_countries, which also returns the countries iso codes
function tep_get_countries_with_iso_codes($countries_id)
{
    return tep_get_countries($countries_id, true);
}

////
// Generate a path to categories
function tep_get_path($current_category_id = '')
{
    global $cPath_array;

    if(tep_not_null($current_category_id))
    {
        $cp_size = sizeof($cPath_array);
        if($cp_size == 0)
        {
            $cPath_new = $current_category_id;
        }
        else
        {
            $cPath_new = '';
            $last_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int) $cPath_array[($cp_size - 1)] . "'");
            $last_category = tep_db_fetch_array($last_category_query);

            $current_category_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int) $current_category_id . "'");
            $current_category = tep_db_fetch_array($current_category_query);

            if($last_category['parent_id'] == $current_category['parent_id'])
            {
                for($i = 0; $i < ($cp_size - 1); $i++)
                {
                    $cPath_new .= '_' . $cPath_array[$i];
                }
            }
            else
            {
                for($i = 0; $i < $cp_size; $i++)
                {
                    $cPath_new .= '_' . $cPath_array[$i];
                }
            }
            $cPath_new .= '_' . $current_category_id;

            if(substr($cPath_new, 0, 1) == '_')
            {
                $cPath_new = substr($cPath_new, 1);
            }
        }
    }
    else
    {
        $cPath_new = implode('_', $cPath_array);
    }

    return 'cPath=' . $cPath_new;
}

////
// Returns the clients browser
function tep_browser_detect($component)
{
    global $HTTP_USER_AGENT;

    return stristr($HTTP_USER_AGENT, $component);
}

////
// Alias function to tep_get_countries()
function tep_get_country_name($country_id)
{
    $country_array = tep_get_countries($country_id);

    return $country_array['countries_name'];
}

////
// Returns the zone (State/Province) name
// TABLES: zones
function tep_get_zone_name($country_id, $zone_id, $default_zone)
{
    $zone_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . (int) $country_id . "' and zone_id = '" . (int) $zone_id . "'");
    if(tep_db_num_rows($zone_query))
    {
        $zone = tep_db_fetch_array($zone_query);
        return $zone['zone_name'];
    }
    else
    {
        return $default_zone;
    }
}

////
// Returns the zone (State/Province) code
// TABLES: zones
function tep_get_zone_code($country_id, $zone_id, $default_zone)
{
    $zone_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_country_id = '" . (int) $country_id . "' and zone_id = '" . (int) $zone_id . "'");
    if(tep_db_num_rows($zone_query))
    {
        $zone = tep_db_fetch_array($zone_query);
        return $zone['zone_code'];
    }
    else
    {
        return $default_zone;
    }
}

////
// Wrapper function for round()
function tep_round($number, $precision)
{
    if(strpos($number, '.') && (strlen(substr($number, strpos($number, '.') + 1)) > $precision))
    {
        $number = substr($number, 0, strpos($number, '.') + 1 + $precision + 1);

        if(substr($number, -1) >= 5)
        {
            if($precision > 1)
            {
                $number = substr($number, 0, -1) + ('0.' . str_repeat(0, $precision - 1) . '1');
            }
            elseif($precision == 1)
            {
                $number = substr($number, 0, -1) + 0.1;
            }
            else
            {
                $number = substr($number, 0, -1) + 1;
            }
        }
        else
        {
            $number = substr($number, 0, -1);
        }
    }

    return $number;
}

////
// Returns the tax rate for a zone / class
// TABLES: tax_rates, zones_to_geo_zones
function tep_get_tax_rate($class_id, $country_id = -1, $zone_id = -1)
{
    global $customer_zone_id, $customer_country_id;

    if(($country_id == -1) && ($zone_id == -1))
    {
        if(!tep_session_is_registered('customer_id'))
        {
            $country_id = STORE_COUNTRY;
            $zone_id = STORE_ZONE;
        }
        else
        {
            $country_id = $customer_country_id;
            $zone_id = $customer_zone_id;
        }
    }

    $tax_query = tep_db_query("select sum(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int) $country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int) $zone_id . "') and tr.tax_class_id = '" . (int) $class_id . "' group by tr.tax_priority");
    if(tep_db_num_rows($tax_query))
    {
        $tax_multiplier = 1.0;
        while($tax = tep_db_fetch_array($tax_query))
        {
            $tax_multiplier *= 1.0 + ($tax['tax_rate'] / 100);
        }
        return ($tax_multiplier - 1.0) * 100;
    }
    else
    {
        return 0;
    }
}

////
// Return the tax description for a zone / class
// TABLES: tax_rates;
function tep_get_tax_description($class_id, $country_id, $zone_id)
{
    $tax_query = tep_db_query("select tax_description from " . TABLE_TAX_RATES . " tr left join " . TABLE_ZONES_TO_GEO_ZONES . " za on (tr.tax_zone_id = za.geo_zone_id) left join " . TABLE_GEO_ZONES . " tz on (tz.geo_zone_id = tr.tax_zone_id) where (za.zone_country_id is null or za.zone_country_id = '0' or za.zone_country_id = '" . (int) $country_id . "') and (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . (int) $zone_id . "') and tr.tax_class_id = '" . (int) $class_id . "' order by tr.tax_priority");
    if(tep_db_num_rows($tax_query))
    {
        $tax_description = '';
        while($tax = tep_db_fetch_array($tax_query))
        {
            $tax_description .= $tax['tax_description'] . ' + ';
        }
        $tax_description = substr($tax_description, 0, -3);

        return $tax_description;
    }
    else
    {
        return TEXT_UNKNOWN_TAX_RATE;
    }
}

////
// Add tax to a products price
function tep_add_tax($price, $tax)
{
    global $currencies;

    if((DISPLAY_PRICE_WITH_TAX == 'true') && ($tax > 0))
    {
        return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + tep_calculate_tax($price, $tax);
    }
    else
    {
        return tep_round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
    }
}

// Calculates Tax rounding the result
function tep_calculate_tax($price, $tax)
{
    global $currencies;

    return tep_round($price * $tax / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
}

////
// Return the number of products in a category
// TABLES: products, products_to_categories, categories
/*
  function tep_count_products_in_category($category_id, $include_inactive = false) {
  $products_count = 0;

  $sub_array = array();
  $sub_where = '';
  tep_get_subcategories($sub_array, $category_id);
  for ($i=0, $n=sizeof($sub_array); $i<$n; $i++ ) {
  $sub_where .= " or p2c.categories_id = '" . $sub_array[$i] . "'";
  }
  if ($include_inactive == true) $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and (p2c.categories_id = '" . (int)$category_id . "' " . $sub_where . ")");
  else $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and (p2c.categories_id = '" . (int)$category_id . "' " . $sub_where . ")");

  $products = tep_db_fetch_array($products_query);
  $products_count = $products['total'];

  return $products_count;
  }
 */
function tep_count_products_in_category($category_id, $include_inactive = false)
{
    $products_count = 0;
    $r_current_subcats = tep_make_cat_list($category_id);

    $sub_where = '';
    $count_subcats = count($r_current_subcats);
    for($i = 0; $i < $count_subcats; $i++)
    {
        $sub_where .= " or p2c.categories_id ='" . $r_current_subcats[$i] . "'";
    }
    //    if ($include_inactive == true)
    $products_query = tep_db_query("select count(p2c.products_id) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where (p2c.categories_id = '" . (int) $category_id . "' " . $sub_where . ")");
//        else $products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = p2c.products_id and p.products_status = '1' and (p2c.categories_id = '" . (int)$category_id . "' " . $sub_where . ")");

    $products = tep_db_fetch_array($products_query);

    $products_count = $products['total'];

    return $products_count;
}

function tep_make_cat_list($parent_cat = 0)
{

    $result = tep_db_query('select categories_id, parent_id from ' . TABLE_CATEGORIES);
    while($row = tep_db_fetch_array($result))
    {

        $table[$row['parent_id']][] = $row['categories_id'];
        if($row['parent_id'] == $parent_cat)
            $table2[$parent_cat][] = $row['categories_id'];
    }
    $table3 = tep_rec_cats($table, $table2[$parent_cat]);

    if(is_array($table3))
        $table3 = array_merge($table3, $table2[$parent_cat]);
    else
        $table3 = $table2[$parent_cat];

    return $table3;
}

function tep_rec_cats($table, $table2)
{
    if(is_array($table2))
    {
        foreach($table as $k => $v)
        {
            if(in_array($k, $table2))
            {
                foreach($v as $k2 => $v2)
                {
                    $table3[] = $v2;
                }
            }
        }
        if(!empty($table3))
        {
            $table4 = tep_rec_cats($table, $table3);
            if(is_array($table4))
                $table3 = array_merge($table3, $table4);
        }
        return $table3;
    }
}

////
// Return true if the category has subcategories
// TABLES: categories
function tep_has_category_subcategories($category_id)
{
    $child_category_query = tep_db_query("select count(*) as count from " . TABLE_CATEGORIES . " where parent_id = '" . (int) $category_id . "'");
    $child_category = tep_db_fetch_array($child_category_query);

    if($child_category['count'] > 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}

////
// Returns the address_format_id for the given country
// TABLES: countries;
function tep_get_address_format_id($country_id)
{
    $address_format_query = tep_db_query("select address_format_id as format_id from " . TABLE_COUNTRIES . " where countries_id = '" . (int) $country_id . "'");
    if(tep_db_num_rows($address_format_query))
    {
        $address_format = tep_db_fetch_array($address_format_query);
        return $address_format['format_id'];
    }
    else
    {
        return '1';
    }
}

////
// Return a formatted address
// TABLES: address_format
function tep_address_format($address_format_id, $address, $html, $boln, $eoln)
{
    if(is_null($address_format_id) or $address_format_id == '0')
        $address_format_id = 1;
    $address_format_query = tep_db_query("select address_format as format from " . TABLE_ADDRESS_FORMAT . " where address_format_id = '" . (int) $address_format_id . "'");
    $address_format = tep_db_fetch_array($address_format_query);

    if(is_array($address['country']))
    {
        $tmp_country = $address['country'];
        $address['country'] = $tmp_country['title'];
    }

    $company = tep_output_string_protected($address['company']);
    if(isset($address['firstname']) && tep_not_null($address['firstname']))
    {
        $firstname = tep_output_string_protected($address['firstname']);
        $lastname = tep_output_string_protected($address['lastname']);
    }
    elseif(isset($address['name']) && tep_not_null($address['name']))
    {
        $firstname = tep_output_string_protected($address['name']);
        $lastname = '';
    }
    else
    {
        $firstname = '';
        $lastname = '';
    }
    $street = tep_output_string_protected($address['street_address']);
    $suburb = tep_output_string_protected($address['suburb']);
    $city = tep_output_string_protected($address['city']);
    $state = tep_output_string_protected($address['state']);
    if(isset($address['country_id']) && tep_not_null($address['country_id']))
    {
        $country = tep_get_country_name($address['country_id']);

        if(isset($address['zone_id']) && tep_not_null($address['zone_id']))
        {
//        $state = tep_get_zone_code($address['country_id'], $address['zone_id'], $state);
            $state = tep_get_zone_name($address['country_id'], $address['zone_id'], $state);
        }
    }
    elseif(isset($address['country']) && tep_not_null($address['country']))
    {
        $country = tep_output_string_protected($address['country']);
    }
    else
    {
        $country = '';
    }
    $postcode = tep_output_string_protected($address['postcode']);
    $zip = $postcode;

    if($html)
    {
// HTML Mode
        $HR = '<hr>';
        $hr = '<hr>';
        if(($boln == '') && ($eoln == "\n"))
        { // Values not specified, use rational defaults
            $CR = '<br>';
            $cr = '<br>';
            $eoln = $cr;
        }
        else
        { // Use values supplied
            $CR = $eoln . $boln;
            $cr = $CR;
        }
    }
    else
    {
// Text Mode
        $CR = $eoln;
        $cr = $CR;
        $HR = '----------------------------------------';
        $hr = '----------------------------------------';
    }

    $statecomma = '';
    $streets = $street;
    if($suburb != '')
        $streets = $street . $cr . $suburb;
    if($country == '')
        $country = tep_output_string_protected($address['country']);
    if($state != '')
        $statecomma = $state . ', ';

    $fmt = $address_format['format'];
    eval("\$address2 = \"$fmt\";");

    if((ACCOUNT_COMPANY == 'true') && (tep_not_null($company)))
    {
        $address2 = $company . $cr . $address2;
    }

    return $address2;
}

////
// Return a formatted address
// TABLES: customers, address_book
function tep_address_label($customers_id, $address_id = 1, $html = false, $boln = '', $eoln = "\n")
{
    $address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int) $customers_id . "' and address_book_id = '" . (int) $address_id . "'");
    $address = tep_db_fetch_array($address_query);

    $format_id = tep_get_address_format_id($address['country_id']);

    return tep_address_format($format_id, $address, $html, $boln, $eoln);
}

function tep_row_number_format($number)
{
    if(($number < 10) && (substr($number, 0, 1) != '0'))
        $number = '0' . $number;

    return $number;
}

/*
  function tep_get_categories($categories_array = '', $parent_id = '0', $indent = '') {
  global $languages_id;

  if (!is_array($categories_array)) $categories_array = array();

  $categories_query = tep_db_query("select c.categories_id, cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where parent_id = '" . (int)$parent_id . "' and c.categories_status = '1' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by sort_order, cd.categories_name");
  while ($categories = tep_db_fetch_array($categories_query)) {
  $categories_array[] = array('id' => $categories['categories_id'],
  'text' => $indent . $categories['categories_name']);

  if ($categories['categories_id'] != $parent_id) {
  $categories_array = tep_get_categories($categories_array, $categories['categories_id'], $indent . '&nbsp;&nbsp;');
  }
  }

  return $categories_array;
  }
 */

/// - ТОПовая категория для товара
function tep_get_categ_name($products_id)
{
    $top_categ = explode("_", tep_get_product_path($products_id));

    $perem = $top_categ[0];

    $cat_query = tep_db_query("select categories_name from categories_description where language_id = '1' and categories_id='" . $perem . "'  ");

    while($cat = tep_db_fetch_array($cat_query))
    {
        $cName = $cat['categories_name'];
    }

    return $cName;
}

/// - Категория для товара по id товара
function tep_get_product_cat($products_id)
{
    $cPath = '';

    $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int) $products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");

    if(tep_db_num_rows($category_query))
    {

        $category = tep_db_fetch_array($category_query);
    }

    return $category['categories_id'];
}

function tep_get_manufacturers($manufacturers_array = '')
{
    if(!is_array($manufacturers_array))
        $manufacturers_array = array();

    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while($manufacturers = tep_db_fetch_array($manufacturers_query))
    {
        $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers['manufacturers_name']);
    }

    return $manufacturers_array;
}

function tep_date_atom($mysql_time_string)
{
    return date(DATE_ATOM, strtotime($mysql_time_string));
}

////
// Return all subcategory IDs
// TABLES: categories
/*
  function tep_get_subcategories(&$subcategories_array, $parent_id = 0) {
  $subcategories_query = tep_db_query("select categories_id from " . TABLE_CATEGORIES . " where parent_id = '" . (int)$parent_id . "'");
  while ($subcategories = tep_db_fetch_array($subcategories_query)) {
  $subcategories_array[sizeof($subcategories_array)] = $subcategories['categories_id'];
  if ($subcategories['categories_id'] != $parent_id) {
  tep_get_subcategories($subcategories_array, $subcategories['categories_id']);
  }
  }
  }
 */
// -------------by Zahar
function tep_date_short_custom($raw_date, $render = false)
{
    if(($raw_date == '0000-00-00 00:00:00') || ($raw_date == ''))
        return false;

    $year = substr($raw_date, 0, 4);
    $month = (int) substr($raw_date, 5, 2);
    $day = (int) substr($raw_date, 8, 2);
    $hour = (int) substr($raw_date, 11, 2);
    $minute = (int) substr($raw_date, 14, 2);
    $second = (int) substr($raw_date, 17, 2);

    $output['d'] = $day;
    $output['m'] = $month;
    $output['y'] = $year;

    if($render)
    {
        $str = '';
        foreach($output as $key => $value)
        {
            if($key == 'y')
            {
                $str .= $value;
                continue;
            }$str .= $value . '.';
        }
        return $str;
    }
    return $output;
}

// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
function tep_date_long($raw_date)
{
    if(($raw_date == '0000-00-00 00:00:00') || ($raw_date == ''))
        return false;

    $year = (int) substr($raw_date, 0, 4);
    $month = (int) substr($raw_date, 5, 2);
    $day = (int) substr($raw_date, 8, 2);
    $hour = (int) substr($raw_date, 11, 2);
    $minute = (int) substr($raw_date, 14, 2);
    $second = (int) substr($raw_date, 17, 2);

    return strftime(DATE_FORMAT_LONG, mktime($hour, $minute, $second, $month, $day, $year));
}

////
// Output a raw date string in the selected locale date format
// $raw_date needs to be in this format: YYYY-MM-DD HH:MM:SS
// NOTE: Includes a workaround for dates before 01/01/1970 that fail on windows servers
function tep_date_short($raw_date)
{
    if(($raw_date == '0000-00-00 00:00:00') || ($raw_date == ''))
        return false;

    $year = substr($raw_date, 0, 4);
    $month = (int) substr($raw_date, 5, 2);
    $day = (int) substr($raw_date, 8, 2);
    $hour = (int) substr($raw_date, 11, 2);
    $minute = (int) substr($raw_date, 14, 2);
    $second = (int) substr($raw_date, 17, 2);

    if(@date('Y', mktime($hour, $minute, $second, $month, $day, $year)) == $year)
    {
        return date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
    }
    else
    {
        return preg_replace('/2037' . '$/', $year, date(DATE_FORMAT, mktime($hour, $minute, $second, $month, $day, 2037)));
    }
}

// -------------by raid
function tep_date_short_r($raw_date)
{
    if(($raw_date == '0000-00-00 00:00:00') || ($raw_date == ''))
        return false;

    $year = substr($raw_date, 0, 4);
    $month = (int) substr($raw_date, 5, 2);
    $day = (int) substr($raw_date, 8, 2);
    $hour = (int) substr($raw_date, 11, 2);
    $minute = (int) substr($raw_date, 14, 2);
    $second = (int) substr($raw_date, 17, 2);

    $vivod = array($day, $month, $year);
    return $vivod;
}

////
// Parse search string into indivual objects
function tep_parse_search_string($search_str = '', &$objects)
{
    $search_str = trim(strtolower($search_str));

// Break up $search_str on whitespace; quoted string will be reconstructed later
    $pieces = explode('[[:space:]]+', $search_str);
    $objects = array();
    $tmpstring = '';
    $flag = '';

    for($k = 0; $k < count($pieces); $k++)
    {
        while(substr($pieces[$k], 0, 1) == '(')
        {
            $objects[] = '(';
            if(strlen($pieces[$k]) > 1)
            {
                $pieces[$k] = substr($pieces[$k], 1);
            }
            else
            {
                $pieces[$k] = '';
            }
        }

        $post_objects = array();

        while(substr($pieces[$k], -1) == ')')
        {
            $post_objects[] = ')';
            if(strlen($pieces[$k]) > 1)
            {
                $pieces[$k] = substr($pieces[$k], 0, -1);
            }
            else
            {
                $pieces[$k] = '';
            }
        }

// Check individual words

        if((substr($pieces[$k], -1) != '"') && (substr($pieces[$k], 0, 1) != '"'))
        {
            $objects[] = trim($pieces[$k]);

            for($j = 0; $j < count($post_objects); $j++)
            {
                $objects[] = $post_objects[$j];
            }
        }
        else
        {
            /* This means that the $piece is either the beginning or the end of a string.
              So, we'll slurp up the $pieces and stick them together until we get to the
              end of the string or run out of pieces.
             */

// Add this word to the $tmpstring, starting the $tmpstring
            $tmpstring = trim(preg_replace('/"/', ' ', $pieces[$k]));

// Check for one possible exception to the rule. That there is a single quoted word.
            if(substr($pieces[$k], -1) == '"')
            {
// Turn the flag off for future iterations
                $flag = 'off';

                $objects[] = trim($pieces[$k]);

                for($j = 0; $j < count($post_objects); $j++)
                {
                    $objects[] = $post_objects[$j];
                }

                unset($tmpstring);

// Stop looking for the end of the string and move onto the next word.
                continue;
            }

// Otherwise, turn on the flag to indicate no quotes have been found attached to this word in the string.
            $flag = 'on';

// Move on to the next word
            $k++;

// Keep reading until the end of the string as long as the $flag is on

            while(($flag == 'on') && ($k < count($pieces)))
            {
                while(substr($pieces[$k], -1) == ')')
                {
                    $post_objects[] = ')';
                    if(strlen($pieces[$k]) > 1)
                    {
                        $pieces[$k] = substr($pieces[$k], 0, -1);
                    }
                    else
                    {
                        $pieces[$k] = '';
                    }
                }

// If the word doesn't end in double quotes, append it to the $tmpstring.
                if(substr($pieces[$k], -1) != '"')
                {
// Tack this word onto the current string entity
                    $tmpstring .= ' ' . $pieces[$k];

// Move on to the next word
                    $k++;
                    continue;
                }
                else
                {
                    /* If the $piece ends in double quotes, strip the double quotes, tack the
                      $piece onto the tail of the string, push the $tmpstring onto the $haves,
                      kill the $tmpstring, turn the $flag "off", and return.
                     */
                    $tmpstring .= ' ' . trim(preg_replace('/"/', ' ', $pieces[$k]));

// Push the $tmpstring onto the array of stuff to search for
                    $objects[] = trim($tmpstring);

                    for($j = 0; $j < count($post_objects); $j++)
                    {
                        $objects[] = $post_objects[$j];
                    }

                    unset($tmpstring);

// Turn off the flag to exit the loop
                    $flag = 'off';
                }
            }
        }
    }

// add default logical operators if needed
    $temp = array();
    for($i = 0; $i < (count($objects) - 1); $i++)
    {
        $temp[] = $objects[$i];
        if(($objects[$i] != 'and') &&
                ($objects[$i] != 'or') &&
                ($objects[$i] != '(') &&
                ($objects[$i + 1] != 'and') &&
                ($objects[$i + 1] != 'or') &&
                ($objects[$i + 1] != ')'))
        {
            $temp[] = ADVANCED_SEARCH_DEFAULT_OPERATOR;
        }
    }
    $temp[] = $objects[$i];
    $objects = $temp;

    $keyword_count = 0;
    $operator_count = 0;
    $balance = 0;
    for($i = 0; $i < count($objects); $i++)
    {
        if($objects[$i] == '(')
            $balance --;
        if($objects[$i] == ')')
            $balance ++;
        if(($objects[$i] == 'and') || ($objects[$i] == 'or'))
        {
            $operator_count ++;
        }
        elseif(($objects[$i]) && ($objects[$i] != '(') && ($objects[$i] != ')'))
        {
            $keyword_count ++;
        }
    }

    if(($operator_count < $keyword_count) && ($balance == 0))
    {
        return true;
    }
    else
    {
        return false;
    }
}

////
// Check date
function tep_checkdate($date_to_check, $format_string, &$date_array)
{
    $separator_idx = -1;

    $separators = array('-', ' ', '/', '.');
    $month_abbr = array('jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec');
    $no_of_days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

    $format_string = strtolower($format_string);

    if(strlen($date_to_check) != strlen($format_string))
    {
        return false;
    }

    $size = sizeof($separators);
    for($i = 0; $i < $size; $i++)
    {
        $pos_separator = strpos($date_to_check, $separators[$i]);
        if($pos_separator != false)
        {
            $date_separator_idx = $i;
            break;
        }
    }

    for($i = 0; $i < $size; $i++)
    {
        $pos_separator = strpos($format_string, $separators[$i]);
        if($pos_separator != false)
        {
            $format_separator_idx = $i;
            break;
        }
    }

    if($date_separator_idx != $format_separator_idx)
    {
        return false;
    }

    if($date_separator_idx != -1)
    {
        $format_string_array = explode($separators[$date_separator_idx], $format_string);
        if(sizeof($format_string_array) != 3)
        {
            return false;
        }

        $date_to_check_array = explode($separators[$date_separator_idx], $date_to_check);
        if(sizeof($date_to_check_array) != 3)
        {
            return false;
        }

        $size = sizeof($format_string_array);
        for($i = 0; $i < $size; $i++)
        {
            if($format_string_array[$i] == 'mm' || $format_string_array[$i] == 'mmm')
                $month = $date_to_check_array[$i];
            if($format_string_array[$i] == 'dd')
                $day = $date_to_check_array[$i];
            if(($format_string_array[$i] == 'yyyy') || ($format_string_array[$i] == 'aaaa'))
                $year = $date_to_check_array[$i];
        }
    } else
    {
        if(strlen($format_string) == 8 || strlen($format_string) == 9)
        {
            $pos_month = strpos($format_string, 'mmm');
            if($pos_month != false)
            {
                $month = substr($date_to_check, $pos_month, 3);
                $size = sizeof($month_abbr);
                for($i = 0; $i < $size; $i++)
                {
                    if($month == $month_abbr[$i])
                    {
                        $month = $i;
                        break;
                    }
                }
            }
            else
            {
                $month = substr($date_to_check, strpos($format_string, 'mm'), 2);
            }
        }
        else
        {
            return false;
        }

        $day = substr($date_to_check, strpos($format_string, 'dd'), 2);
        $year = substr($date_to_check, strpos($format_string, 'yyyy'), 4);
    }

    if(strlen($year) != 4)
    {
        return false;
    }

    if(!settype($year, 'integer') || !settype($month, 'integer') || !settype($day, 'integer'))
    {
        return false;
    }

    if($month > 12 || $month < 1)
    {
        return false;
    }

    if($day < 1)
    {
        return false;
    }

    if(tep_is_leap_year($year))
    {
        $no_of_days[1] = 29;
    }

    if($day > $no_of_days[$month - 1])
    {
        return false;
    }

    $date_array = array($year, $month, $day);

    return true;
}

////
// Check if year is a leap year
function tep_is_leap_year($year)
{
    if($year % 100 == 0)
    {
        if($year % 400 == 0)
            return true;
    } else
    {
        if(($year % 4) == 0)
            return true;
    }

    return false;
}

////
// Return table heading with sorting capabilities
function tep_create_sort_heading($sortby, $colnum, $heading)
{
    global $PHP_SELF;

    $sort_prefix = '';
    $sort_suffix = '';

    if($sortby)
    {
        $sort_prefix = '<a rel="nofollow" href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('page', 'info', 'sort')) . 'page=1&sort=' . $colnum . ($sortby == $colnum . 'a' ? 'd' : 'a')) . '" title="' . tep_output_string(TEXT_SORT_PRODUCTS . ($sortby == $colnum . 'd' || substr($sortby, 0, 1) != $colnum ? TEXT_ASCENDINGLY : TEXT_DESCENDINGLY) . TEXT_BY . $heading) . '" class="productListing-heading">' . (substr($sortby, 0, 1) == $colnum ? '<b>' : '');
        $sort_suffix = (substr($sortby, 0, 1) == $colnum ? (substr($sortby, 1, 1) == 'a' ? ' +</b>' : ' -</b>') : '') . '</a>';
    }

    return $sort_prefix . $heading . $sort_suffix;
}

////
// Recursively go through the categories and retreive all parent categories IDs
// TABLES: categories
function tep_get_parent_categories(&$categories, $categories_id)
{
    $parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int) $categories_id . "'");
    while($parent_categories = tep_db_fetch_array($parent_categories_query))
    {
        if($parent_categories['parent_id'] == 0)
            return true;
        $categories[sizeof($categories)] = $parent_categories['parent_id'];
        if($parent_categories['parent_id'] != $categories_id)
        {
            tep_get_parent_categories($categories, $parent_categories['parent_id']);
        }
    }
}

////
// Construct a category path to the product
// TABLES: products_to_categories
function tep_get_product_path($products_id)
{
    $cPath = '';

    $category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int) $products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");

    if(tep_db_num_rows($category_query))
    {

        $category = tep_db_fetch_array($category_query);

        $categories = array();
        tep_get_parent_categories($categories, $category['categories_id']);

        $categories = array_reverse($categories);

        $cPath = implode('_', $categories);

        if(tep_not_null($cPath))
            $cPath .= '_';
        $cPath .= $category['categories_id'];
    }

    return $cPath;
}

function tep_parse_uprid($uprid)
{
    $tmp = explode('{', $uprid);
    if($tmp)
    {
        $result = array(
            'products_id' => intval($tmp[0]),
            'attributes' => array()
        );
        for($i = 1; $i < count($tmp); $i++)
        {
            $_tmp = explode('}', $tmp[$i]);
            if(count($_tmp) === 2)
            {
                $result['attributes'][intval($_tmp[0])] = intval($_tmp[1]);
            }
        }
        return $result;
    }
    return null;
}

////
// Return a product ID with attributes
function tep_get_uprid($prid, $params)
{
    if(is_numeric($prid))
    {
        $uprid = $prid;

        if(is_array($params) && (sizeof($params) > 0))
        {
            $attributes_check = true;
            $attributes_ids = '';

            reset($params);
            while(list($option, $value) = each($params))
            {
                if(is_numeric($option) && is_numeric($value))
                {

// otf 1.71 Add processing around $value. This is needed for text attributes.
                    $attributes_ids .= '{' . (int) $option . '}' . (int) $value;

                    // Add else stmt to process product ids passed in by other routines.
                }
                else
                {
                    $attributes_ids .= htmlspecialchars(stripslashes($attributes_ids), ENT_QUOTES);
                    $attributes_check = false;
                    break;
                }
            }

            if($attributes_check == true)
            {
                $uprid .= $attributes_ids;
            }
        }
    }
    else
    {
        $uprid = tep_get_prid($prid);

        if(is_numeric($uprid))
        {
            if(strpos($prid, '{') !== false)
            {
                $attributes_check = true;
                $attributes_ids = '';

// strpos()+1 to remove up to and including the first { which would create an empty array element in explode()
                $attributes = explode('{', substr($prid, strpos($prid, '{') + 1));

                for($i = 0, $n = sizeof($attributes); $i < $n; $i++)
                {
                    $pair = explode('}', $attributes[$i]);

                    if(is_numeric($pair[0]) && is_numeric($pair[1]))
                    {
                        $attributes_ids .= '{' . (int) $pair[0] . '}' . (int) $pair[1];
                    }
                    else
                    {
                        $attributes_check = false;
                        break;
                    }
                }

                if($attributes_check == true)
                {
                    $uprid .= $attributes_ids;
                }
            }
        }
        else
        {
            return false;
        }
    }

    return $uprid;
}

////
// Return a product ID from a product ID with attributes
function tep_get_prid($uprid)
{
    $pieces = explode('{', $uprid);

    if(is_numeric($pieces[0]))
    {
        return $pieces[0];
    }
    else
    {
        return false;
    }
}

////
// Return a customer greeting
function tep_customer_greeting()
{
    global $customer_id, $customer_first_name;

    if(tep_session_is_registered('customer_first_name') && tep_session_is_registered('customer_id'))
    {
        $greeting_string = sprintf(TEXT_GREETING_PERSONAL, tep_output_string_protected($customer_first_name), tep_href_link(FILENAME_PRODUCTS_NEW));
    }
    else
    {
        $greeting_string = sprintf(TEXT_GREETING_GUEST, tep_href_link(FILENAME_LOGIN, '', 'SSL'), tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'));
    }

    return $greeting_string;
}

////
//! Send email (text/html) using MIME
// This is the central mail function. The SMTP Server should be configured
// correct in php.ini
// Parameters:
// $to_name           The name of the recipient, e.g. "Jan Wildeboer"
// $to_email_address  The eMail address of the recipient,
//                    e.g. jan.wildeboer@gmx.de
// $email_subject     The subject of the eMail
// $email_text        The text of the eMail, may contain HTML entities
// $from_email_name   The name of the sender, e.g. Shop Administration
// $from_email_adress The eMail address of the sender,
//                    e.g. info@mytepshop.com

function tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address)
{
    $mailer = tep_get_mailer();
    
    // Получатели
    $addresses = tep_parse_emails($to_email_address);
    if(is_array($addresses))
    {
        foreach($addresses as $addresse)
        {
            $mailer->addAddress($addresse['email'], $addresse['name']);
        }
    }
    else
    {
        $mailer->addAddress($to_email_address, $to_name);
    }
    
    // Тема и текст письма
    $mailer->Subject = $email_subject;
    $mailer->Body = $email_text;
    
    return $mailer->send();
}

/**
 * Возвращает объект класса PHPMailer с установленными некоторыми изначальными параметрами, заданными в настройках магазина, которые касаются отправки электронной почты
 * 
 * @return \PHPMailer
 */
function tep_get_mailer()
{
    static $dkim_key_file;
    $mailer = new PHPMailer();
    $mailer->CharSet = defined('CHARSET') ? CHARSET : 'UTF-8';
    $mailer->setFrom(EMAIL_FROM, STORE_NAME);
    if(EMAIL_DKIM_STATUS && EMAIL_DKIM_SELECTOR && EMAIL_DKIM_DOMAIN && EMAIL_DKIM_PRIVATE)
    {
        if(!$dkim_key_file)
        {
            $dkim_key_file = tempnam(sys_get_temp_dir(), 'DKM');
            if($dkim_key_file)
            {
                file_put_contents($dkim_key_file, EMAIL_DKIM_PRIVATE);
                
                // Удалить временный файл после окончания работы скрипта
                register_shutdown_function(function() use ($dkim_key_file){
                    @unlink($dkim_key_file);
                });
            }
        }
        if($dkim_key_file)
        {
            $mailer->DKIM_domain = EMAIL_DKIM_DOMAIN;
            $mailer->DKIM_identity = EMAIL_DKIM_IDENTITY ? EMAIL_DKIM_IDENTITY : EMAIL_FROM;
            $mailer->DKIM_passphrase = EMAIL_DKIM_PASSPHRASE;
            $mailer->DKIM_private = $dkim_key_file;
            $mailer->DKIM_selector = EMAIL_DKIM_SELECTOR;
        }
    }
    $mailer->isHTML(EMAIL_USE_HTML === 'true');
    return $mailer;
}

/**
 * Парсит строку получателей электронной почты в формате
 * <pre>Имя получателя 1 &lt;email&#64;poluchatelya1&gt;, Имя получателя 2 &lt;email&#64;poluchatelya2&gt;, Имя получателя 3 &lt;email&#64;poluchatelya3&gt;</pre>
 * возвращает массив имён и e-mail адресов, указанных в строке.
 * 
 * @param type $emails_string Строка имён и адресов получателей в формате
 * <pre>Имя получателя 1 &lt;email&#64;poluchatelya1&gt;, Имя получателя 2 &lt;email&#64;poluchatelya2&gt;, Имя получателя 3 &lt;email&#64;poluchatelya3&gt;</pre>
 * @return array|null Возвращает массив имён и адресов, которые содержались в строке, например:
 * <pre>
 * array(
 *     // Первый получатель
 *     0 => array(
 *         'name'  => '', // Имя
 *         'email' => ''  // E-mail
 *     ),
 *     // Второй получатель
 *     1 => array(
 *         'name'  => '', // Имя
 *         'email' => ''  // E-mail
 *     ),
 *     // ...
 * );
 * </pre>
 * Если не удалось распарсить строку, то функция возвращает null
 */
function tep_parse_emails($emails_string)
{
    $matches       = array();
    if(preg_match_all('/([^>]+)?<([^<]+)>,?/', $emails_string, $matches, PREG_SET_ORDER))
    {
        $parsed_emails = array();
        foreach($matches as $match_set)
        {
            $parsed_emails[] = array(
                'name'  => trim($match_set[1]),
                'email' => trim($match_set[2])
            );
        }
        return $parsed_emails;
    }
    return null;
}

////
// Check if product has attributes
function tep_has_product_attributes($products_id)
{
    $attributes_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int) $products_id . "'");
    $attributes = tep_db_fetch_array($attributes_query);

    if($attributes['count'] > 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}

////
// Get the number of times a word/character is present in a string
function tep_word_count($string, $needle)
{
    $temp_array = split($needle, $string);

    return sizeof($temp_array);
}

function tep_count_modules($modules = '')
{
    $count = 0;

    if(empty($modules))
        return $count;

    $modules_array = explode(';', $modules);

    for($i = 0, $n = sizeof($modules_array); $i < $n; $i++)
    {
        $class = substr($modules_array[$i], 0, strrpos($modules_array[$i], '.'));

        if(is_object($GLOBALS[$class]))
        {
            if($GLOBALS[$class]->enabled)
            {
                $count++;
            }
        }
    }

    return $count;
}

function tep_count_payment_modules()
{
    return tep_count_modules(MODULE_PAYMENT_INSTALLED);
}

function tep_count_shipping_modules()
{
    $count = 0;
    foreach(array_filter(array_unique(explode(';', MODULE_SHIPPING_INSTALLED))) as $module)
    {
        $module = substr($module, 0, strrpos($module, '.'));
        if (($_module = shipping::getInstanceOf($module)) && ($_module->enabled))
        {
            $count++;
        }
    }
    return $count;
}

function tep_create_random_value($length, $type = 'mixed')
{
    if(($type != 'mixed') && ($type != 'chars') && ($type != 'digits'))
        return false;

    $rand_value = '';
    while(strlen($rand_value) < $length)
    {
        if($type == 'digits')
        {
            $char = tep_rand(0, 9);
        }
        else
        {
            $char = chr(tep_rand(0, 255));
        }
        if($type == 'mixed')
        {
            if(preg_match('/^[a-z0-9]$/i', $char))
                $rand_value .= $char;
        } elseif($type == 'chars')
        {
            if(preg_match('/^[a-z]$/i', $char))
                $rand_value .= $char;
        } elseif($type == 'digits')
        {
            if(preg_match('/^[0-9]$/', $char))
                $rand_value .= $char;
        }
    }

    return $rand_value;
}

function tep_array_to_string($array, $exclude = '', $equals = '=', $separator = '&')
{
    if(!is_array($exclude))
        $exclude = array();

    $get_string = '';
    if(sizeof($array) > 0)
    {
        while(list($key, $value) = each($array))
        {
            if((!in_array($key, $exclude)) && ($key != 'x') && ($key != 'y'))
            {
                $get_string .= $key . $equals . $value . $separator;
            }
        }
        $remove_chars = strlen($separator);
        $get_string = substr($get_string, 0, -$remove_chars);
    }

    return $get_string;
}

function tep_not_null($value)
{
    if(is_array($value))
    {
        if(sizeof($value) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        if(($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

////
// Output the tax percentage with optional padded decimals
function tep_display_tax_value($value, $padding = TAX_DECIMAL_PLACES)
{
    if(strpos($value, '.'))
    {
        $loop = true;
        while($loop)
        {
            if(substr($value, -1) == '0')
            {
                $value = substr($value, 0, -1);
            }
            else
            {
                $loop = false;
                if(substr($value, -1) == '.')
                {
                    $value = substr($value, 0, -1);
                }
            }
        }
    }

    if($padding > 0)
    {
        if($decimal_pos = strpos($value, '.'))
        {
            $decimals = strlen(substr($value, ($decimal_pos + 1)));
            for($i = $decimals; $i < $padding; $i++)
            {
                $value .= '0';
            }
        }
        else
        {
            $value .= '.';
            for($i = 0; $i < $padding; $i++)
            {
                $value .= '0';
            }
        }
    }

    return $value;
}

////
// Checks to see if the currency code exists as a currency
// TABLES: currencies
function tep_currency_exists($code)
{
    $code = tep_db_prepare_input($code);

    $currency_code = tep_db_query("select currencies_id from " . TABLE_CURRENCIES . " where code = '" . tep_db_input($code) . "'");
    if(tep_db_num_rows($currency_code))
    {
        return $code;
    }
    else
    {
        return false;
    }
}

function tep_string_to_int($string)
{
    return (int) $string;
}

////
// Parse and secure the cPath parameter values
function tep_parse_category_path($cPath)
{
// make sure the category IDs are integers
    $cPath_array = array_map('tep_string_to_int', explode('_', $cPath));

// make sure no duplicate category IDs exist which could lock the server in a loop
    $tmp_array = array();
    $n = sizeof($cPath_array);
    for($i = 0; $i < $n; $i++)
    {
        if(!in_array($cPath_array[$i], $tmp_array))
        {
            $tmp_array[] = $cPath_array[$i];
        }
    }

    return $tmp_array;
}

////
// Return a random value
function tep_rand($min = null, $max = null)
{
    static $seeded;

    if(!isset($seeded))
    {
        mt_srand((double) microtime() * 1000000);
        $seeded = true;
    }

    if(isset($min) && isset($max))
    {
        if($min >= $max)
        {
            return $min;
        }
        else
        {
            return mt_rand($min, $max);
        }
    }
    else
    {
        return mt_rand();
    }
}

function tep_setcookie($name, $value = '', $expire = 0, $path = '/', $domain = '', $secure = 0)
{
    setcookie($name, $value, $expire, $path, (tep_not_null($domain) ? $domain : ''), $secure);
}

function tep_get_ip_address()
{
    if(isset($_SERVER))
    {
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        elseif(isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    }
    else
    {
        if(getenv('HTTP_X_FORWARDED_FOR'))
        {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif(getenv('HTTP_CLIENT_IP'))
        {
            $ip = getenv('HTTP_CLIENT_IP');
        }
        else
        {
            $ip = getenv('REMOTE_ADDR');
        }
    }

    return $ip;
}

function tep_count_customer_orders($id = '', $check_session = true)
{
    global $customer_id;

    if(is_numeric($id) == false)
    {
        if(tep_session_is_registered('customer_id'))
        {
            $id = $customer_id;
        }
        else
        {
            return 0;
        }
    }

    if($check_session == true)
    {
        if((tep_session_is_registered('customer_id') == false) || ($id != $customer_id))
        {
            return 0;
        }
    }

    $orders_check_query = tep_db_query("select count(*) as total from " . TABLE_ORDERS . " where customers_id = '" . (int) $id . "'");
    $orders_check = tep_db_fetch_array($orders_check_query);

    return $orders_check['total'];
}

function tep_count_customer_address_book_entries($id = '', $check_session = true)
{
    global $customer_id;

    if(is_numeric($id) == false)
    {
        if(tep_session_is_registered('customer_id'))
        {
            $id = $customer_id;
        }
        else
        {
            return 0;
        }
    }

    if($check_session == true)
    {
        if((tep_session_is_registered('customer_id') == false) || ($id != $customer_id))
        {
            return 0;
        }
    }

    $addresses_query = tep_db_query("select count(*) as total from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int) $id . "'");
    $addresses = tep_db_fetch_array($addresses_query);

    return $addresses['total'];
}

// nl2br() prior PHP 4.2.0 did not convert linefeeds on all OSs (it only converted \n)
function tep_convert_linefeeds($from, $to, $string)
{
    if((PHP_VERSION < "4.0.5") && is_array($from))
    {
        return preg_replace('/(' . implode('|/', $from) . ')', $to, $string);
    }
    else
    {
        return str_replace($from, $to, $string);
    }
}

//TotalB2B start
function tep_xppp_getmaxprices()
{
    //max prices per product
    return 10;
}

function tep_xppp_getpricesnum()
{
//    $prices_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'XPRICES_NUM'");
//    $prices = tep_db_fetch_array($prices_query);
//    return $prices['configuration_value'];
    return XPRICES_NUM;
}

function tep_xppp_getpricelist($ts)
{
    $prices_num = tep_xppp_getpricesnum();
    for($i = 2; $i <= $prices_num; $i++)
    {
        if($ts != NULL)
            $price_list .= $ts . ".products_price_" . $i . ",";
        else
            $price_list .= "products_price_" . $i . ",";
    }
    if($ts != NULL)
        $price_list .= $ts . ".products_price";
    else
        $price_list .= "products_price";
    return $price_list;
}

function tep_xppp_getproductprice($products_id)
{
    global $customer_id;

    $customer_query = tep_db_query("select g.customers_groups_price from " . TABLE_CUSTOMERS_GROUPS . " g inner join  " . TABLE_CUSTOMERS . " c on g.customers_groups_id = c.customers_groups_id and c.customers_id = '" . $customer_id . "'");
    $customer_query_result = tep_db_fetch_array($customer_query);
    $customer_price = $customer_query_result['customers_groups_price'];

    $products_price_list = tep_xppp_getpricelist("");
    $product_info_query = tep_db_query("select products_id, " . $products_price_list . "  from " . TABLE_PRODUCTS . " where products_id = '" . $products_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);
    if($product_info['products_price_' . $customer_price] == NULL)
    {
        $product_info['products_price_' . $customer_price] = $product_info['products_price'];
    }
    if((int) $customer_price != 1)
    {
        $product_info['products_price'] = $product_info['products_price_' . $customer_price];
    }
// BOF FlyOpenair: Extra Product Price
    $product_info['products_price'] = extra_product_price($product_info['products_price']);
// EOF FlyOpenair: Extra Product Price
    return $product_info['products_price'];
}

//TotalB2B end


function tep_get_products_info($product_id)
{
    global $languages_id;

    $product_query = tep_db_query("select products_info from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . $product_id . "' and language_id = '" . $languages_id . "'");
    $product_info = tep_db_fetch_array($product_query);

    return $product_info['products_info'];
}

////
//CLR 030228 Add function tep_decode_specialchars
// Decode string encoded with htmlspecialchars()
function tep_decode_specialchars($string)
{
    $string = str_replace('&gt;', '>', $string);
    $string = str_replace('&lt;', '<', $string);
    $string = str_replace('&#039;', "'", $string);
    $string = str_replace('&quot;', "\"", $string);
    $string = str_replace('&amp;', '&', $string);

    return $string;
}

////
// Return a product's minimum quantity
// TABLES: products
function tep_get_products_quantity_order_min($product_id)
{

    $the_products_quantity_order_min_query = tep_db_query("select products_id, products_quantity_order_min from " . TABLE_PRODUCTS . " where products_id = '" . $product_id . "'");
    $the_products_quantity_order_min = tep_db_fetch_array($the_products_quantity_order_min_query);
    return $the_products_quantity_order_min['products_quantity_order_min'];
}

////
// Return a product's minimum unit order
// TABLES: products
function tep_get_products_quantity_order_units($product_id)
{

    $the_products_quantity_order_units_query = tep_db_query("select products_id, products_quantity_order_units from " . TABLE_PRODUCTS . " where products_id = '" . $product_id . "'");
    $the_products_quantity_order_units = tep_db_fetch_array($the_products_quantity_order_units_query);

    return $the_products_quantity_order_units['products_quantity_order_units'];
}

// begin mod for ProductsProperties v2.01
function tep_get_prop_options_name($options_id, $language = '')
{
    global $languages_id;

    if(empty($language))
        $language = $languages_id;

    $options = tep_db_query("select products_options_name from " . TABLE_PRODUCTS_PROP_OPTIONS . " where products_options_id = '" . (int) $options_id . "' and language_id = '" . (int) $languages_id . "'");
    $options_values = tep_db_fetch_array($options);

    return $options_values['products_options_name'];
}

function tep_get_prop_values_name($values_id, $language = '')
{
    global $languages_id;

    if(empty($language))
        $language = $languages_id;

    $values = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_PROP_OPTIONS_VALUES . " where products_options_values_id = '" . (int) $values_id . "' and language_id = '" . (int) $languages_id . "'");
    $values_values = tep_db_fetch_array($values);

    return $values_values['products_options_values_name'];
}

// end mod for ProductsProperties v2.01
////
// saved from old code
function tep_output_warning($warning)
{
    new errorBox(array(array('text' => tep_image(DIR_WS_ICONS . 'warning.gif', ICON_WARNING) . ' ' . $warning)));
}

function tep_get_languages_id($code)
{
    global $languages_id;
    $language_query = tep_db_query("select languages_id from " . TABLE_LANGUAGES . " where code = '" . DEFAULT_LANGUAGE . "'");
    if(tep_db_num_rows($language_query))
    {
        $language = tep_db_fetch_array($language_query);
        $languages_id = $language['languages_id'];
        return $language['languages_id'];
    }
    else
    {
        return false;
    }
}

/* One Page Checkout - BEGIN */

function tep_cfg_pull_down_zone_list_one_page($zone_id)
{
    return tep_draw_pull_down_menu('configuration_value', tep_get_country_zones(ONEPAGE_AUTO_SHOW_DEFAULT_COUNTRY), $zone_id);
}

/* One Page Checkout - END */


/**
 * Функция для вывода значений для отладки
 * 
 * @param mixed $data
 */
function debug($data)
{
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}

/**
 * Экранирует специальные символы HTML в строке
 * 
 * @param string $string
 * @return string
 */
function tep_escape($string)
{
    return htmlspecialchars($string, ENT_COMPAT, CHARSET);
}

/**
 * Загружает файл с переводом
 * 
 * @param string $filename Имя файла в каталоге языковый файлов текущего языка
 * @return array
 */
function loadTranslation($filename)
{
    $lookup_file = DIR_WS_LANGUAGES . $_SESSION['language'] . DIRECTORY_SEPARATOR . $filename;
    if(is_file($lookup_file))
    {
        return require $lookup_file;
    }
    return array();
}

/**
 * Оборачивает строку в CDATA
 * 
 * @param string $string
 * @return string
 */
function wrapIntoCDATA($string)
{
    return '<![CDATA[' . str_replace(']]>', ']]]]><![CDATA[>', $string) . ']]>';
}

/**
 * Подготавливает строку для использования в URL
 * 
 * Выполняет транслитерацию, заменяет все символы, кроме арабских цифр, латинских букв,
 * дефисоминуса и нижнего подчёркивания, на дефисоминусы
 * 
 * @param string $string
 * @return string
 */
function tep_make_uri_friendly_string($string)
{
    $string = mb_strtolower($string, defined('CHARSET') ? CHARSET : 'UTF-8');
    $translit_in = array(
        // Кирилица
        'ё', 'й', 'ц', 'у', 'к', 'е', 'н', 'г', 'ш', 'щ', 'з', 'х', 'ф', 'ы',
        'в', 'а', 'п', 'р', 'о', 'л', 'д', 'ж', 'э', 'я', 'ч', 'с', 'м', 'и',
        'т', 'б', 'ю', 'і', 'ї', 'ґ', 'ь', 'ъ', '\'', '’',
        
        // Словацкий
        'á', 'ä', 'č', 'ď', 'é', 'ĺ', 'ľ', 'ň', 'ó', 'ô', 'ŕ', 'š', 'ť', 'ú', 
        'ý', 'ž'
    );
    $translit_out = array(
        // Кирилица
        'e', 'y', 'c', 'u', 'k', 'e', 'n', 'g', 'sh', 'sch', 'z', 'h', 'f', 'y',
        'v', 'a', 'p', 'r', 'o', 'l', 'd', 'j', 'e', 'ya', 'ch', 's', 'm', 'i',
        't', 'b', 'yu', 'i', 'yi', 'g', '', '', '', '',
        
        // Словацкий
        'a', 'a', 'c', 'd', 'e', 'l', 'l', 'n', 'o', 'o', 'r', 's', 't', 'u', 
        'y', 'z'
    );
    $string = str_replace($translit_in, $translit_out, $string);
    $string = preg_replace(array('/[^a-z0-9_]/', '/^-+|-+$/', '/--+/'), array('-', '', '-'), $string);
    return $string;
}

/**
 * Конвертирует значение размера, из той формы, в которой
 * оно указанно в ini-файле, в байты.
 * <pre>
 * 14  = 14
 * 14K = 14000
 * 14M = 14000000
 * 14G = 14000000000
 * </pre>
 * 
 * @param string $ini_size Размер в том виде, в котором он указан в ini-файле.
 * @return int
 */
function iniSizeToBytes($ini_size)
{
    $matches = array();
    if(preg_match('/\s*?([\d\.]+)\s*?([kmg])?/i', $ini_size, $matches))
    {
        $size = floatval($matches[1]);
        if(!empty($matches[2]))
        {
            $matches[2] = strtolower($matches[2]);
            if($matches[2] === 'k')
            {
                $size *= 1000;
            }
            elseif($matches[2] === 'm')
            {
                $size *= 1000000;
            }
            else
            {
                $size *= 1000000000;
            }
        }
        return intval($size);
    }
    return 0;
}

/**
 * Возвращает максимальеый размер файла, доступный для загрузки на сервер
 * 
 * @return int
 */
function getUploadMaxFileSize()
{
    return min(array(
        iniSizeToBytes(ini_get('post_max_size')),
        iniSizeToBytes(ini_get('upload_max_filesize'))
    ));
}

/**
 * Выполняет подготовку и уникализацию имени файла для сохраненния в указанном каталоге
 * 
 * @param string $file_name Текущее имя файла
 * @param string $directory Путь до директории, в которую планируется поместить файл с указанным именем
 * @return string Имя, под которым можно будет сохранить файл в каталоге для хранения изображений слайдера
 */
function makeUniqueFileName($file_name, $directory)
{
    $file_name = explode('.', $file_name);
    $extension = array_pop($file_name);
    $file_name = implode('.', $file_name);
    $file_name = tep_make_uri_friendly_string($file_name);
    $extension = tep_make_uri_friendly_string($extension);
    if(!$file_name || !$extension)
    {
        return null;
    }
    $i         = 0;
    $sufix     = '';
    $directory = addEndingDirectorySeparator($directory);
    while(is_file($directory . $file_name . $sufix . '.' . $extension))
    {
        $sufix = '-' . ($i++);
    }
    return $file_name . $sufix . '.' . $extension;
}

/**
 * Проверяет, стоит ли в конце строки разделитель каталогов. Если не стоит, то он добавляется.
 * 
 * @param string $string Строка, к которой следует добавить конечный символ
 * @return string
 */
function addEndingDirectorySeparator($string)
{
    $last_symbol = $string[strlen($string) - 1];
    if($last_symbol !== '\\' && $last_symbol !== '/')
    {
        $string .= DIRECTORY_SEPARATOR;
    }
    return $string;
}

/**
 * Возвращает первый непустой аргумент
 * 
 * @return mixed
 */
function getFirstNoneEmpty()
{
    if(func_num_args())
    {
        foreach(func_get_args() as $arg)
        {
            if(!empty($arg))
            {
                return $arg;
            }
        }
    }
    return null;
}

/**
 * Генерирует URL-кодированную строку запроса на основании параметров текщего запроса с указаными исключениями и дополнениями
 * 
 * @param array $remove Массив имён параметров текущего запроса, которые должны быть исключены из результирующей строки запроса.
 * Если значение данного аргуменат ложно, то из исходного массива ($_GET) ничего не удаляется.
 * @param array $append Ассоциативный массив в виде <code>имя_параметра => значение_параметра</code>, которыми должен быть
 * дополнен текущий запрос. Если значение данного аргуменат ложно, то результирующая строка запросат не будет дополнена ничем.
 * Параметры с такими же именами, содержащиеся в исходном запросе, будут заменены параметрами из масива данного аргумента функции.
 * @return string
 */
function manageGetParams($remove, $append)
{
    $result = $_GET;
    if($remove && is_array($remove))
    {
        foreach($remove as $key)
        {
            unset($result[$key]);
        }
    }
    if($append && is_array($append))
    {
        foreach($append as $key => $value)
        {
            $result[$key] = $value;
        }
    }
    return http_build_query_rfc_3986($result);
}

/**
 * Строит строку запроса, кодированную в соответствии с RFC 3986
 * 
 * @param array|object $query_params параметры запроса
 * @return string
 */
function http_build_query_rfc_3986($query_params)
{
    if(defined('PHP_QUERY_RFC3986'))
    {
        return http_build_query($query_params, '', '&', PHP_QUERY_RFC3986);
    }
    return str_replace('+', '%20', http_build_query($query_params));
}

// Отзывы
function countCommentList($prodId){
    $query_string = <<<SQL
SELECT
    count(products_id) as countComment
FROM road
WHERE
    products_id = '{$prodId}' 
SQL;
    $query = tep_db_query($query_string);
    if($query && tep_db_num_rows($query))
    {
        $row = tep_db_fetch_array($query);
    }
     return $row['countComment'];    
}