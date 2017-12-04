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
define('GO_COMPARE', 'В Списке');
define('IN_WHISHLIST', 'В желаниях');
define('COMPARE', 'Сравнить');
define('COMPARE_BOX', 'Сравнить товары');
define('WHISH', 'Желания');
// HMCS: Begin Autologon   ******************************************************************
define('ENTRY_REMEMBER_ME', 'Запомнить меня<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:win_autologon();"><b><u>прочитайте сначала это</u></b></a>');
// HMCS: End Autologon     ******************************************************************

define('DATE_FORMAT_SHORT', '%d/%m/%Y');  // this is used for strftime()
//define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
//define('DATE_FORMAT_LONG', '%d %B %Y г.'); // this is used for strftime()
define('DATE_FORMAT_LONG', '%d.%m.%Y'); // this is used for strftime()
define('DATE_FORMAT', 'd.m.Y h:i:s'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');

////
// Return date in raw format
// $date should be in format mm/dd/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'RUR');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="ru"');

// charset for web pages and emails
define('CHARSET', 'utf-8'); //UTF-8

// page title
define('TITLE', 'Интернет-магазин');

// header text in includes/header.php
define('HEADER_TITLE_CREATE_ACCOUNT', 'Регистрация');
define('HEADER_TITLE_MY_ACCOUNT', 'Мои данные');
define('HEADER_TITLE_CART_CONTENTS', 'Корзина');
define('HEADER_TITLE_CHECKOUT', 'Оформить заказ');
define('HEADER_TITLE_TOP', 'Главная');
define('HEADER_TITLE_CATALOG', 'Каталог');
define('HEADER_TITLE_LOGOFF', 'Выход');
define('HEADER_TITLE_LOGIN', 'Мои данные');

// footer text in includes/footer.php
define('FOOTER_TEXT_REQUESTS_SINCE', 'человек посетили магазин c');

define('SIGN_FROM_SOC', 'Войти через соц. сети');
define('CALL_PROBLEM_TITLE', 'Не дозвонились?');
define('ONLINE_SUPPORT_HEADING', 'Онлайн консультант');
define('BOX_SHOPPING_CART_PRODUCTS', '<span>%s</span> товар(ов) на сумму %s');


// text for gender
define('MALE', 'Мужской');
define('FEMALE', 'Женский');
define('MALE_ADDRESS', 'Г-н');
define('FEMALE_ADDRESS', 'Г-жа');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd/mm/yyyy');

// quick_find box text in includes/boxes/quick_find.php
define('BOX_SEARCH_TEXT', 'Введите слово для поиска.');
define('BOX_SEARCH_ADVANCED_SEARCH', 'Расширенный поиск');

// reviews box text in includes/boxes/reviews.php
define('BOX_REVIEWS_WRITE_REVIEW', 'Напишите Ваше мнение о товаре!');
define('BOX_REVIEWS_NO_REVIEWS', 'К настоящему времени нет ни одного отзыва');
define('BOX_REVIEWS_TEXT_OF_5_STARS', '%s из 5 звёзд!');

// shopping_cart box text in includes/boxes/shopping_cart.php
define('BOX_SHOPPING_CART_EMPTY', 'Корзина пуста');

// notifications box text in includes/boxes/products_notifications.php
define('BOX_NOTIFICATIONS_NOTIFY', 'Сообщите мне о новинках и&nbsp;<b>%s</b>');
define('BOX_NOTIFICATIONS_NOTIFY_REMOVE', 'Не сообщайте мне о новинках <b>%s</b>');

// manufacturer box text
define('BOX_MANUFACTURER_INFO_HOMEPAGE', 'Сайт %s');
define('BOX_MANUFACTURER_INFO_OTHER_PRODUCTS', 'Другие товары данного производителя');
define('DRUGIE_HEAD_TITLE', 'Похожие товары');

// information box text in includes/boxes/information.php
define('BOX_INFORMATION_PRIVACY', 'Безопасность');
define('BOX_INFORMATION_CONDITIONS', 'Условия и гарантии');
define('BOX_INFORMATION_SHIPPING', 'Доставка и возврат');
define('BOX_INFORMATION_CONTACT', 'Свяжитесь с нами');

define('BOX_INFORMATION_PRICE_XLS', 'Прайс-лист (Excel)');
define('BOX_INFORMATION_PRICE_HTML', 'Карта сайта');

// tell a friend box text in includes/boxes/tell_a_friend.php
define('BOX_TELL_A_FRIEND_TEXT', 'Сообщите своим друзьям и близким о нашем магазине');

//BEGIN allprods modification
define('BOX_INFORMATION_ALLPRODS', 'Полный список товаров');
//END allprods modification

// VJ Links Manager v1.00 begin
define('BOX_INFORMATION_LINKS', 'Ссылки');
// VJ Links Manager v1.00 end

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'Адрес доставки');
define('CHECKOUT_BAR_PAYMENT', 'Способ оплаты');
define('CHECKOUT_BAR_CONFIRMATION', 'Подтверждение');
define('CHECKOUT_BAR_FINISHED', 'Заказ оформлен!');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Производитель');
define('TYPE_BELOW', 'Выбор ниже');

// javascript messages
define('JS_ERROR', 'Ошибки при заполнении формы!\n\nИсправьте пожалуйста:\n\n');

