<?php
/*
  One Page Checkout, Version: 1.08

  I.T. Web Experts
  http://www.itwebexperts.com

  Copyright (c) 2009 I.T. Web Experts

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Оформление заказа');
define('NAVBAR_TITLE_1', 'Оформление заказа');

define('HEADING_TITLE', 'Оформление заказа');

define('TABLE_HEADING_SHIPPING_ADDRESS', 'Адрес доставки');
define('TABLE_HEADING_BILLING_ADDRESS', 'Ваши данные');

define('TABLE_HEADING_PRODUCTS_MODEL', 'Намименование');
define('TABLE_HEADING_PRODUCTS_NAME', 'Товары');
define('TABLE_HEADING_PRODUCTS_QTY', 'Количество');
define('TABLE_HEADING_PRODUCTS_PRICE', 'Цена за шт.');
define('TABLE_HEADING_PRODUCTS_FINAL_PRICE', 'Общая цена');

define('TABLE_HEADING_PRODUCTS', 'Корзина');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Всего');

define('ENTRY_TELEPHONE', 'Телефон: ');

define('TEXT_CHOOSE_SHIPPING_DESTINATION', 'Please choose from your address book where you would like the items to be delivered to.');
define('TEXT_SELECTED_BILLING_DESTINATION', 'Please choose from your address book where you would like the invoice to be sent to.');

define('TITLE_SHIPPING_ADDRESS', 'Адрес доставки:');
define('TITLE_BILLING_ADDRESS', 'Адрес оплати:');

define('TABLE_HEADING_SHIPPING_METHOD', 'Способ доставки');
define('TABLE_HEADING_PAYMENT_METHOD', 'Способ оплаты');

//define('TEXT_CHOOSE_SHIPPING_METHOD', 'Выберите, пожалуйста, способ доставки:');
//define('TEXT_SELECT_PAYMENT_METHOD', 'Выберите, пожалуйста, способ оплаты:');
define('TEXT_CHOOSE_SHIPPING_METHOD', '');
define('TEXT_SELECT_PAYMENT_METHOD', '');

define('TITLE_PLEASE_SELECT', 'Выберите');

define('TEXT_ENTER_SHIPPING_INFORMATION', 'This is currently the only shipping method available to use on this order.');
//define('TEXT_ENTER_PAYMENT_INFORMATION', 'This is currently the only payment method available to use on this order.');
define('TEXT_ENTER_PAYMENT_INFORMATION', '');

define('TABLE_HEADING_COMMENTS', 'Комментарии:');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Продолжить оформление заказа');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', 'для подтверждения нажмите "Продолжить"');

define('TEXT_EDIT', 'Edit');

define('TEXT_SELECTED_SHIPPING_DESTINATION', 'This is the currently selected shipping address where the items in this order will be delivered to.');
define('TABLE_HEADING_NEW_ADDRESS', 'Новый адрес');
define('TABLE_HEADING_EDIT_ADDRESS', 'Редактировать адрес');
define('TEXT_CREATE_NEW_SHIPPING_ADDRESS', 'Пожалуйста, воспользуйтесь следующей формой для добавления нового адреса доствки для этого заказа.');
define('TABLE_HEADING_ADDRESS_BOOK_ENTRIES', 'Адресная книга');


define('EMAIL_SUBJECT', 'Рады приветствовать Вас в интернет-магазине ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Уважаемая %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Уважаемый %s,' . "\n\n");
define('EMAIL_GREET_NONE', 'Уважаемый(ая) %s <br /><br/>');
define('EMAIL_WELCOME', 'Рады приветствовать Вас в интернет-магазине <b>' . STORE_NAME . '</b>. <br /><br/>');
define('EMAIL_TEXT', 'Теперь Вы имеете доступ к некоторым дополнительным возможностям, которые доступны зарегистрированным пользователям:' . "\n\n" . '<li><b>Корзина</b> - любые продукты, добавленые в корзину, остаються там, пока Вы не удалите их или не оформите заказ.' . "\n" . '<li><b>Адресная книга</b> - теперь мы можем отправлять свою продукцию на адрес, который Вы указали в пункте "Адрес доставки".' . "\n" . '<li><b>История заказов</b> - у Вас есть возможность просматривать историю заказов в нашем магазине.<br><br>');
define('EMAIL_CONTACT', 'Если у Вас возникли какие-либо вопросы, пишите: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '');

//define('ONPAGE_USER_CREATED_MESSAGE', 'Теперь Вы можете использовать эти "логин" и "пароль" при оформлении следующих заказов: <br>' .
//			'Логин: %s<br>' .
//			'Пароль: %s<br><br>'.
//			'Пароль: %s<br><br>');

define('ONPAGE_USER_CREATED_MESSAGE', 'Теперь Вы можете использовать эти "логин" и "пароль" при оформлении следующих заказов: <br>');
define('ONPAGE_CRE_LOGIN', 'Логин: ');
define('ONPAGE_CRE_PASS', 'Пароль: ');


// Start - CREDIT CLASS Gift Voucher Contribution
define('EMAIL_GV_INCENTIVE_HEADER', "\n\n" .'As part of our welcome to new customers, we have sent you an e-Gift Voucher worth %s');
define('EMAIL_GV_REDEEM', 'The redeem code for the e-Gift Voucher is %s, you can enter the redeem code when checking out while making a purchase');
define('EMAIL_GV_LINK', 'or by following this link ');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Congratulations, to make your first visit to our online shop a more rewarding experience we are sending you an e-Discount Coupon.' . "\n" .
										' Below are details of the Discount Coupon created just for you' . "\n");
define('EMAIL_COUPON_REDEEM', 'To use the coupon enter the redeem code which is %s during checkout while making a purchase');
// End - CREDIT CLASS Gift Voucher Contribution

define('TEXT_AGREE_TO_TERMS', 'I agree to the terms and conditions');

define('WINDOW_BUTTON_CANCEL', 'Отменить');
define('WINDOW_BUTTON_CONTINUE', 'Продолжить');
//define('WINDOW_BUTTON_CONTINUE', 'Подтвердить');
define('WINDOW_BUTTON_NEW_ADDRESS', 'Новый адрес');
define('WINDOW_BUTTON_EDIT_ADDRESS', 'Редактировать адрес');

define('CHECKOUT_EMAIL_EXIST', 'Такой e-mail уже существует, пожалуйста <a href="' . tep_href_link(FILENAME_LOGIN, 'from=' . rawurlencode(tep_href_link(FILENAME_CHECKOUT))) . '">войдите</a> под указанным e-mail');
define('CHECKOUT_WRONG_EMAIL', 'вы ввели неверный e-mail.');
define('CHECKOUT_ORDER_PROCESSES', 'Заказ обрабатывается, подождите...');

define('TEXT_PLEASE_SELECT', 'Выберите');
define('TEXT_PASSWORD_FORGOTTEN', 'Забыли пароль?');
define('IMAGE_UPDATE_CART', 'Обновить корзину');
define('IMAGE_LOGIN', 'Войти');
define('TEXT_PAYMENT_METHOD_UPDATE_ERROR', 'Please try again and if problems persist, please try another payment method.');
define('TEXT_HAVE_COUPON_CCGV', 'Have A Coupon?');
define('TEXT_HAVE_COUPON_KGT', 'Have A Coupon?');
define('TEXT_EXISTING_CUSTOMER_LOGIN', 'У Вас уже есть аккаунт?');
define('TEXT_DIFFERENT_SHIPPING', 'Адрес доставки отличается от адреса оплаты?');
define('TEXT_SHIPPING_NO_ADDRESS', 'Введите, пожалуйста, адрес оплаты для того, чтобы получить доступ к способам доставки');
define('TEXT_CHECKOUT_UPDATE_VIEW_ORDER', 'to update/view your order.');
define('CHECKOUT_BAR_CONFIRMATION', 'Checkout');
// Points/Rewards Module V2.1rc2a BOF
define('TABLE_HEADING_REDEEM_SYSTEM', 'Shopping Rewards Points Redemptions ');
define('TABLE_HEADING_REFERRAL', 'Referral System');
define('TEXT_REDEEM_SYSTEM_START', 'You have a credit balance of %s ,would you like to use it to pay for this order?<br />The estimated total of your purchase is: %s .');
define('TEXT_REDEEM_SYSTEM_SPENDING', 'Tick here to use Maximum Points allowed for this order. (%s points %s)&nbsp;&nbsp;->');
define('TEXT_REDEEM_SYSTEM_NOTE', '<span class="pointWarning">Total Purchase is greater than the maximum points allowed, you will also need to choose a payment method</span>');
define('TEXT_REFERRAL_REFERRED', 'If you were referred to us by a friend please enter their email address here. ');
// Points/Rewards Module V2.1rc2a EOF

//---checkout_errors
//rCheckoutErrors.chk_plz_fill_paymnet
define('CHECKOUT_RELOAD','Обновление');
define('CHECKOUT_SHCRT_ERR','В процессе обновления корзины возникла ошибка, пожалуйста, проинформируйте');
define('CHECKOUT_RELOAD_METHOD','Обновление способа');
define('CHECKOUT_RELOAD_PROCESS','В процессе обновления ');
define('CHECKOUT_ERR_FEED_BACK',' возникла ошибка, пожалуйста, проинформируйте  ');
define('CHECKOUT_SET_METHOD','Установка метода ');
define('CHECKOUT_ERR_SETTING','Возникла ошибка в процессе установки ');
define('CHECKOUT_PLZ_INFORM',' метода, пожалуйста, проинформируйте ');
define('CHECKOUT_SET_ADDRESS','Установка адреса ');
define('CHECKOUT_SHIPPING','Доставки');
define('CHECKOUT_PAYMENT','Оплаты');
define('CHECKOUT_SET_SHIPPING_ADDRESS','Установка адреса доставки');
define('CHECKOUT_PAYMENT_ADDRESS','адрес оплаты');
define('CHECKOUT_ADDRESS_ERROR','Ошибка адреса');
define('CHECKOUT_PLZ_FILL_PAYMENT','Заполните, пожалуйста, необходимые поля в разделе "Адрес оплаты"');
define('CHECKOUT_PLZ_CHECK_CORRECT','Проверьте, пожалуйста, корректность ввода данных в разделе "Адрес оплаты"');
define('CHECKOUT_PLZ_FILL_SHIPPING','Заполните, пожалуйста, все необходимые поля в "Адресе доставки"');
define('CHECKOUT_PLZ_CHECK_SHIPPING','Проверьте, пожалуйста, корректность ввода данных в разделе "Адрес доставки"');
define('CHECKOUT_ERR_SET_PAYMENT','Ошибка выбора способа оплаты');
define('CHECKOUT_CHOOSE_PAYMENT','Вы должны выбрать способ оплаты.');
define('CHECKOUT_CHK_EMAIL','Проверка E-mail адреса');
define('CHECKOUT_ERR_EMAIL_FEED_BACK','В процессе проверки email адреса возникла ошибка, пожалуйста, проинформируйте ');
define('CHECKOUT_ABOUT_ERR',' о ней.');

define('CHECKOUT_MIN_SUM', 'МИНИМАЛЬНАЯ СУММА ЗАКАЗА:');
define('CHECKOUT_REQUIRED_FIELD', 'Это поле обязательно для заполнения');
define('ENTRY_COMMENT', 'Комментарий к заказу:');
define('CHECKOUT_FORM_SUBMIT_BUTTON_TEXT_WAIT', 'Пожалуйста, подождите');
define('CHECKOUT_FORM_SUBMIT_BUTTON_TEXT_SUBMIT', 'Заказ подтверждаю');
define('CHECKOUT_FORM_TEXT_TERMS_OF_USE', 'Оформляя заказ, вы даёте своё согласие на обработку ваших персональных данных в соответствии с Законом Украины "О защите персональных данных".');
define('CHECKOUT_FORM_NOT_NOW_BUTTON_TITLE', 'Вернуться на главную страницу');
define('CHECKOUT_FORM_NOT_NOW_BUTTON_TEXT', 'Не сейчас');
define('CHECKOUT_FORM_NOTICE_HEADER_TEXT', 'Примечание');
define('CHECKOUT_CART_TEXT_PRICE', 'Цена');
define('CHECKOUT_CART_TEXT_QUANTITY', 'Количество');
define('CHECKOUT_CART_TEXT_TOTAL', 'Сумма');