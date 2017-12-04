<?php
  /*
  $Id: edit_orders.php, v2.1 2006/03/21 10:42:44 ams Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
 
  Original file written by Jonathan Hilgeman of SiteCreative.com
    
*/
  
  // First things first: get the required includes, classes, etc.
  include_once __DIR__ . '/includes/application_top.php';

//b2b
  global $customer_id;
  $customer_id=intval($_GET['customer_id']);
  if(!$customer_id){$customer_id=intval($_POST['customer_id']);}

//b2b

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  include(DIR_WS_CLASSES . 'order.php');

// Next up: define the functions unique to this file 
 // Function    : tep_get_country_id
  // Arguments   : country_name        country name string
  // Return      : country_id
  // Description : Function to retrieve the country_id based on the country's name
  function tep_get_country_id($country_name) {
    $country_id_query = tep_db_query("select * from " . TABLE_COUNTRIES . " where countries_name = '" . $country_name . "'");
    if (!tep_db_num_rows($country_id_query)) {
      return 0;
    }
    else {
      $country_id_row = tep_db_fetch_array($country_id_query);
      return $country_id_row['countries_id'];
    }
  }

   // Function    : tep_get_zone_id
  // Arguments   : country_id        country id string    zone_name        state/province name
  // Return      : zone_id
  // Description : Function to retrieve the zone_id based on the zone's name
  function tep_get_zone_id($country_id, $zone_name) {
    $zone_id_query = tep_db_query("select * from " . TABLE_ZONES . " where zone_country_id = '" . $country_id . "' and zone_name = '" . $zone_name . "'");
    if (!tep_db_num_rows($zone_id_query)) {
      return 0;
    }
    else {
      $zone_id_row = tep_db_fetch_array($zone_id_query);
      return $zone_id_row['zone_id'];
    }
  }
  
  // Function    : tep_field_exists
  // Arguments   : table    table name  field      field name
  // Return      : true/false
  // Description : Function to check the existence of a database field
  function tep_field_exists($table,$field) {
    $describe_query = tep_db_query("describe $table");
    while($d_row = tep_db_fetch_array($describe_query))
    {
      if ($d_row["Field"] == "$field")
      return true;
    }
    return false;
  }

  // Function    : tep_html_quotes
  // Arguments   : string    any string
  // Return      : string with single quotes converted to html equivalent
  // Description : Function to change quotes to HTML equivalents for form inputs.
  function tep_html_quotes($string) {
    return str_replace("'", "&#39;", $string);
  }

  // Function    : tep_html_unquote
  // Arguments   : string    any string
  // Return      : string with html equivalent converted back to single quotes
  // Description : Function to change HTML equivalents back to quotes
  function tep_html_unquote($string) {
    return str_replace("&#39;", "'", $string);
  }
  //
  
   // Then we get down to the nitty gritty
   
  $orders_statuses = array();
  $orders_status_array = array();
  $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_statuses[] = array('id' => $orders_status['orders_status_id'],
                               'text' => $orders_status['orders_status_name']);
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : 'edit');

  // Update Inventory Quantity
  if (tep_not_null($action)) {
    switch ($action) {
        
    // 1. UPDATE ORDER ###############################################################################################
    case 'update_order':
        
        $oID = tep_db_prepare_input($_GET['oID']);
        $order = new order($oID);
        $status = tep_db_prepare_input($_POST['status']);
        
        // 1.1 UPDATE ORDER INFO #####
        
        $UpdateOrders = "UPDATE " . TABLE_ORDERS . " set 
            customers_name = '" . tep_db_input(stripslashes($_POST['update_customer_name'])) . "',
            customers_company = '" . tep_db_input(stripslashes($_POST['update_customer_company'])) . "',
            customers_street_address = '" . tep_db_input(stripslashes($_POST['update_customer_street_address'])) . "',
            customers_suburb = '" . tep_db_input(stripslashes($_POST['update_customer_suburb'])) . "',
            customers_city = '" . tep_db_input(stripslashes($_POST['update_customer_city'])) . "',
            customers_state = '" . tep_db_input(stripslashes($_POST['update_customer_state'])) . "',
            customers_postcode = '" . tep_db_input($_POST['update_customer_postcode']) . "',
            customers_country = '" . tep_db_input(stripslashes($_POST['update_customer_country'])) . "',
            customers_telephone = '" . tep_db_input($_POST['update_customer_telephone']) . "',
            customers_fax = '" . tep_db_input($update_customer_fax) . "',
            customers_email_address = '" . tep_db_input($_POST['update_customer_email_address']) . "',";
        
        $UpdateOrders .= "billing_name = '" . tep_db_input(stripslashes($_POST['update_billing_name'])) . "',
            billing_company = '" . tep_db_input(stripslashes($_POST['update_billing_company'])) . "',
            billing_street_address = '" . tep_db_input(stripslashes($_POST['update_billing_street_address'])) . "',
            billing_suburb = '" . tep_db_input(stripslashes($_POST['update_billing_suburb'])) . "',
            billing_city = '" . tep_db_input(stripslashes($_POST['update_billing_city'])) . "',
            billing_state = '" . tep_db_input(stripslashes($_POST['update_billing_state'])) . "',
            billing_postcode = '" . tep_db_input($_POST['update_billing_postcode']) . "',
            billing_country = '" . tep_db_input(stripslashes($_POST['update_billing_country'])) . "',";
        
        $UpdateOrders .= "delivery_name = '" . tep_db_input(stripslashes($_POST['update_delivery_name'])) . "',
            delivery_company = '" . tep_db_input(stripslashes($_POST['update_delivery_company'])) . "',
            delivery_street_address = '" . tep_db_input(stripslashes($_POST['update_delivery_street_address'])) . "',
            delivery_suburb = '" . tep_db_input(stripslashes($_POST['update_delivery_suburb'])) . "',
            delivery_city = '" . tep_db_input(stripslashes($_POST['update_delivery_city'])) . "',
            delivery_state = '" . tep_db_input(stripslashes($_POST['update_delivery_state'])) . "',
            delivery_postcode = '" . tep_db_input($_POST['update_delivery_postcode']) . "',
            delivery_country = '" . tep_db_input(stripslashes($_POST['update_delivery_country'])) . "',
            payment_method = '" . tep_db_input($_POST['update_info_payment_method']) . "',
            cc_type = '" . tep_db_input($_POST['update_info_cc_type']) . "',
            cc_owner = '" . tep_db_input($_POST['update_info_cc_owner']) . "',
            cc_number = '" . tep_db_input($_POST['update_info_cc_number']) . "',
            cc_expires = '" . tep_db_input($_POST['update_info_cc_expires']) . "',
            orders_status = '" . tep_db_input($_POST['status']) . "'";
        
        $UpdateOrders .= " where orders_id = '" . tep_db_input($_GET['oID']) . "';";

        tep_db_query($UpdateOrders);
        $order_updated = true;

    $check_status_query = tep_db_query("select customers_name, customers_email_address, orders_status, date_purchased from " . TABLE_ORDERS . " where orders_id = '" . (int)$oID . "'");
    $check_status = tep_db_fetch_array($check_status_query);
    
        // 1.2 UPDATE STATUS HISTORY & SEND EMAIL TO CUSTOMER IF NECESSARY #####
        
         if ( ($check_status['orders_status'] != $status) || tep_not_null($_POST['comments'])) {
        
            // Notify Customer
      $customer_notified = '0';
            if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
              $notify_comments = '';
              if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
                $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $_POST['comments']) . "\n\n";
              }
              $email = STORE_NAME . '<br>' . EMAIL_SEPARATOR . '<br>' . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . '<br>' . EMAIL_TEXT_INVOICE_URL . ' ' . tep_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $oID, 'SSL') . '<br>' . EMAIL_TEXT_DATE_ORDERED . ' ' . tep_date_long($check_status['date_purchased']) . '<br><br>' . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]) . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE2);
              tep_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
              $customer_notified = '1';
            }              
                  
            tep_db_query("insert into " . TABLE_ORDERS_STATUS_HISTORY . " 
                (orders_id, orders_status_id, date_added, customer_notified, comments) 
                values ('" . tep_db_input($_GET['oID']) . "', '" . tep_db_input($_POST['status']) . "', now(), " . tep_db_input($customer_notified) . ", '" . tep_db_input($_POST['comments'])  . "')");
            }

        // 1.3 UPDATE PRODUCTS #####
        
        $RunningSubTotal = 0;
        $RunningTax = 0;

    // Do pre-check for subtotal field existence (CWS)
        $ot_subtotal_found = false;
        $ot_total_found = false;
        if (is_array ($_POST['update_totals'])) {
    foreach($_POST['update_totals'] as $total_details) {
          extract($total_details,EXTR_PREFIX_ALL,"ot");
            if($ot_class == "ot_subtotal") {
              $ot_subtotal_found = true;
        break;
            }
            
            if($ot_class == "ot_total"){
            $ot_total_found = true;
            break;
            }
        }//end foreach() 
        }//end if (is_array())
                
        // 1.3.1 Update orders_products Table
        if (is_array ($_POST['update_products'])){
        foreach($_POST['update_products'] as $orders_products_id => $products_details)    {
        
            // 1.3.1.1 Update Inventory Quantity
            $order_query = tep_db_query("SELECT products_id, products_quantity 
            FROM " . TABLE_ORDERS_PRODUCTS . " 
            WHERE orders_id = '" . (int)$oID . "'
            AND orders_products_id = '$orders_products_id'");
            $order = tep_db_fetch_array($order_query);
            
            // First we do a stock check 
            if ($products_details["qty"] != $order['products_quantity']){
            $quantity_difference = ($products_details["qty"] - $order['products_quantity']);
                if (STOCK_CHECK == 'true'){
                    tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET 
                    products_quantity = products_quantity - " . $quantity_difference . ",
                    products_ordered = products_ordered + " . $quantity_difference . " 
                    WHERE products_id = '" . (int)$order['products_id'] . "'");
                    } else {
                    tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
                    products_ordered = products_ordered + " . $quantity_difference . "
                    WHERE products_id = '" . (int)$order['products_id'] . "'");
                }
            }
               
             //Then we check if the product should be deleted  
             if (isset($products_details['delete'])){
             //update quantities first
             if (STOCK_CHECK == 'true'){
                    tep_db_query("UPDATE " . TABLE_PRODUCTS . " SET 
                    products_quantity = products_quantity + " . $products_details["qty"] . ",
                    products_ordered = products_ordered - " . $products_details["qty"] . " 
                    WHERE products_id = '" . (int)$order['products_id'] . "'");
                    } else {
                    tep_db_query ("UPDATE " . TABLE_PRODUCTS . " SET
                    products_ordered = products_ordered - " . $products_details["qty"] . "
                    WHERE products_id = '" . (int)$order['products_id'] . "'");
                    }
                    
            //then delete the little bugger
            $Query = "DELETE FROM " . TABLE_ORDERS_PRODUCTS . " 
            WHERE orders_id = '" . (int)$oID . "' 
            AND orders_products_id = '$orders_products_id';";
                tep_db_query($Query);
                            
                // and all its attributes
                if(isset($products_details[attributes]))
                {
                $Query = "DELETE from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " 
                WHERE orders_id = '" . (int)$oID . "' 
                AND orders_products_id = '$orders_products_id';";
                tep_db_query($Query);
                }
                
            
            }// end of if (isset($products_details['delete']))
            
               else { // if we don't delete, we update
                $Query = "UPDATE " . TABLE_ORDERS_PRODUCTS . " set
                    products_model = '" . $products_details["model"] . "',
                    products_name = '" . tep_html_quotes($products_details["name"]) . "',
                    final_price = '" . $products_details["final_price"] . "',
                    products_tax = '" . $products_details["tax"] . "',
                    products_quantity = '" . $products_details["qty"] . "'
                    where orders_products_id = '$orders_products_id';";
                tep_db_query($Query);
                            
                   //update subtotal and total during update function
                $RunningSubTotal += $products_details["qty"] * $products_details["final_price"]; 
                $RunningTax += (($products_details['tax']/100) * ($products_details['qty'] * $products_details['final_price']));
                
                // Update Any Attributes
                if(isset($products_details[attributes]))
                {
                    foreach($products_details["attributes"] as $orders_products_attributes_id => $attributes_details)
                    {
                        $Query = "UPDATE " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
                            products_options = '" . $attributes_details["option"] . "',
                            products_options_values = '" . $attributes_details["value"] . "',
                            options_values_price ='" . $attributes_details["price"] . "',
                            price_prefix ='" . $attributes_details["prefix"] . "'
                            where orders_products_attributes_id = '$orders_products_attributes_id';";
                        tep_db_query($Query);
                    }//end of foreach($products_details["attributes"]
                }// end of if(isset($products_details[attributes]))
                }// end of if/else (isset($products_details['delete']))
            
        }//end of foreach
        }//end of if (is_array())
        
        // 1.4 UPDATE SHIPPING, CUSTOM FEES, DISOUNTS, TAXES, AND TOTALS #####
        
    /* 1.4.0.1 Shipping Tax
          Optional Tax Rate/Percent (two methods)
            If you want to add tax to shipping, activate either method */
            
    //Method 1: calculated based on store configuration
    //If you have more than one tax class, change the '1' in (1, $CountryID, $ZoneID) if necessary    
            //$CountryID = tep_get_country_id($_POST['update_customer_country']);
            //$ZoneID = tep_get_zone_id($CountryID, ($_POST['update_customer_state']));
            //$AddShippingTax = tep_get_tax_rate(1, $CountryID, $ZoneID);
            
    //or
    //Method 2: enter the appropriate tax rate manually
            //comment out the next line if you use Method 1
            $AddShippingTax = "0.00"; // e.g. shipping tax of 17.5% is "17.5"
            
            if (is_array ($_POST['update_totals'])){
            foreach($_POST['update_totals'] as $total_index => $total_details)
            {
                extract($total_details,EXTR_PREFIX_ALL,"ot");
                if(($ot_class == "ot_shipping") && ($RunningTax != 0))
                {
                    $RunningTax += (($AddShippingTax / 100) * $ot_value);
                }
              }
            }
        
        $RunningTotal = 0;
        $sort_order = 0;
            
            // 1.4.1 Do pre-check for Tax field existence
            $ot_tax_found = 0;
            if (is_array ($_POST['update_totals'])){
            foreach($_POST['update_totals'] as $total_details)    {
                extract($total_details,EXTR_PREFIX_ALL,"ot");
                if($ot_class == "ot_tax")
                {
                    $ot_tax_found = 1;
                    break;
                }
                
    ///////////////////////This section is, for reasons I cannot yet comprehend, necessary for section 1.4.1.1, below, to work properly.  Without it the text value is written to the db as '0'
        if ($ot_class == "ot_total" || $ot_class == "ot_tax" || $ot_class == "ot_subtotal" || $ot_class == "ot_shipping" || $ot_class == "ot_custom" || $ot_class == "ot_loworderfee") {
        $order = new order($oID);
        $RunningTax += 0 * $products_details['tax'] / $order->info['currency_value'] / 100 ; 
         }
    ///////////////////End bizarro code section
            
            }//end foreach
            }//end if (is_array
                
                        
            // 1.4.1.1  If ot_tax doesn't exist, but $RunningTax has been calculated, create an appropriate entry in the db and add tax to the total
            if (($RunningTax != 0) && ($ot_tax_found != 1)) {
            $Query = "INSERT INTO " . TABLE_ORDERS_TOTAL . " set
                            orders_id = '" . $oID . "',
                            title ='" . ENTRY_TAX . "',
                            text = '" . $currencies->format($RunningTax, true, $order->info['currency'], $order->info['currency_value']) . "',
                            value = '" . $RunningTax . "',
                            class = 'ot_tax',
                            sort_order = '2'";
                        tep_db_query($Query);
                        $ot_tax_found = 1;
                        $RunningTotal += $RunningTax;
                        }
                        
                ////////////////////OPTIONAL- create entries for subtotal and/or total if none exists
                /*            
            //1.4.1.2
            /////////////////////////Add in subtotal to db if it doesn't already exist
            if (($RunningSubTotal >0) && ($ot_subtotal_found != true)) {
                $Query = 'INSERT INTO ' . TABLE_ORDERS_TOTAL . ' SET
                            orders_id = "' . $oID . '",
                            title ="' . ENTRY_SUB_TOTAL . '",
                            text = "' . $currencies->format($RunningSubTotal, true, $order->info['currency'], $order->info['currency_value']) . '",
                            value = "' . $RunningSubTotal . '",
                            class = "ot_subtotal",
                            sort_order = "1"';
                        tep_db_query($Query);
                        $ot_subtotal_found = true;
                        $RunningTotal += $RunningSubTotal;
                        }
                        
                        //1.4.1.3
            /////////////////////////Add in total to db if it doesn't already exist
            if (($RunningTotal >0) && ($ot_total_found != true)) {
                $Query = 'INSERT INTO ' . TABLE_ORDERS_TOTAL . ' SET
                            orders_id = "' . $oID . '",
                            title ="' . ENTRY_TOTAL . '",
                            text = "' . $currencies->format($RunningTotal, true, $order->info['currency'], $order->info['currency_value']) . '",
                            value = "' . $RunningTotal . '",
                            class = "ot_total",
                            sort_order = "4"';
                        tep_db_query($Query);
                        $ot_total_found = true;
                        }
                        */
                        //////////////////////////end optional section
                        
            // 1.4.2. Summing up total
            if (is_array ($_POST['update_totals'])) {
            foreach($_POST['update_totals'] as $total_index => $total_details)    
            
            {
            
              // 1.4.2.1 Prepare Tax Insertion            
            extract($total_details,EXTR_PREFIX_ALL,"ot");
            
            
            
         // 1.4.2.2 Update ot_subtotal, ot_tax, and ot_total classes aka "The Final Countdown"
                if (trim($ot_title) && trim($ot_value)) 
                
                {
                    $sort_order++;
                    if ($ot_class == "ot_subtotal") {
                        $ot_value = $RunningSubTotal;
                    }                        
                    if ($ot_class == "ot_tax") {
                        $ot_value = $RunningTax;
                    }

                    // Check for existence of subtotals (CWS)                      
                    if ($ot_class == "ot_total") {
                    $ot_value = $RunningTotal;
                                           
                          if ( !$ot_subtotal_found ) 
                          { // There was no subtotal on this order, lets add the running subtotal in.
                               $ot_value +=  $RunningSubTotal;
                          }
                     
                     }
                                    
                    // Set $ot_text (display-formatted value)
                    $order = new order($oID);
                    $ot_text = $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']);
                        
                //this little ditty writes the total into the database in with <b> and </b>
                    if ($ot_class == "ot_total") {
                        $ot_text = "<b>" . $ot_text . "</b>";
                    }
                    
                    if($ot_total_id > 0) { // Already in database --> Update
                        $Query = "UPDATE " . TABLE_ORDERS_TOTAL . " set
                            title = '" . $ot_title . "',
                            text = '" . $ot_text . "',
                            value = '" . $ot_value . "',
                            sort_order = '" . $sort_order . "'
                            WHERE orders_total_id = '". $ot_total_id . "'";
                        tep_db_query($Query);
                    } else { // New Insert (does this even work?)
                        $Query = "INSERT INTO " . TABLE_ORDERS_TOTAL . " SET
                            orders_id = '" . $oID . "',
                            title = '" . $ot_title . "',
                            text = '" . $ot_text . "',
                            value = '" . $ot_value . "',
                            class = '" . $ot_class . "',
                            sort_order = '" . $sort_order . "'";
                        tep_db_query($Query);
                    }
                                        
                    if ($ot_class == "ot_shipping" || $ot_class == "ot_custom" || $ot_class == "ot_loworderfee") {
                        // Again, because products are calculated in terms of default currency, we need to align shipping, custom etc. values with default currency
                        $RunningTotal += $ot_value / $order->info['currency_value'];
                    }
                    else
                    {
                        $RunningTotal += $ot_value;
                    }
            
                }
                
    elseif (($ot_total_id > 0) && ($ot_class != "ot_shipping")) { // value = 0 => Delete Total Piece
                
                    $Query = "DELETE from " . TABLE_ORDERS_TOTAL . " 
                    WHERE orders_id = '" . (int)$oID . "' 
                    AND orders_total_id = '$ot_total_id'";
                    tep_db_query($Query);
                }

            }
        
}//end if (is_array())
        
        // 1.5 SUCCESS MESSAGE #####
        
        if ($order_updated)    {
            $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
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
        // eof denuz added accumulated discount


        tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit'));
        
    break;

    // 2. ADD A PRODUCT ###############################################################################################
    case 'add_product':
    
        if($_POST['step'] == 5)
        {
            // 2.1 GET ORDER INFO #####
            
            $oID = tep_db_prepare_input($_GET['oID']);
            $order = new order($oID);

            $AddedOptionsPrice = 0;

            // 2.1.1 Get Product Attribute Info
            if(is_array ($_POST['add_product_options']))
            {
                foreach($_POST['add_product_options'] as $option_id => $option_value_id)
                {
                    $result = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " 
                    pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po 
                    ON po.products_options_id=pa.options_id 
                    LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov 
                    ON pov.products_options_values_id=pa.options_values_id 
                    WHERE products_id=" . $_POST['add_product_products_id'] . " 
                    and options_id=" . $option_id . " 
                    and options_values_id=" . $option_value_id . " 
                    and po.language_id = '" . (int)$languages_id . "' 
                    and pov.language_id = '" . (int)$languages_id . "'");
                    
                    $row = tep_db_fetch_array($result);
                    extract($row, EXTR_PREFIX_ALL, "opt");
                    $AddedOptionsPrice += $opt_options_values_price;
                    $option_value_details[$option_id][$option_value_id] = array ("options_values_price" => $opt_options_values_price);
                    $option_names[$option_id] = $opt_products_options_name;
                    $option_values_names[$option_value_id] = $opt_products_options_values_name;
                }
            }

            // 2.1.2 Get Product Info
            $InfoQuery = "select 
                          p.products_model,
                          p.products_price,
                          pd.products_name,
                          p.products_tax_class_id 
                          from " . TABLE_PRODUCTS . " 
                          p left 
                          join " . TABLE_PRODUCTS_DESCRIPTION . " 
                          pd on pd.products_id=p.products_id 
                          where p.products_id=" . $_POST['add_product_products_id'] . " 
                          and pd.language_id = '" . (int)$languages_id . "'";
            $result = tep_db_query($InfoQuery);

            $row = tep_db_fetch_array($result);
            extract($row, EXTR_PREFIX_ALL, "p");
            
            // 2.1.3  Pull specials price from db if there is an active offer
            $special_price = tep_db_query("select specials_new_products_price 
            from " . TABLE_SPECIALS . " 
            where products_id =". $_POST['add_product_products_id'] . " 
            and status");
            $new_price = tep_db_fetch_array($special_price);
            
            if ($new_price) 
            {
            $p_products_price = $new_price['specials_new_products_price'];
            }
            
            // Following two functions are defined at the top of this file
            $CountryID = tep_get_country_id($order->delivery["country"]);
            $ZoneID = tep_get_zone_id($CountryID, $order->delivery["state"]);
            $ProductsTax = tep_get_tax_rate($p_products_tax_class_id, $CountryID, $ZoneID);
            
// Спец. цена
//Modified 4 VAM           
            if ($new_price = 
tep_get_products_special_price($add_product_products_id)) 
{$p_products_price=$new_price;} else {
            
$p_products_price=b2b_display_price($add_product_products_id,$p_products_price);
            }
//End mod 4 VAM

// Спец. цена - скидка
//Modified 4 VAM           
//            if ($new_price = 
//tep_get_products_special_price($add_product_products_id)) 
//{$p_products_price=$new_price;} 
//
//$p_products_price=b2b_display_price($add_product_products_id,$p_products_price);
//End mod 4 VAM


            // 2.2 UPDATE ORDER ####
            $Query = "INSERT INTO " . TABLE_ORDERS_PRODUCTS . " set
              orders_id = '" . $oID . "',
              products_id = '" . $_POST['add_product_products_id'] . "',
              products_model = '" . $p_products_model . "',
              products_name = '" . tep_html_quotes($p_products_name) . "',
              products_price = '". $p_products_price . "',
              final_price = '" . ($p_products_price + $AddedOptionsPrice) . "',
              products_tax = '" . $ProductsTax . "',
              products_quantity = '" . $_POST['add_product_quantity'] . "'";
              tep_db_query($Query);
              $new_product_id = tep_db_insert_id();
            
            // 2.2.1 Update inventory Quantity
            //This is only done if store is set up to use stock
            if (STOCK_CHECK == 'true'){
            tep_db_query("UPDATE " . TABLE_PRODUCTS . " set
            products_quantity = products_quantity - " . $_POST['add_product_quantity'] . " 
            where products_id = '" . $_POST['add_product_products_id'] . "'");
            }
            
            //2.2.1.1 Update products_ordered info
            tep_db_query ("UPDATE " . TABLE_PRODUCTS . " set
            products_ordered = products_ordered + " . $_POST['add_product_quantity'] . "
            where products_id = '" . $_POST['add_product_products_id'] . "'");
                       
            //2.2.1.2 keep a record of the products attributes
            if (is_array ($_POST['add_product_options'])) {
                foreach($_POST['add_product_options'] as $option_id => $option_value_id) {
                $Query = "INSERT INTO " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " set
                        orders_id = '" . $oID . "',
                        orders_products_id = '" . $new_product_id . "',
                        products_options = '" . $option_names[$option_id] . "',
                        products_options_values = '" . tep_db_input($option_values_names[$option_value_id]) . "',
                        options_values_price = '" . $option_value_details[$option_id][$option_value_id]['options_values_price'] . "',
                        price_prefix = '+'";
                    tep_db_query($Query);
                }
            }
            
            // 2.2.2 Calculate Tax and Sub-Totals
            $order = new order($oID);
            $RunningSubTotal = 0;
            $RunningTax = 0;

            for ($i=0; $i<sizeof($order->products); $i++) {


// This calculatiion of Subtotal and Tax is part of the 'add a product' process
        $RunningSubTotal += ($order->products[$i]['qty'] * $order->products[$i]['final_price']);
        $RunningTax += (($order->products[$i]['tax'] / 100) * ($order->products[$i]['qty'] * $order->products[$i]['final_price']));            
            }
            
            // 2.2.2.1 Tax
            $Query = 'UPDATE ' . TABLE_ORDERS_TOTAL . ' set
                text = "' . $currencies->format($RunningTax, true, $order->info['currency'], $order->info['currency_value']) . '",
                value = "' . $RunningTax . '"
                WHERE class= "ot_tax" AND orders_id= "' . $oID . '"';
            tep_db_query($Query);
            
            // 2.2.2.2 Sub-Total
            $Query = 'UPDATE ' . TABLE_ORDERS_TOTAL . ' set
                text = "' . $currencies->format($RunningSubTotal, true, $order->info['currency'], $order->info['currency_value']) . '",
                value = "' . $RunningSubTotal . '"
                WHERE class="ot_subtotal" AND orders_id= "' . $oID . '"';
            tep_db_query($Query);
            
            // 2.2.2.3 Total
            $Query = 'SELECT sum(value) AS total_value from ' . TABLE_ORDERS_TOTAL . '
            WHERE class != "ot_total" AND orders_id= "' . $oID . '"';
            $result = tep_db_query($Query);
            $row = tep_db_fetch_array($result);
            $Total = $row['total_value'];

            $Query = 'UPDATE ' . TABLE_ORDERS_TOTAL . ' set
                text = "' . $currencies->format($Total, true, $order->info['currency'], $order->info['currency_value']) . '",
                value = "' . $Total . '"
                WHERE class="ot_total" and orders_id= "' . $oID . '"';
            tep_db_query($Query);

            // 2.3 REDIRECTION #####
//            tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit'));
            tep_redirect(tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=edit&customer_id='.$customer_id));

        }
    
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
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body>
<style type="text/css">
.Subtitle {
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 11px;
  font-weight: bold;
  color: #FF6600;
}
</style>
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
            <td class="pageHeading"><?php echo HEADING_TITLE . '&nbsp;(' . HEADING_TITLE_NUMBER . '&nbsp;' . $oID . '&nbsp;' . HEADING_TITLE_DATE  . '&nbsp;' . tep_datetime_short($order->info['date_purchased']) . ')'; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
             <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('oID', 'action')) . 'oID=' . $_GET['oID'] . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_INVOICE, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_invoice.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS_PACKINGSLIP, 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . tep_image_button('button_packingslip.gif', IMAGE_ORDERS_PACKINGSLIP) . '</a> <a href="' . tep_href_link(FILENAME_ORDERS, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> '; ?></td>
          </tr>
        <tr>
              <td class="main" colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
          </tr>
          <tr>
            <td class="main" colspan="3"><?php echo HEADING_SUBTITLE; ?></td>
          </tr>
          <tr>
              <td class="main" colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>

<!-- Begin Addresses Block -->

      <tr><?php echo tep_draw_form('edit_order', FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action')) . 'action=update_order'); ?>
      </tr>
      <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>   

    <!-- Begin Update Block -->
<!-- Improvement: more "Update" buttons (Michel Haase, 2005-02-18) - here after FORM und before MENUE_TITLE_CUSTOMER -->
    
      <tr>
          <td>
          <table width="100%" border="0" cellpadding="2" cellspacing="1">
            <tr>
              <td class="main" bgcolor="#FAEDDE"><?php echo HINT_PRESS_UPDATE; ?></td>
              <td class="main" bgcolor="#FBE2C8" width="10">&nbsp;</td>
              <td class="main" bgcolor="#FFCC99" width="10">&nbsp;</td>
              <td class="main" bgcolor="#F8B061" width="10">&nbsp;</td>
              <td class="main" bgcolor="#FF9933" width="120" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
              </tr>
          </table>
                </td>
      </tr>
      <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>   
    <!-- End of Update Block -->

      <tr>
        <td class="SubTitle"><?php echo MENUE_TITLE_CUSTOMER; ?></td>
      </tr>
      <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>   

            <tr>
              <td>
      
<table border="0" class="dataTableRow" cellpadding="2" cellspacing="0">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" width="80"></td>
    <td class="dataTableHeadingContent" width="150"><?php echo ENTRY_CUSTOMER_ADDRESS; ?></td>
    <td class="dataTableHeadingContent" width="6">&nbsp;</td>
    <td class="dataTableHeadingContent" width="150"><?php echo ENTRY_SHIPPING_ADDRESS; ?></td>
  </tr>
 <?php
  if (ACCOUNT_COMPANY == 'true') {
?>
 <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_COMPANY; ?>: </b></td>
    <td><span class="main"><input name="update_customer_company" size="25" value="<?php echo tep_html_quotes($order->customer['company']); ?>" /></span></td>
        <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_company" size="25" value="<?php echo tep_html_quotes($order->delivery['company']); ?>" /></span></td>
  </tr>
  <?php
  }
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_NAME; ?>: </b></td>
    <td><span class="main"><input name="update_customer_name" size="25" value="<?php echo tep_html_quotes($order->customer['name']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_name" size="25" value="<?php echo tep_html_quotes($order->delivery['name']); ?>" /></span></td>
  </tr>
  <tr>
    <td class="main"><b><?php echo ENTRY_ADDRESS; ?>: </b></td>
    <td><span class="main"><input name="update_customer_street_address" size="25" value="<?php echo tep_html_quotes($order->customer['street_address']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_street_address" size="25" value="<?php echo tep_html_quotes($order->delivery['street_address']); ?>" /></span></td>
  </tr>
  <?php
  if (ACCOUNT_SUBURB == 'true') {
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_SUBURB; ?>: </b></td>
    <td><span class="main"><input name="update_customer_suburb" size="25" value="<?php echo tep_html_quotes($order->customer['suburb']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_suburb" size="25" value="<?php echo tep_html_quotes($order->delivery['suburb']); ?>" /></span></td>
  </tr>
  <?php
  }
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_CITY; ?>: </b></td>
    <td><span class="main"><input name="update_customer_city" size="25" value="<?php echo tep_html_quotes($order->customer['city']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_city" size="25" value="<?php echo tep_html_quotes($order->delivery['city']); ?>" /></span></td>
  </tr>
  <?php
  if (ACCOUNT_STATE == 'true') {
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_STATE; ?>: </b></td>
    <td><span class="main"><input name="update_customer_state" size="25" value="<?php echo tep_html_quotes($order->customer['state']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_state" size="25" value="<?php echo tep_html_quotes($order->delivery['state']); ?>" /></span></td>
  </tr>
  <?php
  }
?>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_POSTCODE; ?>: </b></td>
    <td><span class="main"><input name="update_customer_postcode" size="25" value="<?php echo $order->customer['postcode']; ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_postcode" size="25" value="<?php echo $order->delivery['postcode']; ?>" /></span></td>
  </tr>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_COUNTRY; ?>: </b></td>
    <td><span class="main"><input name="update_customer_country" size="25" value="<?php echo tep_html_quotes($order->customer['country']); ?>" /></span></td>
    <td>&nbsp;</td>
    <td><span class="main"><input name="update_delivery_country" size="25" value="<?php echo tep_html_quotes($order->delivery['country']); ?>" /></span></td>
  </tr>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_PHONE; ?>: </b></td>
    <td><span class="main"><input name="update_customer_telephone" size="25" value="<?php echo $order->customer['telephone']; ?>" /></span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
   </tr>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_FAX; ?>: </b></td>
    <td><span class="main"><input name="update_customer_fax" size="25" value="<?php echo $order->customer['fax']; ?>" /></span></td>
     <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td class="main"><b><?php echo ENTRY_CUSTOMER_EMAIL; ?>: </b></td>
    <td><span class="main"><input name="update_customer_email_address" size="25" value="<?php echo $order->customer['email_address']; ?>" /></span></td>
     <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
</table>

                </td>
            </tr>
<!-- End Addresses Block -->

      <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>      

<!-- Begin Payment Block -->
      <tr>
          <td class="SubTitle"><?php echo MENUE_TITLE_PAYMENT; ?></td>
            </tr>
      <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>   
      <tr>
          <td>
                
<table border="0" cellspacing="0" cellpadding="2" class="dataTableRow">
  <tr class="dataTableHeadingRow">
    <td colspan="2" class="dataTableHeadingContent"><?php echo ENTRY_PAYMENT_METHOD; ?></td>
    </tr>
  <tr>
      <td colspan="2" class="main"><input name="update_info_payment_method" size="35" value="<?php echo $order->info['payment_method']; ?>" /></td>
    </tr>

    <!-- Begin Credit Card Info Block -->
      <?php if ($order->info['cc_type'] || $order->info['cc_owner'] || $order->info['cc_number'] || $order->info['payment_method'] == "Credit Card" || $order->info['payment_method'] == "Kreditkarte") { ?>
      <tr>
        <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo ENTRY_CREDIT_CARD_TYPE; ?></td>
        <td class="main"><input name="update_info_cc_type" size="10" value="<?php echo $order->info['cc_type']; ?>" /></td>
      </tr>
      <tr>
        <td class="main"><?php echo ENTRY_CREDIT_CARD_OWNER; ?></td>
        <td class="main"><input name="update_info_cc_owner" size="20" value="<?php echo $order->info['cc_owner']; ?>" /></td>
      </tr>
      <tr>
        <td class="main"><?php echo ENTRY_CREDIT_CARD_NUMBER; ?></td>
        <td class="main"><input name="update_info_cc_number" size="20" value="<?php echo $order->info['cc_number']; ?>" /></td>
      </tr>
      <tr>
        <td class="main"><?php echo ENTRY_CREDIT_CARD_EXPIRES; ?></td>
        <td class="main"><input name="update_info_cc_expires" size="4" value="<?php echo $order->info['cc_expires']; ?>" maxlength="4" /></td>
      </tr>
    <?php } ?>
  <!-- End Credit Card Info Block -->
    
</table>
   </td>
      </tr>
       
<!-- End Payment Block -->
    
      <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>

<!-- Begin Products Listing Block -->
      <tr>
          <td class="SubTitle"><?php echo MENUE_TITLE_ORDER; ?></td>
            </tr>
      <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>   
      <tr>
          <td>
                  
    <?php
    // Override order.php Class's Field Limitations
        $index = 0;
        $order->products = array();
        $orders_products_query = tep_db_query("select * from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$oID . "'");
        while ($orders_products = tep_db_fetch_array($orders_products_query)) {
        $order->products[$index] = array('qty' => $orders_products['products_quantity'],
                                     'name' => tep_html_quotes($orders_products['products_name']),
                                     'model' => $orders_products['products_model'],
                                     'tax' => $orders_products['products_tax'],
                                     'price' => $orders_products['products_price'],
                                     'final_price' => $orders_products['final_price'],
                                     'orders_products_id' => $orders_products['orders_products_id']);

        $subindex = 0;
        $attributes_query_string = "select * from " . TABLE_ORDERS_PRODUCTS_ATTRIBUTES . " where orders_id = '" . (int)$oID . "' and orders_products_id = '" . (int)$orders_products['orders_products_id'] . "'";
        $attributes_query = tep_db_query($attributes_query_string);

        if (tep_db_num_rows($attributes_query)) {
        while ($attributes = tep_db_fetch_array($attributes_query)) {
          $order->products[$index]['attributes'][$subindex] = array('option' => $attributes['products_options'],
                                                                    'value' => $attributes['products_options_values'],
                                                                    'prefix' => $attributes['price_prefix'],
                                                                    'price' => $attributes['options_values_price'],
                                                                    'orders_products_attributes_id' => $attributes['orders_products_attributes_id']);
          $subindex++;
          }
        }
        $index++;
    }
    
?>

<table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr class="dataTableHeadingRow">
      <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_DELETE; ?></td>
      <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_QUANTITY; ?></td>
      <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_PRODUCTS; ?></td>
      <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_PRODUCTS_MODEL; ?></td>
      <td class="dataTableHeadingContent"><?php  echo TABLE_HEADING_TAX; ?></td>
      <td class="dataTableHeadingContent" align="right"><?php  echo TABLE_HEADING_UNIT_PRICE; ?></td>
      <td class="dataTableHeadingContent" align="right"><?php  echo TABLE_HEADING_UNIT_PRICE_TAXED; ?></td>
      <td class="dataTableHeadingContent" align="right"><?php  echo TABLE_HEADING_TOTAL_PRICE; ?></td>
      <td class="dataTableHeadingContent" align="right"><?php  echo TABLE_HEADING_TOTAL_PRICE_TAXED; ?></td>
    </tr>
    <?php

    for ($i=0; $i<sizeof($order->products); $i++) {
    $orders_products_id = $order->products[$i]['orders_products_id'];
    $delete_products = $order->products[$i]['orders_products_id'];
        $RowStyle = "dataTableContent";
        echo '      <tr class="dataTableRow">' . "\n" .
             '        <td class="' . $RowStyle . '" valign="top"><div align="center">' . "<input name='update_products[$orders_products_id][delete]' type='checkbox' /></div></td>\n" . 
             '        <td class="' . $RowStyle . '" align="right" valign="top"><div align="center">' . "<input name='update_products[$orders_products_id][qty]' size='2' value='" . $order->products[$i]['qty'] . "'></div></td>\n" . 
              '        <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][name]' size='25' value='" . $order->products[$i]['name'] . "'>";
        
        // Has Attributes? 
        if (sizeof($order->products[$i]['attributes']) > 0) {
            for ($j=0; $j<sizeof($order->products[$i]['attributes']); $j++) {
                $orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
                echo '<br /><nobr><small>&nbsp;<i> - ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][option]' size='6' value='" . $order->products[$i]['attributes'][$j]['option'] . "'>" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][value]' size='10' value='" . $order->products[$i]['attributes'][$j]['value'] . "'>" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][prefix]' size='1' value='" . $order->products[$i]['attributes'][$j]['prefix'] . "'>" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][price]' size='6' value='" . $order->products[$i]['attributes'][$j]['price'] . "'>";
                echo '</i></small></nobr>';
            }
        }
        
        echo '        </td>' . "\n" .
             '        <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][model]' size='12' value='" . $order->products[$i]['model'] . "'>" . '</td>' . "\n" .
             '        <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][tax]' size='6' value='" . tep_display_tax_value($order->products[$i]['tax']) . "'>" . '</td>' . "\n" .

// BOF Order Editor Yeti's bugFix
           '     <td class="' . $RowStyle . '" align="right" valign="top">' . "<input name='update_products[$orders_products_id][final_price]' size='5' value='" . number_format($order->products[$i]['final_price'], 2, '.', '') . "'>" . '</td>' . "\n" .
           '     <td class="' . $RowStyle . '" align="right" valign="top">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .

// EOF Order Editor Yeti's bugFix

             '        <td class="' . $RowStyle . '" align="right" valign="top">' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" . 
             '        <td class="' . $RowStyle . '" align="right" valign="top"><b>' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
             '      </tr>' . "\n" .
             '     <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>' . "\n";
    }

?>
 </table> 
        </td>
      <tr>
          <td>
                  <table width="100%" cellpadding="0" cellspacing="0">
                      <tr>
                          <td valign="top"><?php echo "<span class='smalltext'>" . HINT_DELETE_POSITION . "</span>"; ?></td>
                    <td align="right"><?php echo '<a href="' . $PHP_SELF . '?oID=' . $oID . '&customer_id=' . $customer_id . '&action=add_product&step=1">' . tep_image_button('button_insert.gif', ADDING_TITLE) . '</a>'; ?></td>
                        </tr>
                    </table>
              </td>
      </tr>
      <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
            
    <!-- End Products Listings Block -->

    <!-- Begin Update Block -->
<!-- Improvement: more "Update" buttons (Michel Haase, 2005-02-18) -->
    
      <tr>
          <td>
          <table width="100%" border="0" cellpadding="2" cellspacing="1">
            <tr>
              <td class="main" bgcolor="#FAEDDE"><?php echo HINT_PRESS_UPDATE; ?></td>
              <td class="main" bgcolor="#FBE2C8" width="10">&nbsp;</td>
              <td class="main" bgcolor="#FFCC99" width="10">&nbsp;</td>
              <td class="main" bgcolor="#F8B061" width="10">&nbsp;</td>
              <td class="main" bgcolor="#FF9933" width="120" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
              </tr>
          </table>
                </td>
      </tr>
      <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>   
    <!-- End of Update Block -->

    <!-- Begin Order Total Block -->
      <tr>
          <td class="SubTitle"><?php echo MENUE_TITLE_TOTAL; ?></td>
            </tr>
      <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>   
      <tr>
          <td>

<table border="0" cellspacing="0" cellpadding="2" class="dataTableRow">
    <tr class="dataTableHeadingRow">
      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TOTAL_MODULE; ?></td>
      <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TOTAL_AMOUNT; ?></td>
      <td class="dataTableHeadingContent"width="1"><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
    </tr>
<?php
  // Override order.php Class's Field Limitations
  $totals_query = tep_db_query("select * from " . TABLE_ORDERS_TOTAL . " where orders_id = '" . (int)$oID . "' order by sort_order");
  $order->totals = array();
  while ($totals = tep_db_fetch_array($totals_query)) { 
      $order->totals[] = array('title' => $totals['title'], 'text' => $totals['text'], 'class' => $totals['class'], 'value' => $totals['value'], 'orders_total_id' => $totals['orders_total_id']); 
    }

// START OF MAKING ALL INPUT FIELDS THE SAME LENGTH 
    $max_length = 0;
    $TotalsLengthArray = array();
    for ($i=0; $i<sizeof($order->totals); $i++) {
        $TotalsLengthArray[] = array("Name" => $order->totals[$i]['title']);
    }
    reset($TotalsLengthArray);
    foreach($TotalsLengthArray as $TotalIndex => $TotalDetails) {
        if (strlen($TotalDetails["Name"]) > $max_length) {
            $max_length = strlen($TotalDetails["Name"]);
        }
    }
// END OF MAKING ALL INPUT FIELDS THE SAME LENGTH

    $TotalsArray = array();
        for ($i=0; $i<sizeof($order->totals); $i++) {
        $TotalsArray[] = array("Name" => $order->totals[$i]['title'], "Price" => number_format($order->totals[$i]['value'], 2, '.', ''), "Class" => $order->totals[$i]['class'], "TotalID" => $order->totals[$i]['orders_total_id']);
        $TotalsArray[] = array("Name" => "", "Price" => "", "Class" => "ot_custom", "TotalID" => "0");
    }
    
    array_pop($TotalsArray);
    foreach($TotalsArray as $TotalIndex => $TotalDetails)
    {
        $TotalStyle = "smallText";
        if($TotalDetails["Class"] == "ot_total")
        {
            echo '    <tr>' . "\n" .
                   '        <td align="right" class="' . $TotalStyle . '"><b>' . $TotalDetails["Name"] . '</b></td>' .
                   '        <td align="right" class="' . $TotalStyle . '"><b>' . $currencies->format($TotalDetails["Price"], true, $order->info['currency'], $order->info['currency_value']) . '</b>' . 
                            "<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' size='" . strlen($TotalDetails["Name"]) . "' >" . 
                            "<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" . 
                            "<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" . 
                            "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' . 
                   '        <td align="right" class="' . $TotalStyle . '"><b>' . tep_draw_separator('pixel_trans.gif', '1', '17') . '</b>' . 
                   '    </tr>' . "\n";
        }
        elseif($TotalDetails["Class"] == "ot_subtotal") 
        {
            echo '    <tr>' . "\n" .
                   '        <td align="right" class="' . $TotalStyle . '"><b>' . $TotalDetails["Name"] . '</b></td>' .
                   '        <td align="right" class="' . $TotalStyle . '"><b>' . $currencies->format($TotalDetails["Price"], true, $order->info['currency'], $order->info['currency_value']) . '</b>' . 
                            "<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim($TotalDetails["Name"]) . "' size='" . strlen($TotalDetails["Name"]) . "' >" . 
                            "<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" . 
                            "<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" . 
                            "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' . 
                   '        <td align="right" class="' . $TotalStyle . '"><b>' . tep_draw_separator('pixel_trans.gif', '1', '17') . '</b>' . 
                   '    </tr>' . "\n";
        }
        elseif($TotalDetails["Class"] == "ot_tax")
        {
            echo '    <tr>' . "\n" .
                   '        <td align="right" class="' . $TotalStyle . '"><b>' . trim($TotalDetails["Name"]) . "</b><input name='update_totals[$TotalIndex][title]' type='hidden' size='" . $max_length . "' value='" . trim($TotalDetails["Name"]) . "'>" . '</td>' . "\n" .
                   '        <td align="right" class="' . $TotalStyle . '"><b>' . $currencies->format($TotalDetails["Price"], true, $order->info['currency'], $order->info['currency_value']) . '</b>' . 
                            "<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" . 
                            "<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" . 
                            "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' . 
                   '        <td align="right" class="' . $TotalStyle . '"><b>' . tep_draw_separator('pixel_trans.gif', '1', '17') . '</b>' . 
                   '    </tr>' . "\n";
        }
            else
        {
            echo '    <tr>' . "\n" .
                   '        <td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][title]' size='" . $max_length . "' value='" . tep_html_quotes($TotalDetails["Name"]) . "'>" . '</td>' . "\n" .
                   '        <td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][value]' size='6' value='" . $TotalDetails["Price"] . "'>" . 
                            "<input type='hidden' name='update_totals[$TotalIndex][class]' value='" . $TotalDetails["Class"] . "'>" . 
                            "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . 
                   '        <td align="right" class="' . $TotalStyle . '"><b>' . tep_draw_separator('pixel_trans.gif', '1', '17') . '</b>' . 
                     '   </td>' . "\n" .
                   '    </tr>' . "\n";
        }
    }
    
        ?>
</table>

          </td>
      <tr>
              <td class="smalltext"><?php echo HINT_TOTALS; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
    <!-- End Order Total Block -->
    
    <!-- Begin Status Block -->
      <tr>
          <td class="SubTitle"><?php echo MENUE_TITLE_STATUS; ?></td>
            </tr>
      <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr> 
      <tr>
        <td class="main">
                  
<table border="0" cellspacing="0" cellpadding="2" class="dataTableRow">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
    <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></td>
    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
    <td class="dataTableHeadingContent" align="left"><?php echo HEADING_TITLE_STATUS; ?></td>
   <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_COMMENTS; ?></td>
   </tr>
<?php
$orders_history_query = tep_db_query("select * from " . TABLE_ORDERS_STATUS_HISTORY . " where orders_id = '" . tep_db_input($oID) . "' order by date_added");
if (tep_db_num_rows($orders_history_query)) {
  while ($orders_history = tep_db_fetch_array($orders_history_query)) {
    echo '  <tr>' . "\n" .
         '    <td class="smallText" align="center">' . tep_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
         '    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>' . "\n" .
         '    <td class="smallText" align="center">';
    if ($orders_history['customer_notified'] == '1') {
      echo tep_image(DIR_WS_ICONS . 'tick.gif', ICON_TICK) . "</td>\n";
    } else {
      echo tep_image(DIR_WS_ICONS . 'cross.gif', ICON_CROSS) . "</td>\n";
    }
    echo '    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>' . "\n" .
         '    <td class="smallText" align="left">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n";
   echo '    <td class="dataTableHeadingContent" align="left" width="10">&nbsp;</td>' . "\n" .
           '    <td class="smallText" align="left">' . nl2br(tep_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n";
  echo '  </tr>' . "\n";
  }
} else {
  echo '  <tr>' . "\n" .
       '    <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
       '  </tr>' . "\n";
}
?>
</table>

              </td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>
      <tr>
              <td>    
                        
<table border="0" cellspacing="0" cellpadding="2" class="dataTableRow">
  <tr class="dataTableHeadingRow">
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_STATUS; ?></td>
    <td class="main" width="10">&nbsp;</td>
    <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_COMMENTS; ?></td>
  </tr>
    <tr>
      <td>
          <table border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td class="main"><b><?php echo ENTRY_STATUS; ?></b></td>
          <td class="main" align="right"><?php echo tep_draw_pull_down_menu('status', $orders_statuses, $order->info['orders_status']); ?></td>
        </tr>
        <tr>
          <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b></td>
          <td class="main" align="right"><?php echo tep_draw_checkbox_field('notify', '', false); ?></td>
        </tr>
        <tr>
          <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b></td>
          <td class="main" align="right"><?php echo tep_draw_checkbox_field('notify_comments', '', false); ?></td>
        </tr>
     </table>
      </td>
    <td class="main" width="10">&nbsp;</td>
    <td class="main">
    <?php
         echo tep_draw_textarea_field('comments', 'soft', '40', '5');
          ?>
    </td>
  </tr>
</table>

              </td>
            </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
    <!-- End of Status Block -->
    
    <!-- Begin Update Block -->
    
      <tr>
          <td class="SubTitle"><?php echo MENUE_TITLE_UPDATE; ?></td>
            </tr>
      <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '1'); ?></td>
      </tr>   
      <tr>
          <td>
          <table width="100%" border="0" cellpadding="2" cellspacing="1">
            <tr>
              <td class="main" bgcolor="#FAEDDE"><?php echo HINT_PRESS_UPDATE; ?></td>
              <td class="main" bgcolor="#FBE2C8" width="10">&nbsp;</td>
              <td class="main" bgcolor="#FFCC99" width="10">&nbsp;</td>
              <td class="main" bgcolor="#F8B061" width="10">&nbsp;</td>
              <td class="main" bgcolor="#FF9933" width="120" align="center"><?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
              </tr>
          </table>
                </td>
      </tr>
    <!-- End of Update Block -->
    
      </form>
            
<?php
}
if($action == "add_product")
{
?>
      <tr>
        <td width="100%">
                  <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo ADDING_TITLE; ?> (<?php echo HEADING_TITLE_NUMBER; ?> <?php echo $oID; ?>)</td>
              <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
              <td class="pageHeading" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ORDERS_EDIT, tep_get_all_get_params(array('action'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
            </tr>
          </table>
                </td>
      </tr>

<?php
    // ############################################################################
    //   Get List of All Products
    // ############################################################################

        $result = tep_db_query("SELECT products_name, p.products_id, categories_name, ptc.categories_id FROM " . TABLE_PRODUCTS . " p LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON pd.products_id=p.products_id LEFT JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc ON ptc.products_id=p.products_id LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON cd.categories_id=ptc.categories_id where pd.language_id = '" . (int)$languages_id . "' ORDER BY categories_name");
        while($row = tep_db_fetch_array($result))
        {
            extract($row,EXTR_PREFIX_ALL,"db");
            $ProductList[$db_categories_id][$db_products_id] = $db_products_name;
            $CategoryList[$db_categories_id] = $db_categories_name;
            $LastCategory = $db_categories_name;
        }
        
    // ############################################################################
    //   Add Products Steps
    // ############################################################################
    echo '<tr><td><table border="0">' . "\n";
        
        // Set Defaults
            if(!isset($_POST['add_product_categories_id']))
            $add_product_categories_id = 0;

            if(!isset($_POST['add_product_products_id']))
            $add_product_products_id = 0;
            
            // Step 1: Choose Category
            echo '<tr class="dataTableRow"><form action=' . $_SERVER['PHP_SELF'] .'?oID=' . $_GET['oID'] . '&action=' . $_GET['action'] . ' method="POST">' . "\n";

        //b2b
         print tep_draw_hidden_field('customer_id',$customer_id);

         //b2b
            echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 1:</b></td>' .  "\n";
            echo '<td class="dataTableContent" valign="top">';
            if (isset($_POST['add_product_categories_id'])) {
            $current_category_id = $_POST['add_product_categories_id'];
            }
            echo ' ' . tep_draw_pull_down_menu('add_product_categories_id', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
            echo '<input type="hidden" name="step" value="2">' . "\n";
            echo '</td>' . "\n";
            echo '<td class="dataTableContent">' . ADDPRODUCT_TEXT_STEP1 . '</td>' . "\n";
            echo '</form></tr>' . "\n";
            echo '<tr><td colspan="3">&nbsp;</td></tr>' . "\n";
           
        // Step 2: Choose Product
           if(($_POST['step'] > 1) && ($_POST['add_product_categories_id'] > 0))
           {
           echo '<tr class="dataTableRow"><form action=' . $_SERVER['PHP_SELF'] .'?oID=' . $_GET['oID'] . '&action=' . $_GET['action'] . ' method="POST">' . "\n";

        //b2b
         print tep_draw_hidden_field('customer_id',$customer_id);

         //b2b
           echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 2: </b></td>' . "\n";
           echo '<td class="dataTableContent" valign="top"><select name="add_product_products_id" onChange="this.form.submit();">';
           $ProductOptions = "<option value='0'>" . ADDPRODUCT_TEXT_SELECT_PRODUCT . "\n";
           asort($ProductList[$_POST['add_product_categories_id']]);
           foreach($ProductList[$_POST['add_product_categories_id']] as $ProductID => $ProductName)
           {
              $ProductOptions .= "<option value='$ProductID'> $ProductName\n";
           }
           if(isset($_POST['add_product_products_id'])){
         $ProductOptions = str_replace("value='" . $_POST['add_product_products_id'] . "'", "value='" . $_POST['add_product_products_id'] . "' selected=\"selected\"", $ProductOptions);
           }
           echo ' ' . $ProductOptions .  ' ';
           echo '</select></td>' . "\n";
           echo '<input type="hidden" name="add_product_categories_id" value=' . $_POST['add_product_categories_id'] . '>';
           echo '<input type="hidden" name="step" value="3">' . "\n";
           echo '<td class="dataTableContent">' . ADDPRODUCT_TEXT_STEP2 . '</td>' . "\n";
           echo '</form></tr>' . "\n";
           echo '<tr><td colspan="3">&nbsp;</td></tr>' . "\n";
           }

        // Step 3: Choose Options
        if(($_POST['step'] > 2) && ($_POST['add_product_products_id'] > 0))
        
        {
            // Get Options for Products
            $result = tep_db_query("SELECT * FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa LEFT JOIN " . TABLE_PRODUCTS_OPTIONS . " po ON po.products_options_id=pa.options_id LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov ON pov.products_options_values_id=pa.options_values_id WHERE products_id=" . $_POST['add_product_products_id'] . " and po.language_id = '" . (int)$languages_id . "'");
            
            // Skip to Step 4 if no Options
            if(tep_db_num_rows($result) == 0)
            {
                echo '<tr class="dataTableRow">' . "\n";
                echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 3: </b></td>' . "\n";
                echo '<td class="dataTableContent" valign="top" colspan="2"><i>' . ADDPRODUCT_TEXT_OPTIONS_NOTEXIST . '</i></td>' . "\n";
                echo '</tr>' . "\n";
                $_POST['step'] = 4;
            }
            else
            {
                while($row = tep_db_fetch_array($result))
                {
                    extract($row,EXTR_PREFIX_ALL,"db");
                    $Options[$db_products_options_id] = $db_products_options_name;
                    $ProductOptionValues[$db_products_options_id][$db_products_options_values_id] = $db_products_options_values_name;
                }
            
                echo '<tr class="dataTableRow"><form action=' . $_SERVER['PHP_SELF'] .'?oID=' . $_GET['oID'] . '&action=' . $_GET['action'] . ' method="POST">' . "\n";

//b2b
                print tep_draw_hidden_field('customer_id',$customer_id);

//b2b
                echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 3: </b></td><td class="dataTableContent" valign="top">';
                foreach($ProductOptionValues as $OptionID => $OptionValues)
                {
                    $OptionOption = "<b>" . $Options[$OptionID] . "</b> - <select name='add_product_options[$OptionID]'>";
                    foreach($OptionValues as $OptionValueID => $OptionValueName)
                    {
                    $OptionOption .= "<option value='$OptionValueID'> $OptionValueName\n";
                    }
                    $OptionOption .= "</select><br />\n";
                    
                    if(isset($_POST['add_product_options'])){
                     $OptionOption = str_replace("value='" . $_POST['add_product_options'][$OptionID] . "'", "value='" . $_POST['add_product_options'][$OptionID] . "' selected=\"selected\"", $OptionOption);
                    }
                    echo '' .  $OptionOption . '';
                }        
                echo '</td>';
                echo '<td class="dataTableContent" align="center"><input type="submit" value="' . ADDPRODUCT_TEXT_OPTIONS_CONFIRM . '">';
                echo '<input type="hidden" name="add_product_categories_id" value=' . $_POST['add_product_categories_id']. '>';
                echo '<input type="hidden" name="add_product_products_id" value=' . $_POST['add_product_products_id'] . '>';
                echo '<input type="hidden" name="step" value="4">';
                echo '</td>' . "\n";
                echo '</form></tr>' . "\n";
            }

            echo '<tr><td colspan="3">&nbsp;</td></tr>' . "\n";
        }

        // Step 4: Confirm
        if($_POST['step'] > 3)
        
        {
               echo '<tr class="dataTableRow"><form action=' . $_SERVER['PHP_SELF'] .'?oID=' . $_GET['oID'] . '&action=' . $_GET['action'] . ' method="POST">' . "\n";

    //b2b
         print tep_draw_hidden_field('customer_id',$customer_id);

      //b2b
            echo '<td class="dataTableContent" align="right"><b>' . ADDPRODUCT_TEXT_STEP . ' 4: </b></td>';
            echo '<td class="dataTableContent" valign="top"><input name="add_product_quantity" size="2" value="1"> ' . ADDPRODUCT_TEXT_CONFIRM_QUANTITY . '</td>';
            echo '<td class="dataTableContent" align="center"><input type="submit" value="' . ADDPRODUCT_TEXT_CONFIRM_ADDNOW . '">';

            if(is_array ($_POST['add_product_options']))
            {
                foreach($_POST['add_product_options'] as $option_id => $option_value_id)
                {
                    echo '<input type="hidden" name="add_product_options[' . $option_id . ']" value="' . $option_value_id . '">';
                }
            }
            echo '<input type="hidden" name="add_product_categories_id" value=' . $_POST['add_product_categories_id'] . '>';
            echo '<input type="hidden" name="add_product_products_id" value=' . $_POST['add_product_products_id'] . '>';
            echo '<input type="hidden" name="step" value="5">';
            echo '</td>' . "\n";
            echo '</form></tr>' . "\n";
        }
        
        echo '</table></td></tr>' . "\n";
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
<br />
</body>
</html>
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>