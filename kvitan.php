<?php
/*
  $Id: kvitan.php,v 1.6 2003/06/20 00:37:30 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

include_once __DIR__ . '/includes/application_top.php';

require(DIR_WS_CLASSES . 'order.php');
  $order = new order;

/*error_log("Квитанция заказ ".$order);
error_log("Квитанция корзина ".$cart->count_contents());
error_log("Квитанция заказ всего " .$order->info['total']);
error_log("Квитанция заказ доставка страна " . $order->delivery['country']);
error_log("Квитанция заказ доставка name " . $order->delivery['name']);
error_log("Квитанция заказ доставка street_address " . $order->delivery['street_address']);
error_log("Квитанция заказ доставка city  " . $order->delivery['city']);
error_log("Квитанция заказ доставка postcode " . $order->delivery['postcode']);
error_log("Квитанция заказ покупатель name " . $order->customer['customers_name']);
error_log("Квитанция заказ покупатель city " . $order->customer['city']);
error_log("Это cart ---------------------", 0);
      while ( list( $key, $val ) = each($cart) ) {
         error_log("$key => $val", 0);
         }
error_log("Это order delivery ---------------------", 0);
      while ( list( $key, $val ) = each($order->delivery) ) {
         error_log("$key => $val", 0);
         }
error_log("Это shipping ---------------------", 0);
      while ( list( $key, $val ) = each($shipping) ) {
         error_log("$key => $val", 0);
         }
error_log("Это order_total ---------------------", 0);
      while ( list( $key, $val ) = each($order_total)) {
         error_log("$key => $val", 0);
         }*/
$FIO = $order->delivery['lastname'] . " " . $order->delivery['firstname'];
$Adress = $FIO . "<BR>" . $order->delivery['postcode'] . ", " . $order->delivery['state'] . ", " . $order->delivery['city'] . ", " . $order->delivery['street_address'];
//$total = $order->info['total'] . " руб.";
$total = number_format( $order->info['total'] * $currencies->get_value('RUR'), $currencies->get_decimal_places('RUR')) . " руб.";
//error_log("Это FIO ". $FIO, 0);
$date = date("d-m-Y");
//error_log("Это дата ". $date, 0);

