<?php

/*
  $Id: application_top.php,v 1.2 2003/09/24 13:57:07 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

// Start the clock for the page parse time log
define('PAGE_PARSE_START_TIME', microtime());

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
        }
    }

    // Сброс буфера вывода
    while(ob_get_level() > 0)
    {
        ob_end_flush();
    }
});

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

// Set the level of error reporting
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

// Check if register_globals is enabled.
// Since this is a temporary measure this message is hardcoded. The requirement will be removed before 2.2 is finalized.
/* if (function_exists('ini_get')) {
  ini_get('register_globals') or exit('FATAL ERROR: register_globals is disabled in php.ini, please enable it!');
  } */

// Composer autoloader
include __DIR__ . '/../../includes/vendor/autoload.php';

// Set the local configuration parameters - mainly for developers
if(file_exists('includes/local/configure.php'))
    include('includes/local/configure.php');

// Include application configuration parameters
require('includes/configure.php');
if(stristr($_SERVER['REQUEST_URI'], '.php/login'))
{
    die();
}
// Define the project version
define('PROJECT_VERSION', 'SMosc1.1');

// set php_self in the local scope
$PHP_SELF = (isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);

// Used in the "Backup Manager" to compress backups
define('LOCAL_EXE_GZIP', '/usr/bin/gzip');
define('LOCAL_EXE_GUNZIP', '/usr/bin/gunzip');
define('LOCAL_EXE_ZIP', '/usr/local/bin/zip');
define('LOCAL_EXE_UNZIP', '/usr/local/bin/unzip');

// include the list of project filenames
require(DIR_WS_INCLUDES . 'filenames.php');

// include the list of project database tables
require(DIR_WS_INCLUDES . 'database_tables.php');

//     define('BOX_WIDTH', 125); // how wide the boxes should be in pixels (default: 125)
define('MENU_DHTML', true);

// Define how do we update currency exchange rates
// Possible values are 'oanda' 'xe' or ''
define('CURRENCY_SERVER_PRIMARY', 'cbr');
define('CURRENCY_SERVER_BACKUP', 'xe');

// include the database functions
require(DIR_WS_FUNCTIONS . 'database.php');

// make a connection to the database... now
tep_db_connect() or die('Unable to connect to database server!');

// set application wide parameters
$configuration_query = tep_db_query('select configuration_key as cfgKey, configuration_value as cfgValue from ' . TABLE_CONFIGURATION);
while($configuration = tep_db_fetch_array($configuration_query))
{
    define($configuration['cfgKey'], $configuration['cfgValue']);
}
// set application wide parameters
// Configuration Cache modification start
//  require ('includes/configuration_cache_read.php');
// Configuration Cache modification end


if(MENU_DHTML == 'true')
    define('BOX_WIDTH', 0);
else
    define('BOX_WIDTH', 125);

// define our general functions used application-wide
require(DIR_WS_FUNCTIONS . 'general.php');
require(DIR_WS_FUNCTIONS . 'html_output.php');
//Admin begin
require(DIR_WS_FUNCTIONS . 'password_funcs.php');
//Admin end
// initialize the logger class
require(DIR_WS_CLASSES . 'logger.php');

// include shopping cart class
require(DIR_WS_CLASSES . 'shopping_cart.php');

// some code to solve compatibility issues
require(DIR_WS_FUNCTIONS . 'compatibility.php');

// check to see if php implemented session management functions - if not, include php3/php4 compatible session class
if(!function_exists('session_start'))
{
    define('PHP_SESSION_NAME', 'osCAdminID');
    define('PHP_SESSION_PATH', '/');
    define('PHP_SESSION_SAVE_PATH', SESSION_WRITE_DIRECTORY);

    include(DIR_WS_CLASSES . 'sessions.php');
}

// define how the session functions will be used
require(DIR_WS_FUNCTIONS . 'sessions.php');

// set the session name and save path
tep_session_name('osCAdminID');
tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
if(function_exists('session_set_cookie_params'))
{
    session_set_cookie_params(0, DIR_WS_ADMIN);
}
elseif(function_exists('ini_set'))
{
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.cookie_path', DIR_WS_ADMIN);
}

// lets start our session
tep_session_start();

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

// set the language
if(empty($_SESSION['language']) || empty($_SESSION['languages_id']) || isset($_GET['language']))
{
    if(!tep_session_is_registered('language'))
    {
        tep_session_register('language');
        tep_session_register('languages_id');
    }

    include(DIR_WS_CLASSES . 'language.php');
    $lng = new language();

    if(isset($_GET['language']) && tep_not_null($_GET['language']))
    {
        $lng->set_language($_GET['language']);
    }
    else
    {
        $lng->get_browser_language();
    }

    $language = $_SESSION['language'] = $lng->language['directory'];
    $languages_id = $_SESSION['languages_id'] = $lng->language['id'];
}
else
{
    $language = $_SESSION['language'];
    $languages_id = $_SESSION['languages_id'];
}


