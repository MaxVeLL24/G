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

require __DIR__ . '/../../includes/sm_config.php';

define('SERVER_DOMAIN', empty($_SERVER['SERVER_NAME']) ? '' : $_SERVER['SERVER_NAME']);
define('SERVER_HTTPS', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443);

define('HTTP_SERVER', SERVER_DOMAIN ? (SERVER_HTTPS ? 'https://' : 'http://') . SERVER_DOMAIN : ''); // eg, http://localhost - should not be empty for productive servers
define('HTTP_CATALOG_SERVER', HTTP_SERVER);
define('HTTPS_CATALOG_SERVER', HTTP_CATALOG_SERVER);
define('ENABLE_SSL_CATALOG', 'false'); // secure webserver for catalog module
define('DIR_FS_DOCUMENT_ROOT', $path); // where the pages are located on the server
define('DIR_WS_ADMIN', '/' . $admin . '/'); // absolute path required
define('DIR_FS_ADMIN', $path . DIR_WS_ADMIN); // absolute pate required
define('DIR_WS_CATALOG', '/'); // absolute path required
define('DIR_FS_CATALOG', $path); // absolute path required
define('DIR_WS_IMAGES', 'images/');
define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
define('DIR_WS_CATALOG_ARTICLES_IMAGES', DIR_WS_CATALOG . 'articles_images/');
define('DIR_WS_INCLUDES', 'includes/');
define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
define('DIR_FS_CATALOG_ARTICLES_IMAGES', DIR_FS_CATALOG . 'articles_images');
define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');

// Added for Templating
define('DIR_FS_CATALOG_MAINPAGE_MODULES', DIR_FS_CATALOG_MODULES . 'mainpage_modules/');
define('DIR_WS_TEMPLATES', DIR_WS_CATALOG . 'templates/');
define('DIR_FS_TEMPLATES', DIR_FS_CATALOG . 'templates/');

// define our database connection
define('DB_SERVER', $server); // eg, localhost - should not be empty for productive servers
define('DB_SERVER_USERNAME', $db_user);
define('DB_SERVER_PASSWORD', $db_pass);
define('DB_DATABASE', $db_name);
define('USE_PCONNECT', 'false'); // use persisstent connections?
define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'