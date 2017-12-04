<?php

/*
  $Id: orders.php,v 1.2 2003/09/24 15:18:15 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include_once __DIR__ . '/includes/application_top.php';
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {

      case 'clear_all':
          $status = tep_db_prepare_input($_POST['status']);
          if (tep_not_null($status)){ $_where = " where orders_status = '" . $status . "'";} else {$_where = '';}
          $orders_clear_query ="delete from " . TABLE_ORDERS . $_where;
          tep_db_query($orders_clear_query);

          break;


      case 'update_order':
        $oID = tep_db_prepare_input($_GET['oID']);
        $status = tep_db_prepare_input($_POST['status']);
        if($status==0) $status=1;
        $comments = tep_db_prepare_input($_POST['comments']);

        $order_updated = false;
        $check_status_query = tep_db_query("select customers_name, customers_preorder, customers_email_address, orders_status, date_purchased, customers_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        $check_status = tep_db_fetch_array($check_status_query);
// BOF: WebMakers.com Added: Downloads Controller
// always update date and time on order_status
// original        if ( ($check_status['orders_status'] != $status) || tep_not_null($comments)) {
                   if ( ($check_status['orders_status'] != $status) || $comments != '' || ($status ==DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE) ) {
          tep_db_query("update " . TABLE_ORDERS . " set orders_status = '" . tep_db_input($status) . "', last_modified = now() where orders_id = '" . (int)$oID . "'");
        $check_status_query2 = tep_db_query("select customers_name, customers_preorder, customers_id, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
        $check_status2 = tep_db_fetch_array($check_status_query2);
      if ( $check_status2['orders_status']==DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE ) {
        tep_db_query("update " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " set download_maxdays = '" . tep_get_configuration_key_value('DOWNLOAD_MAX_DAYS') . "', download_count = '" . tep_get_configuration_key_value('DOWNLOAD_MAX_COUNT') . "' where orders_id = '" . (int)$oID . "'");
      }
// EOF: WebMakers.com Added: Downloads Controller

          $customer_notified = '0';
          if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
            $notify_comments = '';

// BOF: WebMakers.com Added: Downloads Controller - Only tell of comments if there are comments
            if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
              $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . '<br />';
            }
// EOF: WebMakers.com Added: Downloads Controller
            $email = STORE_NAME . '<br />' . EMAIL_SEPARATOR . '<br />' . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . '<br />' . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . '<br />' . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_short($check_status['date_purchased']) . '<br /><br />' . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);

            tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT . ' №' . tep_db_input($oID), $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

            $customer_notified = '1';
          }
          
          //sms с комментарием и статусом заказа
          if (SMS_ENABLE=='true' && SMS_CHANGE_STATUS=='true') {    
              if (isset($_POST['notify_sms']) && ($_POST['notify_sms'] == 'on')) {            
                                $customer_query = tep_db_query("select c.customers_id, o.* from customers as c, orders as o where o.customers_id = c.customers_id and o.orders_id = " . (int)$oID);
                                $customer = tep_db_fetch_array($customer_query);
                                $enc_terminal = SMS_ENC;
                                
                                $client = new SoapClient('http://vipsms.net/api/soap.html');
                                $res = $client->auth(SMS_LOGIN, SMS_PASSWORD);
                                $sessid = $res->message;
      
                                $res = $client->sendSmsOne($sessid, $customer['customers_telephone'], SMS_SIGN, TEXT_ORDER_SMS_ORDER.$oID.'. '.TEXT_ORDER_SMS_STATUS.$orders_status_array[$status].'. '.$comments);
                            
                        }
              }           
          //sms с комментарием и статусом заказа
          
          tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . (int)$oID . "', '" . tep_db_input($status) . "', now(), '" . tep_db_input($customer_notified) . "', '" . tep_db_input($comments)  . "')");

          $order_updated = true;
        }

        if ($order_updated == true) {
         $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
        } else {
          $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
        }

        // denuz added accumulated discount

        $changed = false;

        $check_group_query = tep_db_query("select customers_groups_id from customers_groups_orders_status where orders_status_id = " . $status);
        if (tep_db_num_rows($check_group_query)) {
           while ($groups = tep_db_fetch_array($check_group_query)) {
              // calculating total customers purchase
              // building query
              $customer_query = tep_db_query("select c.* from customers as c, orders as o where o.customers_id = c.customers_id and o.orders_id = " . (int)$oID);
              $customer = tep_db_fetch_array($customer_query);
              $customer_id = $customer['customers_id'];
              $statuses_groups_query = tep_db_query("select orders_status_id from customers_groups_orders_status where customers_groups_id = " . $groups['customers_groups_id']);
              $purchase_query = "select sum(ot.value) as total from orders_total as ot, orders as o where ot.orders_id = o.orders_id and o.customers_id = " . $customer_id . " and ot.class = 'ot_total' and (";
              $statuses = tep_db_fetch_array($statuses_groups_query);
              $purchase_query .= " o.orders_status = " . $statuses['orders_status_id'];
              while ($statuses = tep_db_fetch_array($statuses_groups_query)) {
                  $purchase_query .= " or o.orders_status = " . $statuses['orders_status_id'];
              }
              $purchase_query .=");";

              $total_purchase_query = tep_db_query($purchase_query);
              $total_purchase = tep_db_fetch_array($total_purchase_query);
              $customers_total = $total_purchase['total'];

              // looking for current accumulated limit & discount
              $acc_query = tep_db_query("select cg.customers_groups_accumulated_limit, cg.customers_groups_name, cg.customers_groups_discount from customers_groups as cg, customers as c where cg.customers_groups_id = c.customers_groups_id and c.customers_id = " . $customer_id);
              $current_limit = @mysql_result($acc_query, 0, "customers_groups_accumulated_limit");
              $current_discount = @mysql_result($acc_query, 0, "customers_groups_discount");
              $current_group = @mysql_result($acc_query, "customers_groups_name");

              // ok, looking for available group
              $groups_query = tep_db_query("select customers_groups_discount, customers_groups_id, customers_groups_name, customers_groups_accumulated_limit from customers_groups where customers_groups_accumulated_limit < " . $customers_total . " and customers_groups_discount < " . $current_discount . " and customers_groups_accumulated_limit > " . $current_limit . " and customers_groups_id = " . $groups['customers_groups_id'] . " order by customers_groups_accumulated_limit DESC");

              if (tep_db_num_rows($groups_query)) {
                 // new group found
                 $customers_groups_id = @mysql_result($groups_query, 0, "customers_groups_id");
                 $customers_groups_name = @mysql_result($groups_query, 0, "customers_groups_name");
                 $limit = @mysql_result($groups_query, 0, "customers_groups_accumulated_limit");
                 $current_discount = @mysql_result($groups_query, 0, "customers_groups_discount");

                 // updating customers group
                 tep_db_query("update customers set customers_groups_id = " . $customers_groups_id . " where customers_id = " . $customer_id);
                 $changed = true;
             }
           }
           $groups_query = tep_db_query("select cg.* from customers_groups as cg, customers as c where c.customers_groups_id = cg.customers_groups_id and c.customers_id = " . $customer_id);
           $customers_groups_id = @mysql_result($groups_query, 0, "customers_groups_id");
           $customers_groups_name = @mysql_result($groups_query, 0, "customers_groups_name");
           $limit = @mysql_result($groups_query, 0, "customers_groups_accumulated_limit");
           $current_discount = @mysql_result($groups_query, 0, "customers_groups_discount");
           if ($changed) {
             // send emails
             $text =     EMAIL_TEXT_LIMIT . $currencies->display_price($limit, 0) . '111111<br>' .
                         EMAIL_TEXT_CURRENT_GROUP . $customers_groups_name . '<br>' .
                         EMAIL_TEXT_DISCOUNT . $current_discount . "%";

             // to store owner
             $email_text = EMAIL_ACC_DISCOUNT_INTRO_OWNER . '<br><br>' .
                           EMAIL_TEXT_CUSTOMER_NAME . ' ' . $customer['customers_firstname'] . ' ' . $customer['customers_lastname']  . '7777<br>' .
                           EMAIL_TEXT_CUSTOMER_EMAIL_ADDRESS . ' ' . $customer['customers_email_address']  . '<br>' .
                           EMAIL_TEXT_CUSTOMER_TELEPHONE . ' ' . $customer['customers_telephone'] . '<br>' . $text;
             tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_ACC_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

             // to customer
             $email_text = EMAIL_ACC_INTRO_CUSTOMER . '<br><br>' .
                           $text . '<br><br>' .
                           EMAIL_ACC_FOOTER;
             tep_mail ($customer['customers_firstname'] . ' ' . $customer['customers_lastname'], $customer['customers_email_address'], EMAIL_ACC_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
           }
        }
        // eof denuz added accumulated discount

        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=edit'));
        break;
      case 'deleteconfirm':
        $oID = tep_db_prepare_input($_GET['oID']);

        tep_remove_order($oID, $_POST['restock']);

        tep_redirect(tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action'))));
        break;
    }
  }

  if (($action == 'edit') && isset($_GET['oID'])) {
    $oID = tep_db_prepare_input($_GET['oID']);

    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $order_exists = true;
    if (!tep_db_num_rows($orders_query)) {
      $order_exists = false;
      $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
  }
// BOF: WebMakers.com Added: Additional info for Orders
// Look up things in orders
$the_extra_query= tep_db_query("select * from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
$the_extra= tep_db_fetch_array($the_extra_query);
$the_customers_id= $the_extra['customers_id'];
// Look up things in customers
$the_extra_query= tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $the_customers_id . "'");
$the_extra= tep_db_fetch_array($the_extra_query);
$the_customers_fax= $the_extra['customers_fax'];
// EOF: WebMakers.com Added: Additional info for Orders

  include(DIR_WS_CLASSES . 'order.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<style>
.dataTableContent a {
  text-decoration:underline;
}

.attr_img img{
  cursor:pointer;
}
</style>
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<link type="text/css" href="../includes/javascript/ui/css/smoothness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
<script src="https://www.google.com/jsapi"></script>
<script>
   google.load("jquery", "1.7.1");
   google.load("jqueryui", "1.7.2");
</script>

<script type="text/javascript">
      $(function(){
        $("#fdp_in, #fdd_in, #fdp_out, #fdd_out").datepicker({dateFormat: 'yy-mm-dd'});
      });

            function change_oplacheno(orders_id,current_id,select_id_name) {
              $('#'+select_id_name+orders_id+'_'+current_id).css('display','none');

          $('select[name='+select_id_name+'_href]').each(function(){ // скрываем все ранее открытые селекты
              $(this).parent().find('a').css('display','block'); // возвращаем вместо селекта название
              $(this).remove(); // удаляем селект
          });

              $('#id_'+select_id_name)
         .clone()
         .removeAttr('id')
         .attr('name',select_id_name+'_href')
         .css('display','block')
         .appendTo($('#'+select_id_name+orders_id+'_'+current_id).parent());

        $('select[name='+select_id_name+'_href] option[value='+current_id+']').attr('selected', true); // из спана ищем что сейчас выбрано
        $('select[name='+select_id_name+'_href]').change(function(){
          $(this).css('opacity','0.5');
          var selected_text = $('select[name='+select_id_name+'_href] option:selected').text();
          $.get('r_order_change.php',{'change':select_id_name,'current_id':$(this).val(),'orders_id':orders_id,'selected_text':selected_text},function(data){
            $('select[name='+select_id_name+'_href]').parent().html(data);
          });

        });
      }

            function change_input(orders_id,current_id,select_id_name) {
        $('#'+select_id_name+orders_id+'_'+current_id).css('display','none');
          $('input[name='+select_id_name+'_href]').each(function(){ // скрываем все ранее открытые селекты
              $(this).parent().find('a').css('display','block'); // возвращаем вместо селекта название
              $(this).remove(); // удаляем селект
          });

              $('#id_'+select_id_name)
         .clone()
         .removeAttr('id')
         .attr('name',select_id_name+'_href')
         .attr('type','text')
         .css({'display':'block','width':'75px','fontSize':'9px','text-align':'center'})
         .appendTo($('#'+select_id_name+orders_id+'_'+current_id).parent());

        if(select_id_name=='orders_date_finished') {
            $("input[name=orders_date_finished_href]").datepicker({dateFormat: 'yy-mm-dd'}).datepicker( "show" );
        }

        $('input[name='+select_id_name+'_href]').val(current_id); // из спана ищем что сейчас выбрано
        $('input[name='+select_id_name+'_href]').change(function(){
          $(this).css('opacity','0.5');
          var selected_text = $('input[name='+select_id_name+'_href]').val();
          $.get('r_order_change.php',{'change':select_id_name,'current_id':$(this).val(),'orders_id':orders_id,'selected_text':selected_text},function(data){
            $('input[name='+select_id_name+'_href]').parent().html(data);
          });

        });
      }



</script>
        <style>
          #orders_table tr:hover td{
           background:#E9F4FF;
         }

        </style>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_smend //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_smend //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
<td class="pageHeading" align="right">

<?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $_GET['oID'] . '&action=delete') . '" >' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>'; ?>&nbsp;
<?php
// Modifyed 4 VaM
echo '<a href="' . tep_href_link("edit_orders.php",
tep_get_all_get_params(array('action'))) .
'&customer_id='.$the_customers_id.'">' .
tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> &nbsp; ';
//end mod for VaM
?>
<?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?>
</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
        <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table width="50%" border="0" cellspacing="0" cellpadding="2" align="left">
              <tr>
                <td class="main" valign="top" width="170px;"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
                <td class="main"><?php echo tep_address_format($order->customer['format_id'], $order->customer, 1, '', '<br>'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
                <td class="main"><?php echo $order->customer['telephone']; ?></td>
              </tr>
               <tr>
                 <td class="main"><b><?php echo ENTRY_FAX_NUMBER; ?></b></td>
                 <td class="main"><?php echo $order->customer['fax']; ?></td>
               </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
              </tr>
        <tr>
        <td class="main"><b><?php echo TEXT_REFERER; ?></b></td>
        <td class="main"><a href="<?php echo $order->info['customers_referer_url'];  ?>" target="_blank"><?php echo $order->info['customers_referer_url']; ?></a></td>
                </tr>

            </table>
            <table width="48%" border="0" cellspacing="0" cellpadding="2" align="right">
              <tr>
                <td class="main" valign="top" width="15%"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
                <td class="main" align="left"><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, '', '<br>'); ?></td>
              </tr>
              <?php if(!empty($order->delivery['suburb'])) : ?>
              <tr>
                <td class="main" valign="top" width="15%"><b><?php echo ENTRY_SHIPPING_SUBURB; ?></b></td>
                <td class="main" align="left"><?php echo tep_escape($order->delivery['suburb']); ?></td>
              </tr>
              <?php endif; ?>
            </table></td>

      </tr>

      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
<?php
// BOF: WebMakers.com Added: Show Order Info
?>
<!-- add Order # // -->
<tr>
<td class="main"><b><?php echo TEXT_INFO_DELETE_DATA_OID; ?></b></td>
<td class="main"><?php echo tep_db_input($oID); ?></td>
</tr>
<!-- add date/time // -->
<tr>
<td class="main"><b><?php echo EMAIL_TEXT_DATE_ORDERED; ?></b></td>
<td class="main"><?php echo tep_datetime_short($order->info['date_purchased']); ?></td>
</tr>
<?php
// EOF: WebMakers.com Added: Show Order Info
?>
          <tr>
            <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
            <td class="main"><?php echo $order->info['payment_method']; ?></td>
          </tr>
<?php
    if (tep_not_null($order->info['cc_type']) || tep_not_null($order->info['cc_owner']) || tep_not_null($order->info['cc_number'])) {
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
            <td class="main"><?php echo $order->info['cc_type']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
            <td class="main"><?php echo $order->info['cc_owner']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
            <td class="main"><?php echo $order->info['cc_number']; ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
            <td class="main"><?php echo $order->info['cc_expires']; ?></td>
          </tr>
<?php
    }
?>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) { 
      echo '          <tr class="dataTableRow" >' . "\n" .
           '            <td class="dataTableContent" valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
  //         '            <td class="dataTableContent" valign="top">' . $order->products[$i]['name'];
           '            <td class="dataTableContent" valign="top"><a target="_blank" href="'.HTTP_CATALOG_SERVER.'/product_info.php?products_id='.$order->products[$i]['products_id'].'">' . $order->products[$i]['name'].'</a>';

      if (isset($order->products[$i]['attributes']) && (sizeof($order->products[$i]['attributes']) > 0)) {
        for ($j = 0, $k = sizeof($order->products[$i]['attributes']); $j < $k; $j++) {
          echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
          if ($order->products[$i]['attributes'][$j]['price'] != '0') echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
          echo '</i></small></nobr>';
        }
      }

      echo '            </td>' . "\n" .
           '            <td class="dataTableContent" valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td class="dataTableContent" align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
      echo '          </tr>' . "\n";
    }
?>
          <tr>
            <td align="right" colspan="5"><table border="0" cellspacing="0" cellpadding="2">
<?php
    for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
      echo '              <tr>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
           '              </tr>' . "\n";
    }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
<?php
    $orders_history_query = tep_db_query("select orders_status_id, date_added, customer_notified, comments from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . (int)$oID . "' order by date_added");
    if (tep_db_num_rows($orders_history_query)) {
      while ($orders_history = tep_db_fetch_array($orders_history_query)) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($orders_history['customer_notified'] == '1') {
          echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
        } else {
          echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
        }
        echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n" .
             '            <td class="smallText">' . tep_db_output($orders_history['comments']) . '&nbsp;</td>' . "\n" .
             '          </tr>' . "\n";
      }
    } else {
        echo '          <tr>' . "\n" .
             '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
             '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr>
        <td class="main"><br><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '5'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('status', FILENAME_ORDERS, tep_get_all_get_params(array('action')) . 'action=update_order'); ?>
        <td class="main"><?php echo tep_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></td>
              </tr>
              <tr>
                <td class="main">
                <?php echo tep_draw_checkbox_field('notify', '', true); ?> <b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b><br />
                <?php if(SMS_ENABLE=='true' && SMS_CHANGE_STATUS=='true'): ?>
                <?php echo tep_draw_checkbox_field('notify_sms', '', false); ?> <b><?php echo ENTRY_NOTIFY_CUSTOMER_SMS; ?></b><br />
                <?php endif; ?>                
                <?php echo tep_draw_checkbox_field('notify_comments', '', true); ?> <b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b></td>
              </tr>
            </table></td>
            <td valign="top"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
          </tr>
        </table></td>
      </form></tr>
           <tr>
        <td colspan="2" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a><a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
     </tr>
<?php
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" id="orders_table">
<?php
  $HEADING_CUSTOMERS = TABLE_HEADING_CUSTOMERS .'&nbsp;';
  $HEADING_CUSTOMERS .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=customer&order=ascending">';
  $HEADING_CUSTOMERS .= '+</a>';
  $HEADING_CUSTOMERS .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=customer&order=decending">';
  $HEADING_CUSTOMERS .= '-</a>';
  $HEADING_DATE_PURCHASED = TABLE_HEADING_DATE_PURCHASED .'&nbsp;';
  $HEADING_DATE_PURCHASED .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=date&order=ascending">';
  $HEADING_DATE_PURCHASED .= '+</a>';
  $HEADING_DATE_PURCHASED .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=date&order=decending">';
  $HEADING_DATE_PURCHASED .= '-</a>';
//  $HEADING_ORDER_NUMBER = TABLE_HEADING_ORDER_NUMBER .'&nbsp;';
  $HEADING_ORDER_NUMBER =  '#&nbsp;';
  $HEADING_ORDER_NUMBER .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=orders&order=ascending">';
  $HEADING_ORDER_NUMBER .= '+</a>';
  $HEADING_ORDER_NUMBER .= '<a href="' . $_SERVER['PHP_SELF'] . '?sort=orders&order=decending">';
  $HEADING_ORDER_NUMBER .= '-</a>';

  $pl_sposob_oplaty = array();
  $pl_sposob_oplaty[] = array('id' => 0, 'text' => 'Нал');
  $pl_sposob_oplaty[] = array('id' => 1, 'text' => 'Безнал');
  $pl_sposob_oplaty[] = array('id' => 2, 'text' => 'Приват');
  $pl_sposob_oplaty[] = array('id' => 3, 'text' => 'Наложенный');
  echo tep_draw_pull_down_menu('sposob_oplaty', $pl_sposob_oplaty,'','id="id_sposob_oplaty" style="display:none;"');


 ?>
              <tr>
               <?php echo tep_draw_form('orders', FILENAME_ORDERS, '', 'get'); ?>
                <td class="dataTableContent" align="center" width="260"><?php echo tep_draw_input_field('filter_cid', '', 'style="width:260px;"');?></td>
                <td class="dataTableContent" align="center"><?php echo tep_draw_input_field('filter_oid', '', 'style="width:30px;"');?></td>
                <td>&nbsp;</td>
                <td class="dataTableContent" align="right">c <?php echo tep_draw_input_field('filter_date_pokup_in', '', 'style="width:76px;padding:0 2px;font-size:9px;" id="fdp_in"');?><br />по <?php echo tep_draw_input_field('filter_date_pokup_out', '', 'style="width:76px;padding:0 2px;font-size:9px;" id="fdp_out"');?></td>
                <td class="dataTableContent" align="right">c <?php echo tep_draw_input_field('filter_date_dostav_in', '', 'style="width:76px;padding:0 2px;font-size:9px;" id="fdd_in"');?><br />по <?php echo tep_draw_input_field('filter_date_dostav_out', '', 'style="width:76px;padding:0 2px;font-size:9px;" id="fdd_out"');?></td>
                <td>&nbsp;</td>
                <td class="dataTableContent" align="center"><?php echo tep_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), '', 'onChange="this.form.submit();" style="width:110px;text-align:right;"'); ?></td>
                <td colspan="3">
                  <?php echo tep_image_submit('button_search.gif', IMAGE_DELETE);
                        echo tep_draw_hidden_field('filter_on', 'on');
                        echo '&nbsp;&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ORDERS) . '">' . tep_image(DIR_WS_ICONS . 'del.gif', 'сбросить фильтр') . '</a>'; ?>
                </td>
               </form>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo $HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo $HEADING_ORDER_NUMBER; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo $HEADING_DATE_PURCHASED; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo SHIPPING_DATE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php  echo SHIPPING_MAN; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php  echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php  echo SHIPPING_METHOS; ?>
                  <?php
  echo tep_draw_pull_down_menu('orders_status', $orders_statuses,'','id="id_orders_status" style="display:none;"');
  echo tep_draw_hidden_field('voditel', '','id="id_voditel"');
  echo tep_draw_hidden_field('orders_date_finished', '','id="id_orders_date_finished"');
                  ?>
                </td>
                <td class="dataTableHeadingContent" align="center"><?php  echo ACTION; ?></td>
              </tr>
<?php
    $sortorder = 'order by ';
    if($_GET["sort"] == 'customer') {
      if($_GET["order"] == 'ascending') {
        $sortorder .= 'o.customers_name  asc, ';
      } else {
        $sortorder .= 'o.customers_name desc, ';
      }
    } elseif($_GET["sort"] == 'date') {
      if($_GET["order"] == 'ascending') {
        $sortorder .= 'o.date_purchased  asc, ';
      } else {
        $sortorder .= 'o.date_purchased desc, ';
      }
    } elseif($_GET["sort"] == 'orders') {
      if($_GET["order"] == 'ascending') {
        $sortorder .= 'o.orders_id  asc, ';
      } else {
        $sortorder .= 'o.orders_id desc, ';
      }
     }
    $sortorder .= 'o.orders_id DESC';

    if (isset($_GET['cID'])) {
      $cID = tep_db_prepare_input($_GET['cID']);
      $orders_query_raw = "select o.orders_id, o.orders_date_finished, o.pl_sposob_oplaty, o.pl_voditel, o.orders_status, o.customers_name, o.customers_preorder, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$cID . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by orders_id DESC";
    } elseif ($_GET['filter_on']=='on') {

      $where_filter = '';
      if($_GET['status']!='') $where_filter .= "and s.orders_status_id = '" . $_GET['status'] . "'";

      if($_GET['status_docs']=='-1') $_GET['status_docs'] = '';
      if($_GET['status_docs']!='') $where_filter .= "and o.pl_status_docs = '" . $_GET['status_docs'] . "'";

      if($_GET['filter_cid']!='') $where_filter .= "and o.customers_name like '%" . $_GET['filter_cid'] . "%'";
      if($_GET['filter_oid']!='') $where_filter .= "and o.orders_id = '" . $_GET['filter_oid'] . "'";

      if($_GET['filter_date_pokup_in']!='') $where_filter .= "and o.date_purchased >= '" . $_GET['filter_date_pokup_in'] . "'";
      if($_GET['filter_date_pokup_out']!='') $where_filter .= "and o.date_purchased <= '" . $_GET['filter_date_pokup_out'] . "'";
      if($_GET['filter_date_dostav_in']!='') $where_filter .= "and o.orders_date_finished >= '" . $_GET['filter_date_dostav_in'] . "'";

      $orders_query_raw = "select o.orders_id, o.orders_date_finished, o.pl_sposob_oplaty, o.pl_voditel, o.orders_status, o.customers_name, o.customers_preorder, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id ".$where_filter." and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' order by o.orders_id DESC";
    } else {
      $orders_query_raw = "select o.orders_id, o.orders_date_finished, o.pl_sposob_oplaty, o.pl_voditel, o.orders_status, o.customers_name, o.customers_preorder, o.customers_id, o.payment_method, o.date_purchased, o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text as order_total from " . TABLE_ORDERS . " o left join " . TABLE_ORDERS_TOTAL . " ot on (o.orders_id = ot.orders_id), " . TABLE_ORDERS_STATUS . " s where o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and ot.class = 'ot_total' " . $sortorder;
    }
    $orders_split = new splitPageResults($_GET['page'], 50 /*MAX_DISPLAY_SEARCH_RESULTS*/, $orders_query_raw, $orders_query_numrows);
    $orders_query = tep_db_query($orders_query_raw);


    $sposob_oplaty_array = array('Нал','Безнал','Приват','Наложенный');
    $orders_status_array = $orders_status_array;

    while ($orders = tep_db_fetch_array($orders_query)) {
      if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders['orders_id']))) && !isset($oInfo)) {
        $oInfo = new objectInfo($orders);
      }
      $preorder = '';
      if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id) && $action == 'delete') {
            echo '<tr style="background:#FFE8E9;">';
      } else if ($orders['customers_preorder'] == 'true') {
            echo '<tr style="background:#ffe2c3;">'; 
            $preorder = '<span style="color:red; font-weight:bold;">Предзаказ</span>';
      } else echo '<tr >';

