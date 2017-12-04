<?php
/*
  $Id: gv_send.php,v 1.1.1.1 2003/09/18 19:04:28 wilt Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Gift Voucher System v1.0
  Copyright (c) 2001,2002 Ian C Wilson
  http://www.phesis.org

  Released under the GNU General Public License
*/

  define('HEADING_TITLE', 'Відправити сертифікат');
  define('NAVBAR_TITLE', 'Відправити сертифікат');
  define('EMAIL_SUBJECT', 'Повідомлення від Інтернет-магазину');
  define('HEADING_TEXT', '<br> Щоб відправити сертифікат, Ви повинні заповнити наступну форму. <br>');
  define('ENTRY_NAME', 'Ім\'я одержувача:');
  define('ENTRY_EMAIL', 'E-Mail адреса одержувача:');
  define('ENTRY_MESSAGE', 'Повідомлення:');
  define('ENTRY_AMOUNT', 'Сума сертифіката:');
  define('ERROR_ENTRY_AMOUNT_CHECK', '&nbsp;&nbsp;<span class="errorText"> Невірна сума </span>');
  define('ERROR_ENTRY_EMAIL_ADDRESS_CHECK', '&nbsp;&nbsp;<span class="errorText">Невірний Email адреса</span>');
  define('MAIN_MESSAGE', 'Ви вирішили відправити сертифікат на суму %s своєму знайомому %s, його Email адреса: %s <br> <br> Отримувач сертифіката отримає наступне повідомлення: <br> <br> Шановний %s <br> <br>Вам відправлений сертифікат на суму %s, відправник: %s');

  define('PERSONAL_MESSAGE', '%s пише:');
  define('TEXT_SUCCESS', 'Вітаємо, Ваш сертифікат успішно відправлений');


  define('EMAIL_SEPARATOR', '------------------------------------------- --------------------------------------------- ');
  define('EMAIL_GV_TEXT_HEADER', 'Вітаємо, Ви отримали сертифікат на суму %s');
  define('EMAIL_GV_TEXT_SUBJECT', 'Подарунок від %s');
  define('EMAIL_GV_FROM', 'Відправник цього сертифікату - %s');
  define('EMAIL_GV_MESSAGE', 'Повідомлення відправника:');
  define('EMAIL_GV_SEND_TO', 'Привіт %s');
  define('EMAIL_GV_REDEEM', 'Щоб активізувати сертифікат, відкрийте посилання, яка розташована нижче. Код сертифіката: %s');
  define('EMAIL_GV_LINK', 'Клікніть тут, щоб активізувати сертифікат');
  define('EMAIL_GV_VISIT', 'або зайдіть сюди');
  define('EMAIL_GV_ENTER', 'і введіть код сертифіката');
  define('EMAIL_GV_FIXED_FOOTER', 'Якщо у Вас виникають проблеми при активізації сертифікату за допомогою посилання, зазначеного вище,'. "\n".' Ми рекомендуємо вводити код сертифіката при оформленні замовлення, а не через посилання, що вказане вище.' . "\n\n");
  define('EMAIL_GV_SHOP_FOOTER', '');
?>