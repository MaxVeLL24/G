<?php

/*
  $Id: application_top.php,v 1.2 2003/09/24 15:34:33 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

// start the timer for the page parse time log
define('PAGE_PARSE_START_TIME', microtime());

// Register Globals MOD - http://www.magic-seo-url.com
/* if(version_compare(phpversion(), "4.1.0", "<") === true)
{
    $_GET &= $HTTP_GET_VARS;
    $_POST &= $HTTP_POST_VARS;
    $_SERVER &= $HTTP_SERVER_VARS;
    $_FILES &= $HTTP_POST_FILES;
    $_ENV &= $HTTP_ENV_VARS;
    if(isset($HTTP_COOKIE_VARS))
    {
        $_COOKIE &= $HTTP_COOKIE_VARS;
    }
}

if(!ini_get("register_globals"))
{
    extract($_GET, EXTR_SKIP);
    extract($_POST, EXTR_SKIP);
    extract($_COOKIE, EXTR_SKIP);
} */

// Include path
$current_include_path  = explode(PATH_SEPARATOR, get_include_path());
$required_include_path = realpath(__DIR__ . '/../');
chdir($required_include_path);
if(!in_array('.', $current_include_path))
{
    array_unshift($current_include_path, '.');
}
if(!in_array($required_include_path, $current_include_path))
{
    array_push($current_include_path, $required_include_path);
}
set_include_path(implode(PATH_SEPARATOR, $current_include_path));

// Включить кэширование вывода
ob_start();
register_shutdown_function(function() {
    // Добавляем заголовок Content-Type, если не был добавлен ранее
    if(!headers_sent())
    {
        $prepared_headers = implode("\n", headers_list());
        if(stristr($prepared_headers, 'Content-Type') === false)
        {
            header('Content-Type: text/html; charset=' . (defined('CHARSET') ? CHARSET : 'UTF-8'));
            // Добавляем заголовок Content-Language
            if(!empty($_SESSION['languages_id']) && class_exists('language'))
            {
                $code = language::getCodeByID($_SESSION['languages_id']);
                if($code)
                {
                    header('Content-Language: ' . $code);
                }
            }
        }
    }

    // Сброс буфера вывода
    while(ob_get_level() > 0)
    {
        ob_end_flush();
    }
});

/* Autoload classes */
spl_autoload_register(function ($class) {
    $class_dir = __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    if(file_exists($class_dir . $class . '.php'))
    {
        include_once $class_dir . $class . '.php';
    }
    elseif(file_exists($class_dir . $class . '.class.php'))
    {
        include_once $class_dir . $class . '.class.php';
    }
    elseif(file_exists($class_dir . dirname($class) . DIRECTORY_SEPARATOR . 'class.' . basename($class) . '.php'))
    {
        include_once $class_dir . dirname($class) . DIRECTORY_SEPARATOR . 'class.' . basename($class) . '.php';
    }
});

// Composer autoloader
include __DIR__ . '/vendor/autoload.php';

$query_counts = 0;
$query_total_time = 0;

while(list($key, $value) = each($_GET))
{
    $_GET[$key] = preg_replace('/[<>]/', '', $value);
    unset($GLOBALS[$key]);
}

if(isset($_GET['products_id']))
    $_GET['products_id'] = $_GET['products_id'] = $products_id = $GLOBALS['products_id'] = (int) $_GET['products_id'];

// set the level of error reporting
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
date_default_timezone_set('UTC');

// check support for register_globals
  if (function_exists('ini_get') && (ini_get('register_globals') == false) && (PHP_VERSION < 4.3) ) {
    exit('Server Requirement Error: register_globals is disabled in your PHP configuration. This can be enabled in your php.ini configuration file or in the .htaccess file in your catalog directory. Please use PHP 4.3+ if register_globals cannot be enabled on the server.');
  }


require('includes/configure.php'); // include server parameters
require(DIR_WS_INCLUDES . 'spider_configure.php'); // Spiderkiller
// set the type of request (secure or not)
$request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
if(!isset($PHP_SELF))
    $PHP_SELF = $_SERVER['PHP_SELF'];

if($request_type == 'NONSSL')
{
    define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
}
else
{
    define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
}


require(DIR_WS_INCLUDES . 'filenames.php'); // include the list of project filenames
require(DIR_WS_INCLUDES . 'database_tables.php');  // include the list of project database tables
require(DIR_WS_FUNCTIONS . 'database.php'); // include the database functions

