<?php
/*
  $Id: english.php,v 1.1.1.1 2003/09/18 19:04:27 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'
@setlocale(LC_TIME, 'en_US.ISO_8859-1');

define('ONLINE_SUPPORT_HEADING', 'Online support');
define('CALL_PROBLEM_TITLE', 'Call problem?');
define('NEW_PRODUCTS', 'New Products');
define('SERVICE', 'Service');
define('MADE_BY', 'Developer:');
define('SITEMAP', 'Site map');


define('GO_COMPARE', 'Go to compare');
define('IN_WHISHLIST', 'In Whishlist');
define('COMPARE', 'Compare');
define('COMPARE_BOX', 'Compare items');
define('WHISH', 'To Whishlist');

define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
//define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
//define('DATE_FORMAT_LONG', '%d %B %Y г.'); // this is used for strftime()
define('DATE_FORMAT_LONG', '%d.%m.%Y'); // this is used for strftime()
define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

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

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'USD');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="en"');

// charset for web pages and emails
// define('CHARSET', 'iso-8859-1');
define('CHARSET', 'UTF-8');

// page title
define('TITLE', 'Your Store Name');

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Create an Account');
define('HEADER_TITLE_MY_ACCOUNT', 'My Account');
define('HEADER_TITLE_CART_CONTENTS', 'Cart Contents');
define('HEADER_TITLE_CHECKOUT', 'Checkout');
define('HEADER_TITLE_TOP', 'Top');
define('HEADER_TITLE_CATALOG', 'Catalog');
define('HEADER_TITLE_LOGOFF', 'Log Off');
define('HEADER_TITLE_LOGIN', 'Log In');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'requests since');

// text for gender
define('MALE', 'Male');
define('FEMALE', 'Female');
define('MALE_ADDRESS', 'Mr.');
define('FEMALE_ADDRESS', 'Ms.');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');


// quick_find box text in includes/boxes/quick_find.php
define('BOX_SEARCH_TEXT', 'Use keywords to find the product you are looking for.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'Advanced Search');

// reviews box text in includes/boxes/reviews.php
define('BOX_REVIEWS_WRITE_REVIEW', 'Write a review on this product!');
define('BOX_REVIEWS_NO_REVIEWS', 'There are currently no product reviews');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s of 5 Stars!');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_SHOPPING_CART_EMPTY', '0 items');

define('BOX_SHOPPING_CART_PRODUCTS', '<span>%s</span> products in the amount of %s');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_NOTIFICATIONS_NOTIFY', 'Notify me of updates to <b>%s</b>');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'Do not notify me of updates to <b>%s</b>');

// manufacturer box text
define('BOX_MANUFACTURER_INFO_HOMEPAGE', '%s Homepage');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'Other products');
define('DRUGIE_HEAD_TITLE', 'Similar Products');
// information box text in includes/boxes/information.php
define('BOX_INFORMATION_PRIVACY', 'Privacy Notice');
define('BOX_INFORMATION_CONDITIONS', 'Conditions of Use');
define('BOX_INFORMATION_SHIPPING', 'Shipping & Returns');
define('BOX_INFORMATION_CONTACT', 'Contact Us');

define('BOX_INFORMATION_PRICE_XLS', 'Price-list (Excel)');
define('BOX_INFORMATION_PRICE_HTML', 'Price-list (HTML)');


// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_TELL_A_FRIEND_TEXT', 'Tell someone you know about this product.');

//BEGIN allprods modification
define('BOX_INFORMATION_ALLPRODS', 'View All Items');
//END allprods modification

// VJ Links Manager v1.00 begin
define('BOX_INFORMATION_LINKS', 'Links');
// VJ Links Manager v1.00 end

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'Delivery Information');
define('CHECKOUT_BAR_PAYMENT', 'Payment Information');
define('CHECKOUT_BAR_CONFIRMATION', 'Confirmation');
define('CHECKOUT_BAR_FINISHED', 'Finished!');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Please Select');
define('TYPE_BELOW', 'Type Below');

// javascript messages
define('JS_ERROR', 'Errors have occured during the process of your form.\n\nPlease make the following corrections:\n\n');

define('JS_REVIEW_TEXT', '* The ’Review Text’ must have at least ' . REVIEW_TEXT_MIN_LENGTH . ' characters.\n');

define('JS_FIRST_NAME', '* The ’First Name’ must contain a minimum of ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_LAST_NAME', '* The ’Last Name’ must contain a minimum of ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n');


define('JS_REVIEW_RATING', '* You must rate the product for your review.\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Please select a payment method for your order.\n');

define('JS_ERROR_SUBMITTED', 'This form has already been submitted. Please press Ok and wait for this process to be completed.');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Please select a payment method for your order.');

define('CATEGORY_COMPANY', 'Company Details');
define('CATEGORY_PERSONAL', 'Your Personal Details');
define('CATEGORY_ADDRESS', 'Your Address');
define('CATEGORY_CONTACT', 'Your Contact Information');
define('CATEGORY_OPTIONS', 'Options');
define('CATEGORY_PASSWORD', 'Your Password');

define('NEW_CUSTOMER', 'New customer');
define('RETURNING_CUSTOMER', 'Returning cusomer');

define('ENTRY_COMPANY', 'Company Name:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', 'Gender:');
define('ENTRY_GENDER_ERROR', 'Please select your Gender.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'First Name:');
define('ENTRY_FIRST_NAME_ERROR', 'Your First Name must contain a minimum of ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Last Name:');
define('ENTRY_LAST_NAME_ERROR', 'Your Last Name must contain a minimum of ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Your Date of Birth must be in this format: MM/DD/YYYY (eg 05/21/1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', '* (eg. 05/21/1970)');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Your E-Mail Address must contain a minimum of ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Your E-Mail Address does not appear to be valid - please make any necessary corrections.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Your E-Mail Address already exists in our records - please log in with the e-mail address or create an account with a different address.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Street Address:');
define('ENTRY_STREET_ADDRESS_ERROR', 'Your Street Address must contain a minimum of ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'Suburb:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Post Code:');
define('ENTRY_POST_CODE_ERROR', 'Your Post Code must contain a minimum of ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'City:');
define('ENTRY_CITY_ERROR', 'Your City must contain a minimum of ' . ENTRY_CITY_MIN_LENGTH . ' characters.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'State/Province:');
define('ENTRY_STATE_ERROR', 'Your State must contain a minimum of ' . ENTRY_STATE_MIN_LENGTH . ' characters.');
define('ENTRY_STATE_ERROR_SELECT', 'Please select a state from the States pull down menu.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_COUNTRY_ERROR', 'You must select a country from the Countries pull down menu.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Your Telephone Number must contain a minimum of ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Fax Number:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'Subscribed');
define('ENTRY_NEWSLETTER_NO', 'Unsubscribed');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_PASSWORD_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'The Password Confirmation must match your Password.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Password Confirmation:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Current Password:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_NEW', 'New Password:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Your new Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'The Password Confirmation must match your new Password.');
define('PASSWORD_HIDDEN', '--HIDDEN--');

define('FORM_REQUIRED_INFORMATION', '* Required information');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Result Pages:');
define('TEXT_RESULT_VIEW', 'Display:');
define('TEXT_SWITCH_VIEW', 'View:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> reviews)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> new products)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> specials)');
define('TEXT_DISPLAY_NUMBER_OF_FEATURED', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> featured products)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'First Page');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'Previous Page');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Next Page');
define('PREVNEXT_TITLE_LAST_PAGE', 'Last Page');
define('PREVNEXT_TITLE_PAGE_NO', 'Page %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Previous Set of %d Pages');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Next Set of %d Pages');
define('PREVNEXT_BUTTON_FIRST', '&lt;&lt;FIRST');
define('PREVNEXT_BUTTON_PREV', '[&lt;&lt;&nbsp;Prev]');
define('PREVNEXT_BUTTON_NEXT', '[Next&nbsp;&gt;&gt;]');
define('PREVNEXT_BUTTON_LAST', 'LAST&gt;&gt;');

define('IMAGE_BUTTON_ADD_ADDRESS', 'Add Address');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Address Book');
define('IMAGE_BUTTON_BACK', 'Back');
define('IMAGE_BUTTON_BUY_NOW', 'Buy Now');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Change Address');
define('IMAGE_BUTTON_CHECKOUT', 'Checkout');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Confirm Order');
define('IMAGE_BUTTON_CONTINUE', 'Continue');
define('IMAGE_BUTTON_SEND', 'Send');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Continue Shopping');
define('IMAGE_BUTTON_DELETE', 'Delete');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Edit Account');
define('IMAGE_BUTTON_HISTORY', 'Order History');
define('IMAGE_BUTTON_LOGIN', 'Sign In');

define('IMAGE_BUTTON_IN_CART', 'In cart');
define('IMAGE_BUTTON_ADDTO_CART', 'Buy');

define('SIGN_FROM_SOC', 'Sign with:');

define('IMAGE_BUTTON_NOTIFICATIONS', 'Notifications');
define('IMAGE_BUTTON_QUICK_FIND', 'Quick Find');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Remove Notifications');
define('IMAGE_BUTTON_REVIEWS', 'Reviews');
define('IMAGE_BUTTON_SEARCH', 'Search');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Shipping Options');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Tell a Friend');
define('IMAGE_BUTTON_UPDATE', 'Update');
define('IMAGE_BUTTON_UPDATE_CART', 'Update Cart');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Write Review');
define('IMAGE_REDEEM_VOUCHER_TITLE', 'Cupon');
define('IMAGE_REDEEM_VOUCHER', 'Apply');

define('SMALL_IMAGE_BUTTON_DELETE', 'Delete');
define('SMALL_IMAGE_BUTTON_EDIT', 'Edit');
define('SMALL_IMAGE_BUTTON_VIEW', 'View');

define('ICON_ARROW_RIGHT', 'more');
define('ICON_CART', 'In Cart');
define('ICON_ERROR', 'Error');
define('ICON_SUCCESS', 'Success');
define('ICON_WARNING', 'Warning');

define('TEXT_GREETING_PERSONAL', 'Welcome back <span class="greetUser">%s!</span> Would you like to see which <a href="%s"><u>new products</u></a> are available to purchase?');
define('TEXT_CUSTOMER_GREETING_HEADER', 'Our Customer Greeting');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>If you are not %s, please <a href="%s"><u>log yourself in</u></a> with your account information.</small>');
define('TEXT_GREETING_GUEST', 'Welcome <span class="greetUser">Guest!</span> Would you like to <a href="%s"><u>log yourself in</u></a>? Or would you prefer to <a href="%s"><u>create an account</u></a>?');

define('TEXT_SORT_PRODUCTS', 'Sort by:');
define('TEXT_DESCENDINGLY', 'descendingly');
define('TEXT_ASCENDINGLY', 'ascendingly');
define('TEXT_BY', ' by ');

define('TEXT_REVIEW_BY', 'by %s');
define('TEXT_REVIEW_WORD_COUNT', '%s words');
define('TEXT_REVIEW_RATING', 'Rating: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', 'Date Added: %s');
define('TEXT_NO_REVIEWS', 'There are currently no product reviews.');

define('TEXT_NO_NEW_PRODUCTS', 'There are currently no products.');

define('TEXT_NO_PRODUCTS', 'There are currently no products in this range.');

define('TEXT_UNKNOWN_TAX_RATE', 'Unknown tax rate');

define('TEXT_REQUIRED', '<span class="errorText">Required</span>');

// Down For Maintenance
define('TEXT_BEFORE_DOWN_FOR_MAINTENANCE', 'NOTICE: This website will be down for maintenance on: ');
define('TEXT_ADMIN_DOWN_FOR_MAINTENANCE', 'NOTICE: the website is currently Down For Maintenance to the public');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>TEP ERROR:</small> Cannot send the email through the specified SMTP server. Please check your php.ini setting and correct the SMTP server if necessary.</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Warning: Installation directory exists at: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install. Please remove this directory for security reasons.');
define('WARNING_CONFIG_FILE_WRITEABLE', 'Warning: I am able to write to the configuration file: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php. This is a potential security risk - please set the right user permissions on this file.');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Warning: The sessions directory does not exist: ' . tep_session_save_path() . '. Sessions will not work until this directory is created.');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Warning: I am not able to write to the sessions directory: ' . tep_session_save_path() . '. Sessions will not work until the right user permissions are set.');
define('WARNING_SESSION_AUTO_START', 'Warning: session.auto_start is enabled - please disable this php feature in php.ini and restart the web server.');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Warning: The downloadable products directory does not exist: ' . DIR_FS_DOWNLOAD . '. Downloadable products will not work until this directory is valid.');


define('TEXT_CCVAL_ERROR_INVALID_DATE', 'The expiry date entered for the credit card is invalid.<br>Please check the date and try again.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'The credit card number entered is invalid.<br>Please check the number and try again.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'The first four digits of the number entered are: %s <br>If that number is correct, we do not accept that type of credit card.<br>If it is wrong, please try again.');

require(DIR_WS_LANGUAGES . 'add_ccgvdc_english.php');
/////////////////////////////////////////////////////////////////////
// HEADER.PHP
// Header Links
define('HEADER_LINKS_DEFAULT','Home');
define('HEADER_LINKS_WHATS_NEW','What’s new?');
define('HEADER_LINKS_SPECIALS','Specials');
define('HEADER_LINKS_REVIEWS','Reviews');
define('HEADER_LINKS_LOGIN','Login');
define('HEADER_LINKS_LOGOFF','Log Off');
define('HEADER_LINKS_PRODUCTS_ALL','Catalog');
define('HEADER_LINKS_ACCOUNT_INFO','Account Info');
define('HEADER_LINKS_CHECKOUT','Checkout');
define('HEADER_LINKS_CART','Shopping Cart');
define('HEADER_LINKS_DVD', 'Catalog');

/////////////////////////////////////////////////////////////////////

// BOF: Lango added for print order mod
define('IMAGE_BUTTON_PRINT_ORDER', 'Order printable');
// EOF: Lango added for print order mod

// WebMakers.com Added: Attributes Sorter
require(DIR_WS_LANGUAGES . $language . '/' . 'attributes_sorter.php');

define('BOX_LOGINBOX_HEADING', 'Your account');
define('BOX_LOGINBOX_EMAIL', 'E-Mail:');
define('BOX_LOGINBOX_PASSWORD', 'Password:');
define('IMAGE_BUTTON_LOGIN', 'Sign in');

define('BOX_HEADING_LOGIN_BOX_MY_ACCOUNT','My account');
define('LOGIN_BOX_MY_CABINET','My account');
define('LOGIN_BOX_ACCOUNT_EDIT','Edit account');
define('LOGIN_BOX_ACCOUNT_HISTORY','Order history');
define('LOGIN_BOX_ADDRESS_BOOK','Address book');
define('LOGIN_BOX_PRODUCT_NOTIFICATIONS','Notifications');
define('LOGIN_BOX_MY_ACCOUNT','My account');
define('LOGIN_BOX_LOGOFF','Log off');

define('LOGIN_FROM_SITE','Login from site');
define('DISCOUNT_HEADING', 'Discounts');

define('HELP', '<a href="http://web.icq.com/whitepages/message_me/1,,,00.icq?uin=' . STORE_OWNER_ICQ_NUMBER . '&action=message" target="_blank"><img src="http://web.icq.com/whitepages/online?icq=' . STORE_OWNER_ICQ_NUMBER . '&amp;img=5" title="ICQ Status" align="absmiddle" border="0">' . STORE_OWNER_ICQ_NUMBER . '</a>
<br>
');

define('ICQ', 'ICQ:<br>');
define('TEXT_MORE_INFO', 'More info...');

// Article Manager
define('BOX_ALL_ARTICLES', 'All Articles');
define('BOX_NEW_ARTICLES', 'New Articles');
define('HEAD_ARTICLES_LIST', 'Useful Articles');
define('TEXT_DISPLAY_NUMBER_OF_ARTICLES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> articles)');
define('TEXT_DISPLAY_NUMBER_OF_ARTICLES_NEW', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> new articles)');
define('TABLE_HEADING_AUTHOR', 'Author');
define('TABLE_HEADING_ABSTRACT', 'Abstract');
define('NAVBAR_TITLE_DEFAULT', 'Articles');

define('TABLE_HEADING_INFO','Short description');

//TotalB2B start
define('PRICES_LOGGED_IN_TEXT','Must be logged in for prices!');
//TotalB2B end

define('PRODUCTS_ORDER_QTY_TEXT','Add Qty: ');
define('PRODUCTS_ORDER_QTY_MIN_TEXT','<br>' . ' Min Qty: ');
define('PRODUCTS_ORDER_QTY_MIN_TEXT_INFO','Order Minumum is: ');
define('PRODUCTS_ORDER_QTY_MIN_TEXT_CART','Order Minimum is: ');
define('PRODUCTS_ORDER_QTY_MIN_TEXT_CART_SHORT',' Min Qty: ');
define('PRODUCTS_ORDER_QTY_UNIT_TEXT',' in Units of: ');
define('PRODUCTS_ORDER_QTY_UNIT_TEXT_INFO','Order in Units of: ');
define('PRODUCTS_ORDER_QTY_UNIT_TEXT_CART','Order in Units of: ');
define('PRODUCTS_ORDER_QTY_UNIT_TEXT_CART_SHORT',' Units: ');
define('ERROR_PRODUCTS_QUANTITY_ORDER_MIN_TEXT','');
define('ERROR_PRODUCTS_QUANTITY_INVALID','Invalid Qty: ');
define('ERROR_PRODUCTS_QUANTITY_ORDER_UNITS_TEXT','');
define('ERROR_PRODUCTS_UNITS_INVALID','Invalid Units: ');

// Comments
define('COMMENT_HEAD_TITLE', 'COMMENTS');
define('ADD_COMMENT_HEAD_TITLE', 'ADD YOUR COMMENT ABOUT ');

// Poll Box Text
define('_RESULTS', 'Results');
define('_VOTE', 'Vote');
define('_COMMENTS','Comments:');
define('_VOTES', 'Votes:');
define('_NOPOLLS','No eligible polls');
define('_NOPOLLSCONTENT','There are no polls that you are eligible for, however you can still view the results of other polls<br><br><a href="pollbooth.php">['._POLLS.']');

define('IMAGE_BUTTON_PREVIOUS', 'Previous item');
define('IMAGE_BUTTON_NEXT', 'Next item');
define('IMAGE_BUTTON_RETURN_TO_PRODUCT_LIST', 'Back to product list');
define('PREV_NEXT_PRODUCT', 'Product ');
define('PREV_NEXT_PRODUCT1', '/');
define('PREV_NEXT_CAT', ' of category ');
define('PREV_NEXT_MB', ' of manafacturer ');

define('BOX_TEXT_DOWNLOAD', 'Your downloads: ');
define('BOX_DOWNLOAD_DOWNLOAD', 'Download files');
define('BOX_TEXT_DOWNLOAD_NOW', 'Download');

// English names of boxes

define('BOX_HEADING_CATEGORIES', 'Categories');
define('BOX_HEADING_INFORMATION', 'Information');
define('BOX_HEADING_TEMPLATE_SELECT', 'Theme select');
define('BOX_HEADING_MANUFACTURERS', 'Manufacturers');
define('BOX_HEADING_SPECIALS', 'Specials');
define('BOX_HEADING_NEWSDESK_LATEST', 'Latest News');
define('BOX_HEADING_NEWSDESK_ALL', 'All news');
define('BOX_HEADING_SEARCH', 'Quick find');
define('BOX_HEADING_WHATS_NEW', 'Latest products');
define('BOX_HEADING_LANGUAGES', 'Languages');
define('BOX_HEADING_NEWSBOX', 'News');
define('BOX_HEADING_ALL_NEWS', 'All News');
define('BOX_HEADING_FEATURED', 'Featured Products');
define('BOX_HEADING_SHOP_BY_PRICE', 'Shop by price');
define('BOX_HEADING_NEWSDESK_CATEGORIES', 'News categories');
define('BOX_HEADING_ARTICLES', 'Articles');
define('BOX_HEADING_AUTHORS', 'Authors');
define('BOX_HEADING_LINKS', 'Links');
define('BOX_HEADING_SHOPPING_CART', 'Shopping cart');
define('BOX_HEADING_DOWNLOAD', 'Downloads');
define('BOX_HEADING_LOGIN', 'Your account');
define('HELP_HEADING', 'Help');
define('BOX_HEADING_FILTER_BY_PRICE', 'Price');
define('BOX_HEADING_SELECTED_FILTERS', 'Selected filters');
define('TEXT_CLEAR_SELECTED_FILTERS', 'Reset selected');
define('BOX_HEADING_WISHLIST', 'Wishlist');
define('BOX_WISHLIST_ITEMS', 'pcs');
define('BOX_HEADING_REVIEWS', 'Reviews');
define('BOX_HEADING_CUSTOMER_ORDERS', 'Orders history');
define('BOX_HEADING_AFFILIATE', 'Affiliate program');
define('BOX_HEADING_MANUFACTURER_INFO', 'Manufactures info');
define('BOX_HEADING_BESTSELLERS', 'Best Sellers');
define('BOX_HEADING_MOSTVIEWED', 'TOP Viewed');
define('BOX_HEADING_TELL_A_FRIEND', 'Tell a friend');
define('BOX_HEADING_NOTIFICATIONS', 'Notifications');
define('BOX_HEADING_CURRENCIES', 'Currencies');
define('BOX_HEADING_FAQDESK_CATEGORIES', 'FAQ');
define('BOX_HEADING_FAQDESK_LATEST', 'FAQDesk Latest');
define('_POLLS', 'Polls');
define('BOX_HEADING_LAST_VIEWED', 'Last Viewed');

// Shopping cart quotes
  define('SHIPPING_OPTIONS', 'Shipping Options:');
  if (strstr($PHP_SELF,'shopping_cart.php')) {
    define('SHIPPING_OPTIONS_LOGIN', 'Please <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u>Log In</u></a>, to display your personal shipping costs.');
  } else {
    define('SHIPPING_OPTIONS_LOGIN', 'Please Log In, to display your personal shipping costs.');
  }
  define('SHIPPING_METHOD_TEXT','Shipping Methods:');
  define('SHIPPING_METHOD_RATES','Rates:');
  define('SHIPPING_METHOD_TO','Ship to: ');
  define('SHIPPING_METHOD_TO_NOLOGIN', 'Ship to: <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u>Log In</u></a>');
  define('SHIPPING_METHOD_FREE_TEXT','Free Shipping');
  define('SHIPPING_METHOD_ALL_DOWNLOADS','- Downloads');
  define('SHIPPING_METHOD_RECALCULATE','Recalculate');
  define('SHIPPING_METHOD_ZIP_REQUIRED','true');
  define('SHIPPING_METHOD_ADDRESS','Address:');
  define('SHIPPING_METHOD_QTY','Items: ');
  define('SHIPPING_METHOD_WEIGHT','Weight: ');
  define('SHIPPING_METHOD_WEIGHT1',' lbs');

  define('LOW_STOCK_TEXT1','Low stock warning: ');
  define('LOW_STOCK_TEXT2','Model No.: ');
  define('LOW_STOCK_TEXT3','Quantity: ');
  define('LOW_STOCK_TEXT4','Product URL: ');
  define('LOW_STOCK_TEXT5','Current Low order limit is ');

// wishlist box text in includes/boxes/wishlist.php
  define('BOX_HEADING_CUSTOMER_WISHLIST', 'My Wishlist');
  define('TEXT_WISHLIST_COUNT', 'Currently %s items are on your Wish List.');

  define('BOX_TEXT_VIEW', 'Show');
  define('BOX_TEXT_HELP', 'Help');
  define('BOX_WISHLIST_EMPTY', '0 items');
  define('BOX_TEXT_NO_ITEMS', 'No products are in your Wishlist.');
  define('IMAGE_BUTTON_ADD_WISHLIST', 'Add to wishlist');

  define('TEXT_VERSION', 'Version: ');
  define('TOTAL_QUERIES', 'Total queries: ');
  define('TOTAL_TIME', 'Execution time: ');

// otf 1.71 defines needed for Product Option Type feature.
  define('PRODUCTS_OPTIONS_TYPE_SELECT', 0);
  define('PRODUCTS_OPTIONS_TYPE_TEXT', 1);
  define('PRODUCTS_OPTIONS_TYPE_RADIO', 2);
  define('PRODUCTS_OPTIONS_TYPE_CHECKBOX', 3);
  define('PRODUCTS_OPTIONS_TYPE_TEXTAREA', 4);
  define('TEXT_PREFIX', 'txt_');
  define('PRODUCTS_OPTIONS_VALUE_TEXT_ID', 0);  //Must match id for user defined "TEXT" value in db table TABLE_PRODUCTS_OPTIONS_VALUES

// Product info
define('PRODUCT_POPCART_IMAGE', 'Image');
define('PRODUCT_POPCART_NAME', 'Name');
define('PRODUCT_POPCART_PRICE', 'Price');
define('PRODUCT_POPCART_QTY', 'Quantity');
define('PRODUCT_POPCART_TOTAL', 'Total');

define('PREV_PRODUCT', 'Previous product');
define('NEXT_PRODUCT', 'Next product');
define('SHOW_CATALOG', 'Show catalog');
define('PRODUCT_AVIAIlABLE', 'In stock');
define('PRODUCT_NOT_AVIAIlABLE', 'Not in stock');


// Product info
  // Product tabs
define('DESCRIPTION', 'Description');
define('FEATURES', 'Features');
define('COMMENTS', 'Comments');
define('RELATED_PRODUCTS', 'Related products');
define('ALSO_PURCHASED', 'ALSO_PURCHASED');
//include('includes/languages/english_support.php');
include('includes/languages/english_newsdesk.php');
include('includes/languages/english_faqdesk.php');

define('FORWARD', 'Forward');
define('BACKWARD', 'Backward');
define('MY_ORDER', 'My order');


define('USER_ACCOUNT_NAVIGATION', 'Navigation');
define('MY_ACCOUNT_INFORMATION','My information');

define('MY_ORDERS_VIEW','My orders');
define('MY_ACCOUNT_PASSWORD','Change password');


define('LOGIN_FROM_SITE','Login');

define('PHOTOGALLERY','Photogallery');
define('HEADING_PEREZVONIM','We will call you');

define('SEND_MESSAGE','Send');
define('SHOW_ALL_SRCH_RES','Show all results');
define('ALL_RIGHTS','All Right Reserved');
define('ADDRESS_MAINPAGE','Ukraine, 020232 Dragomanova 18, office 5');

define('BOX_CURRENCY','Currency: ');

define('SORT_NAME_ASC', 'A-Z');
define('SORT_NAME_DESC', 'Z-A');
define('SORT_PRICE_ASC','Cheap first');
define('SORT_PRICE_DESC','Expensive first');
define('SORT_NEW','New first');
define('SORT_POPULAR','Popular first');
define('VIEW_LIST','List');
define('VIEW_COL','Columns');
define('SORT_ALL','All');

define('PROD_ENLARGE','Enlarge');
define('PROD_BETTER_TOGETHER','Also you can buy with discount');

define('COMP_PROD_HEAD','Products compare');
define('COMP_PROD_NAME','Name');
define('COMP_PROD_IMG','Image');
define('COMP_PROD_PRICE','Price');
define('COMP_PROD_CLEAR','Clear all');
define('COMP_PROD_BACK','Back');
define('COMP_PROD_ADD_TO','Add products to compare!');

define('ATTRIBUTES','Attributes');
define('QUICK_ORDER','Quick order');
define('QUICK_ORDER_SUCCESS','Success');
define('QUICK_ORDER_BUTTON','Buy in one click');
define('QUICK_PRE_ORDER_BUTTON','Pre order');

define('DIALOG_DIS', 'Ask a Question');

// Всплывающие подсказки для логотипов платёжных систем в подвале
define('FPSLT_MASTER_CARD', 'Master Card');
define('FPSLT_WEBMONEY', 'Webmoney');
define('FPSLT_VISA', 'Visa');
define('FPSLT_YANDEX_MONEY', 'Yandex.Money');

// Текст для чекбокса "Все" в фильтре категории
define('TEXT_FILTER_ALL', 'All');

define('NO_PRODUCTS_FOUND', 'No products matching your search criteria.');
define('LOAD_MORE_PRODUCTS', 'Load more products');

define('PRODUCTS_FOUND_HEADER', 'Search results for query «%s»');
define('NO_PRODUCTS_FOUND_HEADER', 'There is no products matching your request');
define('MODULE_ARTICLES_XSELL_TITLE', 'Related products');
// Текст в логотипе
define('LOGO_TEXT', 'Toys online store');

// График работы
define('TEXT_SCHEDULE', '<span>Working hours:</span><br> Mon-Fri from 9am till 6pm,<br> <span>Sat, Sun - day off</span>');

// Заголовки колонок в подвале
define('FOOTER_COLUMN_INFORMATION_TITLE', 'Information');
define('FOOTER_COLUMN_ABOUT_US_TITLE', 'About our store');
define('FOOTER_COLUMN_CATEGORIES_TITLE', 'Categories');
define('FOOTER_COLUMN_SUBSCRIBE_TITLE', 'Gigimot’s news');
define('TEXT_SUBSCRIBE', 'Subscribe');
define('FOOTER_SUBSCRIBE_DESCRIPTION', 'Receive the latest news about your favorite store or product. Stay in touch.');
define('FOOTER_SUBSCRIBE_FORM_EMAIL_INPUT_PLACEHOLDER', 'Your email address');
define('READMORE_ARTICLE_LINK_TITLE', 'Read the article «%s»');
define('READMORE_NEWS_LINK_TITLE', 'Read more «%s»');

// Модуль производителей на главной
define('MPM_MANUFACTURESR_LIST_LABEL', 'Brands');
define('MPM_MANUFACTURESR_OTHER', 'Other');
define('MPM_MANUFACTURESR_ALL', 'All brands');

// Модуль новинок на главной
define('MPM_NEW_PRODUCTS_ALL', 'All new products');

// Модуль рекомендуемых товаров
define('MPM_FEATURED_ALL', 'All products');

// Модуль просмотренных товаров
define('MPM_VIEWED_PRODUCTS_TITLE', 'Viewed products');
define('MPM_VIEWED_PRODUCTS_ALL', 'All');

// Список товаров
define('PRODUCT_LISTING_ADD_TO_WISHLIST', 'Add to wishlist');
define('PRODUCT_LISTING_IN_WISHLIST', 'In <a href="' . tep_href_link(FILENAME_WISHLIST) . '" title="Go to wishlist." rel="nofollow" onclick="var e = (arguments[0] || window.event); if(\'stopPropagation\' in e){e.stopPropagation();} else {e.cancelBubble = true;}">wishlist</a>');
define('PRODUCT_LISTING_ADD_TO_COMPARE', 'Add to compare');
define('PRODUCT_LISTING_IN_COMPARISON', 'In <a href="' . tep_href_link(FILENAME_COMPARE) . '" title="Go to compare." rel="nofollow" onclick="var e = (arguments[0] || window.event); if(\'stopPropagation\' in e){e.stopPropagation();} else {e.cancelBubble = true;}">comparison</a>');
define('PRODUCT_LISTING_COMMENTS_COUNT', 'Reviews (%d)');
define('PRODUCT_LISTING_PRE_ORDER', 'Pre-order');
define('PRODUCTS_PRE_ORDER_INFO', 'Delivery in <br />5 days');

define('PRODUCT_LISTING_WAIT', 'Please wait');

// Номер страницы в тайтле
define('TITLE_ADD_PAGE_NUMBER', '. Page %d');
define('TITLE_DELIMITER', ' — ');

// Локаль для Facebook OpenGraph
define('FACEBOOK_OG_LOCALE', 'en_US');

// Header tags
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . 'header_tags.php';