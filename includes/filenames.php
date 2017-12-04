<?php

/*
  $Id: filenames.php,v 1.2 2003/09/24 15:34:33 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */
// Google XML Sitemap
define('FILENAME_GOOGLE_SITEMAP', 'sitemaps.index.php');
// Fine 
// define the content used in the project
// HMCS: Begin Autologon	******************************************************************
define('FILENAME_INFO_AUTOLOGON', 'info_autologon.php');
define('FILENAME_CONFIGURATION_CACHE', 'cache/cachefile.inc.php');
// HMCS: End Autologon		******************************************************************

define('CONTENT_ACCOUNT', 'account');
define('CONTENT_ACCOUNT_EDIT', 'account_edit');
define('CONTENT_ACCOUNT_HISTORY', 'account_history');
define('CONTENT_ACCOUNT_HISTORY_INFO', 'account_history_info');
define('CONTENT_ACCOUNT_NEWSLETTERS', 'account_newsletters');
define('CONTENT_ACCOUNT_NOTIFICATIONS', 'account_notifications');
define('CONTENT_ACCOUNT_PASSWORD', 'account_password');
define('CONTENT_ADDRESS_BOOK', 'address_book');
define('CONTENT_ADDRESS_BOOK_PROCESS', 'address_book_process');
define('CONTENT_ADVANCED_SEARCH', 'advanced_search');
define('CONTENT_ADVANCED_SEARCH_RESULT', 'advanced_search_result');
define('CONTENT_ALSO_PURCHASED_PRODUCTS', 'also_purchased_products');
define('CONTENT_CHECKOUT_ONEPAGE', 'checkout');
define('CONTENT_CHECKOUT_CONFIRMATION', 'checkout_confirmation');
define('CONTENT_CHECKOUT_PAYMENT', 'checkout_payment');
define('CONTENT_CHECKOUT_PAYMENT_ADDRESS', 'checkout_payment_address');
define('CONTENT_CHECKOUT_SHIPPING', 'checkout_shipping');
define('CONTENT_CHECKOUT_SHIPPING_ADDRESS', 'checkout_shipping_address');
define('CONTENT_CHECKOUT_SUCCESS', 'checkout_success');
define('CONTENT_CONTACT_US', 'contact_us');
define('CONTENT_COOKIE_USAGE', 'cookie_usage');
define('CONTENT_CREATE_ACCOUNT', 'create_account');
define('CONTENT_CREATE_ACCOUNT_SUCCESS', 'create_account_success');
define('CONTENT_DOWNLOAD', 'download');
define('CONTENT_DOWNLOAD_FILES', 'download_files');
define('CONTENT_INDEX_DEFAULT', 'index_default');
define('CONTENT_INDEX_NESTED', 'index_nested');
define('CONTENT_INDEX_PRODUCTS', 'index_products');
define('CONTENT_INFO_SHOPPING_CART', 'info_shopping_cart');
define('CONTENT_LOGIN', 'login');
define('CONTENT_LOGOFF', 'logoff');
define('CONTENT_NEW_PRODUCTS', 'new_products');
define('CONTENT_PASSWORD_FORGOTTEN', 'password_forgotten');
define('CONTENT_POPUP_IMAGE', 'popup_image');
define('CONTENT_POPUP_SEARCH_HELP', 'popup_search_help');
define('CONTENT_PRODUCT_INFO', 'product_info');
define('CONTENT_PRODUCT_LISTING', 'product_listing');
define('CONTENT_PRODUCTS_NEW', 'products_new');
define('CONTENT_REVIEWS', 'reviews');
define('CONTENT_SHOPPING_CART', 'shopping_cart');
define('CONTENT_SPECIALS', 'specials');
define('CONTENT_SSL_CHECK', 'ssl_check');
define('CONTENT_TELL_A_FRIEND', 'tell_a_friend');
define('CONTENT_UPCOMING_PRODUCTS', 'upcoming_products');
define('CONTENT_CHECKOUT_PROCESS', 'checkout_process');
define('CONTENT_MIN_ORDER', 'min_order');
define('CONTENT_RSS2_INFO', 'rss2_info');
define('CONTENT_MIN_ORDER_B2B', 'min_order_b2b');
define('CONTENT_PRICE_HTML', 'price');
define('CONTENT_ARTICLE_INFO', 'article_info');
define('CONTENT_ARTICLES', 'articles');
define('CONTENT_ARTICLES_NEW', 'articles_new');
define('CONTENT_ARTICLE_SEARCH', 'articles_search');

