<?php

/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)

require($_SERVER['DOCUMENT_ROOT'] . '/includes/sm_config.php');

define('SERVER_DOMAIN', empty($_SERVER['SERVER_NAME']) ? '' : $_SERVER['SERVER_NAME']);
define('SERVER_HTTPS', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443);

define('HTTP_SERVER', SERVER_DOMAIN ? (SERVER_HTTPS ? 'https://' : 'http://') . SERVER_DOMAIN : ''); // eg, http://localhost - should not be empty for productive servers
define('HTTPS_SERVER', HTTP_SERVER); // eg, https://localhost - should not be empty for productive servers
define('ENABLE_SSL', false); // secure webserver for checkout procedure?
define('HTTP_COOKIE_DOMAIN', SERVER_DOMAIN);
define('HTTPS_COOKIE_DOMAIN', HTTP_COOKIE_DOMAIN);
define('HTTP_COOKIE_PATH', '/');
define('HTTPS_COOKIE_PATH', HTTP_COOKIE_PATH);
define('DIR_WS_HTTP_CATALOG', '/');
define('DIR_WS_HTTPS_CATALOG', DIR_WS_HTTP_CATALOG);
define('DIR_WS_IMAGES', 'images/');
define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
define('DIR_WS_INCLUDES', 'includes/');
define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');

//Added for BTS1.0
define('DIR_WS_TEMPLATES', 'templates/');
define('DIR_WS_CONTENT', DIR_WS_TEMPLATES . 'content/');
define('DIR_WS_JAVASCRIPT', DIR_WS_INCLUDES . 'javascript/');
//End BTS1.0

define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
define('DIR_FS_CATALOG', $path);
define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

// define our database connection
define('DB_SERVER', $server); // eg, localhost - should not be empty for productive servers
define('DB_SERVER_USERNAME', $db_user);
define('DB_SERVER_PASSWORD', $db_pass);
define('DB_DATABASE', $db_name);
define('USE_PCONNECT', 'false'); // use persistent connections?
define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
define('WRITE_DB_QUERIES_TO_LOG', false);