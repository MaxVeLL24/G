<?php
/*
  $Id: index.php,v 1.1.1.1 2003/09/18 19:04:30 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  define('TEXT_MAIN', '&nbsp;');
  define('TABLE_HEADING_NEW_PRODUCTS', 'Новинки %s');
  define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Очікується');
  define('TABLE_HEADING_DATE_EXPECTED', 'Дата надходження');
  define('TABLE_HEADING_DEFAULT_SPECIALS', 'Знижки %s');

if (($category_depth == 'products') || (isset($_GET['manufacturers_id']))) {
     define('HEADING_TITLE', 'Список товарів');

     define('TABLE_HEADING_IMAGE', '<span class="sort">Сортувати за:</span>');
     define('TABLE_HEADING_MODEL', 'Код товару');
     define('TABLE_HEADING_PRODUCTS', '<span class="cena">Найменування</span>');
     define('TABLE_HEADING_MANUFACTURER', 'Виробник');
     define('TABLE_HEADING_QUANTITY', 'Кількість');
     define('TABLE_HEADING_PRICE', '<span class="cena">Ціна</span>');
     define('TABLE_HEADING_WEIGHT', 'Вага');
     define('TABLE_HEADING_BUY_NOW', 'Купити');
     define('TABLE_HEADING_PRODUCT_SORT', 'Порядок');
     define('TEXT_NO_PRODUCTS', 'Немає жодного товару в цьому розділі.');
     define('TEXT_NO_PRODUCTS2', 'Немає жодного товару даного виробника.');
     define('TEXT_NUMBER_OF_PRODUCTS', 'Кількість товару:');
     define('TEXT_SHOW', '<b>Дивитися:</b>');
     define('TEXT_BUY', 'Купити \' ');
     define('TEXT_NOW', '\' зараз ');
     define('TEXT_ALL_CATEGORIES', 'Всі розділи');
     define('TEXT_ALL_MANUFACTURERS', 'Всі виробники');
} elseif ($category_depth == 'top') {
     define('HEADING_TITLE', 'Ласкаво просимо');
} elseif ($category_depth =='nested') {
     define('HEADING_TITLE', 'Розділи');
}
     define('HEADING_CUSTOMER_GREETING', 'Ласкаво просимо');
     define('MAINPAGE_HEADING_TITLE', 'Вітаємо Вас в нашому інтернет-магазині');
// BOF: Lango added for Featured Products
     define('TABLE_HEADING_FEATURED_PRODUCTS', 'Рекомендовані товари');
     define('TABLE_HEADING_FEATURED_PRODUCTS_CATEGORY', 'Рекомендовані товари розділу %s');
// EOF: Lango added for Featured Products
?>
