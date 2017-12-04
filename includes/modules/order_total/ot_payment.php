<?php
/*
  $Id: ot_lev_members.php,v 1.0 2002/04/08 01:13:43 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class ot_payment {
    var $title, $output;

    function ot_payment() {
      $this->code = 'ot_payment';
      $this->title = MODULE_PAYMENT_DISC_TITLE;
      $this->description = MODULE_PAYMENT_DISC_DESCRIPTION;
      $this->enabled = MODULE_PAYMENT_DISC_STATUS;
      $this->sort_order = MODULE_PAYMENT_DISC_SORT_ORDER;
      $this->include_shipping = MODULE_PAYMENT_DISC_INC_SHIPPING;
      $this->include_tax = MODULE_PAYMENT_DISC_INC_TAX;
      $this->percentage = MODULE_PAYMENT_DISC_PERCENTAGE;
      $this->minimum = MODULE_PAYMENT_DISC_MINIMUM;
      $this->calculate_tax = MODULE_PAYMENT_DISC_CALC_TAX;
//      $this->credit_class = true;
      $this->output = array();
    }

    function process() {
     global $order, $currencies;

      $od_amount = $this->calculate_credit($this->get_order_total());
      if ($od_amount>0) {
      $this->deduction = $od_amount;
      $this->output[] = array('title' => $this->title . ' ' . MODULE_PAYMENT_DISC_PERCENTAGE . '%:',
                              'text' => '<b>' . $currencies->format($od_amount) . '</b>',
                              'value' => $od_amount);
    $order->info['total'] = $order->info['total'] - $od_amount;  
}
    }
    

  function calculate_credit($amount) {
    global $order, $customer_id, $payment;
    $od_amount=0;
    $od_pc = $this->percentage;
    $do = false;
    if ($amount > $this->minimum) {
    $table = preg_split("[,]" , MODULE_PAYMENT_DISC_TYPE);
    for ($i = 0; $i < count($table); $i++) {
          if ($payment == $table[$i]) $do = true;
        }
    if ($do) {
// Calculate tax reduction if necessary
    if($this->calculate_tax == 'true') {
// Calculate main tax reduction
      $tod_amount = round($order->info['tax']*10)/10*$od_pc/100;
      $order->info['tax'] = $order->info['tax'] - $tod_amount;
// Calculate tax group deductions
      reset($order->info['tax_groups']);
      while (list($key, $value) = each($order->info['tax_groups'])) {
        $god_amount = round($value*10)/10*$od_pc/100;
        $order->info['tax_groups'][$key] = $order->info['tax_groups'][$key] - $god_amount;
      }  
    }
    $od_amount = round($amount*10)/10*$od_pc/100;
    $od_amount = $od_amount + $tod_amount;
    }
    }
    return $od_amount;
  }

   
  function get_order_total() {
    global  $order, $cart;
    $order_total = $order->info['total'];
// Check if gift voucher is in cart and adjust total
    $products = $cart->get_products();
    for ($i=0; $i<sizeof($products); $i++) {
      $t_prid = tep_get_prid($products[$i]['id']);
      $gv_query = tep_db_query("select products_price, products_tax_class_id, products_model from " . TABLE_PRODUCTS . " where products_id = '" . $t_prid . "'");
      $gv_result = tep_db_fetch_array($gv_query);
      if (preg_match('/^GIFT/', addslashes($gv_result['products_model']))) { 
        $qty = $cart->get_quantity($t_prid);
        $products_tax = tep_get_tax_rate($gv_result['products_tax_class_id']);
        if ($this->include_tax =='false') {
           $gv_amount = $gv_result['products_price'] * $qty;
        } else {
          $gv_amount = ($gv_result['products_price'] + tep_calculate_tax($gv_result['products_price'],$products_tax)) * $qty;
        }
        $order_total=$order_total - $gv_amount;
      }
    }
    if ($this->include_tax == 'false') $order_total=$order_total-$order->info['tax'];
    if ($this->include_shipping == 'false') $order_total=$order_total-$order->info['shipping_cost'];
    return $order_total;
  }  

    
    function check() {
      if (!isset($this->check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_DISC_STATUS'");
        $this->check = tep_db_num_rows($check_query);
      }

      return $this->check;
    }

    function keys() {
      return array('MODULE_PAYMENT_DISC_STATUS', 'MODULE_PAYMENT_DISC_SORT_ORDER','MODULE_PAYMENT_DISC_PERCENTAGE','MODULE_PAYMENT_DISC_MINIMUM', 'MODULE_PAYMENT_DISC_TYPE', 'MODULE_PAYMENT_DISC_INC_SHIPPING', 'MODULE_PAYMENT_DISC_INC_TAX', 'MODULE_PAYMENT_DISC_CALC_TAX');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Разрешить модуль', 'MODULE_PAYMENT_DISC_STATUS', 'true', 'Активировать модуль?', '6', '1','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Порядок сортировки', 'MODULE_PAYMENT_DISC_SORT_ORDER', '799', 'Порядок сортировки модуля обязательно должен быть ниже чем модуль Всего.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Учитывать доставку', 'MODULE_PAYMENT_DISC_INC_SHIPPING', 'true', 'Включать доставку в расчёты', '6', '5', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Учитывать налог', 'MODULE_PAYMENT_DISC_INC_TAX', 'true', 'Включать налог в расчёты.', '6', '6','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Скидка', 'MODULE_PAYMENT_DISC_PERCENTAGE', '5', 'Скидка (в процентах), просто укажите число.', '6', '7', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function ,date_added) values ('Считать налог', 'MODULE_PAYMENT_DISC_CALC_TAX', 'false', 'Учитывать налог при подсчёте скидки.', '6', '5','tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Минимальная сумма заказа', 'MODULE_PAYMENT_DISC_MINIMUM', '100', 'Минимальная сумма заказа для получения скидки.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Способ оплаты', 'MODULE_PAYMENT_DISC_TYPE', 'webmoney', 'Здесь нужно указать название класса модуля оплаты, класс можо узнать в файле модуля, например /includes/modules/payment/webmoney.php. Сверху видно class webmoney, значит если мы хотим дать скидку при оплате через WebMoney, пишем webmoney.', '6', '2', now())");
    }

    function remove() {
      $keys = '';
      $keys_array = $this->keys();
      for ($i=0; $i<sizeof($keys_array); $i++) {
        $keys .= "'" . $keys_array[$i] . "',";
      }
      $keys = substr($keys, 0, -1);

      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in (" . $keys . ")");
    }
  }
?>