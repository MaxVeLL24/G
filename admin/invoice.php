<?php
/*
  $Id: invoice.php,v 1.2 2003/09/24 15:18:15 wilt Exp $
  
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  include_once __DIR__ . '/includes/application_top.php';

  $customer_number_query = tep_db_query("select customers_id from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($_GET['order_id'])) . "'");
  $customer_number = tep_db_fetch_array($customer_number_query);
/*
  if ($customer_number['customers_id'] != $customer_id) {
    tep_redirect(tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
  }
*/  
  $payment_info_query = tep_db_query("select payment_info from " . TABLE_ORDERS . " where orders_id = '". tep_db_input(tep_db_prepare_input($_GET['order_id'])) . "'");
  $payment_info = tep_db_fetch_array($payment_info_query);
  $payment_info = $payment_info['payment_info'];

//  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_INVOICE);

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $oID = tep_db_prepare_input($_GET['oID']);
  $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . tep_db_input($oID) . "'");

  include(DIR_WS_CLASSES . 'order.php');
  $order = new order($oID);

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE_PRINT_ORDER . $oID; ?></title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="print.css">
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">


<!-- body_text //-->
<table width="590" align="center" cellpadding="10"><tr><td style="border:2px solid #cccccc">
<table width="580" border="0" align="center" cellpadding="2" cellspacing="0" style="font-family: Tahoma;">
  <tr> 
    <td align="center" class="main"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr> 
        <td valign="top" align="left" class="main"></td>
        <td align="right" valign="bottom" class="main"><script language="JavaScript">
  if (window.print) {
    document.write('<a href="javascript:;" onClick="javascript:window.print()" onMouseOut=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" onMouseOver=document.imprim.src="<?php echo (DIR_WS_IMAGES . 'printimage_over.gif'); ?>"><img src="<?php echo (DIR_WS_IMAGES . 'printimage.gif'); ?>" width="43" height="28" align="absbottom" border="0" name="imprim">' + '<?php echo IMAGE_BUTTON_PRINT; ?></a></center>');
  }
  else document.write ('<h2><?php echo IMAGE_BUTTON_PRINT; ?></h2>')
        </script></td>
      </tr>
    </table></td>
  </tr>
  <tr> 
    <td align="center"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr> 
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr> 
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr> 
                <td class="dataTableHeadingContent" style="font-size: 12px"><b><?php echo ENTRY_SOLD_TO; ?></b></td>
              </tr>
              <tr> 
                <td class="dataTableContent" style="font-size: 12px">
                  <?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '&nbsp;', '<br>'); ?>
                  
                  <br /><br /><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b>&nbsp;&nbsp;<?php echo $order->customer['telephone']; ?>
                  <br /><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b>&nbsp;&nbsp;<?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?>
                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="1">
          <tr> 
            <td align="center" valign="top"><table align="center" width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow"> 
                <td class="dataTableHeadingContent" style="font-size: 12px"><b>ПРОДАВЕЦ</b></td>
              </tr>
              <tr class="dataTableRow"> 
                <td class="dataTableContent" style="font-size: 12px">
                  <?= $_SERVER['HTTP_HOST'];?>
                  <br />
                  <br />
                  <br /><br />
                  <br />+38 
                  <br />
                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="left" class="main"><table width="100%" border="0" cellspacing="0" cellpadding="2" style="font-size: 12px"> 
      <tr> 
        <td  class="main"><?php echo '<b>' . ENTRY_PAYMENT_METHOD . '</b> ' . $order->info['payment_method']; ?></td>
      </tr>
      <tr> 
        <td  class="main"><?php echo $payment_info; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr> 
    <td><table border="0" width="100%" cellspacing="0" cellpadding="3" bgcolor="#cccccc" style="font-size: 12px;">
      <tr><td bgcolor="#cccccc" style="padding-left: 8px;"><b><?php echo TITLE_PRINT_ORDER  . $oID; ?>, от: <?php echo tep_date_short($order->info['date_purchased']); ?></b></td></tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" bgcolor="#ffffff" style="font-size: 12px;">
          <tr> 
            <td style="font-size: 14px; font-weight: bold;padding:5px" align="left"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td style="font-size: 14px; font-weight: bold;padding:5px" align="center"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td style="font-size: 14px; font-weight: bold;padding:5px" align="center">Кол-во <small>(шт)</small></td>
            <td style="font-size: 14px; font-weight: bold;padding:5px" align="center"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></td>
            <td style="font-size: 14px; font-weight: bold;padding:5px" align="center"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          </tr>
        <?php
    for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
      echo '      <tr>' . "\n" .           
           '        <td class="dataTableContent" valign="top" align="left" style="border-top: 1px dashed #cccccc;padding:5px;">' . $order->products[$i]['name'] . '<br>';

    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i><br /></small></nobr>';
      }
    }

      echo '        </td>' . "\n" .
           '        <td class="dataTableContent" valign="top" align="center" style="border-top: 1px dashed #cccccc;padding:5px;">' . $order->products[$i]['model'] . '</td>' . "\n".
           '        <td class="dataTableContent" valign="top" align="center" style="border-top: 1px dashed #cccccc;padding:5px;">' . $order->products[$i]['qty'] . '</td>' . "\n";
echo           '        <td class="dataTableContent" align="center" valign="top" style="border-top: 1px dashed #cccccc;padding:5px;"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '        <td class="dataTableContent" align="center" valign="top" style="border-top: 1px dashed #cccccc;padding:5px;"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '      </tr>' . "\n";
            
      $sum_qty+=$order->products[$i]['qty'];
      $sum_weight+=$order->products[$i]['weight'];      
    }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="right" colspan="7"><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="2" style="font-size: 12px;font-family:Tahoma">
        <tr>
            <td align="right">Товаров в заказе, шт:
            </td>
            <td align="right" width="70px;">
            <?php
                echo $sum_qty;             
            ?>                        
            </td>            
        </tr>
      
              <?php
  for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
    echo '          <tr>' . "\n" .
         '            <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
         '            <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
        </table></td>
      </tr>
    </table></td>
  </tr>
<tr>
<td align="right" style="font-size: 12px;font-family:Tahoma">
<br>
<br>
<br>
Заказ получен.
<br>
Претензий по количеству и качеству нет.
<br>
<br>
__________________________
<br>
подпись клиента
<br>
</td>
</tr>  
</table>
</td></tr></table>
<!-- body_text_smend //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>