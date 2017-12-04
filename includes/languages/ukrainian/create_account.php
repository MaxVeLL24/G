<?php
/*
  $Id: create_account.php,v 1.1.1.1 2003/09/18 19:04:28 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// guest_account start
if ($guest_account==false) {// Not a Guest Account
     define('NAVBAR_TITLE', 'Реєстрація');
} else {
     define('NAVBAR_TITLE', 'Оформлення замовлення');
}
// Guest_account end

  define('HEADING_TITLE', 'Мої дані');
  define('HEADING_TITLE_REG_CLIENT', 'Реєстрація клієнта');
  define('HEADING_TITLE_REG_LOGIN', '<span><b><u>Увійдіть</u></b></span></a>, якщо ви вже зареєстровані.');

// Guest_account start

  define('HEADING_TITLE_GUEST', 'Введіть Ваші дані');

// Guest_account end

  define('TEXT_ORIGIN_LOGIN', '<font style="font-size:12px;"> <font color="#FF0000"><small><b> УВАГА: </ b> </small></font> Вам потрібно заповнити реєстраційну форму. Це дасть Вам можливість оформляти замовлення в нашому інтернет-магазині,<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;якщо Ви вже зареєстровані на нашому сайті, введіть, будь ласка, Ваш логін і пароль&nbsp;</font> <a href="%s"><u>тут</u></a>. ');

  define('EMAIL_SUBJECT', 'Ласкаво просимо в '. STORE_NAME);
  define('EMAIL_GREET_MR', 'Шановний %s! <br> <br>');
  define('EMAIL_GREET_MS', 'Шановна %s! <br> <br>');
  define('EMAIL_GREET_NONE', 'Шановний %s! <br> <br>');
  define('EMAIL_WELCOME', 'Ми раді запросити Вас в інтернет-магазин <b>'. STORE_NAME. '</b>. <br> <br>');
  define('EMAIL_CONTACT', 'Якщо у Вас виникли будь-які питання, пишіть: '. STORE_OWNER_EMAIL_ADDRESS. '. <br> <br>');
  define('EMAIL_TEXT', 'Тепер Ви можете здійснювати покупки.'. "\n");
  define('EMAIL_WARNING', '');

/* ICW Credit class gift voucher begin */
  define('EMAIL_GV_INCENTIVE_HEADER', "\n\n" .'Также повідомити Вам, що Ви отримуєте сертифікат на суму %s');
  define('EMAIL_GV_REDEEM', 'Код Вашого сертифікату %s, Ви можете використовувати Ваш сертифікат при оплаті замовлення, при цьому номінальна вартість сертифікату буде зарахована в якості оплати всього замовлення, або в якості оплати частини вартості Вашого замовлення.');
  define('EMAIL_GV_LINK', 'Перейдіть по посиланню для активізації сертифіката:');
  define('EMAIL_COUPON_INCENTIVE_HEADER', 'Вітаємо з реєстрацією в нашому магазині, раді повідомити, що ми даруємо Вам купон на отримання знижки в нашому магазині.'. "\n".' Даний купон дійсний тільки для Вас.' . "\n");
  define('EMAIL_COUPON_REDEEM', 'Щоб скористатися купоном, Ви повинні вказати код купона в процесі оформлення замовлення, щоб отримати знижку.'. "\n". 'Код Вашого купона: %s');
/* ICW Credit class gift voucher end */
?>