tep_db_connect() or die('Unable to connect to database server!'); // make a connection to the database... now
// set the application parameters
$configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
while($configuration = tep_db_fetch_array($configuration_query))
{
    define($configuration['cfgKey'], $configuration['cfgValue']);
}

// define general functions used application-wide
require(DIR_WS_FUNCTIONS . 'general.php');
require(DIR_WS_FUNCTIONS . 'html_output.php');
require(DIR_WS_MODULES . 'rating/rating.php'); // РјРѕРґСѓР»СЊ СЂРµР№С‚РёРЅРіР° (Р·РІРµР·РґРѕС‡РµРє)
require(DIR_WS_CLASSES . 'shopping_cart.php'); // include shopping cart class
require(DIR_WS_CLASSES . 'wishlist.php'); // include wishlist class
require(DIR_WS_FUNCTIONS . 'compatibility.php'); // some code to solve compatibility issues
require(DIR_WS_FUNCTIONS . 'extra_product_price.php');  // BOF FlyOpenair: Extra Product Price
require(DIR_WS_INCLUDES . 'template_application_top.php'); // Lango added for template BOF:
// set the cookie domain
$cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
$cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// check if sessions are supported, otherwise use the php3 compatible session class
if(!function_exists('session_start'))
{
    define('PHP_SESSION_NAME', 'osCsid');
    define('PHP_SESSION_PATH', $cookie_path);
    define('PHP_SESSION_DOMAIN', $cookie_domain);
    define('PHP_SESSION_SAVE_PATH', SESSION_WRITE_DIRECTORY);

    include(DIR_WS_CLASSES . 'sessions.php');
}

require(DIR_WS_FUNCTIONS . 'sessions.php'); // define how the session functions will be used
// set the session name and save path
tep_session_name('osCsid');
tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
if(function_exists('session_set_cookie_params'))
{
    session_set_cookie_params(0, $cookie_path, $cookie_domain);
}
elseif(function_exists('ini_set'))
{
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', $cookie_path);
    ini_set('session.cookie_domain', $cookie_domain);
}

// set the session ID if it exists
if(isset($_POST[tep_session_name()]))
{
    tep_session_id($_POST[tep_session_name()]);
}
elseif(($request_type == 'SSL') && isset($_GET[tep_session_name()]))
{
    tep_session_id($_GET[tep_session_name()]);
}

// start the session
$session_started = false;
if(SESSION_FORCE_COOKIE_USE == 'True')
{
    tep_setcookie('cookie_test', 'please_accept_for_session', time() + 60 * 60 * 24 * 30, $cookie_path, $cookie_domain);

    if(isset($_COOKIE['cookie_test']))
    {
        tep_session_start();
        $session_started = true;
    }
}
elseif(SESSION_BLOCK_SPIDERS == 'True')
{
    $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
    $spider_flag = false;

    if(tep_not_null($user_agent))
    {
        $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');

        for($i = 0, $n = sizeof($spiders); $i < $n; $i++)
        {
            if(tep_not_null($spiders[$i]))
            {
                if(is_integer(strpos($user_agent, trim($spiders[$i]))))
                {
                    $spider_flag = true;
                    break;
                }
            }
        }
    }

    // START HACK for remove old sessions from search engines
    if(($spider_flag) && (is_integer(strpos($_SERVER{'REQUEST_URI'}, "?osCsid="))))
    {
        preg_match("/(.+)\?osCsid=.+/", $_SERVER{'REQUEST_URI'}, $matches);
        header('Location: ' . $matches[1]);
        header('HTTP/1.0 301 Moved Permanently');
        die;  // Don't send any more output.
    }
    // END HACK

    if($spider_flag == false)
    {
        tep_session_start();
        $session_started = true;
    }
}
else
{
    tep_session_start();
    $session_started = true;
}

// Выпустить CSRF токен и поместить его в куки для добавления в AJAX-запросы
\EShopmakers\Security\CSRFToken::addToCookie();

//HTTP_REFERER
if(!$referer_url)
{
    if($_SERVER['HTTP_REFERER'])
    {
        $referer_url = $_SERVER['HTTP_REFERER'];
        tep_session_register('referer_url');
    }
}