define('CONTENT_PHOTOGALLERY', 'photogallery');
define('CONTENT_PHOTOGALLERY_FULL', 'photogallery_full');

define('CONTENT_POLLS', 'pollbooth');

// Lango added for GV FAQ: BOF
define('CONTENT_GV_REDEEM', 'gv_redeem');
define('CONTENT_GV_SEND', 'gv_send');
// Lango added for GV FAQ: EOF
// Lango Added for Down for Maintainance Mod: BOF
define('CONTENT_DOWN_FOR_MAINTAINANCE', 'down_for_maintenance');
// Lango Added for Down for Maintainance Mod: EOF
// Lango added forALL_PRODS: BOF
define('CONTENT_ALL_PRODS', 'allprods');
// Lango added forALL_PRODS: EOF
// Lango added for Featured products: BOF
define('CONTENT_FEATURED', 'featured');
define('CONTENT_FEATURED_PRODUCTS', 'featured_products');
// Lango added for Featured products: EOF
// Lango Added for WishList Mod: BOF
define('CONTENT_WISHLIST', 'wishlist');
define('CONTENT_WISHLIST_HELP', 'wishlist_help');
define('CONTENT_WISHLIST_PUBLIC', 'wishlist_public');
// Lango Added for WishList Mod: EOF
//DWD Modify: Information Page Unlimited 1.1f - PT

define('CONTENT_INFORMATION', 'information');
//DWD Modify End
// Lango Added for Links Manager Mod: BOF
define('CONTENT_LINKS', 'links');
define('CONTENT_LINKS_SUBMIT', 'links_submit');
define('CONTENT_LINKS_SUBMIT_SUCCESS', 'links_submit_success');
// Lango Added for Links Manager Mod:

define('FILENAME_FEATURED', 'featured.php');
define('FILENAME_FEATURED_PRODUCTS', 'featured_products.php'); // This is the featured products page

define('FILENAME_NEWSDESK_INDEX', 'newsdesk_index.php');
define('FILENAME_NEWSDESK_INFO', 'newsdesk_info.php');

// Lango Added for shop by price Mod: BOF
define('CONTENT_SHOP_BY_PRICE', 'shop_by_price');
// Lango Added for shop by price Mod: EOF
// define the filenames used in the project
define('FILENAME_ACCOUNT', CONTENT_ACCOUNT . '.php');
define('FILENAME_ACCOUNT_EDIT', CONTENT_ACCOUNT_EDIT . '.php');
define('FILENAME_ACCOUNT_HISTORY', CONTENT_ACCOUNT_HISTORY . '.php');
define('FILENAME_ACCOUNT_HISTORY_INFO', CONTENT_ACCOUNT_HISTORY_INFO . '.php');
define('FILENAME_ACCOUNT_NEWSLETTERS', CONTENT_ACCOUNT_NEWSLETTERS . '.php');
define('FILENAME_ACCOUNT_NOTIFICATIONS', CONTENT_ACCOUNT_NOTIFICATIONS . '.php');
define('FILENAME_ACCOUNT_PASSWORD', CONTENT_ACCOUNT_PASSWORD . '.php');
define('FILENAME_ADDRESS_BOOK', CONTENT_ADDRESS_BOOK . '.php');
define('FILENAME_ADDRESS_BOOK_PROCESS', CONTENT_ADDRESS_BOOK_PROCESS . '.php');
define('FILENAME_ADVANCED_SEARCH', CONTENT_ADVANCED_SEARCH . '.php');
define('FILENAME_ADVANCED_SEARCH_RESULT', CONTENT_ADVANCED_SEARCH_RESULT . '.php');
define('FILENAME_ALSO_PURCHASED_PRODUCTS', CONTENT_ALSO_PURCHASED_PRODUCTS . '.php');
define('FILENAME_CHECKOUT_ONEPAGE', CONTENT_CHECKOUT_ONEPAGE . '.php');
define('FILENAME_CHECKOUT_CONFIRMATION', CONTENT_CHECKOUT_CONFIRMATION . '.php');
define('FILENAME_CHECKOUT_PAYMENT', CONTENT_CHECKOUT_PAYMENT . '.php');
define('FILENAME_CHECKOUT_PAYMENT_ADDRESS', CONTENT_CHECKOUT_PAYMENT_ADDRESS . '.php');
define('FILENAME_CHECKOUT_PROCESS', CONTENT_CHECKOUT_PROCESS . '.php');
define('FILENAME_CHECKOUT_SHIPPING', CONTENT_CHECKOUT_SHIPPING . '.php');
define('FILENAME_CHECKOUT_SHIPPING_ADDRESS', CONTENT_CHECKOUT_SHIPPING_ADDRESS . '.php');
define('FILENAME_CHECKOUT_SUCCESS', CONTENT_CHECKOUT_SUCCESS . '.php');
define('FILENAME_CONTACT_US', CONTENT_CONTACT_US . '.php');
define('FILENAME_COOKIE_USAGE', CONTENT_COOKIE_USAGE . '.php');
define('FILENAME_CREATE_ACCOUNT', CONTENT_CREATE_ACCOUNT . '.php');

