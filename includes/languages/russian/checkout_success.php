<?php
/*
  $Id: checkout_success.php,v 1.1.1.1 2003/09/18 19:04:30 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Оформление заказа');
define('NAVBAR_TITLE_2', 'Успешно');

define('HEADING_TITLE', 'Ваш заказ оформлен!');

define('TEXT_SUCCESS', 'Ваш заказ успешно оформлен!'); 
define('TEXT_NOTIFY_PRODUCTS', 'Отметьте те товары, о которых Вы хотите получать уведомления:');
define('TEXT_SEE_ORDERS', 'Вы можете посмотреть историю Ваших заказов, зайдя на Вашу персональную страницу <a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">\'Мои данные\'</a> и далее  <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">\'История заказов\'</a>.');
define('TEXT_CONTACT_STORE_OWNER', '');
define('TEXT_THANKS_FOR_SHOPPING', 'Ваш заказ успешно оформлен!');
define('TEXT_THANKS_CALL_BACK', 'В ближайшее время с вами свяжутся');

define('TABLE_HEADING_COMMENTS', 'У Вас есть вопросы, предложения, комментарии');

define('TABLE_HEADING_DOWNLOAD_DATE', 'Ссылка действительна до: ');
define('TABLE_HEADING_DOWNLOAD_COUNT', ' раз можно загрузить файл.');
define('HEADING_DOWNLOAD', 'Ссылка для скачивания:');
define('FOOTER_DOWNLOAD', 'Вы можете также загрузить Ваши продукты позже в \'%s\'');

// Guest account start
define('TEXT_GUEST_ORDERS', '');
?>