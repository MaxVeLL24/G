<?php
/*
 by raid
 */

function get_var($name, $default = 'none') {
  return (isset($_GET[$name])) ? $_GET[$name] : ((isset($_POST[$name])) ? $_POST[$name] : $default);
}

include_once __DIR__ . '/includes/application_top.php';
require(DIR_WS_CLASSES . 'order.php');
$order = new order;    

$xml_decoded=base64_decode($_POST['operation_xml']);
$xml = simplexml_load_string($xml_decoded);

if ($xml->status == 'success') {
  $sql_data_array = array('orders_status' => MODULE_PAYMENT_LIQPAY_ORDER_STATUS_ID);
  tep_db_perform('orders', $sql_data_array, 'update', "orders_id='".$xml->order_id."'");

  $sql_data_arrax = array('orders_id' => $xml->order_id,
                          'orders_status_id' => MODULE_PAYMENT_LIQPAY_ORDER_STATUS_ID,
                          'date_added' => 'now()',
                          'customer_notified' => '0',
                          'comments' => 'LiqPAY - оплата проведена!');
  tep_db_perform('orders_status_history', $sql_data_arrax);
  echo 'OK'.$xml->order_id;

}
 
?>