define('FILENAME_PHOTOGALLERY', CONTENT_PHOTOGALLERY . '.php');
define('FILENAME_PHOTOGALLERY_FULL', CONTENT_PHOTOGALLERY_FULL . '.php');

define('FILENAME_CREATE_ACCOUNT_SUCCESS', CONTENT_CREATE_ACCOUNT_SUCCESS . '.php');
define('FILENAME_DEFAULT', 'index.php');
define('FILENAME_DEFAULT_SPECIALS', 'default_specials.php');
define('FILENAME_DOWNLOAD', CONTENT_DOWNLOAD . '.php');
define('FILENAME_DOWNLOAD_FILES', CONTENT_DOWNLOAD_FILES . '.php');
define('FILENAME_INFO_SHOPPING_CART', CONTENT_INFO_SHOPPING_CART . '.php');
define('FILENAME_LOGIN', CONTENT_LOGIN . '.php');
define('FILENAME_LOGOFF', CONTENT_LOGOFF . '.php');
define('FILENAME_NEW_PRODUCTS', CONTENT_NEW_PRODUCTS . '.php');
define('FILENAME_PASSWORD_FORGOTTEN', CONTENT_PASSWORD_FORGOTTEN . '.php');
define('FILENAME_POPUP_SEARCH_HELP', CONTENT_POPUP_SEARCH_HELP . '.php');
define('FILENAME_PRODUCT_INFO', CONTENT_PRODUCT_INFO . '.php');
define('FILENAME_PRODUCT_LISTING', CONTENT_PRODUCT_LISTING . '.php');
define('FILENAME_PRODUCTS_NEW', CONTENT_PRODUCTS_NEW . '.php');
define('FILENAME_REDIRECT', 'redirect.php');
define('FILENAME_REVIEWS', CONTENT_REVIEWS . '.php');
define('FILENAME_SHOPPING_CART', CONTENT_SHOPPING_CART . '.php');
define('FILENAME_SPECIALS', CONTENT_SPECIALS . '.php');
define('FILENAME_SSL_CHECK', CONTENT_SSL_CHECK . '.php');
define('FILENAME_UPCOMING_PRODUCTS', CONTENT_UPCOMING_PRODUCTS . '.php');
define('FILENAME_CHECKOUT_PAYPALIPN', 'checkout_paypalipn.php'); // PAYPALIPN
define('FILENAME_POLLS', CONTENT_POLLS . '.php');
define('FILENAME_RSS2_INFO', CONTENT_RSS2_INFO . '.php');
define('FILENAME_RSS2', 'rss2.php');

// Added for Xsell Products Mod
define('FILENAME_XSELL_PRODUCTS', 'xsell_products_buynow.php');
define('FILENAME_PRODUCT_LISTING_COL', 'product_listing_col.php');
define('FILENAME_XSELL_PRODUCTS_BUYNOW', 'xsell_products_buynow.php');

//BEGIN allprods modification
define('FILENAME_ALLPRODS', 'allprods.php');
//END allprods modification
define('FILENAME_DYNAMIC_MOPICS', 'dynamic_mopics.php');