define('JS_REVIEW_TEXT', '* Поле \'Текст отзыва\' должно содержать не менее ' . REVIEW_TEXT_MIN_LENGTH . ' символов.\n');

define('JS_FIRST_NAME', '* Поле \'Имя\' должно содержать не менее ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' символов.\n');
define('JS_LAST_NAME', '* Поле \'Фамилия\' должно содержать не менее ' . ENTRY_LAST_NAME_MIN_LENGTH . ' символов.\n');


define('JS_REVIEW_RATING', '* Вы не указали рейтинг.\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Выберите метод оплаты для Вашего заказа.\n');

define('JS_ERROR_SUBMITTED', 'Эта форма уже заполнена. Нажимайте Ok.');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Выберите, пожалуйста, метод оплаты для Вашего заказа.');

define('CATEGORY_COMPANY', 'Организация');
define('CATEGORY_PERSONAL', 'Ваши персональные данные');
define('CATEGORY_ADDRESS', 'Ваш адрес');
define('CATEGORY_CONTACT', 'Контактная информация');
define('CATEGORY_OPTIONS', 'Рассылка');
define('CATEGORY_PASSWORD', 'Ваш пароль');

define('ENTRY_COMPANY', 'Название компании:');
define('ENTRY_COMPANY_ERROR', '');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', 'Пол:');
define('ENTRY_GENDER_ERROR', 'Вы должны указать свой пол.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'Имя:');
define('ENTRY_FIRST_NAME_ERROR', 'Поле Имя должно содержать как минимум ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' символа.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Фамилия:');
define('ENTRY_LAST_NAME_ERROR', 'Поле Фамилия должно содержать как минимум ' . ENTRY_LAST_NAME_MIN_LENGTH . ' символа.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', 'Дата рождения:');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Дату рождения необходимо вводить в следующем формате: DD/MM/YYYY (пример 21/05/1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', '* (пример 21/05/1970)');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail:');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Поле E-Mail должно содержать как минимум ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' символов.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Ваш E-Mail адрес указан неправильно, попробуйте ещё раз.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Введённый Вами E-Mail уже зарегистрирован в нашем магазине, попробуйте указать другой E-Mail адрес.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Адрес:');
define('ENTRY_STREET_ADDRESS_ERROR', 'Поле Улица и номер дома должно содержать как минимум ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' символов.');
define('ENTRY_STREET_ADDRESS_TEXT', '* Пример: ул. Киевская 8, офис. 2');
define('ENTRY_SUBURB', 'Отделение:');
define('ENTRY_SUBURB_ERROR', '');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Почтовый индекс:');
define('ENTRY_POST_CODE_ERROR', 'Поле Почтовый индекс должно содержать как минимум ' . ENTRY_POSTCODE_MIN_LENGTH . ' символа.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'Город:');
define('ENTRY_CITY_ERROR', 'Поле Город должно содержать как минимум ' . ENTRY_CITY_MIN_LENGTH . ' символа.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'Регион:');
define('ENTRY_STATE_ERROR', 'Поле Область должно содержать как минимум ' . ENTRY_STATE_MIN_LENGTH . ' символа.');
define('ENTRY_STATE_ERROR_SELECT', 'Выберите область.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Страна:');
define('ENTRY_COUNTRY_ERROR', 'Выберите страну.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Телефон:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Поле Телефон должно содержать как минимум ' . ENTRY_TELEPHONE_MIN_LENGTH . ' символа.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Факс:');
define('ENTRY_FAX_NUMBER_ERROR', '');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Получать информацию о скидках, призах, подарках:');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'Подписаться');
define('ENTRY_NEWSLETTER_NO', 'Отказаться от подписки');
define('ENTRY_NEWSLETTER_ERROR', '');
define('ENTRY_PASSWORD', 'Пароль:');
define('ENTRY_PASSWORD_ERROR', 'Ваш пароль должен содержать как минимум ' . ENTRY_PASSWORD_MIN_LENGTH . ' символов.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'Поле Подтвердите пароль должно совпадать с полем Пароль.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Подтвердите пароль:');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Текущий пароль:');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Поле Пароль должно содержать как минимум ' . ENTRY_PASSWORD_MIN_LENGTH . ' символов.');
define('ENTRY_PASSWORD_NEW', 'Новый пароль:');
define('ENTRY_PASSWORD_NEW_TEXT', '*');
define('ENTRY_PASSWORD_NEW_ERROR', 'Ваш Новый пароль должен содержать как минимум ' . ENTRY_PASSWORD_MIN_LENGTH . ' символов.');
define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'Поля Подтвердите пароль и Новый пароль должны совпадать.');
define('PASSWORD_HIDDEN', '--СКРЫТ--');

define('FORM_REQUIRED_INFORMATION', '* Обязательно для заполнения');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Страницы:');
define('TEXT_RESULT_VIEW', 'На страницу:');
define('TEXT_SWITCH_VIEW', 'Вид:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Показано <b>%d</b> - <b>%d</b> из <b>%d</b> позиций');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> заказов)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> отзывов)');
// BEGIN PopTheTop Reviews in Product Description
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS_PRODUCT_INFO', '<FONT COLOR="#006699">Показано <b>%d</b> of <b>%d</b> отзывов</FONT>');
// END PopTheTop Reviews in Product Description
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> новинок)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> специальных предложений)');
define('TEXT_DISPLAY_NUMBER_OF_FEATURED', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> рекомендуемых товаров)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'Первая страница');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'предыдущая');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Следующая страница');
define('PREVNEXT_TITLE_LAST_PAGE', 'Последняя страница');
define('PREVNEXT_TITLE_PAGE_NO', 'Страница %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Предыдущие %d страниц');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Следующие %d страниц');
define('PREVNEXT_BUTTON_FIRST', 'ПЕРВАЯ');
define('PREVNEXT_BUTTON_PREV', '<<');
define('PREVNEXT_BUTTON_NEXT', '>>');
define('PREVNEXT_BUTTON_LAST', 'ПОСЛЕДНЯЯ');