// include the language translations
require(DIR_WS_LANGUAGES . $language . '.php');
$current_page = basename($_SERVER['SCRIPT_NAME']);
if(file_exists(DIR_WS_LANGUAGES . $language . '/' . $current_page))
{
    include(DIR_WS_LANGUAGES . $language . '/' . $current_page);
}

// define our localization functions
require(DIR_WS_FUNCTIONS . 'localization.php');

// Include validation functions (right now only email address)
require(DIR_WS_FUNCTIONS . 'validations.php');

// setup our boxes
require(DIR_WS_CLASSES . 'table_block.php');
require(DIR_WS_CLASSES . 'box.php');

// initialize the message stack for output messages
require(DIR_WS_CLASSES . 'message_stack.php');
$messageStack = new messageStack;

// split-page-results
require(DIR_WS_CLASSES . 'split_page_results.php');

// entry/item info classes
require(DIR_WS_CLASSES . 'object_info.php');

// email classes
require(DIR_WS_CLASSES . 'mime.php');
require(DIR_WS_CLASSES . 'email.php');

// file uploading class
require(DIR_WS_CLASSES . 'upload.php');

// calculate category path
if(isset($_GET['cPath']))
{
    $cPath = $_GET['cPath'];
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

// default open navigation box
if(!tep_session_is_registered('selected_box'))
{
    tep_session_register('selected_box');
    $selected_box = 'configuration';
}

if(isset($_GET['selected_box']))
{
    $selected_box = $_GET['selected_box'];
}

// the following cache blocks are used in the Tools->Cache section
// ('language' in the filename is automatically replaced by available languages)
$cache_blocks = array(array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories', 'file' => 'categories_box-language.cache', 'multiple' => true),
    array('title' => TEXT_CACHE_MANUFACTURERS, 'code' => 'manufacturers', 'file' => 'manufacturers_box-language.cache', 'multiple' => true),
    array('title' => TEXT_CACHE_ALSO_PURCHASED, 'code' => 'also_purchased', 'file' => 'also_purchased-language.cache', 'multiple' => true)
);

// check if a default currency is set
if(!defined('DEFAULT_CURRENCY'))
{
    $messageStack->add(ERROR_NO_DEFAULT_CURRENCY_DEFINED, 'error');
}

// check if a default language is set
if(!defined('DEFAULT_LANGUAGE'))
{
    $messageStack->add(ERROR_NO_DEFAULT_LANGUAGE_DEFINED, 'error');
}

if(function_exists('ini_get') && ((bool) ini_get('file_uploads') == false))
{
    $messageStack->add(WARNING_FILE_UPLOADS_DISABLED, 'warning');
}
//Admin begin
if(basename($PHP_SELF) != FILENAME_LOGIN && basename($PHP_SELF) != FILENAME_PASSWORD_FORGOTTEN)
{
    tep_admin_check_login();
}
//Admin end
// Include OSC-AFFILIATE
// require('includes/affiliate_application_top.php');
// include giftvoucher
REQUIRE(DIR_WS_INCLUDES . 'add_ccgvdc_application_top.php');

// WebMakers.com Added: Includes Functions for Attribute Sorter and Copier
require(DIR_WS_FUNCTIONS . 'webmakers_added_functions.php');


//include('includes/application_top_support.php');
include('includes/application_top_newsdesk.php');
include('includes/application_top_faqdesk.php');

// include the articles functions
require(DIR_WS_FUNCTIONS . 'articles.php');

define('FILENAME_POLLS', 'polls.php');

// entry/item info classes
require(DIR_WS_CLASSES . 'poll_info.php');
require(DIR_WS_CLASSES . 'configuration_info.php');
//BEGIN Added Lines: Dynamic Information pages
// calculate information path
$cPath = $_GET['cPath'];
if(strlen($cPath) > 0)
{
    $cPath_array = explode('_', $cPath);
    $current_infopage_id = $cPath_array[(sizeof($cPath_array) - 1)];
}
else
{
    $current_infopage_id = 0;
}

//END Added Lines: Dynamic Information pages
// Article Manager
if(isset($_GET['tPath']))
{
    $tPath = $_GET['tPath'];
}
else
{
    $tPath = '';
}

if(tep_not_null($tPath))
{
    $tPath_array = tep_parse_topic_path($tPath);
    $tPath = implode('_', $tPath_array);
    $current_topic_id = $tPath_array[(sizeof($tPath_array) - 1)];
}
else
{
    $current_topic_id = 0;
}
