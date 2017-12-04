<?php

	include_once __DIR__ . '/includes/application_top.php';

	$error = false;


// Если вы хотите включить проверку что данные отправлены именно с сайта RUpay уберите все комментарии ниже

//	$url = parse_url($_SERVER["HTTP_REFERER"]);

//	if ($url[host] != 'rupay.com' or $url[host] != 'www.rupay.com') {
//		$error = true;
//	} else {

	$key_md = md5($_GET['order_id'] . '::' . MODULE_PAYMENT_RUPAY_SECRET_KEY);

        $check_query = tep_db_query("select rupay_ORDERID from payment_rupay where rupay_STATUS = '" . $key_md . "'");
        $check = tep_db_fetch_array($check_query);

	if ($check[rupay_ORDERID] != '') {

	$sql_data_array = array('orders_status' => MODULE_PAYMENT_RUPAY_ORDER_RESULT_STATUS_ID);

	$now_data = date('Y-m-d H:i:s');

	$sql_data_arrax = array('orders_id' => $check[rupay_ORDERID],
				'orders_status_id' => MODULE_PAYMENT_RUPAY_ORDER_RESULT_STATUS_ID,
				'date_added' => $now_data,
				'customer_notified' => '0',
				'comments' => 'Автоматическое обновление платежной системой RUpay');

	tep_db_perform('orders', $sql_data_array, 'update', "orders_id = '" . $check[rupay_ORDERID] . "'");

	tep_db_perform('orders_status_history', $sql_data_arrax);

	}

//	}


?>