define('IMAGE_BUTTON_ADD_ADDRESS', 'Добавить адрес');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Адресная книга');
define('IMAGE_BUTTON_BACK', 'Назад');
define('IMAGE_BUTTON_BUY_NOW', 'Купить сейчас');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Изменить адрес');
define('IMAGE_BUTTON_CHECKOUT', 'Оформить заказ');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Подтвердить Заказ');
define('IMAGE_BUTTON_CONTINUE', 'Продолжить');
define('IMAGE_BUTTON_SEND', 'Отправить');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Вернуться в магазин');
define('IMAGE_BUTTON_DELETE', 'Удалить');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Редактировать учетные данные');
define('IMAGE_BUTTON_HISTORY', 'История заказов');
define('IMAGE_BUTTON_LOGIN', 'Войти');
define('IMAGE_BUTTON_IN_CART', 'В корзине');
define('IMAGE_BUTTON_ADDTO_CART', 'Купить');

define('IMAGE_BUTTON_NOTIFICATIONS', 'Уведомления');
define('IMAGE_BUTTON_QUICK_FIND', 'Быстрый поиск');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Удалить уведомления');
define('IMAGE_BUTTON_REVIEWS', 'Отзывы');
define('IMAGE_BUTTON_MORE_REVIEWS', 'Click to read more Reviews on this item');
define('IMAGE_BUTTON_SEARCH', 'Искать');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Способы доставки');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Написать другу');
define('IMAGE_BUTTON_UPDATE', 'Обновить');
define('IMAGE_BUTTON_UPDATE_CART', 'Пересчитать');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Написать отзыв');
define('IMAGE_REDEEM_VOUCHER_TITLE', 'Купон');
define('IMAGE_REDEEM_VOUCHER', 'Применить');

define('SMALL_IMAGE_BUTTON_DELETE', 'Удалить');
define('SMALL_IMAGE_BUTTON_EDIT', 'Изменить');
define('SMALL_IMAGE_BUTTON_VIEW', 'Смотреть');

define('ICON_ARROW_RIGHT', 'Перейти');
define('ICON_CART', 'В корзину');
define('ICON_ERROR', 'Ошибка');
define('ICON_SUCCESS', 'Выполнено');
define('ICON_WARNING', 'Внимание');

define('TEXT_GREETING_PERSONAL', 'Добро пожаловать, <span class="greetUser">%s!</span> Вы хотите посмотреть какие <a href="%s"><u>новые товары</u></a> поступили в наш магазин?');
define('TEXT_CUSTOMER_GREETING_HEADER', 'Добро пожаловать!');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>Если Вы не %s, пожалуйста, <a href="%s"><u>введите</u></a> свои данные для входа.</small>');
define('TEXT_GREETING_GUEST', 'Добро пожаловать, <span class="greetUser">УВАЖАЕМЫЙ ГОСТЬ!</span><br> Если Вы наш постоянный клиент, <a href="%s"><u>введите Ваши персональные данные</u></a> для входа. Если Вы у нас впервые и хотите сделать покупки, Вам необходимо <a href="%s"><u>зарегистрироваться</u></a>.');

define('TEXT_SORT_PRODUCTS', 'Сортировать по:');
define('TEXT_DESCENDINGLY', 'по убыванию');
define('TEXT_ASCENDINGLY', 'по возрастанию');
define('TEXT_BY', ', колонка ');

define('TEXT_REVIEW_BY', ' %s');
define('TEXT_REVIEW_WORD_COUNT', '%s слова');
define('TEXT_REVIEW_RATING', '<font color="#778188"> Оценка: %s</font>');
define('TEXT_REVIEW_DATE_ADDED', 'Дата добавления: %s');
define('TEXT_NO_REVIEWS', 'К настоящему времени нет отзывов, Вы можете стать первым.');

define('TEXT_NO_NEW_PRODUCTS', 'Сегодня нет новых продуктов.');

define('TEXT_NO_PRODUCTS', 'Ни одного товара не найдено.');

define('TEXT_UNKNOWN_TAX_RATE', 'Неизвестна налоговая ставка');

define('TEXT_REQUIRED', '<span class="errorText">Обязательно</span>');

