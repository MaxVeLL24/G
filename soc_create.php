<?php
	if($email!='')
	{
		$first_name = iconv('windows-1251', 'UTF-8', $first_name); 
	}
	
	$text= '';

	$text .= '<div style="padding:15px;">';
	$text .= '<div class="left"><img style="border-radius:4px;" src="'.$photo.'" /></div>';
	$text .= '<div class="left" style="width:300px;padding-left:10px;text-align:left;">'. SOC_CREATE1 .', <b>'.$first_name.'</b>!
			<br />';

	if($city!='')
	$text .= '<br />'. SOC_CREATE2 .': <b>'.$city.'</b>';

	if($email!='')
	{
		$text .= '<br />'. SOC_CREATE3 .': <b>'.$email.'</b><br />';
		$text .= SOC_CREATE4 .': <b>'.$password.'</b><br />';
	}
	else
	{
		$text .= '<div style="margin-top: 5px;">'. SOC_CREATE5 .' <b>e-mail:</b></div>';
		$text .= '<div class="left" style="margin-top: 5px;"><input id="proc_email" class="green_input" type="text" name="proc_email" /></div><div class="clear"></div>';
		$text .= '<div class="left"><a href=javascript:if(document.getElementById(\'proc_email\').value!=\'\'){checkLoginvk("'.$id_social.'","'.$first_name.'","'.$last_name.'","'.$photo.'",document.getElementById(\'proc_email\').value,"'.$city.'","");} class="btn btn-default">'. SOC_CREATE6 .'</a>
				  </div></div>
				  <div class="clear"></div>';
	}
	
	echo $text;


   if($email!='')
   {
	$first_name = str_replace('_', ' ', $first_name);
    
	$sql_data_array = array('customers_firstname' => $first_name,
                              'customers_lastname' => $last_name,
                              'customers_email_address' => $email,
                              'customers_telephone' => '',
                              'customers_groups_id' => '7', // группа для соц. сетей с 5% скидкой
                              'customers_fax' => $id_social,
                              'customers_password' => tep_encrypt_password($password));

      tep_db_perform(TABLE_CUSTOMERS, $sql_data_array);

      $customer_id = tep_db_insert_id();

      $sql_data_array = array('customers_id' => $customer_id,
                              'entry_firstname' => $first_name,
                              'entry_lastname' => $last_name,
                              'entry_street_address' => '',
                              'entry_postcode' => '',
                              'entry_city' => $city,
                              'entry_country_id' => $country);

      tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

      $address_id = tep_db_insert_id();

      tep_db_query("update " . TABLE_CUSTOMERS . " set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");
      tep_db_query("insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . tep_db_input($customer_id) . "', '0', now())");

      if (SESSION_RECREATE == 'True') {
        tep_session_recreate();
      }

      $customer_first_name = $first_name;
      $customer_default_address_id = $address_id;
      $customer_country_id = $country;
      $customer_zone_id = $zone_id;
      tep_session_register('customer_id');
      tep_session_register('customer_first_name');
      tep_session_register('customer_default_address_id');
      tep_session_register('customer_country_id');
      tep_session_register('customer_zone_id');

// restore cart contents
      $cart->restore_contents();

// restore wishlist to sesssion
//      $wishList->restore_wishlist();

// build the message content
      $name = $first_name . ' ' . $last_name;

      $email_text = sprintf(EMAIL_GREET_NONE, $first_name);
      $email_text .= EMAIL_WELCOME . EMAIL_TEXT;
      $email_text .= '<br /><b>'. SOC_CREATE3 .':</b> '.$email.'<br />';
      $email_text .= '<b>'. SOC_CREATE4 .':</b> '.$password.'<br /><br />';
      $email_text .= EMAIL_CONTACT . EMAIL_WARNING;
      tep_mail($name, $email, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

$text= '';
$text .= '<br /><b style="color:#37AE22">'. SOC_CREATE7 .'</b>';
$text .= '</div><div class="clear"></div>';
$text .= '</div>';

// $text = iconv('UTF-8', 'windows-1251', $text);
echo $text;

    } else {

    }

?>