?>
                <td class="dataTableContent"><?php echo '<a style="text-decoration:none;" href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '&nbsp;' . $orders['customers_name'].' '.$preorder.'</a>'; ?></td>
                <td class="dataTableContent" align="right"><?php echo strip_tags($orders['orders_id']); ?></td>
                <td class="dataTableContent" align="right"><?php echo strip_tags($orders['order_total']); ?></td>
                <td class="dataTableContent" align="center"><small style="font-size:9px;color:#888;"><?php echo tep_datetime_short($orders['date_purchased']); ?></small></td>
                <td class="dataTableContent" align="center">
                  <a id="orders_date_finished<?php echo $orders['orders_id'].'_'.$orders['orders_date_finished']; ?>" href="javascript:;" onClick="change_input(<?php echo $orders['orders_id'].',\''.$orders['orders_date_finished'].'\',\'orders_date_finished\''; ?>);">
                    <?php echo $orders['orders_date_finished']==''?'-':strip_tags($orders['orders_date_finished']); ?>
                  </a>
                </td>
                <td class="dataTableContent" align="center">
                  <a id="voditel<?php echo $orders['orders_id'].'_'.$orders['pl_voditel']; ?>" href="javascript:;" onClick="change_input(<?php echo $orders['orders_id'].',\''.$orders['pl_voditel'].'\',\'voditel\''; ?>);">
                    <?php echo $orders['pl_voditel']==''?'-':strip_tags($orders['pl_voditel']); ?>
                  </a>
                </td>

                <td class="dataTableContent" align="right">
                  <a id="orders_status<?php echo $orders['orders_id'].'_'.$orders['orders_status']; ?>" href="javascript:;" onClick="change_oplacheno(<?php echo $orders['orders_id'].','.$orders['orders_status'].',\'orders_status\''; ?>);">
                    <?php echo $orders_status_array[$orders['orders_status']]; ?>
                  </a>
                </td>

                <td class="dataTableContent" align="center">
                  <a id="sposob_oplaty<?php echo $orders['orders_id'].'_'.$orders['pl_sposob_oplaty']; ?>" href="javascript:;" onClick="change_oplacheno(<?php echo $orders['orders_id'].','.$orders['pl_sposob_oplaty'].',\'sposob_oplaty\''; ?>);">
                    <?php echo $sposob_oplaty_array[$orders['pl_sposob_oplaty']]; ?>
                  </a>
                </td>

                <td class="dataTableContent" align="center" width="170" valign="top">