// Down For Maintenance
define('TEXT_BEFORE_DOWN_FOR_MAINTENANCE', 'Внимание: Магазин закрыт по техническим причинам до: ');
define('TEXT_ADMIN_DOWN_FOR_MAINTENANCE', 'Внимание: Магазин закрыт по техническим причинам');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>ОШИБКА:</small> Невозможно отправить email через сервер SMTP. Проверьте, пожалуйста, Ваши установки php.ini и если необходимо, скорректируйте сервер SMTP.</b></font>');
define('WARNING_INSTALL_DIRECTORY_EXISTS', 'Предупреждение: Не удалена директория установки магазина: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install. Пожалуйста, удалите эту директорию для безопасности.');
define('WARNING_CONFIG_FILE_WRITEABLE', 'Предупреждение: Файл конфигурации доступен для записи: ' . dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php. Это - потенциальный риск безопасности - пожалуйста, установите необходимые права доступа к этому файлу.');
define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Предупреждение: директория сессий не существует: ' . tep_session_save_path() . '. Сессии не будут работать пока эта директория не будет создана.');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Предупреждение: Нет доступа к каталогу сессий: ' . tep_session_save_path() . '. Сессии не будут работать пока не установлены необходимые права доступа.');
define('WARNING_SESSION_AUTO_START', 'Предупреждение: опция session.auto_start включена - пожалуйста, выключите данную опцию в файле php.ini и перезапустите веб-сервер.');
define('WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT', 'Предупреждение: Директория отсутствует: ' . DIR_FS_DOWNLOAD . '. Создайте директорию.');


define('TEXT_CCVAL_ERROR_INVALID_DATE', 'Вы указали неверную дату истечения срока действия кредитной карточки.<br>Попробуйте ещё раз.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'Вы указали неверный номер кредитной карточки.<br>Попробуйте ещё раз.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'Первые цифры Вашей кредитной карточки: %s<br>Если Вы указали номер своей кредитной карточки правильно, сообщаем Вам, что мы не принимаем к оплате данный тип кредитных карточек.<br>Если Вы указали номер кредитной карточки неверно, попробуйте ещё раз.');

require(DIR_WS_LANGUAGES . 'add_ccgvdc_russian.php');
/////////////////////////////////////////////////////////////////////
// HEADER.PHP
// Header Links
define('HEADER_LINKS_DEFAULT','Главная');
define('HEADER_LINKS_WHATS_NEW','Новинки');
define('HEADER_LINKS_SPECIALS','Скидки');
define('HEADER_LINKS_REVIEWS','Отзывы');
define('HEADER_LINKS_LOGIN','Войти');
define('HEADER_LINKS_LOGOFF','Выход');
define('HEADER_LINKS_PRODUCTS_ALL','Каталог');
define('HEADER_LINKS_ACCOUNT_INFO','Ваши данные');
define('HEADER_LINKS_CHECKOUT','Оформить заказ');
define('HEADER_LINKS_CART','Корзина');
define('HEADER_LINKS_DVD', 'Каталог товаров');

/////////////////////////////////////////////////////////////////////

// BOF: Lango added for print order mod
define('IMAGE_BUTTON_PRINT_ORDER', 'Версия для печати');
// EOF: Lango added for print order mod

// WebMakers.com Added: Attributes Sorter
require(DIR_WS_LANGUAGES . $language . '/' . 'attributes_sorter.php');
define('BOX_LOGINBOX_HEADING', 'Вход');
define('BOX_LOGINBOX_EMAIL', 'E-Mail:');
define('BOX_LOGINBOX_PASSWORD', 'Пароль:');
define('IMAGE_BUTTON_LOGIN', 'Войти');

define('BOX_HEADING_LOGIN_BOX_MY_ACCOUNT','Мои данные');
define('LOGIN_BOX_MY_CABINET','Ваш кабинет');
define('MY_ORDERS_VIEW','Мои заказы');
define('MY_ACCOUNT_PASSWORD','Изменить пароль');
define('MY_ACCOUNT_MY_GROUP', 'Ваша группа');
define('MY_ACCOUNT_MY_DISCOUNT', 'Ваша скидка');
define('LOGIN_BOX_ACCOUNT_EDIT','Изменить данные');
define('LOGIN_BOX_ACCOUNT_HISTORY','История заказов');
define('LOGIN_BOX_ADDRESS_BOOK','Адресная книга');
define('LOGIN_BOX_PRODUCT_NOTIFICATIONS','Уведомления');
define('LOGIN_BOX_MY_ACCOUNT','Мои данные');
define('LOGIN_BOX_LOGOFF','Выход');

define('LOGIN_FROM_SITE','Войти');


// VJ Guestbook for OSC v1.0 begin
define('BOX_INFORMATION_GUESTBOOK', 'Гостевая книга');
// VJ Guestbook for OSC v1.0 end

// VJ Guestbook for OSC v1.0 begin
define('GUESTBOOK_TEXT_MIN_LENGTH', '10'); //[TODO] move to config db table
define('JS_GUESTBOOK_TEXT', '* Поле \'Ваше сообщение\' должно содержать как минимум ' . GUESTBOOK_TEXT_MIN_LENGTH . ' символов.\n');
define('JS_GUESTBOOK_NAME', '* Вы должны заполнить поле \'Ваше имя\'.\n');
// VJ Guestbook for OSC v1.0 end

// VJ Guestbook for OSC v1.0 begin
define('TEXT_DISPLAY_NUMBER_OF_GUESTBOOK_ENTRIES', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> записей)');
// VJ Guestbook for OSC v1.0 end

