<?php
/*
  $Id: links_submit.php,v 1.00 2003/10/03 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  define('NAVBAR_TITLE_1', 'Посилання');
  define('NAVBAR_TITLE_2', 'Додати посилання');

  define('HEADING_TITLE', 'Додавання посилання');

  define('TEXT_MAIN', 'Заповніть дану форму.');

  define('EMAIL_SUBJECT', 'Обмін посиланнями.');
  define('EMAIL_GREET_NONE', 'Шановний %s'. "\n\n");
  define('EMAIL_WELCOME', 'Спасибі Вам за те, що Ви вирішили обмінятися посиланнями з нашим інтернет-магазином.'. "\n\n");
  define('EMAIL_TEXT', 'Ваше посилання посилання успішно додане. Воно буде доступне для всіх відвідувачів магазину одразу після перевірки адміністратором. Ви отримаєте повідомлення про перевірка Вашого посилання.'. "\n\n");
  define('EMAIL_CONTACT', 'Якщо у Вас є які-небудь питання, пишіть нам за адресою '. STORE_OWNER_EMAIL_ADDRESS. '.'. "\n\n");
  define('EMAIL_WARNING', '<b>Увага:</b> Дана email адреса була надана Вам для обміну посиланнями. Якщо у Вас є питання, задавайте їх, написавши листа за адресою '. STORE_OWNER_EMAIL_ADDRESS. '.'. "\n");
  define('EMAIL_OWNER_SUBJECT', 'Посилання успішно додане!');
  define('EMAIL_OWNER_TEXT', 'Нове посилання успішно додано, але ще не перевірено адміністратором. Будь ласка, перевірте посилання і зробіть його активним.'. "\n\n");

  define('TEXT_LINKS_HELP_LINK', '&nbsp;Допомога&nbsp;[?]');

  define('HEADING_LINKS_HELP', 'Допомога');
  define('TEXT_LINKS_HELP', '<b> Назва сайту: </b> Вкажіть назву вашого сайту. <br> <br> <b> URL Адреса: </b> URL адерс Вашого сайту, починаючи з \' http: / / \'. <br> <br> <b> Розділ: </b> Розділ, який найбільш підходить для Вашого сайту. <br> <br> <b> Опис: </b> Опис Вашого сайту. <br> <br> <b> URL Картинки: </b> Адреса банера Вашого сайту, починаючи з \'http: // \'. Цей банер буде показуватися при перегляді опис Вашого сайту. <br> Приклад адреси: http://your-domain.com/path/to/your/image.gif <br> <br> <b> Ваше ім\'я: </b> Ваше ім\'я для зв\'язку з Вами. <br> <br> <b> Email: </b> Ваш email адресу. Вказуйте, будь ласка, реальну адресу, він буде використовуватися для зв\'язку з Вами, у разі виникнення питань. <br> <br> <b> Адреса сторінки, де буде стояти наша посилання: </b> Адреса сторінки на Вашому сайті, де Ви розмістите наше посилання. <br> Приклад адреси: http://your-domain.com/path/to/your/links_page.php');
  define('TEXT_CLOSE_WINDOW', '<u> Закрити </u> [x]');

// VJ todo - move to common language file
  define('CATEGORY_WEBSITE', 'Інформація про сайт');
  define('CATEGORY_RECIPROCAL', 'Сторінка, де буде розміщена наше посилання');

  define('ENTRY_LINKS_TITLE', 'Назва сайту:');
  define('ENTRY_LINKS_TITLE_ERROR', 'Поле Назва повинна содеражть як мінімум '. ENTRY_LINKS_TITLE_MIN_LENGTH. 'символів.');
  define('ENTRY_LINKS_TITLE_TEXT', '*');
  define('ENTRY_LINKS_URL', 'URL Адреса:');
  define('ENTRY_LINKS_URL_ERROR', 'Поле URL Адреса повинно содеражть як мінімум '. ENTRY_LINKS_URL_MIN_LENGTH. 'символів.');
  define('ENTRY_LINKS_URL_TEXT', '*');
  define('ENTRY_LINKS_CATEGORY', 'Розділ:');
  define('ENTRY_LINKS_CATEGORY_TEXT', '*');
  define('ENTRY_LINKS_DESCRIPTION', 'Опис:');
  define('ENTRY_LINKS_DESCRIPTION_ERROR', 'Поле Опис повинен содеражть як мінімум '. ENTRY_LINKS_DESCRIPTION_MIN_LENGTH. 'символів.');
  define('ENTRY_LINKS_DESCRIPTION_TEXT', '*');
  define('ENTRY_LINKS_IMAGE', 'URL Картинки:');
  define('ENTRY_LINKS_IMAGE_TEXT', '');
  define('ENTRY_LINKS_CONTACT_NAME', 'Ваше ім\'я:');
  define('ENTRY_LINKS_CONTACT_NAME_ERROR', 'Поле Ваше ім\'я має содеражть як мінімум '. ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH. 'символів.');
  define('ENTRY_LINKS_CONTACT_NAME_TEXT', '*');
  define('ENTRY_LINKS_RECIPROCAL_URL', 'Адреса сторінки, де буде стояти наше посилання:');
  define('ENTRY_LINKS_RECIPROCAL_URL_ERROR', 'Поле Адреса сторінки, де буде стояти наше посилання повинно містити як мінімум '. ENTRY_LINKS_URL_MIN_LENGTH. ' символів.');
  define('ENTRY_LINKS_RECIPROCAL_URL_TEXT', '*');
?>