// Register Globals MOD - http://www.magic-seo-url.com
/* if(!ini_get("register_globals"))
{
    if(version_compare(phpversion(), "4.1.0", "<") === true)
    {
        if(isset($HTTP_SESSION_VARS))
            $_SESSION &= $HTTP_SESSION_VARS;
    }
    extract($_SESSION, EXTR_SKIP);
} */

// set SID once, even if empty
$SID = (defined('SID') ? SID : '');

// verify the ssl_session_id if the feature is enabled
if(($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true))
{
    $ssl_session_id = getenv('SSL_SESSION_ID');
    if(!tep_session_is_registered('SSL_SESSION_ID'))
    {
        $SESSION_SSL_ID = $ssl_session_id;
        tep_session_register('SESSION_SSL_ID');
    }

    if($SESSION_SSL_ID != $ssl_session_id)
    {
        tep_session_destroy();
        tep_redirect(tep_href_link(FILENAME_SSL_CHECK));
    }
}

// verify the browser user agent if the feature is enabled
if(SESSION_CHECK_USER_AGENT == 'True')
{
    $http_user_agent = getenv('HTTP_USER_AGENT');
    if(!tep_session_is_registered('SESSION_USER_AGENT'))
    {
        $SESSION_USER_AGENT = $http_user_agent;
        tep_session_register('SESSION_USER_AGENT');
    }

    if($SESSION_USER_AGENT != $http_user_agent)
    {
        tep_session_destroy();
        tep_redirect(tep_href_link(FILENAME_LOGIN));
    }
}

// create the shopping cart & fix the cart if necesary
if(tep_session_is_registered('cart') && is_object($cart))
{
    
}
else
{
    tep_session_register('cart');
    $cart = new shoppingCart;
}

// include currencies class and create an instance
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

// Язык
$lng = new language();

// ЧПУ
$rewrite = \EShopmakers\Http\Rewrite::getInstance();
if($_SERVER['PHP_SELF'] !== DIR_WS_CATALOG . FILENAME_FORBIDDEN && $_SERVER['PHP_SELF'] !== DIR_WS_CATALOG . FILENAME_NOT_FOUND)
{
    if(isset($_SERVER['REDIRECT_STATUS']))
    {
        list($include_file, $_GET) = $rewrite->parse(($_SERVER['SERVER_PORT'] === 443 ? HTTPS_SERVER : HTTP_SERVER) . $_SERVER['REQUEST_URI']);
        $PHP_SELF = $include_file;
        // Если файл не существует, то показываем страницу с ошибкой 404
        if(!file_exists($include_file))
        {
            $include_file = FILENAME_NOT_FOUND;
        }
        if(isset($_GET['language']) && tep_not_null($_GET['language']))
        {
            $lng->set_language($_GET['language']);
        }
    }
    else
    {
        $rewrite->parse(($_SERVER['SERVER_PORT'] === 443 ? HTTPS_SERVER : HTTP_SERVER) . $_SERVER['REQUEST_URI']);
    }
}

$language = $_SESSION['language'] = $lng->language['directory'];
$languages_id = $_SESSION['languages_id'] = $lng->language['id'];

// include the language translations
require(DIR_WS_LANGUAGES . $language . '.php');

// currency
if(!tep_session_is_registered('currency') || isset($_GET['currency']) || ( (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency) ))
{
    if(!tep_session_is_registered('currency'))
        tep_session_register('currency');

    if(isset($_GET['currency']))
    {
        if(!$currency = tep_currency_exists($_GET['currency']))
            $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    } else
    {
        $currency = (USE_DEFAULT_LANGUAGE_CURRENCY == 'true') ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
    }
}


// BOF: Down for Maintenance except for admin ip
if(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE != getenv('REMOTE_ADDR'))
{
    if(DOWN_FOR_MAINTENANCE == 'true' and ! strstr($PHP_SELF, DOWN_FOR_MAINTENANCE_FILENAME))
    {
        tep_redirect(tep_href_link(DOWN_FOR_MAINTENANCE_FILENAME));
    }
}
// do not let people get to down for maintenance page if not turned on
if(DOWN_FOR_MAINTENANCE == 'false' and strstr($PHP_SELF, DOWN_FOR_MAINTENANCE_FILENAME))
{
    tep_redirect(tep_href_link(FILENAME_DEFAULT));
}
// EOF: WebMakers.com Added: Down for Maintenance
// wishlist data
$wishList = wishlist::getInstance();

