<?php
/*
  $Id: login.php,v 1.1.1.1 2003/09/18 19:04:28 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  define('NAVBAR_TITLE', 'Пароль');
  define('HEADING_TITLE', 'Дозвольте увійти!');

// HMCS: Begin Autologon ******************************************** **********************
  define('ENTRY_REMEMBER_ME', 'Запам\'ятати мене <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:win_autologon();"> <b> <u> Прочитати спочатку це! </u></b></a>');
// HMCS: End Autologon ******************************************** **********************


  define('HEADING_NEW_CUSTOMER', 'Реєстрація');
  define('TEXT_NEW_CUSTOMER', 'Я хочу зареєструватися!');
  define('TEXT_NEW_CUSTOMER_INTRODUCTION', 'Зареєструвавшись в нашому магазині, Ви зможете здійснювати покупки набагато <b>швидше і зручніше </b>, крім того, Ви зможете стежити за виконанням замовлень, дивитися історію своїх замовлень.');

  define('HEADING_RETURNING_CUSTOMER', 'Зареєстрований користувач');
  define('TEXT_RETURNING_CUSTOMER', 'Я вже зареєстрований!');

  define('TEXT_PASSWORD_FORGOTTEN', 'Якщо Ви забули пароль, натисніть тут');

  define('TEXT_LOGIN_ERROR', '<font><b>ПОМИЛКА:</b></font>Невірна \'E-Mail Адреса\' та/або \'Пароль \'.');
  define('TEXT_VISITORS_CART', '<font color="#ff0000"> <b>ДО УВАГИ:</b> </font>&nbsp;Вміст Вашого &quot;кошика відвідувача&quot; буде об\'єднано з вмістом Вашого &quot;постійного кошика&quot; як тільки Ви підтвердите реєстрацію. <a href="javascript:session_win();"> [Докладніше] </a> ');

// Guest_account start

  define('HEADING_GUEST_CUSTOMER', 'Швидке оформлення замовлення');
  define('TEXT_GUEST_CUSTOMER', 'Я не хочу реєструватися в магазині!');
  define('TEXT_GUEST_CUSTOMER_INTRODUCTION', 'Якщо Ви хочете максимально швидко оформити замовлення, натискайте кнопку "Продовжити", яка розташована нижче, швидке оформлення замовлення заощадить Ваш час, але у Вас не буде адресної книги, Ви не зможете отримувати новини про останні новинки в нашому магазині. <br> <br> Якщо Ви вже зареєстровані в нашому магазині, введіть свій e-mail адресу і пароль.');
?>
