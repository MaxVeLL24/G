<?php
/*
  $Id: english.php,v 1.3 2003/09/28 23:37:26 anotherlango Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// Google SiteMaps
define('BOX_TOOLS_COMMENT8R', 'Comments');
define('BOX_GOOGLE_SITEMAP', 'Google SiteMaps');
define('HEADING_SORT', 'Sorting');

define('LABEL_TOP', 'TOP');
define('LABEL_NEW', 'NEW');
define('LABEL_SALE', 'Sale');

//Fine

define('TABLE_HEADING_FIRSTNAME', 'Name');
define('TABLE_HEADING_LASTNAME', 'Lastname');

define('TABLE_HEADING_PRODUCT_NAME', 'Products name');
define('TABLE_HEADING_PRODUCT_PRICE', 'Price');

  define('TBL_LINK_TITLE', 'Ajax categories');
  define('TBL_HEADING_TITLE_BACK_TO_PARENT', 'Back');
  define('TBL_HEADING_TITLE_SEARCH', 'Search');
  define('TBL_HEADING_CATEGORIES_PRODUCTS', 'Categories/Products');
  define('TBL_HEADING_MODEL', 'Model');
  define('TBL_HEADING_QUANTITY', 'Quantity');
  define('TBL_HEADING_PRICE', 'Price');
  define('TBL_HEADING_TITLE_BACK_TO_DEFAULT_ADMIN', 'Back To Default Administration');
  define('TBL_HEADING_PRODUCTS_COUNT', ' products');
  define('TBL_HEADING_PRODUCTS_COUNT', ' product');
  define('TBL_HEADING_SUBCATEGORIES_COUNT', ' subcategories');
  define('TBL_HEADING_SUBCATEGORIE_COUNT', ' subcategory');
//Admin begin
// header text in includes/header.php
define('HEADER_TITLE_ACCOUNT', 'My Account');
define('HEADER_TITLE_LOGOFF', 'Logoff');

// Admin Account
define('BOX_HEADING_MY_ACCOUNT', 'My Account');

// configuration box text in includes/boxes/administrator.php
define('BOX_HEADING_ADMINISTRATOR', 'Administrator');
define('BOX_ADMINISTRATOR_MEMBERS', 'Member Groups');
define('BOX_ADMINISTRATOR_MEMBER', 'Members');
define('BOX_ADMINISTRATOR_BOXES', 'File Access');
define('BOX_ADMINISTRATOR_ACCOUNT_UPDATE', 'Update Account');


  // limex: mod query performance START
  define('TEXT_DISPLAY_NUMBER_OF_QUERIES', 'Displayed <b>%d</b> - <b>%d</b> (from <b>%d</b> queries)');
  define('BOX_TOOLS_MYSQL_PERFORMANCE', 'Slow queries');
  define('TEXT_DELETE','Remove all entries?');
  define('IMAGE_BUTTON_DELETE','Remove all entries');
  define('IMAGE_BUTTON_CANCEL','Dont remove all entries');
  // limex: mod query performance END


//mod for ez price updater
define('BOX_CATALOG_PRICE_QUICK_UPDATES', 'Quick price change');
define('BOX_CATALOG_PRICE_UPDATE_VISIBLE', 'The apparent change in price');
define('BOX_CATALOG_PRICE_UPDATE__ALL', 'change all prices');
define('BOX_CATALOG_PRICE_CANGE', 'Change price');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_MULTI', 'Manage products');

define('TEXT_INDEX_LANGUAGE','Language: ');
define('TEXT_SUMMARY_CUSTOMERS','Customers');
define('TEXT_SUMMARY_ORDERS','Orders');
define('TEXT_SUMMARY_PRODUCTS','Products');
define('TEXT_SUMMARY_HELP','Help');
define('TEXT_SUMMARY_STAT','Statistics');

// images
define('IMAGE_FILE_PERMISSION', 'File Permission');
define('IMAGE_GROUPS', 'Groups List');
define('IMAGE_INSERT_FILE', 'Insert File');
define('IMAGE_MEMBERS', 'Members List');
define('IMAGE_NEW_GROUP', 'New Group');
define('IMAGE_NEW_MEMBER', 'New Member');
define('IMAGE_NEXT', 'Next');

define('ONE_PAGE_CHECKOUT_TITLE', 'Checkout');
define('BROWSE_BY_CATEGORIES_TITLE', 'Browse by categories');
define('SEO_TITLE', 'SEO URLs');

// constants for use in tep_prev_next_display function
define('TEXT_DISPLAY_NUMBER_OF_FILENAMES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> filenames)');
define('TEXT_DISPLAY_NUMBER_OF_MEMBERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> members)');
//Admin end

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'en_US'
// on FreeBSD 4.0 I use 'en_US.ISO_8859-1'
// this may not work under win32 environments..
setlocale(LC_TIME, 'en_US.UTF8');
define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
//define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT_LONG', '%d %B %Y'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('PHP_DATE_TIME_FORMAT', 'm/d/Y H:i:s'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('DATE_FORMAT_SPIFFYCAL', 'MM/dd/yyyy');  //Use only 'dd', 'MM' and 'yyyy' here in any order


////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
  }
}

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="en"');

// charset for web pages and emails
define('CHARSET', 'UTF-8');

// page title
define('TITLE', 'osCommerce');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Admin');
define('HEADER_TITLE_SUPPORT_SITE', 'osCommerce');
define('HEADER_TITLE_ONLINE_CATALOG', 'Catalog');
define('HEADER_TITLE_ADMINISTRATION', 'Admin');
define('HEADER_TITLE_CHAINREACTION', 'Chainreactionweb');
define('HEADER_TITLE_PHESIS', 'PHESIS Loaded6');
// MaxiDVD Added Line For WYSIWYG HTML Area: BOF
define('BOX_CATALOG_DEFINE_MAINPAGE', 'Define MainPage');
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF


// text for gender
define('MALE', 'Male');
define('FEMALE', 'Female');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Configuration');
define('BOX_CONFIGURATION_MYSTORE', 'My Store');
define('BOX_CONFIGURATION_LOGGING', 'Logging');
define('BOX_CONFIGURATION_CACHE', 'Cache');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Modules');
define('BOX_MODULES_PAYMENT', 'Payment');
define('BOX_MODULES_SHIPPING', 'Shipping');
define('BOX_MODULES_ORDER_TOTAL', 'Order Total');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Catalog');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categories/Products');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Attributes - Add values');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES_NEW', 'Attributes - Set values');
define('BOX_CATALOG_MANUFACTURERS', 'Manufacturers');
define('BOX_CATALOG_REVIEWS', 'Reviews');
define('BOX_CATALOG_SPECIALS', 'Specials');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Products Expected');
define('BOX_CATALOG_EASYPOPULATE', 'Products import/export');
define('BOX_CATALOG_PARSER', 'Parser prices Rozetka.ua');

define('BOX_CATALOG_SALEMAKER', 'SaleMaker');

// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Customers/Orders');
define('BOX_CUSTOMERS_CUSTOMERS', 'Customers');
define('BOX_CUSTOMERS_ORDERS', 'Orders');
define('BOX_CUSTOMERS_EDIT_ORDERS', 'Edit Orders');
define('BOX_CUSTOMERS_ENTRY', 'Number of Enries');


// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Locations / Taxes');
define('BOX_TAXES_COUNTRIES', 'Countries');
define('BOX_TAXES_ZONES', 'Zones');
define('BOX_TAXES_GEO_ZONES', 'Tax Zones');
define('BOX_TAXES_TAX_CLASSES', 'Tax Classes');
define('BOX_TAXES_TAX_RATES', 'Tax Rates');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Reports');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Products Viewed');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Products Purchased');
define('BOX_REPORTS_ORDERS_TOTAL', 'Customer Orders-Total');

// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Tools');
define('BOX_TOOLS_BACKUP', 'Database Backup');
define('BOX_TOOLS_BANNER_MANAGER', 'Banner Manager');
define('BOX_TOOLS_CACHE', 'Cache Control');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Define Languages');
define('BOX_TOOLS_FILE_MANAGER', 'File Manager');
define('BOX_TOOLS_MAIL', 'Send Email');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Newsletter Manager');
define('BOX_TOOLS_SERVER_INFO', 'Server Info');
define('BOX_TOOLS_WHOS_ONLINE', 'Who\'s Online');

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Localization');
define('BOX_LOCALIZATION_CURRENCIES', 'Currencies');
define('BOX_LOCALIZATION_LANGUAGES', 'Languages');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Orders Status');

// infobox box text in includes/boxes/info_boxes.php
define('BOX_HEADING_BOXES', 'Infobox Admin');
define('BOX_HEADING_TEMPLATE_CONFIGURATION', 'Template Admin');
define('BOX_HEADING_DESIGN_CONTROLS', 'Design controls');

// VJ Links Manager v1.00 begin
// links manager box text in includes/boxes/links.php
define('BOX_HEADING_LINKS', 'Links Manager');
define('BOX_LINKS_LINKS', 'Links');
define('BOX_LINKS_LINK_CATEGORIES', 'Link Categories');
define('BOX_LINKS_LINKS_CONTACT', 'Links Contact');
// VJ Links Manager v1.00 end

// javascript messages
define('JS_ERROR', 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* The new product atribute needs a price value\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* The new product atribute needs a price prefix\n');

define('JS_PRODUCTS_NAME', '* The new product needs a name\n');
define('JS_PRODUCTS_DESCRIPTION', '* The new product needs a description\n');
define('JS_PRODUCTS_PRICE', '* The new product needs a price value\n');
define('JS_PRODUCTS_WEIGHT', '* The new product needs a weight value\n');
define('JS_PRODUCTS_QUANTITY', '* The new product needs a quantity value\n');
define('JS_PRODUCTS_MODEL', '* The new product needs a model value\n');
define('JS_PRODUCTS_IMAGE', '* The new product needs an image value\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* A new price for this product needs to be set\n');

define('JS_GENDER', '* The \'Gender\' value must be chosen.\n');
define('JS_FIRST_NAME', '* The \'First Name\' entry must have at least ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_LAST_NAME', '* The \'Last Name\' entry must have at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_DOB', '* The \'Date of Birth\' entry must be in the format: xx/xx/xxxx (month/date/year).\n');
define('JS_EMAIL_ADDRESS', '* The \'E-Mail Address\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_ADDRESS', '* The \'Street Address\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_POST_CODE', '* The \'Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n');
define('JS_CITY', '* The \'City\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n');
define('JS_STATE', '* The \'State\' entry is must be selected.\n');
define('JS_STATE_SELECT', '-- Select Above --');
define('JS_ZONE', '* The \'State\' entry must be selected from the list for this country.');
define('JS_COUNTRY', '* The \'Country\' value must be chosen.\n');
define('JS_TELEPHONE', '* The \'Telephone Number\' entry must have at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n');
define('JS_PASSWORD', '* The \'Password\' amd \'Confirmation\' entries must match amd have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Order Number %s does not exist!');

define('CATEGORY_PERSONAL', 'Personal');
define('CATEGORY_ADDRESS', 'Address');
define('CATEGORY_CONTACT', 'Contact');
define('CATEGORY_COMPANY', 'Company');
define('CATEGORY_OPTIONS', 'Options');
define('DISCOUNT_OPTIONS', 'Discounts');

define('ENTRY_GENDER', 'Gender:');
define('ENTRY_GENDER_ERROR', '&nbsp;<span class="errorText">required</span>');
define('ENTRY_FIRST_NAME', 'First Name:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' chars</span>');
define('ENTRY_LAST_NAME', 'Last Name:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' chars</span>');
define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(eg. 05/21/1970)</span>');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' chars</span>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">The email address doesn\'t appear to be valid!</span>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<span class="errorText">This email address already exists!</span>');
define('ENTRY_COMPANY', 'Company name:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_STREET_ADDRESS', 'Street Address:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' chars</span>');
define('ENTRY_SUBURB', 'Suburb:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_POST_CODE', 'Post Code:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' chars</span>');
define('ENTRY_CITY', 'City:');
define('ENTRY_CITY_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</span>');
define('ENTRY_STATE', 'State:');
define('ENTRY_STATE_ERROR', '&nbsp;<span class="errorText">required</span>');
define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_COUNTRY_ERROR', '');
define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</span>');
define('ENTRY_FAX_NUMBER', 'Fax Number:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_YES', 'Subscribed');
define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');
define('ENTRY_NEWSLETTER_ERROR', '');

// images
define('IMAGE_ANI_SEND_EMAIL', 'Sending E-Mail');
define('IMAGE_BACK', 'Back');
define('IMAGE_BACKUP', 'Backup');
define('IMAGE_CANCEL', 'Cancel');
define('IMAGE_CONFIRM', 'Confirm');
define('IMAGE_COPY', 'Copy');
define('IMAGE_COPY_TO', 'Copy To');
define('IMAGE_DETAILS', 'Details');
define('IMAGE_DELETE', 'Delete');
define('IMAGE_EDIT', 'Edit');
define('IMAGE_EMAIL', 'Email');
define('IMAGE_FILE_MANAGER', 'File Manager');
define('IMAGE_ICON_STATUS_GREEN', 'Active');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Set Active');
define('IMAGE_ICON_STATUS_RED', 'Inactive');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Set Inactive');
define('IMAGE_ICON_INFO', 'Info');
define('IMAGE_INSERT', 'Insert');
define('IMAGE_LOCK', 'Lock');
define('IMAGE_MODULE_INSTALL', 'Install Module');
define('IMAGE_MODULE_REMOVE', 'Remove Module');
define('IMAGE_MOVE', 'Move');
define('IMAGE_NEW_BANNER', 'New Banner');
define('IMAGE_NEW_CATEGORY', 'New Category');
define('IMAGE_NEW_COUNTRY', 'New Country');
define('IMAGE_NEW_CURRENCY', 'New Currency');
define('IMAGE_NEW_FILE', 'New File');
define('IMAGE_NEW_FOLDER', 'New Folder');
define('IMAGE_NEW_LANGUAGE', 'New Language');
define('IMAGE_NEW_NEWSLETTER', 'New Newsletter');
define('IMAGE_NEW_PRODUCT', 'New Product');
define('IMAGE_NEW_SALE', 'New Sale');
define('IMAGE_NEW_TAX_CLASS', 'New Tax Class');
define('IMAGE_NEW_TAX_RATE', 'New Tax Rate');
define('IMAGE_NEW_TAX_ZONE', 'New Tax Zone');
define('IMAGE_NEW_ZONE', 'New Zone');
define('IMAGE_ORDERS', 'Orders');
define('IMAGE_ORDERS_INVOICE', 'Invoice');
define('IMAGE_ORDERS_PACKINGSLIP', 'Packing Slip');
define('IMAGE_PREVIEW', 'Preview');
define('IMAGE_RESTORE', 'Restore');
define('IMAGE_RESET', 'Reset');
define('IMAGE_SAVE', 'Save');
define('IMAGE_SEARCH', 'Search');
define('IMAGE_SELECT', 'Select');
define('IMAGE_SEND', 'Send');
define('IMAGE_SEND_EMAIL', 'Send Email');
define('IMAGE_UNLOCK', 'Unlock');
define('IMAGE_UPDATE', 'Update');
define('IMAGE_UPDATE_CURRENCIES', 'Update Exchange Rate');
define('IMAGE_UPLOAD', 'Upload');
define('TEXT_IMAGE_NONEXISTENT', 'No image');

define('ICON_CROSS', 'False');
define('ICON_CURRENT_FOLDER', 'Current Folder');
define('ICON_DELETE', 'Delete');
define('ICON_ERROR', 'Error');
define('ICON_FILE', 'File');
define('ICON_FILE_DOWNLOAD', 'Download');
define('ICON_FOLDER', 'Folder');
define('ICON_LOCKED', 'Locked');
define('ICON_PREVIOUS_LEVEL', 'Previous Level');
define('ICON_PREVIEW', 'Preview');
define('ICON_STATISTICS', 'Statistics');
define('ICON_SUCCESS', 'Success');
define('ICON_TICK', 'True');
define('ICON_UNLOCKED', 'Unlocked');
define('ICON_WARNING', 'Warning');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Page %s of %d');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> banners)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> countries)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> customers)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> currencies)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> languages)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> manufacturers)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> newsletters)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders status)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products expected)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> product reviews)');
define('TEXT_DISPLAY_NUMBER_OF_SALES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> sales)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products on special)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax classes)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax zones)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax rates)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> zones)');

define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'default');
define('TEXT_SET_DEFAULT', 'Set as default');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Required</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Error: There is currently no default currency set. Please set one at: Administration Tool->Localization->Currencies');

define('TEXT_CACHE_CATEGORIES', 'Categories Box');
define('TEXT_CACHE_MANUFACTURERS', 'Manufacturers Box');
define('TEXT_CACHE_ALSO_PURCHASED', 'Also Purchased Module');

define('TEXT_NONE', '--none--');
define('TEXT_TOP', 'Top');

define('ERROR_DESTINATION_DOES_NOT_EXIST', 'Error: Destination does not exist.');
define('ERROR_DESTINATION_NOT_WRITEABLE', 'Error: Destination not writeable.');
define('ERROR_FILE_NOT_SAVED', 'Error: File upload not saved.');
define('ERROR_FILETYPE_NOT_ALLOWED', 'Error: File upload type not allowed.');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Success: File upload saved successfully.');
define('WARNING_NO_FILE_UPLOADED', 'Warning: No file uploaded.');
define('WARNING_FILE_UPLOADS_DISABLED', 'Warning: File uploads are disabled in the php.ini configuration file.');

define('TEXT_DISPLAY_NUMBER_OF_PAYPALIPN_TRANSACTIONS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> transactions)'); // PAYPALIPN

define('BOX_HEADING_PAYPALIPN_ADMIN', 'Paypal IPN'); // PAYPALIPN
define('BOX_PAYPALIPN_ADMIN_TRANSACTIONS', 'Transactions'); // PAYPALIPN
define('BOX_PAYPALIPN_ADMIN_TESTS', 'Send Test IPN'); // PAYPALIPN
define('BOX_CATALOG_XSELL_PRODUCTS', 'Cross Sell Products');

define('IMAGE_BUTTON_PRINT_ORDER', 'Order printable');

 // X-Sell
REQUIRE(DIR_WS_LANGUAGES . 'add_ccgvdc_english.php');

// BOF: Lango Added for print order MOD
define('IMAGE_BUTTON_PRINT', 'Print');
// EOF: Lango Added for print order MOD

// BOF: Lango Added for Featured product MOD
  define('BOX_CATALOG_FEATURED', 'Featured Products');
// EOF: Lango Added for Featured product MOD

// BOF: Lango Added for Sales Stats MOD
define('BOX_REPORTS_MONTHLY_SALES', 'Monthly Sales/Tax');
// EOF: Lango Added for Sales Stats MOD

// BOF: Lango Added for template MOD
// WebMakers.com Added: Attribute Sorter, Copier and Catalog additions
require(DIR_WS_LANGUAGES . $language . '/' . 'attributes_sorter.php');

//BEGIN Dynamic information pages unlimited
define('BOX_HEADING_INFORMATION', 'Info pages');
define('BOX_INFORMATION', 'Pages');
//END Dynamic information pages unlimited

	define('BOX_REPORTS_RECOVER_CART_SALES', 'Recover cart sales');
	define('BOX_TOOLS_RECOVER_CART', 'Recover cart sales');

  define('BOX_TOOLS_KEYWORDS', 'Keyword Manager');

// RJW Begin Meta Tags Code
  define('TEXT_META_TITLE', 'Meta Title');
  define('TEXT_META_DESCRIPTION', 'Meta Description');
  define('TEXT_META_KEYWORDS', 'Meta Keywords');
// RJW End Meta Tags Code

// Article Manager
define('BOX_HEADING_ARTICLES', 'Article Manager');
define('BOX_TOPICS_ARTICLES', 'Topics/Articles');
define('BOX_ARTICLES_CONFIG', 'Configuration');
define('BOX_ARTICLES_AUTHORS', 'Authors');
define('BOX_ARTICLES_REVIEWS', 'Reviews');
define('BOX_ARTICLES_XSELL', 'Cross-Sell Articles');
define('IMAGE_NEW_TOPIC', 'New Topic');
define('IMAGE_NEW_ARTICLE', 'New Article');
define('TEXT_DISPLAY_NUMBER_OF_AUTHORS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> authors)');

//TotalB2B start
define('BOX_CUSTOMERS_GROUPS', 'Groups');
define('BOX_MANUDISCOUNT', 'Manu Discount');

// add for Group minimum price to order start
define('GROUP_MIN_PRICE', 'Group min price');
// add for Group minimum price to order end
// add for color groups start
define('GROUP_COLOR_BAR', 'Group Color');
// add for color groups end
//TotalB2B end
define('BOX_CATALOG_QUICK_UPDATES', 'Quick Updates');

define('IMAGE_PROPERTIES_POPUP_ADD_CHANGE_DELETE', 'Add, change, delete Properties');
define('IMAGE_PROPERTIES_POPUP_ADD', 'Add Properties');
define('IMAGE_PROPERTIES', 'Define your Products Properties');

// polls box text in includes/boxes/polls.php

define('BOX_HEADING_POLLS', 'Polls');
define('BOX_POLLS_POLLS', 'Poll Manager');
define('BOX_POLLS_CONFIG','Poll Configuration');
define('BOX_INDEX_GIFTVOUCHERS', 'Gift vouchers / Coupons');

define('BOX_REPORTS_SALES_REPORT2', 'Stats sales 2');
define('BOX_REPORTS_SALES_REPORT', 'Stats sales 3');
define('BOX_REPORTS_CUSTOMERS_ORDERS', 'Customers report');

define('TEXT_NEW_ATTRIBUTE_EDIT', 'Edit productc attributes');

define('MY_SHOP_CONF_TITLE', 'My Store');
define('MIN_VALUES_CONF_TITLE', 'Minimum Values');
define('MAX_VALUES_CONF_TITLE', 'Maximum Values');
define('IMAGES_CONF_TITLE', 'Images');
define('CUSTOMER_DETAILS_CONF_TITLE', 'Customer Details');
define('MODULES_CONF_TITLE', 'Installed Modules');
define('SHIPPING_CONF_TITLE', 'Shipping/Packaging');
define('LISTING_CONF_TITLE', 'Product Listing');
define('STOCK_CONF_TITLE', 'Stock');
define('LOGS_CONF_TITLE', 'Logging');
define('CACHE_CONF_TITLE', 'Cache');
define('EMAIL_CONF_TITLE', 'E-Mail Options');
define('DOWNLOAD_CONF_TITLE', 'Download');
define('GZIP_CONF_TITLE', 'GZip Compression');
define('SESSIONS_CONF_TITLE', 'Sessions');
define('HTML_CONF_TITLE', 'TinyMCE Editor');
define('AFFILIATE_CONF_TITLE', 'Affiliate Program');
define('DYMO_CONF_TITLE', 'Dynamic MoPics');
define('DOWN_CONF_TITLE', 'Site Maintenance');
define('GA_CONF_TITLE', 'Guests');
define('LINKS_CONF_TITLE', 'Links');
define('QUICK_CONF_TITLE', 'Quick Updates');
define('WISHLIST_TITLE', 'Wish List Settings');
define('PAGE_CACHE_TITLE', 'Page cache');
define('YANDEX_MARKET_CONF_TITLE', 'Yandex Market');

define('FAQDESK_LISTING_DB', 'Listing Settings');
define('FAQDESK_SETTINGS_DB', 'Frontpage Settings');
define('FAQDESK_REVIEWS_DB', 'Reviews Settings');
define('FAQDESK_STICKY_DB', 'Sticky Settings');
define('FAQDESK_OTHER_DB', 'Other Settings');

define('NEWSDESK_LISTING_DB', 'Listing Settings');
define('NEWSDESK_SETTINGS_DB', 'Frontpage Settings');
define('NEWSDESK_REVIEWS_DB', 'Reviews Settings');
define('NEWSDESK_STICKY_DB', 'Sticky Settings');

define('ATTRIBUTES_COPY_TEXT1', ' WARNING: Cannot copy from Product ID # ');
define('ATTRIBUTES_COPY_TEXT2', ' to Product ID # ');
define('ATTRIBUTES_COPY_TEXT3', ' ... No copy was made');
define('ATTRIBUTES_COPY_TEXT4', ' WARNING: No Attributes to copy from Product ID # ');
define('ATTRIBUTES_COPY_TEXT5', ' for: ');
define('ATTRIBUTES_COPY_TEXT6', ' ... No copy was made');
define('ATTRIBUTES_COPY_TEXT7', ' WARNING: There is no Product ID # ');
define('ATTRIBUTES_COPY_TEXT8', ' ... No copy was made');

//include('includes/languages/english_support.php');

// BOF FlyOpenair: Extra Product Price
define('BOX_EXTRA_PRODUCT_PRICE', 'Extra Product Price');
define('EXTRA_PRODUCT_PRICE_ID_TITLE', 'Enable Extra Product Price');
define('EXTRA_PRODUCT_PRICE_ID_DESC', 'Enable/Disable Extra Product Price)');
// EOF FlyOpenair: Extra Product Price

define('BOX_TITLE_VAM', 'osCommerce');
define('VAM_LINK_TITLE', 'osCommerce');
define('VAM_LINK_FORUM', 'Support Forum');
define('VAM_LINK_BUGTRACKER', 'Bug Tracker');
define('VAM_LINK_MANUAL', 'User Manual');
define('VAM_LINK_MODULES', 'Modules');
define('VAM_LINK_TEMPLATES', 'Templates');
define('VAM_LINK_SERVICES', 'Services');
define('PHOTOGALLERY', 'Фотогалерея');
define('TEXT_IMAGE_OVERWRITE_WARNING','Внимание: Имя файла было изменено, но не перезаписано ');

// 500 Page )
define('SERVICE_MENU', 'TOOLS');


define('POLLS_MODULE_ENABLED_TITLE', 'Голосование');
define('COMMENTS_MODULE_ENABLED_TITLE', 'Комментарии');
define('PHOTOGALLERY_MODULE_ENABLED_TITLE', 'Фотогалерея');
define('LANGUAGE_SELECTOR_MODULE_ENABLED_TITLE', 'Выбор языка');
define('BETTER_TOGETHER_MODULE_ENABLED_TITLE', 'Скидка на набор товаров');
define('PRODUCT_LABELS_MODULE_ENABLED_TITLE', 'Ярлыки товаров');

define('ARTICLES_MODULE_ENABLED_TITLE', 'Articles');
define('FEATURED_MODULE_ENABLED_TITLE', 'Featured');
define('SALES_MODULE_ENABLED_TITLE', 'Sales system');
define('PRICE_FILTER_MODULE_ENABLED_TITLE', 'Price filter');
define('TOP_VIEWERS_MODULE_ENABLED_TITLE', 'TOP Viewed');
define('RELATED_PRODUCTS_MODULE_ENABLED_TITLE', 'Related products');
define('ATTRIBUTES_PRODUCTS_MODULE_ENABLED_TITLE', 'Filter by attributes');
define('AUTH_MODULE_ENABLED_TITLE', 'Auth');
define('MAIN_SLIDER_MODULE_ENABLED_TITLE', 'Slider');
define('EXCEL_IMPORT_MODULE_ENABLED_TITLE', 'Excel import/export products');
define('FAQ_MODULE_ENABLED_TITLE', 'FAQ');
define('CUPONES_MODULE_ENABLED_TITLE', 'Cupones/Sertificates');
define('COMPARE_MODULE_ENABLED_TITLE', 'Compares');
define('WISHLIST_MODULE_ENABLED_TITLE', 'Wish list');
define('SMSINFORM_MODULE_ENABLED_TITLE', 'Sms-informing');
define('RATING_MODULE_ENABLED_TITLE', 'Rating');
define('MOBILE_VERSION_MODULE_ENABLED_TITLE', 'Mobile version');
define('ALSO_PURCHASED_MODULE_TITLE', 'Also purchased');
define('XML_MODULE_ENABLED_TITLE', 'XML');

define('CANT_CALL_TITLE', 'cant call?');
define('ONLINE_SUPPORT_TITLE', 'online support');

define('TABLE_HEADING_ORDERS', 'Orders:');
define('TABLE_HEADING_CUSTOMER', 'Customer');
define('TABLE_HEADING_ORDER_NUMBER', '№');
define('TABLE_HEADING_ORDER_TOTAL', 'Total');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_DATE', 'Date');
define('CHANGE_TELEPHONE', 'Change phone');
define('CHANGE_MAINPAGE', 'Change main page');
define('CHANGE_SLIDER', 'Edit front slider');

define('SHIPPING_DATE', 'Shipping date');
define('SHIPPING_MAN', 'courier');
define('SHIPPING_METHOS', 'method of payment');
define('ACTION', 'Action');
define('PICTURE', 'Picture');

define('GOTO2', 'Go to');
define('SEARCH2', 'Search');
define('CODE1', 'code');
define('CODE2', 'product');

define('PHOTOGALLERY2', 'Photogallery');
define('COMMENTS', 'Comments');
define('PHOTOGALLERY_NAME', 'Photoalbum Name');
define('PHOTOGALLERY_SAVE', 'Save');
define('PHOTOGALLERY_EDIT', 'edit');
define('PHOTOGALLERY_DEL_PIC', 'delete image');
define('PHOTOGALLERY_SIGN', 'Caption for the picture');
define('PHOTOGALLERY_POPUP_MESSAGE', 'Are you sure you want to delete a gallery?');
define('PHOTOGALLERY_DEL_GAL', 'Delete gallery');
define('PHOTOGALLERY_BACK', 'Back');

define('CONFIG_ID', 'ID Module');
define('CONFIG_CONST', 'Constant');

define('CATEG1', 'code');
define('CATEG2', 'in stock');
define('CATEG3', 'price');

define('INDEX_HOLA', 'Hello');

define('PRODUCTS_ITEM1', 'on');
define('PRODUCTS_ITEM2', 'off');
define('PRODUCTS_ITEM3', 'Color');

define('PRODUCTS_MULTI_ITEM1', 'Move To');
define('PRODUCTS_MULTI_ITEM2', 'Duplicate');
define('PRODUCTS_MULTI_ITEM3', 'Reference');
define('PRODUCTS_MULTI_ITEM4', 'Delete');
define('PRODUCTS_MULTI_ITEM5', 'Mark');
define('TEXT_PRODUCTS_AVERAGE_RATING1', 'Avg. аssessment');

define('TEXT_INFO_CATEGORIEACCESS', 'Access to the management of products and categories');
define('TEXT_RIGHTS_ID', 'Category ID -');

define('TEXT_RIGHTS_CNEW', 'Create category');
define('TEXT_RIGHTS_CEDIT', 'Edit category');
define('TEXT_RIGHTS_CMOVE', 'Move category');
define('TEXT_RIGHTS_CDELETE', 'Delete category');
define('TEXT_RIGHTS_PNEW', 'New products');
define('TEXT_RIGHTS_PEDIT', 'Edit products');
define('TEXT_RIGHTS_PMOVE', 'Move products');
define('TEXT_RIGHTS_PCOPY', 'Copy products');
define('TEXT_RIGHTS_PDELETE', 'Delete products');

define('TABLE_HEADING_STAT_ORDERS', 'Orders Quantity');

define('COMMENTS_HEADING_TITLE', 'Settings Comments');

define('MODULE_PAYMENT_WEBMONEY_1', '11111111111');
define('MODULE_PAYMENT_WEBMONEY_2', 'R11111111111');
define('MODULE_PAYMENT_WEBMONEY_3', 'Z111111111111');

include('includes/languages/english_newsdesk.php');
include('includes/languages/english_faqdesk.php');
include('includes/languages/order_edit_english.php');
?>