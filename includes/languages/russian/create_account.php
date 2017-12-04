<?php
/*
  $Id: create_account.php,v 1.1.1.1 2003/09/18 19:04:28 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// guest_account start
if ($guest_account == false) { // Not a Guest Account
  define('NAVBAR_TITLE', 'Регистрация');
} else {
  define('NAVBAR_TITLE', 'Оформление заказа');
}
// guest_account end

define('HEADING_TITLE', 'Мои данные');
define('HEADING_TITLE_REG_CLIENT', 'Регистрация клиента');
define('HEADING_TITLE_REG_LOGIN', '<span><b><u>Войдите</u></b></span></a>, если вы уже зарегистрированы.');

// guest_account start

define('HEADING_TITLE_GUEST', 'Введите Ваши данные');

// guest_account end

define('TEXT_ORIGIN_LOGIN', '<font  style="font-size:12px;"><font color="#FF0000"><small><b>ВНИМАНИЕ:</b></small></font>&nbsp;Вам нужно заполнить регистрационную форму. Это даст Вам возможность оформлять заказ в нашем интернет магазине,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;если Вы уже зарегистрированы на нашем сайте, введите, пожалуйста, Ваш логин и пароль&nbsp;</font><a href="%s"><u>здесь</u></a>.');   

define('EMAIL_SUBJECT', 'Добро пожаловать в ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Уважаемый %s! <br><br>');
define('EMAIL_GREET_MS', 'Уважаемая %s! <br><br>');
define('EMAIL_GREET_NONE', 'Уважаемый %s! <br><br>');
define('EMAIL_WELCOME', 'Мы рады пригласить Вас в интернет-магазин <b>' . STORE_NAME . '</b>. <br><br>');
define('EMAIL_CONTACT', 'Если у Вас возникли какие-либо вопросы, пишите: ' . STORE_OWNER_EMAIL_ADDRESS . '.<br><br>');
define('EMAIL_TEXT', 'Теперь Вы можете осуществлять покупки.' . "\n");
define('EMAIL_WARNING', '');

/* ICW Credit class gift voucher begin */
define('EMAIL_GV_INCENTIVE_HEADER', "\n\n" .'Также сообщам Вам, что Вы получаете сертификат на сумму %s');
define('EMAIL_GV_REDEEM', 'Код Вашего сертификата %s, Вы можете использовать Ваш сертификат при оплате заказа, при этом номинальная стоимость сертификата будет засчитана в качестве оплаты всего заказа, или в качестве оплаты части стоимости Вашего заказа.');
define('EMAIL_GV_LINK', 'Перейдите по ссылке для активизации сертификата: ');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Поздравляем с регистрацией в нашем магазине, рады сообщить, что мы дарим Вам купон на получение скидки в нашем магазине.' . "\n" .
                                        ' Данный купон действителен только для Вас.' . "\n");
define('EMAIL_COUPON_REDEEM', 'Чтобы воспользоваться купоном, Вы должны указать код купона в процессе оформления заказа, чтобы получить скидку.' . "\n" . 'Код Вашего купона: %s'); 

/* ICW Credit class gift voucher end */
?>