<?php

/*
  One Page Checkout, Version: 1.08

  I.T. Web Experts
  http://www.itwebexperts.com

  Copyright (c) 2009 I.T. Web Experts

  Released under the GNU General Public License
 */

define('NAVBAR_TITLE', 'Оформлення замовлення');
define('NAVBAR_TITLE_1', 'Оформлення замовлення');

define('HEADING_TITLE', 'Оформлення замовлення');

define('TABLE_HEADING_SHIPPING_ADDRESS', 'Адреса доставки');
define('TABLE_HEADING_BILLING_ADDRESS', 'Ваші дані');

define('TABLE_HEADING_PRODUCTS_MODEL', 'Найменування');
define('TABLE_HEADING_PRODUCTS_NAME', 'Товари');
define('TABLE_HEADING_PRODUCTS_QTY', 'Кількість');
define('TABLE_HEADING_PRODUCTS_PRICE', 'Ціна за шт.');
define('TABLE_HEADING_PRODUCTS_FINAL_PRICE', 'Загальна ціна');

define('TABLE_HEADING_PRODUCTS', 'Кошик');
define('TABLE_HEADING_TAX', 'Податок');
define('TABLE_HEADING_TOTAL', 'Всього');

define('ENTRY_TELEPHONE', 'Телефон:');

define('TEXT_CHOOSE_SHIPPING_DESTINATION', 'Please choose from your address book where you would like the items to be delivered to.');
define('TEXT_SELECTED_BILLING_DESTINATION', 'Please choose from your address book where you would like the invoice to be sent to.');

define('TITLE_SHIPPING_ADDRESS', 'Адреса доставки:');
define('TITLE_BILLING_ADDRESS', 'Адреса оплати:');

define('TABLE_HEADING_SHIPPING_METHOD', 'Спосіб доставки');
define('TABLE_HEADING_PAYMENT_METHOD', 'Спосіб оплати');

//   define('TEXT_CHOOSE_SHIPPING_METHOD', 'Оберіть, будь ласка, спосіб доставки:');
//   define('TEXT_SELECT_PAYMENT_METHOD', 'Оберіть, будь ласка, спосіб оплати:');
define('TEXT_CHOOSE_SHIPPING_METHOD', '');
define('TEXT_SELECT_PAYMENT_METHOD', '');

define('TITLE_PLEASE_SELECT', 'Виберіть');

define('TEXT_ENTER_SHIPPING_INFORMATION', 'This is currently the only shipping method available to use on this order.');
//   define('TEXT_ENTER_PAYMENT_INFORMATION', 'This is currently the only payment method available to use on this order.');
define('TEXT_ENTER_PAYMENT_INFORMATION', '');

define('TABLE_HEADING_COMMENTS', 'Коментарі:');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Продовжити оформлення замовлення');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', 'для підтвердження натисніть "Продовжити"');

define('TEXT_EDIT', 'Edit');

define('TEXT_SELECTED_SHIPPING_DESTINATION', 'This is the currently selected shipping address where the items in this order will be delivered to.');
define('TABLE_HEADING_NEW_ADDRESS', 'Нова адреса');
define('TABLE_HEADING_EDIT_ADDRESS', 'Редагувати адресу');
define('TEXT_CREATE_NEW_SHIPPING_ADDRESS', 'Будь ласка, скористайтеся наступною формою для додавання нової адреси доствки для цього замовлення.');
define('TABLE_HEADING_ADDRESS_BOOK_ENTRIES', 'Адресна книга');


