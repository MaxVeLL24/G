<?php

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . DIRECTORY_SEPARATOR . FILENAME_INVOICE;

// ID товара из запроса
$orders_id = isset($_GET['orders_id']) ? filter_var($_GET['orders_id'], FILTER_VALIDATE_INT) : null;
if(!$orders_id)
{
    require FILENAME_NOT_FOUND;
}

// Выгрузить информацию о заказе
$query = tep_db_query("SELECT customers_id FROM orders WHERE orders_id = {$orders_id} LIMIT 1");
if(tep_db_num_rows($query))
{
    $order = tep_db_fetch_array($query);
}
else
{
    require FILENAME_NOT_FOUND;
}

// Если заказ не принадлежит клиенту
if($order['customers_id'] !== $_SESSION['customer_id'])
{
    require FILENAME_FORBIDDEN;
}

$content = CONTENT_INVOICE;
$page_title = sprintf(INVOICE_TITLE, $orders_id);
$breadcrumb->add(INVOICE_BREADCRUMB_TITLE);

require DIR_WS_TEMPLATES . TEMPLATE_NAME . DIRECTORY_SEPARATOR . TEMPLATENAME_MAIN_PAGE;
require DIR_WS_INCLUDES . 'application_bottom.php';