<?php
/*
  $Id: shopping_cart.php,v 1.1.1.1 2003/09/18 19:04:28 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Cart Contents');
define('HEADING_TITLE', 'What\'s In My Cart?');
define('TABLE_HEADING_REMOVE', 'Remove');
define('TABLE_HEADING_QUANTITY', 'Qty.');
define('TABLE_HEADING_MODEL', 'Model');
define('TABLE_HEADING_PRODUCTS', 'Product(s)');
define('TABLE_HEADING_TOTAL', 'Total');
define('TABLE_HEADING_PRICE', 'Price');
define('TEXT_CART_EMPTY', 'Your Shopping Cart is empty!');
define('SUB_TITLE_SUB_TOTAL', 'Sub-Total:');
define('SUB_TITLE_TOTAL', 'Total:');
define('TEXT_RECALCULATE', 'Recalculate');
define('TEXT_CONTINUE_SHOPPING', 'Continue shopping');
define('TEXT_CHECKOUT', 'Checkout');
define('TEXT_REDEEM_COUPON_LABEL', 'Redeem coupon:');
define('TEXT_REDEEM_COUPON_BUTTON', 'Submit');
define('TEXT_REDEEM_COUPON_INPUT_PLACEHOLDER', 'Coupon code');

define('OUT_OF_STOCK_CANT_CHECKOUT', 'Products marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' dont exist in desired quantity in our stock.<br>Please alter the quantity of products marked with (' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '), Thank you');
define('OUT_OF_STOCK_CAN_CHECKOUT', 'Products marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' dont exist in desired quantity in our stock.<br>You can buy them anyway and check the quantity we have in stock for immediate deliver in the checkout process.');