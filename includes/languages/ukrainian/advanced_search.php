<?php
/*
  $Id: advanced_search.php,v 1.1.1.1 2003/09/18 19:04:28 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  define('NAVBAR_TITLE_1', 'Розширений пошук');
  define('NAVBAR_TITLE_2', 'Результати пошуку');

  define('HEADING_TITLE_1', 'Розширений пошук');
  define('HEADING_TITLE_2', 'Товари, по Вашому запиту');

  define('HEADING_SEARCH_CRITERIA', 'Пошук товарів');

  define('TEXT_SEARCH_IN_DESCRIPTION', 'Шукати в описі товарів');
  define('ENTRY_CATEGORIES', 'Категорії:');
  define('ENTRY_INCLUDE_SUBCATEGORIES', 'включаючи підкатегорії');
  define('ENTRY_MANUFACTURERS', 'Виробники:');
  define('ENTRY_PRICE_FROM', 'Ціна від:');
  define('ENTRY_PRICE_TO', 'до:');
  define('ENTRY_DATE_FROM', 'Дата додавання від:');
  define('ENTRY_DATE_TO', 'до:');

  define('TEXT_SEARCH_HELP_LINK', '<u>Рекомендації з пошуку</u> [?]');

  define('TEXT_ALL_CATEGORIES', 'Всі категорії');
  define('TEXT_ALL_MANUFACTURERS', 'Всі виробники');

  define('HEADING_SEARCH_HELP', 'Рекомендації з пошуку');
  define('TEXT_SEARCH_HELP', 'Система пошуку дозволяє Вам шукати продукти, назви, описи і виготівників по ключовому слову. <br> <br> При пошуку, Ви можете розділяти ключові слова і фрази конструкціями * AND *, * OR *. Наприклад, ви можете ввести <u>Кишенькові комп\'ютери AND аксесуари</u>. В результаті будуть виведені посилання, що містять обидва слова. Тим не менше, якщо Ви водите <u>Кишенькові комп\'ютери OR аксесуари</u>, Ви отримаєте список, який містить обидва або одне зі слів, заданих в пошуку. Якщо слова не розділяються символами AND або OR, пошук буде працювати з визначенням OR. <br> <br> Ви можете також знайти точно задані слова, включаючи їх у лапки. Наприклад, якщо Ви шукаєте <u>"Карти пам\'яті"</u>, Ви отримаєте список продуктів, які містять цю фразу цілком. <br> <br> Дужки можуть використовуватися, щоб керувати порядком логічних дій. Наприклад, Ви можете ввести <u>Комп\'ютери (кишенькові or ноутбуки)</u>. ');
  define('TEXT_CLOSE_WINDOW', '<u>Закрити вікно </u> [x]');

  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Код товару');
  define('TABLE_HEADING_PRODUCTS', 'Назва');
  define('TABLE_HEADING_MANUFACTURER', 'Виробник');
  define('TABLE_HEADING_QUANTITY', 'Кількість');
  define('TABLE_HEADING_PRICE', 'Ціна');
  define('TABLE_HEADING_WEIGHT', 'Вага');
  define('TABLE_HEADING_BUY_NOW', 'Купити зараз');
  define('TABLE_HEADING_PRODUCT_SORT', 'Порядок');


  define('TEXT_NO_PRODUCTS', '<br> <span style="font-size: 11px;">За Вашим запитом - <b>'. stripslashes($_GET['keywords']). '</b> - нічого не знайдено.</span> <br> <br> Рекомендації з пошуку: <ol> <li> Перевіряйте правильність написання запиту. </li> <li> Використовуйте різні ключові слова. </li> <li> Використовуйте кілька ключових слів. </li></ol> ');

  define('ERROR_AT_LEAST_ONE_INPUT', 'Ви не заповнили одне з необхідних полів форми.');
  define('ERROR_INVALID_FROM_DATE', 'Неправильно заповнене поле Дата додавання від.');
  define('ERROR_INVALID_TO_DATE', 'Неправильно заповнене поле Дата додавання до.');
  define('ERROR_TO_DATE_LESS_THAN_FROM_DATE', 'Значення поля Дата додавання від повинне бути більше значення поля Дата додавання до.');
  define('ERROR_PRICE_FROM_MUST_BE_NUM', 'Поле Ціна від повинне містити лише цифри.');
  define('ERROR_PRICE_TO_MUST_BE_NUM', 'Поле Ціна до повинно містити лише цифри.');
  define('ERROR_PRICE_TO_LESS_THAN_PRICE_FROM', 'Значення поля Ціна від повинне бути більше значення поля Ціна до.');
  define('ERROR_INVALID_KEYWORDS', 'Пошуковий запит складено невірно.');

  define('TEXT_REPLACEMENT_SUGGESTION', 'Також рекомендуємо шукати:');

  define('TABLE_HEADING_INFO', 'Короткий опис');

// Begin Buy Now button mod

  define('TEXT_BUY', 'Купити 1 \' ');
  define('TEXT_NOW', '\' зараз ');

// End Buy Now button mod
?>