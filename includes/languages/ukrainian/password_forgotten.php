<?php
/*
  $Id: password_forgotten.php,v 1.8 2003/06/09 22:46:46 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  define('NAVBAR_TITLE_1', 'Вхід');
  define('NAVBAR_TITLE_2', 'Відновлення пароля');

  define('HEADING_TITLE', 'Я забув свій пароль!');

  define('TEXT_MAIN', 'Якщо Ви забули свій пароль, введіть свою e-mail адресу і ми надішлемо Ваш парoль на e-mail, який Ви вказали.');

  define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>Помилка:</b></font> E-Mail адреса не відповідає Вашому обліковому запису, спробуйте ще раз.');

  define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME. ' - Ваш пароль');
  define('EMAIL_PASSWORD_REMINDER_BODY', 'Запит на отримання нового пароля був отриманий від '. $REMOTE_ADDR. '. <br /><br /> Ваш новий пароль в \''. STORE_NAME.'\': %s'.'<br /><br />');

  define('SUCCESS_PASSWORD_SENT', 'Виконано: Ваш новий пароль відправлений Вам на e-mail.');
?>