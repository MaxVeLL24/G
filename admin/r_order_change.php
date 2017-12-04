<?php require_once('includes/application_top.php');
      require_once('includes/languages/'.$language.'/orders.php');
      require(DIR_WS_CLASSES . 'currencies.php');
  		$currencies = new currencies();
?>

<?php
if($_GET['change']!='') {
    if($_GET['change']=='voditel' or $_GET['change']=='orders_date_finished') $r_myfunction = 'change_input';
    else $r_myfunction = 'change_oplacheno';

    if($_GET['change']=='orders_status' or $_GET['change']=='orders_date_finished') $column_name = $_GET['change'];
    else $column_name = 'pl_'.$_GET['change'];

  	if($_GET['change']=='orders_status') {
      tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " (orders_id, orders_status_id, date_added, customer_notified, comments) values ('" . $_GET['orders_id'] . "', '" . $_GET['current_id'] . "', now(), '0', '')");
// перевод в группу по накопительному пределу

        $changed = false;
        $status = $_GET['current_id'];
        $oID = $_GET['orders_id'];

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
             $text =     EMAIL_TEXT_LIMIT . $currencies->display_price($limit, 0) . "\n" .
                         EMAIL_TEXT_CURRENT_GROUP . $customers_groups_name . "\n" .
                         EMAIL_TEXT_DISCOUNT . $current_discount . "%";

             // to store owner
             $email_text = EMAIL_ACC_DISCOUNT_INTRO_OWNER . "\n\n" .
                           EMAIL_TEXT_CUSTOMER_NAME . ' ' . $customer['customers_firstname'] . ' ' . $customer['customers_lastname']  . "\n" .
                           EMAIL_TEXT_CUSTOMER_EMAIL_ADDRESS . ' ' . $customer['customers_email_address']  . "\n" .
                           EMAIL_TEXT_CUSTOMER_TELEPHONE . ' ' . $customer['customers_telephone'] . "\n\n" . $text;
             tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_ACC_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

             // to customer
             $email_text = EMAIL_ACC_INTRO_CUSTOMER . "\n\n" .
                           $text . "\n\n" .
                           EMAIL_ACC_FOOTER;
             tep_mail ($customer['customers_firstname'] . ' ' . $customer['customers_lastname'], $customer['customers_email_address'], EMAIL_ACC_SUBJECT, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
           }

        }
		// перевод в группу по накопительному пределу END
		}

    $options_vals_query = tep_db_query("update orders set ".$column_name."='".iconv('UTF-8', 'windows-1251', $_GET['current_id'])."', last_modified = now() where orders_id= '".$_GET['orders_id']."'");
    $text = '<a id="'.$_GET['change'].$_GET['orders_id'].'_'.$_GET['current_id'].'" onclick="'.$r_myfunction.'('.$_GET['orders_id'].',\''.$_GET['current_id'].'\',\''.$_GET['change'].'\');" href="javascript:;">'.$_GET['selected_text'].'</a>';

    // echo iconv('UTF-8', 'windows-1251', $text);
    echo $text;

}
?>