// initialize the message stack for output messages
include_once DIR_WS_CLASSES . 'message_stack.php';
$messageStack = messageStack::getInstance();

// Shopping cart actions
if(!tep_session_is_registered('compares'))
    tep_session_register('compares');
if(isset($_GET['action']) and $_POST['gv_redeem_code'] == '')
{
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
    if($session_started == false)
    {
        tep_redirect(tep_href_link(FILENAME_COOKIE_USAGE));
    }

    if(DISPLAY_CART == 'true')
    {
        $goto = FILENAME_SHOPPING_CART;
        $parameters = array('action', 'cPath', 'products_id', 'pid');
    }
    else
    {
        $goto = basename($PHP_SELF);
        if($_GET['action'] == 'buy_now')
        {
            $parameters = array('action', 'pid', 'products_id');
        }
        else
        {
            $parameters = array('action', 'pid');
        }
    }


    switch($_GET['action'])
    {
        // customer wants to update the product quantity in their shopping cart
        case 'update_product' :
            for($i = 0; $i < sizeof($_POST['products_id']); $i++)
            {

                $r_order_units = tep_get_products_quantity_order_units($_POST['products_id'][$i]);
                $r_order_min = tep_get_products_quantity_order_min($_POST['products_id'][$i]);
                if($r_order_units == 0)
                    $r_order_units = 1;
                if($r_order_min == 0)
                    $r_order_min = 1;

                if(in_array($_POST['products_id'][$i], (is_array($_POST['cart_delete']) ? $_POST['cart_delete'] : array())))
                {
                    $cart->remove($_POST['products_id'][$i]);
                    if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
                    {
                        // возвращаем json информацию после запроса
                        //echo Response::json(array('status'=>'success','cart_btn'=>IMAGE_BUTTON_IN_CART));
                    }
                }
                else
                {
                    $attributes = ($_POST['id'][$_POST['products_id'][$i]]) ? $_POST['id'][$_POST['products_id'][$i]] : '';
                    if(($_POST['cart_quantity'][$i] >= $r_order_min))
                    {
                        if(($_POST['cart_quantity'][$i] % $r_order_units == 0))
                        {
                            $cart->add_cart($_POST['products_id'][$i], $_POST['cart_quantity'][$i], $attributes, false);
                        }
                        else
                        {
                            messageStack::getInstance()->add(trim($error_cart_msg) . '<br>' . trim(tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '11', '10') . ERROR_PRODUCTS_QUANTITY_ORDER_UNITS_TEXT . ' ' . tep_get_products_name($_POST['products_id'][$i]) . ' - ' . ERROR_PRODUCTS_UNITS_INVALID . ' ' . $_POST['cart_quantity'][$i] . ' - ' . PRODUCTS_ORDER_QTY_UNIT_TEXT_CART . ' ' . $r_order_units));
                        }
                    }
                    else
                    {
                        messageStack::getInstance()->add(trim($error_cart_msg) . '<br>' . trim(tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '11', '10') . ERROR_PRODUCTS_QUANTITY_ORDER_MIN_TEXT . ' ' . tep_get_products_name($_POST['products_id'][$i]) . ' - ' . ERROR_PRODUCTS_QUANTITY_INVALID . ' ' . $_POST['cart_quantity'][$i] . ' - ' . PRODUCTS_ORDER_QTY_MIN_TEXT_CART . ' ' . $r_order_min));
                    }
                }
            }
            tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters), 'NONSSL'));
            break;

        // customer adds a product from the products page
        case 'add_product' :
            if(preg_match('/^[0-9]+$/', $_POST['products_id']))
            {

                $r_order_min = tep_get_products_quantity_order_min($_POST['products_id']);
                $r_order_units = tep_get_products_quantity_order_units($_POST['products_id']);
                if($r_order_min == 0)
                {
                    $r_order_min = 1;
                }
                if($r_order_units == 0)
                {
                    $r_order_units = 1;
                }

                if(($_POST['cart_quantity'] >= $r_order_min) or ( $cart->get_quantity(tep_get_uprid($_POST['products_id'], $_POST['id'])) >= $r_order_min ))
                {
                    if($_POST['cart_quantity'] % $r_order_units == 0 and $cart->get_quantity(tep_get_uprid($_POST['products_id'], $_POST['id'])) + ($_POST['cart_quantity']) >= $r_order_min)
                    {
                        $cart->add_cart($_POST['products_id'], $cart->get_quantity(tep_get_uprid($_POST['products_id'], $_POST['id'])) + ($_POST['cart_quantity']), $_POST['id']);
                    }
                    else
                    {
                        $r_mult = ceil($_POST['cart_quantity'] / $r_order_units);   // округляем до бОльшего количества
                        $_POST['cart_quantity'] = $r_mult * $r_order_units;
                        $cart->add_cart($_POST['products_id'], $cart->get_quantity(tep_get_uprid($_POST['products_id'], $_POST['id'])) + ($_POST['cart_quantity']), $_POST['id']);
                        //    $error_cart_msg=ERROR_PRODUCTS_QUANTITY_ORDER_UNITS_TEXT . ERROR_PRODUCTS_UNITS_INVALID . $_POST['cart_quantity']  . ' - ' . PRODUCTS_ORDER_QTY_UNIT_TEXT_INFO . ' ' . $r_order_units;
                    }
                }
                else
                {
                    messageStack::getInstance()->add(ERROR_PRODUCTS_QUANTITY_ORDER_MIN_TEXT . ERROR_PRODUCTS_QUANTITY_INVALID . $_POST['cart_quantity'] . ' - ' . PRODUCTS_ORDER_QTY_MIN_TEXT_INFO . ' ' . $r_order_min);
                }
            }
            // raid - если ajax-добавление в корзину то выводим бокс корзины и останавливаем скрипт
            if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
            {
                // возвращаем json информацию после запроса
                ob_start();

                require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/shopping_cart.php');
                $content = ob_get_contents();
                ob_end_clean();

                echo Response::json(array(
                    'status' => 'success',
                    'cart_btn' => IMAGE_BUTTON_IN_CART,
                    'shopping_cart' => $content,
                    'products_id' => $_POST['products_id']
                ));
                // require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/shopping_cart.php');
                exit();
            }
            break;
    }
}