<?php
 if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id) && $action == 'delete') {

      echo tep_draw_form('orders', FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm');
      echo '<b style="color:#BD0000;">'.TEXT_INFO_DELETE_INTRO.'</b> #<b>' . $oInfo->orders_id . '</b><br />'.$oInfo->customers_name.'<br />';
      echo '<div class="left">'.tep_draw_checkbox_field('restock') . '</div>
            <div class="left" style="width:150px;color:#888;">' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY.'</div>
            <div class="clear"></div>';
      echo '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $oInfo->orders_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
      echo '</form>';

 } else {
        echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=edit') . '">
             ' . tep_image(DIR_WS_ICONS . 'icon_properties_add.gif', ICON_PREVIEW) . '</a> ';
        echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $orders['orders_id'] . '&action=delete') . '">
              ' . tep_image(DIR_WS_ICONS . 'del.gif', IMAGE_DELETE) . '</a>
              <a href="' . tep_href_link(FILENAME_EDIT_ORDERS, 'oID=' . $orders['orders_id'] . '&customer_id='.$orders['customers_id']). '">
              ' . tep_image(DIR_WS_ICONS . 'move.gif', IMAGE_UPDATE) . '</a> ';

        echo '<a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $orders['orders_id']) . '" TARGET="_blank">' . tep_image(DIR_WS_ICONS . 'copy.gif', IMAGE_ORDERS_INVOICE) . '</a>';
 //       echo '<a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $orders['orders_id']) . '" TARGET="_blank">' . tep_image(DIR_WS_ICONS . 'copy.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a>';
 }

?>
                </td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="10"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_query_numrows, 50/*MAX_DISPLAY_SEARCH_RESULTS*/, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_query_numrows, 50/*MAX_DISPLAY_SEARCH_RESULTS*/, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'oID', 'action'))); ?></td>
                  </tr>

                </table></td>

              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_smend //-->
  </tr>
</table>
<!-- body_smend //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_smend //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
