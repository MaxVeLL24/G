<?php
/*
  $Id: russian.php,v 1.1.1.1 2003/09/18 19:04:27 wilt Exp $

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
@setlocale(LC_TIME, 'ru_RU.CP1251');
define('GO_COMPARE', 'У Списку');
define('IN_WHISHLIST', 'В бажаннях');
define('COMPARE', 'Порівняти');
define('COMPARE_BOX', 'Порівняти товари');
define('WHISH', 'Бажання');
// HMCS: Begin Autologon ******************************************** **********************
define('ENTRY_REMEMBER_ME', 'Запам’ятати мене <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:win_autologon();"> <b> <u> прочитайте спочатку це </u> </b> </a> ');
// HMCS: End Autologon ******************************************** **********************

define('DATE_FORMAT_SHORT', '%d/%m/%Y'); // This is used for strftime ()
// define('DATE_FORMAT_LONG', '% A% d% B,% Y'); // This is used for strftime ()
// define('DATE_FORMAT_LONG', '% d% B% Y р'); // This is used for strftime ()
define('DATE_FORMAT_LONG', '%d.%m.%Y'); // This is used for strftime ()
define('DATE_FORMAT', 'd.m.Y h: i: s'); // This is used for date ()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT. '%H:%M:%S');

////
// Return date in raw format
// $ Date should be in format mm / dd / yyyy
// Raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw ($date, $reverse = false) {
   if ($reverse) {
     return substr ($date, 3, 2). substr ($date, 0, 2). substr ($date, 6, 4);
   } Else {
     return substr ($date, 6, 4). substr ($date, 3, 2). substr ($date, 0, 2);
   }
}

// If USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'RUR');

// Global entries for the <html> tag
define('HTML_PARAMS', 'dir="LTR" lang="uk"');

// Charset for web pages and emails
define('CHARSET', 'utf-8'); // UTF-8

// Page title
define('TITLE', 'Інтернет-магазин');

// Header text in includes / header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Реєстрація');
define('HEADER_TITLE_MY_ACCOUNT', 'Мої дані');
define('HEADER_TITLE_CART_CONTENTS', 'Кошик');
define('HEADER_TITLE_CHECKOUT', 'Оформити замовлення');
define('HEADER_TITLE_TOP', 'Головна');
define('HEADER_TITLE_CATALOG', 'Каталог');
define('HEADER_TITLE_LOGOFF', 'Вихід');
define('HEADER_TITLE_LOGIN', 'Мої дані');

// Footer text in includes / footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'чоловік відвідали магазин c');

define('SIGN_FROM_SOC', 'Увійти через соц. мережі');
define('CALL_PROBLEM_TITLE', 'Не дозвонилися?');
define('ONLINE_SUPPORT_HEADING', 'Онлайн консультант');
define('BOX_SHOPPING_CART_PRODUCTS', '<span>%s</span> товар(ів) вартістю %s');


// Text for gender
define('MALE', 'Чоловік');
define('FEMALE', 'Жіночий');
define('MALE_ADDRESS', 'Пан');
define('FEMALE_ADDRESS', 'Пані');

// Text for date of birth example
define('DOB_FORMAT_STRING', 'dd / mm / yyyy');

// Quick_find box text in includes / boxes / quick_find.php
define('BOX_SEARCH_TEXT', 'Введіть слово для пошуку.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'Розширений пошук');

// Reviews box text in includes / boxes / reviews.php
define('BOX_REVIEWS_WRITE_REVIEW', 'Напишіть Вашу думку про товар!');
define('BOX_REVIEWS_NO_REVIEWS', 'До теперішнього часу немає жодного відгуку');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s з 5 зірок!');

// Shopping_cart box text in includes / boxes / shopping_cart.php
define('BOX_SHOPPING_CART_EMPTY', 'Кошик порожній');

// Notifications box text in includes / boxes / products_notifications.php
define('BOX_NOTIFICATIONS_NOTIFY', 'Повідомте мене про нове &nbsp; <b>%s </b>');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'Не повідомляйте мені про новинки <b>%s </b>');

// Manufacturer box text
define('BOX_MANUFACTURER_INFO_HOMEPAGE', 'Сайт %s');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'Інші товари даного виробника');
define('DRUGIE_HEAD_TITLE', 'Подібні товари');

// Information box text in includes / boxes / information.php
define('BOX_INFORMATION_PRIVACY', 'Безпека');
define('BOX_INFORMATION_CONDITIONS', 'Умови та гарантії');
define('BOX_INFORMATION_SHIPPING', 'Доставка та повернення');
define('BOX_INFORMATION_CONTACT', 'Зв’яжіться з нами');

define('BOX_INFORMATION_PRICE_XLS', 'Прайс-лист (Excel)');
define('BOX_INFORMATION_PRICE_HTML', 'Карта сайту');

// Tell a friend box text in includes / boxes / tell_a_friend.php
define('BOX_TELL_A_FRIEND_TEXT', 'Повідомте своїм друзям та близьким про наш магазин');

// BEGIN allprods modification
define('BOX_INFORMATION_ALLPRODS', 'Повний список товарів');
// END allprods modification

// VJ Links Manager v1.00 begin
define('BOX_INFORMATION_LINKS', 'Посилання');
// VJ Links Manager v1.00 end

// Checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'Адреса доставки');
define('CHECKOUT_BAR_PAYMENT', 'Спосіб оплати');
define('CHECKOUT_BAR_CONFIRMATION', 'Підтвердження');
define('CHECKOUT_BAR_FINISHED', 'Замовлення оформлений!');

// Pull down default text
define('PULL_DOWN_DEFAULT', 'Виробник');
define('TYPE_BELOW', 'Вибір нижче');

// Javascript messages
define('JS_ERROR', 'Помилки при заповненні форми!\n\n Виправте, будь ласка:\n\n');

define('JS_REVIEW_TEXT', '* Поле ’Текст відгуку’ повинно містити не менше'. REVIEW_TEXT_MIN_LENGTH. 'символів. \n');

define('JS_FIRST_NAME', '* Поле ’Ім’я ’ повинно містити не менше'. ENTRY_FIRST_NAME_MIN_LENGTH. 'символів. \n');
define('JS_LAST_NAME', '* Поле ’Прізвище’ повинно містити не менше'. ENTRY_LAST_NAME_MIN_LENGTH. 'символів. \n');


define('JS_REVIEW_RATING', '* Ви не вказали рейтинг. \n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Виберіть метод оплати для Вашого замовлення. \n');

define('JS_ERROR_SUBMITTED', 'Ця форма вже заповнена. Натискайте Ok.');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Оберіть, будь ласка, метод оплати для Вашого замовлення.');

define('CATEGORY_COMPANY', 'Організація');
define('CATEGORY_PERSONAL', 'Ваші персональні дані');
define('CATEGORY_ADDRESS', 'Вашу адресу');
define('CATEGORY_CONTACT', 'Контактна інформація');
define('CATEGORY_OPTIONS', 'Розсилка');
define('CATEGORY_PASSWORD', 'Ваш пароль');

define('ENTRY_COMPANY', 'Назва компанії:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', 'Стать:');
define('ENTRY_GENDER_ERROR', 'Ви повинні вказати свою стать.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'Ім’я:');
define('ENTRY_FIRST_NAME_ERROR', 'Поле Ім’я повинно містити як мінімум '. ENTRY_FIRST_NAME_MIN_LENGTH. ' символи.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Прізвище:');
define('ENTRY_LAST_NAME_ERROR', 'Поле Прізвище повинно містити як мінімум '. ENTRY_LAST_NAME_MIN_LENGTH. ' символи.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', 'Дата народження:');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Дату народження необхідно вводити в наступному форматі: DD/MM/YYYY (приклад 21/05/1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', '* (приклад 21/05/1970)');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Поле E-Mail повинно містити як мінімум '. ENTRY_EMAIL_ADDRESS_MIN_LENGTH. ' символів.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Ваш E-Mail адреса вказана неправильно, спробуйте ще раз.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Ваш Вами E-Mail вже зареєстрований в нашому магазині, спробуйте вказати інший E-Mail адресу.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Адреса:');
define('ENTRY_STREET_ADDRESS_ERROR', 'Поле Вулиця та номер будинку повинно містити як мінімум '. ENTRY_STREET_ADDRESS_MIN_LENGTH. ' символів.');
define('ENTRY_STREET_ADDRESS_TEXT', '* Приклад: вул. Київська 8, офіс. 2');
define('ENTRY_SUBURB', 'Відділення:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Поштовий індекс:');
define('ENTRY_POST_CODE_ERROR', 'Поле Поштовий індекс повинно містити як мінімум '. ENTRY_POSTCODE_MIN_LENGTH. ' символів.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'Місто:');
define('ENTRY_CITY_ERROR', 'Поле Місто повинно містити як мінімум '. ENTRY_CITY_MIN_LENGTH. ' символів.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'Регіон:');
define('ENTRY_STATE_ERROR', 'Поле Область має містити як мінімум '. ENTRY_STATE_MIN_LENGTH. ' символів.');
define('ENTRY_STATE_ERROR_SELECT', 'Виберіть область.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Країна:');
define('ENTRY_COUNTRY_ERROR', 'Виберіть країну.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Телефон:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Поле Телефон повинно містити як мінімум '. ENTRY_TELEPHONE_MIN_LENGTH. ' символів.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Факс:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Отримувати інформацію про знижки, призи, подарунки:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'Підписатися');
define('ENTRY_NEWSLETTER_NO', 'Відмовитися від підписки');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Пароль:');
define('ENTRY_PASSWORD_ERROR', 'Ваш пароль повинен містити як мінімум '. ENTRY_PASSWORD_MIN_LENGTH. ' символів.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'Поле Підтвердіть пароль має збігатися з полем Пароль.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Підтвердіть пароль:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Поточний пароль:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Поле Пароль повинно містити як мінімум '. ENTRY_PASSWORD_MIN_LENGTH. ' символів.');
define('ENTRY_PASSWORD_NEW', 'Новий пароль:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Ваш Новий пароль повинен містити як мінімум '. ENTRY_PASSWORD_MIN_LENGTH. ' символів.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'Поля Підтвердіть пароль і Новий пароль повинні збігатися.');
define('PASSWORD_HIDDEN', '--ПРИХОВАНИЙ--');

define('FORM_REQUIRED_INFORMATION', '* Обов’язково для заповнення');

// Constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Сторінки:');
define('TEXT_RESULT_VIEW', 'На сторінку:');
define('TEXT_SWITCH_VIEW', 'Вигляд:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Показано <b>%d </b> - <b>%d </b> з <b>%d </b> позицій');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Показано <b>%d </b> - <b>%d </b> (всього <b>%d </b> замовлень)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Показано <b>%d </b> - <b>%d </b> (всього <b>%d </b> відгуків)');
// BEGIN PopTheTop Reviews in Product Description
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS_PRODUCT_INFO', '<FONT COLOR="#006699"> Показано <b>%d </b> of <b>%d </b> відгуків </FONT>');
// END PopTheTop Reviews in Product Description
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Показано <b>%d </b> - <b>%d </ b> (всього <b>%d </b> новинок)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Показано <b>%d </b> - <b>%d </b> (всього <b>%d </b> спеціальних пропозицій)');
define('TEXT_DISPLAY_NUMBER_OF_FEATURED', 'Показано <b>%d </b> - <b>%d </b> (всього <b>%d </b> рекомендованих товарів)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'Перша сторінка');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'попередня');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Наступна сторінка');
define('PREVNEXT_TITLE_LAST_PAGE', 'Остання сторінка');
define('PREVNEXT_TITLE_PAGE_NO', 'Сторінка %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Попередні %d сторінок');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Наступні %d сторінок');
define('PREVNEXT_BUTTON_FIRST', 'ПЕРША');
define('PREVNEXT_BUTTON_PREV', '<<');
define('PREVNEXT_BUTTON_NEXT', '>>');
define('PREVNEXT_BUTTON_LAST', 'ОСТАННЯ');

define('IMAGE_BUTTON_ADD_ADDRESS', 'Додати адресу');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Адресна книга');
define('IMAGE_BUTTON_BACK', 'Назад');
define('IMAGE_BUTTON_BUY_NOW', 'Купити зараз');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Змінити адресу');
define('IMAGE_BUTTON_CHECKOUT', 'Оформити замовлення');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Підтвердити Замовлення');
define('IMAGE_BUTTON_CONTINUE', 'Продовжити');
define('IMAGE_BUTTON_SEND', 'Відправити');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Повернутися в магазин');
define('IMAGE_BUTTON_DELETE', 'Видалити');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Редагувати облікові дані');
define('IMAGE_BUTTON_HISTORY', 'Історія замовлень');
define('IMAGE_BUTTON_LOGIN', 'Увійти');
define('IMAGE_BUTTON_IN_CART', 'У кошику');
define('IMAGE_BUTTON_ADDTO_CART', 'Купити');

define('IMAGE_BUTTON_NOTIFICATIONS', 'Повідомлення');
define('IMAGE_BUTTON_QUICK_FIND', 'Швидкий пошук');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Видалити повідомлення');
define('IMAGE_BUTTON_REVIEWS', 'Відгуки');
define('IMAGE_BUTTON_MORE_REVIEWS', 'Click to read more Reviews on this item');
define('IMAGE_BUTTON_SEARCH', 'Шукати');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Способи доставки');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Написати одному');
define('IMAGE_BUTTON_UPDATE', 'Оновити');
define('IMAGE_BUTTON_UPDATE_CART', 'Перерахувати');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Написати відгук');
define('IMAGE_REDEEM_VOUCHER_TITLE', 'Купон');
define('IMAGE_REDEEM_VOUCHER', 'Застосувати');

define('SMALL_IMAGE_BUTTON_DELETE', 'Видалити');
define('SMALL_IMAGE_BUTTON_EDIT', 'Змінити');
define('SMALL_IMAGE_BUTTON_VIEW', 'Дивитися');

define('ICON_ARROW_RIGHT', 'Перейти');
define('ICON_CART', 'У кошик');
define('ICON_ERROR', 'Помилка');
define('ICON_SUCCESS', 'Виконано');
define('ICON_WARNING', 'Увага');

define('TEXT_GREETING_PERSONAL', 'Ласкаво просимо, <span class="greetUser">%s! </span> Ви хочете подивитися які <a href="%s"> <u> нові товари </u> </a> надійшли в наш магазин? ');
define('TEXT_CUSTOMER_GREETING_HEADER', 'Ласкаво просимо!');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>Якщо Ви не %s, будь ласка, <a href="%s"> <u> введіть </u> </a> свої дані для входу. </small>') ;
define('TEXT_GREETING_GUEST', 'Ласкаво просимо, <span class="greetUser"> шановний гостю </span> <br> Якщо Ви наш постійний клієнт, <a href="%s"> <u> введіть Ваші персональні дані </u> </a> для входу. Якщо Ви у нас вперше і хочете зробити покупки, Вам необхідно <a href="%s"> <u> зареєструватися </u> </a>. ');

define('TEXT_SORT_PRODUCTS', 'Сортувати за:');
define('TEXT_DESCENDINGLY', 'спаданням');
define('TEXT_ASCENDINGLY', 'по зростанню');
define('TEXT_BY', ', колонка');

define('TEXT_REVIEW_BY', '%s');
define('TEXT_REVIEW_WORD_COUNT', '%s слова');
define('TEXT_REVIEW_RATING', '<font color="#778188"> Оцінка: %s </font>');
define('TEXT_REVIEW_DATE_ADDED', 'Дата додавання:% s');
define('TEXT_NO_REVIEWS', 'До теперішнього часу немає відгуків, Ви можете стати першим.');

define('TEXT_NO_NEW_PRODUCTS', 'Сьогодні немає нових продуктів.');

define('TEXT_NO_PRODUCTS', 'Жодного товару не знайдено.');

define('TEXT_UNKNOWN_TAX_RATE', 'Невідома податкова ставка');

define('TEXT_REQUIRED', '<span class="errorText"> Обов’язково </span>');

// Down For Maintenance
define('TEXT_BEFORE_DOWN_FOR_MAINTENANCE', 'Увага: Магазин закритий з технічних причин до:');
define('TEXT_ADMIN_DOWN_FOR_MAINTENANCE', 'Увага: Магазин закритий з технічних причин');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"> <b> <small> ПОМИЛКА: </small> Неможливо відправити email через сервер SMTP. Перевірте, будь ласка, Ваші установки php.ini і якщо необхідно, скоректуйте сервер SMTP. </b> </font> ');
define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Попередження: Не видалена директорія установки магазину: '.dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']).'/install. Будь ласка, видаліть цю директорію для безпеки.');
define('WARNING_CONFIG_FILE_WRITEABLE', 'Попередження: Файл конфігурації доступний для запису: '.dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']). '/includes/configure.php. Це - потенційний ризик безпеки - будь ласка, встановіть необхідні права доступу до цього файлу. ');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Попередження: директорія сесій не існує: '.tep_session_save_path().'. Сесії не працюватимуть поки ця директорія не буде створена.');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Попередження: Немає доступу до каталогу сесій: '.tep_session_save_path ().'. Сесії не працюватимуть поки не встановлені необхідні права доступу.');
define('WARNING_SESSION_AUTO_START', 'Попередження: опція session.auto_start включена - будь ласка, вимкніть цю опцію у файлі php.ini і перезапустіть веб-сервер.');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Попередження: Директорія відсутня: '.DIR_FS_DOWNLOAD.'. Створіть директорію.');


define('TEXT_CCVAL_ERROR_INVALID_DATE', 'Ви вказали невірну дату закінчення терміну дії кредитної картки. <br> Спробуйте ще раз.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'Ви вказали невірний номер кредитної картки. <br> Спробуйте ще раз.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'Перші цифри Вашої кредитної картки: %s <br> Якщо Ви вказали номер своєї кредитної картки правильно, повідомляємо Вам, що ми не приймаємо до оплати даний тип кредитних карток. <br> Якщо Ви вказали номер кредитної картки невірно, спробуйте ще раз. ');

require(DIR_WS_LANGUAGES. 'add_ccgvdc_russian.php');
////////////////////////////////////////////////// ///////////////////
// HEADER.PHP
// Header Links
define('HEADER_LINKS_DEFAULT', 'Головна');
define('HEADER_LINKS_WHATS_NEW', 'Новинки');
define('HEADER_LINKS_SPECIALS', 'Знижки');
define('HEADER_LINKS_REVIEWS', 'Відгуки');
define('HEADER_LINKS_LOGIN', 'Увійти');
define('HEADER_LINKS_LOGOFF', 'Вихід');
define('HEADER_LINKS_PRODUCTS_ALL', 'Каталог');
define('HEADER_LINKS_ACCOUNT_INFO', 'Ваші дані');
define('HEADER_LINKS_CHECKOUT', 'Оформити замовлення');
define('HEADER_LINKS_CART', 'Кошик');
define('HEADER_LINKS_DVD', 'Каталог товарів');

////////////////////////////////////////////////// ///////////////////

// BOF: Lango added for print order mod
define('IMAGE_BUTTON_PRINT_ORDER', 'Версія для друку');
// EOF: Lango added for print order mod

// WebMakers.com Added: Attributes Sorter
require(DIR_WS_LANGUAGES. $language. '/'. 'attributes_sorter.php');
define('BOX_LOGINBOX_HEADING', 'Вхід');
define('BOX_LOGINBOX_EMAIL', 'E-Mail:');
define('BOX_LOGINBOX_PASSWORD', 'Пароль:');
define('IMAGE_BUTTON_LOGIN', 'Увійти');

define('BOX_HEADING_LOGIN_BOX_MY_ACCOUNT', 'Мої дані');
define('LOGIN_BOX_MY_CABINET', 'Ваш кабінет');
define('MY_ORDERS_VIEW', 'Мої замовлення');
define('MY_ACCOUNT_PASSWORD', 'Змінити пароль');
define('LOGIN_BOX_ACCOUNT_EDIT', 'Змінити дані');
define('LOGIN_BOX_ACCOUNT_HISTORY', 'Історія замовлень');
define('LOGIN_BOX_ADDRESS_BOOK', 'Адресна книга');
define('LOGIN_BOX_PRODUCT_NOTIFICATIONS', 'Повідомлення');
define('LOGIN_BOX_MY_ACCOUNT', 'Мої дані');
define('LOGIN_BOX_LOGOFF', 'Вихід');

define('LOGIN_FROM_SITE', 'Увійти');


// VJ Guestbook for OSC v1.0 begin
define('BOX_INFORMATION_GUESTBOOK', 'Гостьова книга');
// VJ Guestbook for OSC v1.0 end

// VJ Guestbook for OSC v1.0 begin
define('GUESTBOOK_TEXT_MIN_LENGTH', '10'); // [TODO] move to config db table
define('JS_GUESTBOOK_TEXT', '* Поле ’Ваше повідомлення ’ повинно містити як мінімум '. GUESTBOOK_TEXT_MIN_LENGTH. ' символів. \n');
define('JS_GUESTBOOK_NAME', '* Ви повинні заповнити поле ’Ваше ім’я’. \n');
// VJ Guestbook for OSC v1.0 end

// VJ Guestbook for OSC v1.0 begin
define('TEXT_DISPLAY_NUMBER_OF_GUESTBOOK_ENTRIES', 'Показано <b>%d</b> - <b>%d</b> (всього <b>%d</b> записів)');
// VJ Guestbook for OSC v1.0 end

// VJ Guestbook for OSC v1.0 begin
define('IMAGE_BUTTON_SIGN_GUESTBOOK', 'Додати запис');
// VJ Guestbook for OSC v1.0 end

// VJ Guestbook for OSC v1.0 begin
define('TEXT_GUESTBOOK_DATE_ADDED', 'Дата: %s');
define('TEXT_NO_GUESTBOOK_ENTRY', 'Поки немає жодного запису в гостьовій книзі. Будьте першими!');
// VJ Guestbook for OSC v1.0 end

define('DISCOUNT_HEADING', 'Знижки');

define('HELP', '<a href="http://web.icq.com/whitepages/message_me/1,,,00.icq?uin=' . STORE_OWNER_ICQ_NUMBER . '&action=message" target="_blank"><img src="http://web.icq.com/whitepages/online?icq=' . STORE_OWNER_ICQ_NUMBER . '&amp;img=5" title="Статус ICQ" align="absmiddle" border="0">' . STORE_OWNER_ICQ_NUMBER . '</a>
<br>
');

define('ICQ', 'ICQ: <br>');
define('TEXT_MORE_INFO', 'Детальніше ...');

// Article Manager
define('BOX_ALL_ARTICLES', 'Всі статті');
define('BOX_NEW_ARTICLES', 'Нові статті');
define('HEAD_ARTICLES_LIST', 'Корисні статті');
define('TEXT_DISPLAY_NUMBER_OF_ARTICLES', '<font color="#5a5a5a"> Показано <b>%d</b> - <b>%d </b> (всього <b>%d </b> новин) </font> ');
define('TEXT_DISPLAY_NUMBER_OF_ARTICLES_NEW', 'Показано <b>%d</b> - <b>%d</b> (всього <b>%d </b> нових статей)');
define('TABLE_HEADING_AUTHOR', 'Автор');
define('TABLE_HEADING_ABSTRACT', 'Резюме');
define('BOX_HEADING_AUTHORS', 'Автори статей');
define('NAVBAR_TITLE_DEFAULT', 'Статті');

define('TABLE_HEADING_INFO', 'Короткий опис');

// TotalB2B start
define('PRICES_LOGGED_IN_TEXT', '<b> <font style="color:#CE1930">Ціна: </b> </font> <a href="create_account.php"> тільки опт </a>');
// TotalB2B end

define('PRODUCTS_ORDER_QTY_TEXT', 'У кошик:');
define('PRODUCTS_ORDER_QTY_MIN_TEXT', '<br>'. 'Мінімум:');
define('PRODUCTS_ORDER_QTY_MIN_TEXT_INFO', 'Мінімум одиниць для замовлення:'); // order_detail.php
define('PRODUCTS_ORDER_QTY_MIN_TEXT_CART', 'Мінімум одиниць для замовлення:'); // order_detail.php
define('PRODUCTS_ORDER_QTY_MIN_TEXT_CART_SHORT', 'Мінімум:'); // order_detail.php
define('PRODUCTS_ORDER_QTY_UNIT_TEXT', ', Крок:');
define('PRODUCTS_ORDER_QTY_UNIT_TEXT_INFO', 'Крок:'); // order_detail.php
define('PRODUCTS_ORDER_QTY_UNIT_TEXT_CART', 'Крок:'); // order_detail.php
define('PRODUCTS_ORDER_QTY_UNIT_TEXT_CART_SHORT', 'Крок:'); // order_detail.php
define('ERROR_PRODUCTS_QUANTITY_ORDER_MIN_TEXT', '');
define('ERROR_PRODUCTS_QUANTITY_INVALID', 'Ви намагаєтеся покласти в кошик невірну кількість товару:');
define('ERROR_PRODUCTS_QUANTITY_ORDER_UNITS_TEXT', '');
define('ERROR_PRODUCTS_UNITS_INVALID', 'Ви намагаєтеся покласти в кошик невірну кількість товару:');

// Comments

define('COMMENT_HEAD_TITLE', 'Коментарі');
define('ADD_COMMENT_HEAD_TITLE', 'Залишити свій відгук про ');

// Poll Box Text
define('_RESULTS', 'Результати');
define('_VOTE', 'Голосувати');
define('_COMMENTS', 'Відгуків:');
define('_VOTES', 'Голосів:');
define('_NOPOLLS', 'Немає опитувань');
define('_NOPOLLSCONTENT', 'На даний момент немає жодного активного опитування, Ви можете подивитися результати всіх проводилися раніше опитувань. <br> <br> <a href="pollbooth.php"> ['._POLLS.']');

define('IMAGE_BUTTON_PREVIOUS', 'Попередній товар');
define('IMAGE_BUTTON_NEXT', 'Наступний товар');
define('IMAGE_BUTTON_RETURN_TO_PRODUCT_LIST', 'Повернутися до списку товарів');
define('PREV_NEXT_PRODUCT', 'Товар');
define('PREV_NEXT_PRODUCT1', 'з');
define('PREV_NEXT_CAT', 'категорії');
define('PREV_NEXT_MB', 'виробника');

define('PREV_PRODUCT', 'Попередній товар');
define('NEXT_PRODUCT', 'Наступний товар');
define('SHOW_CATALOG', 'Показати каталог');
define('PRODUCT_AVIAIlABLE', 'В наявності');
define('PRODUCT_NOT_AVIAIlABLE', 'Немає в наявності');

define('BOX_TEXT_DOWNLOAD', 'Ваші завантаження:');
define('BOX_DOWNLOAD_DOWNLOAD', 'Завантажити файли');
define('BOX_TEXT_DOWNLOAD_NOW', 'Завантажити');

// Російські назви боксів

define('BOX_HEADING_CATEGORIES', 'Розділи');
define('BOX_HEADING_INFORMATION', 'Інформація');
define('BOX_HEADING_TEMPLATE_SELECT', 'Вибір дизайну');
define('BOX_HEADING_MANUFACTURERS', 'Виробники');
define('BOX_HEADING_SPECIALS', 'Знижки');
define('BOX_HEADING_NEWSDESK_LATEST', 'Останні новини');
define('BOX_HEADING_NEWSDESK_ALL', 'Всі новини');
define('BOX_HEADING_SEARCH', 'Пошук');
define('BOX_HEADING_WHATS_NEW', 'Новинки');
define('BOX_HEADING_LANGUAGES', 'Мова');
define('BOX_HEADING_NEWSBOX', 'Новини');
define('BOX_HEADING_ALL_NEWS', 'Всі новини');
define('BOX_HEADING_FEATURED', 'Ми рекомендуємо');
define('BOX_HEADING_SHOP_BY_PRICE', 'Сортування за ціною');
define('BOX_HEADING_FILTER_BY_PRICE', 'Ціна');
define('BOX_HEADING_SELECTED_FILTERS', 'Обрані фільтри');
define('TEXT_CLEAR_SELECTED_FILTERS', 'Очистити список');
define('BOX_HEADING_NEWSDESK_CATEGORIES', 'Новини');
define('BOX_HEADING_ARTICLES', 'Статті');
define('BOX_HEADING_AUTHORS', 'Автори');
define('BOX_HEADING_LINKS', 'Обмін посиланнями');
define('BOX_HEADING_SHOPPING_CART', 'Кошик');
define('BOX_HEADING_SHOPPING_ENTER', 'Перейти до кошику');
define('BOX_HEADING_DOWNLOAD', 'Файли');
define('BOX_HEADING_LOGIN', 'Вхід');
define('HELP_HEADING', 'Консультант');
define('BOX_HEADING_WISHLIST', 'Відкладені товари');
define('BOX_WISHLIST_ITEMS', 'шт.');
define('BOX_HEADING_REVIEWS', 'Відгуки');
define('BOX_HEADING_CUSTOMER_ORDERS', 'Історія замовлень');
define('BOX_HEADING_AFFILIATE', 'Зароби з нами');
define('BOX_HEADING_MANUFACTURER_INFO', 'Виробник');
define('BOX_HEADING_BESTSELLERS', 'ТОП продажів');
define('BOX_HEADING_MOSTVIEWED', 'TOП переглядів');
define('NEW_PRODUCTS', 'Новинки');
define('SERVICE', 'Сервіс');
define('MADE_BY', 'Розробник:');
define('SITEMAP', 'Мапа сайту');
define('BOX_HEADING_BESTSELLERS', 'Лідери продажів');
define('BOX_HEADING_TELL_A_FRIEND', 'Розповісти товаришу');
define('BOX_HEADING_NOTIFICATIONS', 'Повідомлення');
define('BOX_HEADING_CURRENCIES', 'Валюта');
define('BOX_HEADING_FAQDESK_CATEGORIES', 'FAQ');
define('BOX_HEADING_FAQDESK_LATEST', 'Свіжі питання в FAQ');
define('_POLLS', 'Опитування');
define('BOX_HEADING_LAST_VIEWED', 'Нещодавно переглянуті товари');

// Product info
define('PRODUCT_POPCART_IMAGE', 'Фото');
define('PRODUCT_POPCART_NAME', 'Найменування');
define('PRODUCT_POPCART_PRICE', 'Ціна');
define('PRODUCT_POPCART_QTY', 'Кількість');
define('PRODUCT_POPCART_TOTAL', 'Сума');

define('NEW_CUSTOMER', 'Новий покупець');
define('RETURNING_CUSTOMER', 'Постійний клієнт');

// Способи і вартість доставки в кошику
   define('SHIPPING_OPTIONS', 'Способи і вартість доставки:');
   if (strstr ($PHP_SELF, 'shopping_cart.php')) {
     define('SHIPPING_OPTIONS_LOGIN', 'Будь ласка, <a href="'. tep_href_link(FILENAME_LOGIN,'','SSL').'"><u>увійдіть</u></a> в магазин, щоб побачити точну вартість доставки Вашого замовлення. ');
   } else {
     define('SHIPPING_OPTIONS_LOGIN', 'Будь ласка, увійдіть в магазин, щоб побачити способи і вартість доставки Вашого замовлення.');
   }
   define('SHIPPING_METHOD_TEXT', 'Способи доставки:');
   define('SHIPPING_METHOD_RATES', 'Вартість:');
   define('SHIPPING_METHOD_TO', 'Адреса доставки:');
   define('SHIPPING_METHOD_TO_NOLOGIN', 'Адреса доставки: <a href="'. tep_href_link(FILENAME_LOGIN,'','SSL').'"><u>Увійдіть</u></a>');
   define('SHIPPING_METHOD_FREE_TEXT', 'Безкоштовна доставка');
   define('SHIPPING_METHOD_ALL_DOWNLOADS', '- Скачування');
   define('SHIPPING_METHOD_RECALCULATE', 'Розрахувати');
   define('SHIPPING_METHOD_ZIP_REQUIRED', 'true');
   define('SHIPPING_METHOD_ADDRESS', 'Адреса:');
   define('SHIPPING_METHOD_QTY', 'Кількість товару:');
   define('SHIPPING_METHOD_WEIGHT', 'Вага товару:');
   define('SHIPPING_METHOD_WEIGHT1', 'кг.');

   define('LOW_STOCK_TEXT1', 'Товар на складі закінчується:');
   define('LOW_STOCK_TEXT2', 'Код товару');
   define('LOW_STOCK_TEXT3', 'Поточне кількість:');
   define('LOW_STOCK_TEXT4', 'Посилання на товар:');
   define('LOW_STOCK_TEXT5', 'Поточне значення змінної Ліміт кількості товару на складі:');

// Wishlist box text in includes / boxes / wishlist.php

   define('BOX_HEADING_CUSTOMER_WISHLIST', 'Відкладені товари');
   define('TEXT_WISHLIST_COUNT', 'На даний момент відкладено товарів: %s.');

   define('BOX_TEXT_VIEW', 'Показати');
   define('BOX_TEXT_HELP', 'Допомога');
   define('BOX_WISHLIST_EMPTY', 'немає відкладених товарів.');
   define('BOX_TEXT_NO_ITEMS', 'немає відкладених товарів.');
   define('IMAGE_BUTTON_ADD_WISHLIST', 'Відкласти');

   define('TEXT_VERSION', 'Версія збирання:');
   define('TOTAL_QUERIES', 'Всього запитів:');
   define('TOTAL_TIME', 'Час виконання:');

// Otf 1.71 defines needed for Product Option Type feature.
   define('PRODUCTS_OPTIONS_TYPE_SELECT', 0);
   define('PRODUCTS_OPTIONS_TYPE_TEXT', 1);
   define('PRODUCTS_OPTIONS_TYPE_RADIO', 2);
   define('PRODUCTS_OPTIONS_TYPE_CHECKBOX', 3);
   define('PRODUCTS_OPTIONS_TYPE_TEXTAREA', 4);
   define('TEXT_PREFIX', 'txt_');
   define('PRODUCTS_OPTIONS_VALUE_TEXT_ID', 0); // Must match id for user defined "TEXT" value in db table TABLE_PRODUCTS_OPTIONS_VALUES


//include('includes/languages/english_support.php');
include('includes/languages/russian_newsdesk.php');
include('includes/languages/russian_faqdesk.php');

// Product reviews

// define('NAVBAR_TITLE', 'Кошик');

define('SUB_TITLE_FROM', 'Від:');
define('SUB_TITLE_REVIEW', 'Текст повідомлення:');
define('SUB_TITLE_RATING', 'Рейтинг:');
define('TEXT_NO_HTML', '<small><font color="#505C65"><b>ЗАУВАЖЕННЯ:</b></font></small>&nbsp; HTML теги не підтримуються!');
define('TEXT_BAD', '<small><font color="#505C65"><b>ПОГАНО</b></font></small>');
define('TEXT_GOOD', '<small><font color="#505C65"><b>ОТЛИЧНО</b></font></small>');

define('TEXT_CLICK_TO_ENLARGE', 'Збільшити');


// Product tabs
define('DESCRIPTION', 'Опис');
define('FEATURES', 'Характеристики');
define('COMMENTS', 'Коментарі');
define('RELATED_PRODUCTS', 'Супутні товари');

define('ALSO_PURCHASED', 'Також з цим товаром замовляли');
define('FORWARD', 'Уперед');
define('BACKWARD', 'Назад');
define('MY_ORDER', 'Моє замовлення');

define('USER_ACCOUNT_NAVIGATION', 'Навігація');
define('MY_ACCOUNT_INFORMATION', 'Моя інформація');

define('MY_ORDERS_VIEW', 'Мої замовлення');
define('MY_ACCOUNT_PASSWORD', 'Змінити пароль');

define('MY_ACCOUNT_MY_GROUP', 'Ваша група');
define('MY_ACCOUNT_MY_DISCOUNT', 'Ваша знижка');

define('LOGIN_FROM_SITE', 'Увійти');

define('PHOTOGALLERY', 'Фотогалерея');
define('HEADING_PEREZVONIM', 'Ми вам передзвонимо');

define('SEND_MESSAGE', 'Відправити');
define('SHOW_ALL_SRCH_RES', 'Показати всі результати');
define('ALL_RIGHTS', 'Всі права захищені');
define('ADDRESS_MAINPAGE', 'Україна, 020232 Драгоманова 18, офіс 5');

define('BOX_CURRENCY', 'Валюта:');

define('SORT_NAME_ASC', 'А-Я');
define('SORT_NAME_DESC', 'Я-А');
define('SORT_PRICE_ASC', 'дешевше зверху');
define('SORT_PRICE_DESC', 'дорожче зверху');
define('SORT_NEW', 'нові зверху');
define('SORT_POPULAR', 'популярні зверху');
define('VIEW_LIST', 'списком');
define('VIEW_COL', 'стовпчиком');
define('SORT_ALL', 'Все');

define('PROD_ENLARGE', 'Збільшити');
define('PROD_BETTER_TOGETHER', 'Також ви можете придбати зі знижкою');

define('COMP_PROD_HEAD', 'Порівняння товарів');
define('COMP_PROD_NAME', 'Назва');
define('COMP_PROD_IMG', 'Картинка');
define('COMP_PROD_PRICE', 'Ціна');
define('COMP_PROD_CLEAR', 'Очистити все');
define('COMP_PROD_BACK', 'Повернутися');
define('COMP_PROD_ADD_TO', 'Додайте товари до порівняння!');

define('ATTRIBUTES', 'Атрибути');
define('QUICK_ORDER', 'Швидке замовлення');
define('QUICK_ORDER_SUCCESS', 'Заявку відправлено, незабаром з Вами зв’яжеться менеджер');
define('QUICK_ORDER_BUTTON', 'Купити в один клік');
define('QUICK_PRE_ORDER_BUTTON','Передзамовл.');

define('SOC_CREATE1', 'Ласкаво просимо');
define('SOC_CREATE2', 'Ваше місто');
define('SOC_CREATE3', 'Ваш логін(e-mail)');
define('SOC_CREATE4', 'Ваш пароль');
define('SOC_CREATE5', 'Для завершення реєстрації <br /> введіть, будь ласка, ваш');
define('SOC_CREATE6', 'Підтвердити');
define('SOC_CREATE7', 'Дякую, ви успішно зареєстровані!');

define('DIALOG_DIS', 'Задати питання');

// Всплывающие подсказки для логотипов платёжных систем в подвале
define('FPSLT_MASTER_CARD', 'Master Card');
define('FPSLT_WEBMONEY', 'Webmoney');
define('FPSLT_VISA', 'Visa');
define('FPSLT_YANDEX_MONEY', 'Яндекс.Гроші');

// Текст для чекбокса "Все" в фильтре категории
define('TEXT_FILTER_ALL', 'Усі');

define('NO_PRODUCTS_FOUND', 'Немає товарів, що відповідають критеріям пошуку.');
define('LOAD_MORE_PRODUCTS', 'Показати ще товари');

define('PRODUCTS_FOUND_HEADER', 'Результати пошуку за запитом «%s»');
define('NO_PRODUCTS_FOUND_HEADER', 'За вашим запитом нічого не знайдено');
define('MODULE_ARTICLES_XSELL_TITLE', 'Статті по товару');
// Текст в логотипе
define('LOGO_TEXT', 'Інтернет-магазин іграшок');

// График работы
define('TEXT_SCHEDULE', '<span>Працюємо:</span><br> Пн-Пт 09:00-18:00,<br> <span>Сб, Нд вихідний</span>');

// Заголовки колонок в подвале
define('FOOTER_COLUMN_INFORMATION_TITLE', 'Інформація');
define('FOOTER_COLUMN_ABOUT_US_TITLE', 'Про наш магазин');
define('FOOTER_COLUMN_CATEGORIES_TITLE', 'Розділи');
define('FOOTER_COLUMN_SUBSCRIBE_TITLE', 'Новини Gigimot');
define('TEXT_SUBSCRIBE', 'Підписатися');
define('FOOTER_SUBSCRIBE_DESCRIPTION', 'Отримуйте оновлення улюбленого магазину чи товару. Залишайтеся на зв’язку.');
define('FOOTER_SUBSCRIBE_FORM_EMAIL_INPUT_PLACEHOLDER', 'Ваша адреса елелектронної пошты');
define('READMORE_ARTICLE_LINK_TITLE', 'Читати статтю «%s»');
define('READMORE_NEWS_LINK_TITLE', 'Читати новину «%s»');

// Модуль производителей на главной
define('MPM_MANUFACTURESR_LIST_LABEL', 'Виробники');
define('MPM_MANUFACTURESR_OTHER', 'Інші');
define('MPM_MANUFACTURESR_ALL', 'Усі бренди');

// Модуль новинок на главной
define('MPM_NEW_PRODUCTS_ALL', 'Усі новинки');

// Модуль рекомендуемых товаров
define('MPM_FEATURED_ALL', 'Усі товари');

// Модуль просмотренных товаров
define('MPM_VIEWED_PRODUCTS_TITLE', 'Переглянуті товари');
define('MPM_VIEWED_PRODUCTS_ALL', 'Усі');

// Список товаров
define('PRODUCT_LISTING_ADD_TO_WISHLIST', 'До списку бажань');
define('PRODUCT_LISTING_IN_WISHLIST', 'Додано до <a href="' . tep_href_link(FILENAME_WISHLIST) . '" title="Перейти до списку бажань." rel="nofollow" onclick="var e = (arguments[0] || window.event); if(\'stopPropagation\' in e){e.stopPropagation();} else {e.cancelBubble = true;}">бажань</a>');
define('PRODUCT_LISTING_ADD_TO_COMPARE', 'Додати до порівняння');
define('PRODUCT_LISTING_IN_COMPARISON', 'Додано до <a href="' . tep_href_link(FILENAME_COMPARE) . '" title="Перейти до порівняння." rel="nofollow" onclick="var e = (arguments[0] || window.event); if(\'stopPropagation\' in e){e.stopPropagation();} else {e.cancelBubble = true;}">порівняння</a>');
define('PRODUCT_LISTING_COMMENTS_COUNT', 'Відгуки (%d)');
define('PRODUCT_LISTING_PRE_ORDER', 'Передзам.');
define('PRODUCTS_PRE_ORDER_INFO', 'Термін доставки <br />5 днів.');

define('PRODUCT_LISTING_WAIT', 'Зачекайте…');

// Номер страницы в тайтле
define('TITLE_ADD_PAGE_NUMBER', '. Сторінка %d');
define('TITLE_DELIMITER', ' — ');

// Локаль для Facebook OpenGraph
define('FACEBOOK_OG_LOCALE', 'uk_UA');

// Header tags
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . 'header_tags.php';