define('EMAIL_SUBJECT', 'Раді вітати Вас в інтернет-магазині ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Шановна %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Шановний %s,' . "\n\n");
define('EMAIL_GREET_NONE', 'Шановний (а) %s<br /> <br />');
define('EMAIL_WELCOME', 'Раді вітати Вас в інтернет-магазині <b>' . STORE_NAME . '</b>. <br /> <br/>');
define('EMAIL_TEXT', 'Тепер Ви маєте доступ до деяких додаткових можливостей, які доступні зареєстрованим користувачам:' . "\n\n" . '<li><b>Кошик</b> - будь-які продукти, що додані у кошик, залишаються там, поки Ви не видалите їх або не оформите замовлення. ' . "\n" . ' <li> <b>Адресна книга </b> - тепер ми можемо відправляти свою продукцію на адресу, яку Ви вказали в пункті "Адреса доставки ". ' . "\n" . '<li><b>Історія замовлень</b> - у Вас є можливість переглядати історію замовлень в нашому магазині.<br><br>');
define('EMAIL_CONTACT', 'Якщо у Вас виникли будь-які питання, пишіть: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '');

//   define('ONPAGE_USER_CREATED_MESSAGE', 'Тепер Ви можете використовувати ці "логін" і "пароль" при оформленні наступних замовлень: <br>'.
// 'Логін:% s <br>'.
// 'Пароль:% s <br> <br>'.
// 'Пароль:% s <br> <br>');

define('ONPAGE_USER_CREATED_MESSAGE', 'Тепер Ви можете використовувати ці "логін" і "пароль" при оформленні наступних замовлень: <br>');
define('ONPAGE_CRE_LOGIN', 'Логін:');
define('ONPAGE_CRE_PASS', 'Пароль:');


// Start - CREDIT CLASS Gift Voucher Contribution
define('EMAIL_GV_INCENTIVE_HEADER', "\n\n" . 'As part of our welcome to new customers, we have sent you an e-Gift Voucher worth% s ');
define('EMAIL_GV_REDEEM', 'The redeem code for the e-Gift Voucher is %s, you can enter the redeem code when checking out while making a purchase');
define('EMAIL_GV_LINK', 'or by following this link');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Congratulations, to make your first visit to our online shop a more rewarding experience we are sending you an e-Discount Coupon.' . "\n" .
        'Below are details of the Discount Coupon created just for you' . "\n");
define('EMAIL_COUPON_REDEEM', 'To use the coupon enter the redeem code which is %s during checkout while making a purchase');
// End - CREDIT CLASS Gift Voucher Contribution

define('TEXT_AGREE_TO_TERMS', 'I agree to the terms and conditions');

define('WINDOW_BUTTON_CANCEL', 'Скасувати');
define('WINDOW_BUTTON_CONTINUE', 'Продовжити');
//   define('WINDOW_BUTTON_CONTINUE', 'Підтвердити');
define('WINDOW_BUTTON_NEW_ADDRESS', 'Нова адреса');
define('WINDOW_BUTTON_EDIT_ADDRESS', 'Редагувати адресу');

define('CHECKOUT_EMAIL_EXIST', 'Такий e-mail вже існує, будь ласка <a href="' . tep_href_link(FILENAME_LOGIN, 'from=' . rawurlencode(tep_href_link(FILENAME_CHECKOUT))) . '">увійдіть</a> під зазначеним e-mail');
define('CHECKOUT_WRONG_EMAIL', 'ви ввели невірний e-mail.');
define('CHECKOUT_ORDER_PROCESSES', 'Замовлення обробляється, зачекайте ...');