// VJ Guestbook for OSC v1.0 begin
define('IMAGE_BUTTON_SIGN_GUESTBOOK', 'Добавить запись');
// VJ Guestbook for OSC v1.0 end

// VJ Guestbook for OSC v1.0 begin
define('TEXT_GUESTBOOK_DATE_ADDED', 'Дата: %s');
define('TEXT_NO_GUESTBOOK_ENTRY', 'Пока нет ни одной записи в гостевой книге. Будьте первыми!');
// VJ Guestbook for OSC v1.0 end

define('DISCOUNT_HEADING', 'Скидки');

define('HELP', '<a href="http://web.icq.com/whitepages/message_me/1,,,00.icq?uin=' . STORE_OWNER_ICQ_NUMBER . '&action=message" target="_blank"><img src="http://web.icq.com/whitepages/online?icq=' . STORE_OWNER_ICQ_NUMBER . '&amp;img=5" title="Статус ICQ" align="absmiddle" border="0">' . STORE_OWNER_ICQ_NUMBER . '</a>
<br>
');

define('ICQ', 'ICQ:<br>');
define('TEXT_MORE_INFO', 'Подробнее...');

// Article Manager
define('BOX_ALL_ARTICLES', 'Все статьи');
define('BOX_NEW_ARTICLES', 'Новые статьи');
define('HEAD_ARTICLES_LIST', 'Полезные статьи');
define('TEXT_DISPLAY_NUMBER_OF_ARTICLES', '<font color="#5a5a5a">Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> новостей)</font>');
define('TEXT_DISPLAY_NUMBER_OF_ARTICLES_NEW', 'Показано <b>%d</b> - <b>%d</b> (всего <b>%d</b> новых статей)');
define('TABLE_HEADING_AUTHOR', 'Автор');
define('TABLE_HEADING_ABSTRACT', 'Резюме');
define('BOX_HEADING_AUTHORS', 'Авторы статей');
define('NAVBAR_TITLE_DEFAULT', 'Статьи');

define('TABLE_HEADING_INFO','Краткое описание');

//TotalB2B start
define('PRICES_LOGGED_IN_TEXT','<b><font style="color:#CE1930">Цена: </b></font><a href="create_account.php">только опт</a>');
//TotalB2B end

define('PRODUCTS_ORDER_QTY_TEXT','В корзину: ');
define('PRODUCTS_ORDER_QTY_MIN_TEXT','<br>' . ' Минимум: ');
define('PRODUCTS_ORDER_QTY_MIN_TEXT_INFO','Минимум единиц для заказа: '); // order_detail.php
define('PRODUCTS_ORDER_QTY_MIN_TEXT_CART','Минимум единиц для заказа: '); // order_detail.php
define('PRODUCTS_ORDER_QTY_MIN_TEXT_CART_SHORT',' Минимум: '); // order_detail.php
define('PRODUCTS_ORDER_QTY_UNIT_TEXT',', Шаг: ');
define('PRODUCTS_ORDER_QTY_UNIT_TEXT_INFO','Шаг: '); // order_detail.php
define('PRODUCTS_ORDER_QTY_UNIT_TEXT_CART','Шаг: '); // order_detail.php
define('PRODUCTS_ORDER_QTY_UNIT_TEXT_CART_SHORT',' Шаг: '); // order_detail.php
define('ERROR_PRODUCTS_QUANTITY_ORDER_MIN_TEXT','');
define('ERROR_PRODUCTS_QUANTITY_INVALID','Вы пытаетесь положить в корзину неверное количество товара: ');
define('ERROR_PRODUCTS_QUANTITY_ORDER_UNITS_TEXT','');
define('ERROR_PRODUCTS_UNITS_INVALID','Вы пытаетесь положить в корзину неверное количество товара: ');

// Comments

define('COMMENT_HEAD_TITLE', 'Комментарии');
define('ADD_COMMENT_HEAD_TITLE', 'Оставить свой отзыв о ');

// Poll Box Text
define('_RESULTS', 'Результаты');
define('_VOTE', 'Голосовать');
define('_COMMENTS','Отзывов:');
define('_VOTES', 'Голосов:');
define('_NOPOLLS','Нет опросов');
define('_NOPOLLSCONTENT','На данный момент нет ни одного активного опроса, Вы можете посмотреть результаты всех проводившихся ранее опросов.<br><br><a href="pollbooth.php">['._POLLS.']');

define('IMAGE_BUTTON_PREVIOUS', 'Предыдущий товар');
define('IMAGE_BUTTON_NEXT', 'Следующий товар');
define('IMAGE_BUTTON_RETURN_TO_PRODUCT_LIST', 'Вернуться к списку товаров');
define('PREV_NEXT_PRODUCT', 'Товар ');
define('PREV_NEXT_PRODUCT1', ' из ');
define('PREV_NEXT_CAT', ' категории ');
define('PREV_NEXT_MB', ' производителя ');

define('PREV_PRODUCT', 'Предыдущий товар');
define('NEXT_PRODUCT', 'Следующий товар');
define('SHOW_CATALOG', 'Показать каталог');
define('PRODUCT_AVIAIlABLE', 'В наличии');
define('PRODUCT_NOT_AVIAIlABLE', 'Нет в наличии');

define('BOX_TEXT_DOWNLOAD', 'Ваши загрузки: ');
define('BOX_DOWNLOAD_DOWNLOAD', 'Загрузить файлы');
define('BOX_TEXT_DOWNLOAD_NOW', 'Загрузить');

// Русские названия боксов

define('BOX_HEADING_CATEGORIES', 'Разделы');
define('BOX_HEADING_INFORMATION', 'Информация');
define('BOX_HEADING_TEMPLATE_SELECT', 'Выбор дизайна');
define('BOX_HEADING_MANUFACTURERS', 'Производители');
define('BOX_HEADING_SPECIALS', 'Скидки');
define('BOX_HEADING_NEWSDESK_LATEST', 'Последние новости');
define('BOX_HEADING_NEWSDESK_ALL', 'Все новости');
define('BOX_HEADING_SEARCH', 'Поиск');
define('BOX_HEADING_WHATS_NEW', 'Новинки');
define('BOX_HEADING_LANGUAGES', 'Язык');
define('BOX_HEADING_NEWSBOX', 'Новости');
define('BOX_HEADING_ALL_NEWS', 'Все новости');
define('BOX_HEADING_FEATURED', 'Мы рекомендуем');
define('BOX_HEADING_SHOP_BY_PRICE', 'Сортировка по цене');
define('BOX_HEADING_FILTER_BY_PRICE', 'Цена');
define('BOX_HEADING_SELECTED_FILTERS', 'Выбранные фильтры');
define('TEXT_CLEAR_SELECTED_FILTERS', 'Очистить список');
define('BOX_HEADING_NEWSDESK_CATEGORIES', 'Новости');
define('BOX_HEADING_ARTICLES', 'Статьи');
define('BOX_HEADING_AUTHORS', 'Авторы');
define('BOX_HEADING_LINKS', 'Обмен ссылками');
define('BOX_HEADING_SHOPPING_CART', 'Корзина');
define('BOX_HEADING_SHOPPING_ENTER', 'Войти в корзину');
define('BOX_HEADING_DOWNLOAD', 'Файлы');
define('BOX_HEADING_LOGIN', 'Вход');
define('HELP_HEADING', 'Консультант');
define('BOX_HEADING_WISHLIST', 'Отложенные товары');
define('BOX_WISHLIST_ITEMS', 'шт.');
define('BOX_HEADING_REVIEWS', 'Отзывы');
define('BOX_HEADING_CUSTOMER_ORDERS', 'История заказов');
define('BOX_HEADING_AFFILIATE', 'Заработай с нами');
define('BOX_HEADING_MANUFACTURER_INFO', 'Производитель');
define('BOX_HEADING_BESTSELLERS', 'ТОП продаж');
define('BOX_HEADING_MOSTVIEWED', 'TOП просмотров');
define('NEW_PRODUCTS', 'Новинки');
define('SERVICE', 'Сервис');
define('MADE_BY', 'Разработчик:');
define('SITEMAP', 'Карта сайта');
define('BOX_HEADING_BESTSELLERS', 'Лидеры продаж');
define('BOX_HEADING_TELL_A_FRIEND', 'Рассказать другу');
define('BOX_HEADING_NOTIFICATIONS', 'Уведомления');
define('BOX_HEADING_CURRENCIES', 'Валюта');
define('BOX_HEADING_FAQDESK_CATEGORIES', 'FAQ');
define('BOX_HEADING_FAQDESK_LATEST', 'Свежие вопросы в FAQ');
define('_POLLS', 'Опросы');
define('BOX_HEADING_LAST_VIEWED', 'Недавно просмотренные товары');

// Product info
define('PRODUCT_POPCART_IMAGE', 'Фото');
define('PRODUCT_POPCART_NAME', 'Наименование');
define('PRODUCT_POPCART_PRICE', 'Цена');
define('PRODUCT_POPCART_QTY', 'Количество');
define('PRODUCT_POPCART_TOTAL', 'Сумма');

define('NEW_CUSTOMER', 'Новый покупатель');
define('RETURNING_CUSTOMER', 'Постоянный клиент');

// Способы и стоимость доставки в корзине
  define('SHIPPING_OPTIONS', 'Способы и стоимость доставки:');
  if (strstr($PHP_SELF,'shopping_cart.php')) {
    define('SHIPPING_OPTIONS_LOGIN', 'Пожалуйста, <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u>войдите</u></a> в магазин, чтобы увидеть точную стоимость доставки Вашего заказа.');
  } else {
    define('SHIPPING_OPTIONS_LOGIN', 'Пожалуйста, войдите в магазин, чтобы увидеть способы и стоимость доставки Вашего заказа.');
  }
  define('SHIPPING_METHOD_TEXT','Способы доставки:');
  define('SHIPPING_METHOD_RATES','Стоимость:');
  define('SHIPPING_METHOD_TO','Адрес доставки: ');
  define('SHIPPING_METHOD_TO_NOLOGIN', 'Адрес доставки: <a href="' . tep_href_link(FILENAME_LOGIN, '', 'SSL') . '"><u>Войдите</u></a>');
  define('SHIPPING_METHOD_FREE_TEXT','Бесплатная доставка');
  define('SHIPPING_METHOD_ALL_DOWNLOADS','- Скачивания');
  define('SHIPPING_METHOD_RECALCULATE','Рассчитать');
  define('SHIPPING_METHOD_ZIP_REQUIRED','true');
  define('SHIPPING_METHOD_ADDRESS','Адрес:');
  define('SHIPPING_METHOD_QTY','Количество товара: ');
  define('SHIPPING_METHOD_WEIGHT','Вес товара: ');
  define('SHIPPING_METHOD_WEIGHT1',' кг.');

  define('LOW_STOCK_TEXT1','Товар на складе заканчивается: ');
  define('LOW_STOCK_TEXT2','Код товара');
  define('LOW_STOCK_TEXT3','Текущее количество: ');
  define('LOW_STOCK_TEXT4','Ссылка на товар: ');
  define('LOW_STOCK_TEXT5','Текущее значение переменной Лимит количества товара на складе: ');

// wishlist box text in includes/boxes/wishlist.php

  define('BOX_HEADING_CUSTOMER_WISHLIST', 'Отложенные товары');
  define('TEXT_WISHLIST_COUNT', 'На данный момент отложено товаров: %s.');

  define('BOX_TEXT_VIEW', 'Показать');
  define('BOX_TEXT_HELP', 'Помощь');
  define('BOX_WISHLIST_EMPTY', 'Нет отложенных товаров.');
  define('BOX_TEXT_NO_ITEMS', 'Нет отложенных товаров.');
  define('IMAGE_BUTTON_ADD_WISHLIST', 'Отложить');

  define('TEXT_VERSION', 'Версия сборки: ');
  define('TOTAL_QUERIES', 'Всего запросов: ');
  define('TOTAL_TIME', 'Время исполнения: ');

// otf 1.71 defines needed for Product Option Type feature.
  define('PRODUCTS_OPTIONS_TYPE_SELECT', 0);
  define('PRODUCTS_OPTIONS_TYPE_TEXT', 1);
  define('PRODUCTS_OPTIONS_TYPE_RADIO', 2);
  define('PRODUCTS_OPTIONS_TYPE_CHECKBOX', 3);
  define('PRODUCTS_OPTIONS_TYPE_TEXTAREA', 4);
  define('TEXT_PREFIX', 'txt_');
  define('PRODUCTS_OPTIONS_VALUE_TEXT_ID', 0);  //Must match id for user defined "TEXT" value in db table TABLE_PRODUCTS_OPTIONS_VALUES


//include('includes/languages/english_support.php');
include('includes/languages/russian_newsdesk.php');
include('includes/languages/russian_faqdesk.php');

// Product reviews

//define('NAVBAR_TITLE', 'Корзина');

define('SUB_TITLE_FROM', 'От:');
define('SUB_TITLE_REVIEW', 'Текст сообщения:');
define('SUB_TITLE_RATING', 'Рейтинг:');
define('TEXT_NO_HTML', '<small><font color="#505C65"><b>ЗАМЕЧАНИЕ:</b></font></small>&nbsp;HTML тэги не поддерживаются!');
define('TEXT_BAD', '<small><font color="#505C65"><b>ПЛОХО</b></font></small>');
define('TEXT_GOOD', '<small><font color="#505C65"><b>ОТЛИЧНО</b></font></small>');

define('TEXT_CLICK_TO_ENLARGE', 'Увеличить');


// Product tabs
define('DESCRIPTION', 'Описание');
define('FEATURES', 'Характеристики');
define('COMMENTS', 'Комментарии');
define('RELATED_PRODUCTS', 'Cопутствующие товары');

define('ALSO_PURCHASED', 'Также с этим товаром заказывали');
define('FORWARD', 'Вперед');
define('BACKWARD', 'Назад');
define('MY_ORDER', 'Мой заказ');

define('USER_ACCOUNT_NAVIGATION', 'Навигация');
define('MY_ACCOUNT_INFORMATION','Моя информация');

define('MY_ORDERS_VIEW','Мои заказы');
define('MY_ACCOUNT_PASSWORD','Изменить пароль');

define('LOGIN_FROM_SITE','Войти');

define('PHOTOGALLERY','Фотогалерея');
define('HEADING_PEREZVONIM','Мы вам перезвоним');

define('SEND_MESSAGE','Отправить');
define('SHOW_ALL_SRCH_RES','Показать все результаты');
define('ALL_RIGHTS','Все права защищены');
define('ADDRESS_MAINPAGE','Украина, 020232 Драгоманова 18, офис 5');

define('BOX_CURRENCY','Валюта: ');

define('SORT_NAME_ASC', 'A-Я');
define('SORT_NAME_DESC', 'Я-А');
define('SORT_PRICE_ASC','дешевле сверху');
define('SORT_PRICE_DESC','дороже сверху');
define('SORT_NEW','новые сверху');
define('SORT_POPULAR','популярные сверху');
define('VIEW_LIST','списком');
define('VIEW_COL','колонками');
define('SORT_ALL','Все');

define('PROD_ENLARGE','Увеличить');
define('PROD_BETTER_TOGETHER','Также вы можете приобрести со скидкой');

define('COMP_PROD_HEAD','Сравнение товаров');
define('COMP_PROD_NAME','Название');
define('COMP_PROD_IMG','Картинка');
define('COMP_PROD_PRICE','Цена');
define('COMP_PROD_CLEAR','Очистить все');
define('COMP_PROD_BACK','Вернуться');
define('COMP_PROD_ADD_TO','Добавьте товары к сравнению!');

define('ATTRIBUTES','Атрибуты');
define('QUICK_ORDER','Быстрый заказ');
define('QUICK_ORDER_SUCCESS','Заявка отправлена, в скором времени с Вами свяжется менеджер');
define('QUICK_ORDER_BUTTON','Купить в один клик');
define('QUICK_PRE_ORDER_BUTTON','Предзаказ');

define('SOC_CREATE1', 'Добро пожаловать');
define('SOC_CREATE2', 'Ваш город');
define('SOC_CREATE3', 'Ваш логин(e-mail)');
define('SOC_CREATE4', 'Ваш пароль');
define('SOC_CREATE5', 'Для завершения регистрации<br /> введите, пожалуйста, ваш');
define('SOC_CREATE6', 'Подтвердить');
define('SOC_CREATE7', 'Спасибо, вы успешно зарегистрированы!');

define('DIALOG_DIS', 'Задать вопрос');

// Всплывающие подсказки для логотипов платёжных систем в подвале
define('FPSLT_MASTER_CARD', 'Master Card');
define('FPSLT_WEBMONEY', 'Webmoney');
define('FPSLT_VISA', 'Visa');
define('FPSLT_YANDEX_MONEY', 'Яндекс.Деньги');

// Текст для чекбокса "Все" в фильтре категории
define('TEXT_FILTER_ALL', 'Все');

define('NO_PRODUCTS_FOUND', 'Нет товаров, соответствующих критериям поиска.');
define('LOAD_MORE_PRODUCTS', 'Показать ещё товары');

define('PRODUCTS_FOUND_HEADER', 'Результаты поиска по запросу «%s»');
define('NO_PRODUCTS_FOUND_HEADER', 'По вашему запросу ничего не найдено');
define('MODULE_ARTICLES_XSELL_TITLE', 'Статьи по товару');
// Текст в логотипе
define('LOGO_TEXT', 'Интернет-магазин игрушек');

// График работы
define('TEXT_SCHEDULE', '<span>Работаем:</span><br> Пн-Пт 09:00-18:00,<br> <span>Сб, Вс выходной</span>');

// Заголовки колонок в подвале
define('FOOTER_COLUMN_INFORMATION_TITLE', 'Информация');
define('FOOTER_COLUMN_ABOUT_US_TITLE', 'О нашем магазине');
define('FOOTER_COLUMN_CATEGORIES_TITLE', 'Разделы');
define('FOOTER_COLUMN_SUBSCRIBE_TITLE', 'Новости Gigimot');
define('TEXT_SUBSCRIBE', 'Подписаться');
define('FOOTER_SUBSCRIBE_DESCRIPTION', 'Будьте в курсе акций и новинок в ассортименте любимого магазина.');
define('FOOTER_SUBSCRIBE_FORM_EMAIL_INPUT_PLACEHOLDER', 'Ваш адрес элелектронной почты');
define('READMORE_ARTICLE_LINK_TITLE', 'Читать статью «%s»');
define('READMORE_NEWS_LINK_TITLE', 'Читать новость «%s»');

// Модуль производителей на главной
define('MPM_MANUFACTURESR_LIST_LABEL', 'Производители');
define('MPM_MANUFACTURESR_OTHER', 'Другие');
define('MPM_MANUFACTURESR_ALL', 'Все бренды');

// Модуль новинок на главной
define('MPM_NEW_PRODUCTS_ALL', 'Все новинки');

// Модуль рекомендуемых товаров
define('MPM_FEATURED_ALL', 'Все товары');

// Модуль просмотренных товаров
define('MPM_VIEWED_PRODUCTS_TITLE', 'Просмотренные товары');
define('MPM_VIEWED_PRODUCTS_ALL', 'Все');

// Список товаров
define('PRODUCT_LISTING_ADD_TO_WISHLIST', 'В список желаний');
define('PRODUCT_LISTING_IN_WISHLIST', 'Добавлено в <a href="' . tep_href_link(FILENAME_WISHLIST) . '" title="Перейти к списку желаний." rel="nofollow" onclick="var e = (arguments[0] || window.event); if(\'stopPropagation\' in e){e.stopPropagation();} else {e.cancelBubble = true;}">желания</a>');
define('PRODUCT_LISTING_ADD_TO_COMPARE', 'Добавить к сравнению');
define('PRODUCT_LISTING_IN_COMPARISON', 'Добавлено в <a href="' . tep_href_link(FILENAME_COMPARE) . '" title="Перейти к сравнению." rel="nofollow" onclick="var e = (arguments[0] || window.event); if(\'stopPropagation\' in e){e.stopPropagation();} else {e.cancelBubble = true;}">сравнения</a>');
define('PRODUCT_LISTING_COMMENTS_COUNT', 'Отзывы (%d)');
define('PRODUCT_LISTING_PRE_ORDER', 'Предзаказ');
define('PRODUCTS_PRE_ORDER_INFO', 'Срок доставки <br />5 дней.');
define('PRODUCT_LISTING_WAIT', 'Подождите…');

// Номер страницы в тайтле
define('TITLE_ADD_PAGE_NUMBER', '. Страница %d');
define('TITLE_DELIMITER', ' — ');

// Локаль для Facebook OpenGraph
define('FACEBOOK_OG_LOCALE', 'ru_RU');

// Header tags
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . 'header_tags.php';