// MaxiDVD Added Line For WYSIWYG HTML Area: BOF
define('FILENAME_DEFINE_MAINPAGE', 'mainpage.php');
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF
// Lango Added for ALL_PODS Mod: BOF
define('FILENAME_ALL_PRODS', CONTENT_ALL_PRODS . '.php');
// Lango Added for ALL_PRODS Mod: EOF
// Lango Added for Featured Products: BOF
define('FILENAME_FEATURED', CONTENT_FEATURED . '.php');
define('FILENAME_FEATURED_PRODUCTS', CONTENT_FEATURED_PRODUCTS . '.php');
// Lango Added for Featured Product: EOF
// Lango Added for ALL_PODS Mod: BOF
define('FILENAME_POPUP_AFFILIATE_HELP', 'popup_affiliate_help.php');
// Lango Added for ALL_PRODS Mod: EOF
// Lango Added for WishList Mod: BOF
define('FILENAME_WISHLIST', CONTENT_WISHLIST . '.php');
define('FILENAME_WISHLIST_HELP', CONTENT_WISHLIST_HELP . '.php');
define('FILENAME_WISHLIST_PUBLIC', CONTENT_WISHLIST_PUBLIC . '.php');

// Lango Added for WishList Mod: EOF
// Lango Added for shop by price Mod: BOF
define('FILENAME_SHOP_BY_PRICE', CONTENT_SHOP_BY_PRICE . '.php');
// Lango Added for shop by price Mod: EOF
// define the templatenames used in the project
define('TEMPLATENAME_BOX', 'box.tpl.php');
define('TEMPLATENAME_MAIN_PAGE', 'main_page.tpl.php');
define('TEMPLATENAME_POPUP', 'popup.tpl.php');
define('TEMPLATENAME_STATIC', 'static.tpl.php');

//DWD Modify: Information Page Unlimited 1.1f - PT
define('FILENAME_INFORMATION', CONTENT_INFORMATION . '.php');
//DWD Modify End


define('FILENAME_PRICE_XLS', 'pricexls.php');
define('FILENAME_PRICE_HTML', 'price.php');

define('FILENAME_ARTICLE_INFO', 'article_info.php');
define('FILENAME_ARTICLE_LISTING', 'article_listing.php');
define('FILENAME_ARTICLES', 'articles.php');
define('FILENAME_ARTICLES_NEW', 'articles_new.php');
define('FILENAME_ARTICLES_UPCOMING', 'articles_upcoming.php');
define('FILENAME_ARTICLES_XSELL', 'articles_xsell.php');
define('FILENAME_NEW_ARTICLES', 'new_articles.php');
define('FILENAME_ARTICLE_SEARCH', 'articles_search.php');

define('FILENAME_MIN_ORDER', 'min_order.php');
define('FILENAME_MIN_ORDER_B2B', 'min_order_b2b.php');
define('FILENAME_INFORMATION', 'information.php');

define('FILENAME_BROWSE_CATEGORIES', 'browse_categories.php');
/* One Page Checkout - BEGIN */
define('FILENAME_CHECKOUT', 'checkout.php');
/* One Page Checkout - END */

// 404 страница
define('CONTENT_NOT_FOUND', 'not_found');
define('FILENAME_NOT_FOUND', 'not_found.php');

// 403 страница
define('CONTENT_FORBIDDEN', 'forbidden');
define('FILENAME_FORBIDDEN', 'forbidden.php');

// Скрипт для ресайза картинок
define('FILENAME_IMAGE_RESIZER', 'r_imgs.php');

// Страница со списком всех производителей
define('FILENAME_MANUFACTURERS', 'manufacturers.php');
define('CONTENT_MANUFACTURERS', 'manufacturers');

// Страница подписки на новостную рассылку
define('FILENAME_SUBSCRIBE', 'subscribe.php');
define('CONTENT_SUBSCRIBE', 'subscribe');

// Скрипт для ресайза картинок
define('CONTENT_COMPARE', 'compare');
define('FILENAME_COMPARE', 'compare.php');

// Счёт
define('CONTENT_INVOICE', 'invoice');
define('FILENAME_INVOICE', 'invoice.php');

// Счёт в HTML
define('CONTENT_INVOICE_HTML', 'invoice_html');
define('FILENAME_INVOICE_HTML', 'invoice_html.php');

// Просмотренные товары
define('CONTENT_VIEWED_PRODUCTS', 'viewed_products');
define('FILENAME_VIEWED_PRODUCTS', 'viewed_products.php');