//  require(DIR_WS_FUNCTIONS . 'whos_online.php');  // include the who's online functions
//  tep_update_whos_online();


require(DIR_WS_FUNCTIONS . 'password_funcs.php'); // include the password crypto functions
require(DIR_WS_FUNCTIONS . 'validations.php'); // include validation functions (right now only email address)
require(DIR_WS_CLASSES . 'split_page_results.php'); // split-page-results
require(DIR_WS_CLASSES . 'breadcrumb.php'); // include the breadcrumb class and start the breadcrumb trail
// calculate category path

if(isset($_GET['cPath']))
{
    // raid 23.10.2012
    $cPath = urldecode($_GET['cPath']);
    $cPath = preg_replace('/[^0-9,_]/i', '', $cPath);
}
elseif(isset($_GET['products_id']) && !isset($_GET['manufacturers_id']))
{
    $cPath = tep_get_product_path($_GET['products_id']);
}
else
{
    $cPath = '';
}

if(tep_not_null($cPath))
{
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array) - 1)];
}
else
{
    $current_category_id = 0;
}

//////////////////////////////////////////////////////////////
if(($_GET['currency']))
{
    tep_session_register('kill_sid');
    $kill_sid = false;
}
if(basename($_SERVER['HTTP_REFERER']) == 'allprods.php')
    $kill_sid = true;
if((!tep_session_is_registered('customer_id') ) && ( $cart->count_contents() == 0 ) && (!tep_session_is_registered('kill_sid') ))
    $kill_sid = true;
if((basename($PHP_SELF) == FILENAME_LOGIN) && ($_GET['action'] == 'process'))
    $kill_sid = false;
if(basename($PHP_SELF) == FILENAME_CREATE_ACCOUNT_PROCESS)
    $kill_sid = false;
// Uncomment line bellow to disable SID Killer
// $kill_sid = false;
//////////////////////////////////////////////////////////////

$breadcrumb = new breadcrumb;

