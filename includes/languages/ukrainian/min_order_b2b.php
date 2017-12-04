<?php
/*
  $Id: privacy.php,v 1.3 2001/12/20 14:14:15 dgw_ Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/

  define('NAVBAR_TITLE', 'Мінімальна сума замовлення');
  define('HEADING_TITLE', 'Мінімальна сума замовлення');
  define('TEXT_INFORMATION', 'Ви зробили замовлення на загальну суму <b>'. $currencies->format($cart->show_total()). '</b>, але в нашому магазині мінімальна сума замовлення повинна бути як мінімум <b> '.$currencies->format(MIN_ORDER_B2B).'</b>. Ви можете або покласти в кошик ще товар, тим самим досягши мінімальної суми замовлення, або відмовитися від покупки.');

?>