define('TEXT_PLEASE_SELECT', 'Виберіть');
define('TEXT_PASSWORD_FORGOTTEN', 'Забули пароль?');
define('IMAGE_UPDATE_CART', 'Оновити кошик');
define('IMAGE_LOGIN', 'Увійти');
define('TEXT_PAYMENT_METHOD_UPDATE_ERROR', 'Please try again and if problems persist, please try another payment method.');
define('TEXT_HAVE_COUPON_CCGV', 'Маєте купон?');
define('TEXT_HAVE_COUPON_KGT', 'Маєте купон?');
define('TEXT_EXISTING_CUSTOMER_LOGIN', 'У Вас вже є аккаунт?');
define('TEXT_DIFFERENT_SHIPPING', 'Адреса доставки відрізняється від адреси оплати?');
define('TEXT_SHIPPING_NO_ADDRESS', 'Введіть, будь ласка, адресу оплати для того, щоб отримати доступ до способів доставки');
define('TEXT_CHECKOUT_UPDATE_VIEW_ORDER', 'to update / view your order.');
define('CHECKOUT_BAR_CONFIRMATION', 'Формити замовлення');
// Points / Rewards Module V2.1rc2a BOF
define('TABLE_HEADING_REDEEM_SYSTEM', 'Shopping Rewards Points Redemptions');
define('TABLE_HEADING_REFERRAL', 'Referral System');
define('TEXT_REDEEM_SYSTEM_START', 'You have a credit balance of %s, would you like to use it to pay for this order? <br /> The estimated total of your purchase is: %s.');
define('TEXT_REDEEM_SYSTEM_SPENDING', 'Tick here to use Maximum Points allowed for this order. (%s points %s)&nbsp;&nbsp; ->');
define('TEXT_REDEEM_SYSTEM_NOTE', '<span class="pointWarning"> Total Purchase is greater than the maximum points allowed, you will also need to choose a payment method </span>');
define('TEXT_REFERRAL_REFERRED', 'If you were referred to us by a friend please enter their email address here.');
// Points / Rewards Module V2.1rc2a EOF
// --- checkout_errors
//rCheckoutErrors.chk_plz_fill_paymnet
define('CHECKOUT_RELOAD', 'Оновлення');
define('CHECKOUT_SHCRT_ERR', 'В процесі оновлення кошика виникла помилка, будь ласка, проінформуйте');
define('CHECKOUT_RELOAD_METHOD', 'Оновлення способу');
define('CHECKOUT_RELOAD_PROCESS', 'В процесі оновлення ');
define('CHECKOUT_ERR_FEED_BACK', ' виникла помилка, будь ласка, проінформуйте ');
define('CHECKOUT_SET_METHOD', 'Установка методу ');
define('CHECKOUT_ERR_SETTING', 'Виникла помилка ');
define('CHECKOUT_PLZ_INFORM', 'методу, будь ласка, повідомте ');
define('CHECKOUT_SET_ADDRESS', 'Установка адреси ');
define('CHECKOUT_SHIPPING', 'Доставки');
define('CHECKOUT_PAYMENT', 'Оплати');
define('CHECKOUT_SET_SHIPPING_ADDRESS', 'Установка адреси доставки');
define('CHECKOUT_PAYMENT_ADDRESS', 'адреса оплати');
define('CHECKOUT_ADDRESS_ERROR', 'Помилка адреси');
define('CHECKOUT_PLZ_FILL_PAYMENT', 'Заповніть, будь ласка, необхідні поля в розділі "Адреса оплати"');
define('CHECKOUT_PLZ_CHECK_CORRECT', 'Перевірте, будь ласка, коректність вводу даних у розділі "Адреса оплати"');
define('CHECKOUT_PLZ_FILL_SHIPPING', 'Заповніть, будь ласка, всі необхідні поля в "Адресі доставки"');
define('CHECKOUT_PLZ_CHECK_SHIPPING', 'Перевірте, будь ласка, коректність введення даних у розділі "Адреса доставки"');
define('CHECKOUT_ERR_SET_PAYMENT', 'Помилка вибору способу оплати');
define('CHECKOUT_CHOOSE_PAYMENT', 'Ви повинні вибрати спосіб оплати.');
define('CHECKOUT_CHK_EMAIL', 'Перевірка E-mail адреси');
define('CHECKOUT_ERR_EMAIL_FEED_BACK', 'В процесі перевірки email адреси виникла помилка, будь ласка, проінформуйте ');
define('CHECKOUT_ABOUT_ERR', 'про неї.');

define('CHECKOUT_MIN_SUM', 'МІНІМАЛЬНА СУМА ЗАМОВЛЕННЯ:');
define('CHECKOUT_REQUIRED_FIELD', 'Це поле є обов’язковим для заповнення');
define('ENTRY_COMMENT', 'Коментар до замовлення:');
define('CHECKOUT_FORM_SUBMIT_BUTTON_TEXT_WAIT', 'Зачекайте, будь ласка');
define('CHECKOUT_FORM_SUBMIT_BUTTON_TEXT_SUBMIT', 'Замовлення підтверджую');
define('CHECKOUT_FORM_TEXT_TERMS_OF_USE', 'Оформлюючи замовлення, ви даєте свою згоду на обробку вашмх персональних данних у відповідності до Закону України "Про захист персональних данних".');
define('CHECKOUT_FORM_NOT_NOW_BUTTON_TITLE', 'Повернутися на головну сторінку');
define('CHECKOUT_FORM_NOT_NOW_BUTTON_TEXT', 'Не зараз');
define('CHECKOUT_FORM_NOTICE_HEADER_TEXT', 'Примітка');
define('CHECKOUT_CART_TEXT_PRICE', 'Ціна');
define('CHECKOUT_CART_TEXT_QUANTITY', 'Кількість');
define('CHECKOUT_CART_TEXT_TOTAL', 'Сума');