$breadcrumb->add(HEADER_TITLE_TOP, HTTP_SERVER);
if(isset($cPath_array))
{

    for($i = 0, $n = sizeof($cPath_array); $i < $n; $i++)
    {
        $categories_query = tep_db_query("select categories_id, categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . (int) $cPath_array[$i] . "' and language_id = '" . (int) $languages_id . "'");
        if(tep_db_num_rows($categories_query) > 0)
        {
            $categories = tep_db_fetch_array($categories_query);
            $breadcrumb->add($categories['categories_name'], tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i + 1)))));
        }
        else
        {
            break;
        }
    }
}
elseif(isset($_GET['manufacturers_id']))
{
    $manufacturers_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int) $_GET['manufacturers_id'] . "'");
    if(tep_db_num_rows($manufacturers_query))
    {
        $manufacturers = tep_db_fetch_array($manufacturers_query);
        $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $_GET['manufacturers_id']));
    }
}
// add the products model to the breadcrumb trail
if(isset($_GET['products_id']))
{
    $model_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int) $_GET['products_id'] . "' and language_id = '" . (int) $languages_id . "'");
    if(tep_db_num_rows($model_query))
    {
        $model = tep_db_fetch_array($model_query);
        $breadcrumb->add($model['products_name'], tep_href_link(FILENAME_PRODUCT_INFO, 'cPath=' . $cPath . '&products_id=' . $_GET['products_id']));
        //$breadcrumb->add($model['products_name']);
    }
}

// set which precautions should be checked
define('WARN_INSTALL_EXISTENCE', 'true');
define('WARN_CONFIG_WRITEABLE', 'true');
define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');
define('WARN_SESSION_AUTO_START', 'true');
define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');

// HMCS: Begin Autologon	******************************************************************

if(ALLOW_AUTOLOGON == 'true')
{                                // Is Autologon enabled?
    if(basename($PHP_SELF) != FILENAME_LOGIN)
    {                  // yes
        if(!tep_session_is_registered('customer_id'))
        {
            include('includes/modules/autologon.php');
        }
    }
}
else
{
    setcookie("email_address", "", time() - 3600, $cookie_path);  //no, delete email_address cookie
    setcookie("password", "", time() - 3600, $cookie_path);       //no, delete password cookie
}

// HMCS: End Autologon		******************************************************************
// Include OSC-AFFILIATE

require(DIR_WS_INCLUDES . 'add_ccgvdc_application_top.php');

//include('includes/application_top_support.php');
include('includes/application_top_newsdesk.php');
include('includes/application_top_faqdesk.php');

// BOF: WebMakers.com Added: Header Tags Controller v1.0
require(DIR_WS_FUNCTIONS . 'header_tags.php');
// Clean out HTML comments from ALT tags etc.
require(DIR_WS_FUNCTIONS . 'clean_html_comments.php');
// Also used by: WebMakers.com Added: FREE-CALL FOR PRICE
// EOF: WebMakers.com Added: Header Tags Controller v1.0
// BOF: WebMakers.com Added: Downloads Controller
require(DIR_WS_FUNCTIONS . 'downloads_controller.php');
// EOF: WebMakers.com Added: Downloads Controller
// +Country-State Selector
define('DEFAULT_COUNTRY', STORE_COUNTRY);
// -Country-State Selector
// adapted for Total B2B Contributions start
//Minimum group price to order
// min price
$min_price_query = tep_db_query("select g.customers_groups_price, g.customers_groups_min_price from " . TABLE_CUSTOMERS_GROUPS . " g inner join  " . TABLE_CUSTOMERS . " c on g.customers_groups_id = c.customers_groups_id and c.customers_id = '" . $customer_id . "'");
$min_price = tep_db_fetch_array($min_price_query);
$customers_groups_min_price = $min_price['customers_groups_min_price'];
$customer_price = 'products_price_' . $min_price['customers_groups_price'];
if($min_price['customers_groups_price'] == 1 or ! $customer_id)
{
    $customer_price = 'products_price';
}

// define the minimum order
define('MIN_ORDER_B2B', $customers_groups_min_price);
//Minimum group price to order
//  adapted for Total B2B Contributions end

if(AUTH_MODULE_ENABLED == 'true')
{
    $fb_app_id = "917984764926249";
    $fb_app_secret = "1278056674253519ae74f0f7128fd5fd";
    $fb_url = HTTP_SERVER . "/loginfb.php";
    $fb_state = 'Easy3a';

    $vk_app_id = '5013535';
    $vk_app_secret = '5n2MDEnCnEQM0ul281OX';
    $vk_url = HTTP_COOKIE_DOMAIN . '/loginvk.php';
}

$tmp = ltrim($_SERVER['PHP_SELF'], '/');
if(!empty($include_file) && $include_file !== $tmp)
{
    require $include_file;
    tep_exit();
}