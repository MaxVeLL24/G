<?php
/*
  $Id: checkout_success.php,v 1.1.1.1 2003/09/18 19:04:30 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('NAVBAR_TITLE_1', 'Оформлення замовлення');
  define('NAVBAR_TITLE_2', 'Успішно');

  define('HEADING_TITLE', 'Ваше замовлення оформлене!');

  define('TEXT_SUCCESS', 'Ваше замовлення успішно оформлене!');
  define('TEXT_NOTIFY_PRODUCTS', 'Відмітьте ті товари, про які Ви хочете отримувати повідомлення:');
  define('TEXT_SEE_ORDERS', 'Ви можете подивитися історію Ваших замовлень, зайшовши на Вашу персональну сторінку <a href="'. tep_href_link(FILENAME_ACCOUNT,'','SSL').'"> \'Мої дані\'</a> і далі <a href="'. tep_href_link(FILENAME_ACCOUNT_HISTORY,'','SSL').'">\'Історія замовлень\'</a>.');
  define('TEXT_CONTACT_STORE_OWNER', '');
  define('TEXT_THANKS_FOR_SHOPPING', 'Ваше замовлення успішно оформлено!');
  define('TEXT_THANKS_CALL_BACK', 'Найближчим часом з вами зв\'яжуться');

  define('TABLE_HEADING_COMMENTS', 'У Вас є питання, пропозиції, коментарі');

  define('TABLE_HEADING_DOWNLOAD_DATE', 'Посилання дійсне до:');
  define('TABLE_HEADING_DOWNLOAD_COUNT', ' раз можна завантажити файл.');
  define('HEADING_DOWNLOAD', 'Посилання для скачування:');
  define('FOOTER_DOWNLOAD', 'Ви можете також завантажити Ваші продукти пізніше в \'%s\'');

// Guest account start
  define('TEXT_GUEST_ORDERS', '');
?>