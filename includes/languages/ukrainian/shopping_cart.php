<?php

/*
  $Id: shopping_cart.php,v 1.1.1.1 2003/09/18 19:04:28 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
 */

define('NAVBAR_TITLE', 'Вміст кошика');
define('HEADING_TITLE', 'Мій кошик');
define('TABLE_HEADING_REMOVE', 'Видалити');
define('TABLE_HEADING_QUANTITY', 'Кількість');
define('TABLE_HEADING_MODEL', 'Код товару');
define('TABLE_HEADING_PRODUCTS', 'Товари');
define('TABLE_HEADING_TOTAL', 'Вартість');
define('TABLE_HEADING_PRICE', 'Ціна');
define('TEXT_CART_EMPTY', 'Ваш кошик порожній!');
define('SUB_TITLE_SUB_TOTAL', 'Загальна вартість:');
define('SUB_TITLE_TOTAL', 'Разом:');
define('TEXT_RECALCULATE', 'Перерахувати');
define('TEXT_CONTINUE_SHOPPING', 'Продовжити покупки');
define('TEXT_CHECKOUT', 'Оформити замовлення');
define('TEXT_REDEEM_COUPON_LABEL', 'Використати купон:');
define('TEXT_REDEEM_COUPON_BUTTON', 'Застосувати');
define('TEXT_REDEEM_COUPON_INPUT_PLACEHOLDER', 'Код купона');

define('OUT_OF_STOCK_CANT_CHECKOUT', 'Товари, що відмічені ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' наявні на нашому складі, але в недостатній кількості для Вашого замовлення, <br> будь ласка, змініть кількість продуктів, що виділені (' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '), дякуємо Вам');
define('OUT_OF_STOCK_CAN_CHECKOUT', 'Товари, виділені ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' наявні на нашому складі, але в недостатній кількості для Вашого замовлення. <br> Тим не менше, Ви можете купити їх і перевірити кількість в наявності для поетапної доставки в процесі виконання Вашого замовлення.');