//'Название банка', 'MODULE_PAYMENT_RUS_BANK_1'
//'Расчетный счет', 'MODULE_PAYMENT_RUS_BANK_2'
//'БИК', 'MODULE_PAYMENT_RUS_BANK_3'
//'Кор./счет', 'MODULE_PAYMENT_RUS_BANK_4'
//'ИНН', 'MODULE_PAYMENT_RUS_BANK_5'
//'Получатель', 'MODULE_PAYMENT_RUS_BANK_6'
//'КПП', 'MODULE_PAYMENT_RUS_BANK_7'

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML dir=ltr lang=ru><HEAD><TITLE>Квитанция на оплату услуг для  <?php echo $FIO ?></TITLE>
<META content="text/html; charset=utf-8" http-equiv=Content-Type>
<META content="MSHTML 5.00.3700.6699" name=GENERATOR></HEAD>
<BODY>
<CENTER>
<TABLE border=1 cellPadding=3 cellSpacing=0 width=600>
  <TBODY>
  <TR>
    <TD align=right height=255 vAlign=top width=240><BR><BR><FONT size=2>
      <P>Извещение</P></FONT><FONT
      size=1><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>
      <P>Кассир</P><BR></FONT></FONT></TD>
    <TD align=right height=255 vAlign=center width=100>
      <TABLE border=1 cellPadding=3 cellSpacing=0 width=360><FONT size=-1>
        <P align=center><?php echo "ИНН " . MODULE_PAYMENT_RUS_BANK_5 . ", " . MODULE_PAYMENT_RUS_BANK_1 . "<br>" . MODULE_PAYMENT_RUS_BANK_6 ?>
	<BR><P></P></FONT><FONT size=1>получатель платежа</FONT>
        <TBODY>
        <TR>
          <TD align=middle colSpan=4 height=10 vAlign=center><FONT size=2>
            <P>Расчетный счет №: <?php echo MODULE_PAYMENT_RUS_BANK_2 ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;БИК:
            <?php echo MODULE_PAYMENT_RUS_BANK_3 ?></P></FONT></TD></TR>
        <TR>
          <TD align=center colSpan=4 height=10 vAlign=middle><FONT size=2>
            <P>Кор. сч.: <?php echo MODULE_PAYMENT_RUS_BANK_4 . " &nbsp;&nbsp;&nbsp;&nbsp;КПП " . MODULE_PAYMENT_RUS_BANK_7 ?></P></FONT></TD></TR>
        <TR>
          <TD colSpan=4><FONT size=2>
            <CENTER>&nbsp; <?php echo $Adress ?> <BR></CENTER></FONT></TD></TR>
        <TR>
          <TD align=middle colSpan=2><FONT size=2>Вид платежа</FONT></TD>
          <TD align=middle><FONT size=2>Дата</FONT></TD>
          <TD align=middle><FONT size=2>Сумма</FONT></TD></TR>
        <TR>
          <TD align=middle colSpan=2><FONT size=1><?php echo MODULE_PAYMENT_RUS_BANK_8 ?></FONT></TD>
          <TD align=middle><FONT size=2><?php echo $date ?></FONT><BR></TD>
          <TD align=middle><FONT size=2><?php echo $total ?></FONT></TD></TR>
        <TR>
          <TD align=left colSpan=2 rowSpan=2><FONT
            size=2>Плательщик:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</FONT></TD>
          <TD align=middle><FONT size=1>Пеня:</FONT></TD>
          <TD align=middle>---<BR></TD></TR>
        <TR align=middle>
          <TD><FONT size=2>Всего:</FONT></TD>
          <TD><FONT size=2><?php echo $total ?></FONT><BR></TD></TR></P></TABLE></TD></TR>
  <TR>
    <TD align=right height=255 vAlign=top width=240><FONT size=2><BR><BR>
      <P>Квитанция</P><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><FONT size=1>
      <P>Кассир</P><BR></FONT></FONT></TD>
    <TD align=right height=255 vAlign=center width=100>
      <TABLE border=1 cellPadding=3 cellSpacing=0 width=360><FONT size=-1>
        <P align=center><?php echo "ИНН " . MODULE_PAYMENT_RUS_BANK_5 . ", " . MODULE_PAYMENT_RUS_BANK_1 . "<br>" . MODULE_PAYMENT_RUS_BANK_6 ?>
        <P></P></FONT><FONT size=1>получатель платежа</FONT>
        <TBODY>
        <TR>
          <TD align=middle colSpan=4 height=10 vAlign=center><FONT size=2>
            <P>Расчетный счет №: <?php echo MODULE_PAYMENT_RUS_BANK_2 ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;БИК:
            <?php echo MODULE_PAYMENT_RUS_BANK_3 ?></P></FONT></TD></TR>
        <TR>
          <TD align=center colSpan=4 height=10 vAlign=middle><FONT size=2>
            <P>Кор. сч.: <?php echo MODULE_PAYMENT_RUS_BANK_4 . " &nbsp;&nbsp;&nbsp;&nbsp;КПП " . MODULE_PAYMENT_RUS_BANK_7 ?></P></FONT></TD></TR>
        <TR>
          <TD colSpan=4><FONT size=2>
            <CENTER>&nbsp; <?php echo $Adress ?><BR></CENTER></FONT></TD></TR>
        <TR>
          <TD align=middle colSpan=2><FONT size=2>Вид платежа</FONT></TD>
          <TD align=middle><FONT size=2>Дата</FONT></TD>
          <TD align=middle><FONT size=2>Сумма</FONT></TD></TR>
        <TR>
          <TD align=middle colSpan=2><FONT size=1><?php echo MODULE_PAYMENT_RUS_BANK_8 ?></FONT></TD>
          <TD align=middle><FONT size=2><?php echo $date ?></FONT><BR></TD>
          <TD align=middle><FONT size=2> <?php echo $total ?></FONT></TD></TR>
        <TR>
          <TD align=left colSpan=2 rowSpan=2><FONT
            size=2>Плательщик:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</FONT></TD>
          <TD align=middle><FONT size=1>Пеня:</FONT></TD>
          <TD align=middle>---<BR></TD></TR>
        <TR align=middle>
          <TD><FONT size=2>Всего:</FONT></TD>
          <TD><FONT size=2><?php echo $total ?></FONT><BR></TD></TR></P></TABLE></TD></TR></TBODY></TABLE></CENTER></BODY></HTML>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

