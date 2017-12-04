<?php

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . DIRECTORY_SEPARATOR . FILENAME_INVOICE_HTML;

// Прямое обращение к этому файлу?
$is_standalone = rtrim($_SERVER['DOCUMENT_ROOT'], '\\/') . $_SERVER['PHP_SELF'] === __FILE__;

if($is_standalone)
{
    // ID товара из запроса
    $orders_id = isset($_GET['orders_id']) ? filter_var($_GET['orders_id'], FILTER_VALIDATE_INT) : null;
    if(!$orders_id)
    {
        require FILENAME_NOT_FOUND;
    }
}
elseif(empty($orders_id))
{
    return '';
}

// Выгрузить информацию о заказе
$query = tep_db_query("SELECT o.orders_id, o.customers_id, o.customers_name, ot.value, o.currency, o.currency_value FROM orders AS o INNER JOIN orders_total AS ot ON o.orders_id = ot.orders_id AND ot.class = 'ot_total' WHERE o.orders_id = {$orders_id} LIMIT 1");
if(tep_db_num_rows($query))
{
    $order = tep_db_fetch_array($query);
}
elseif($is_standalone)
{
    require FILENAME_NOT_FOUND;
}
else
{
    return '';
}

// Если заказ не принадлежит клиенту
if($order['customers_id'] != $_SESSION['customer_id'])
{
    if($is_standalone)
    {
        require FILENAME_FORBIDDEN;
    }
    else
    {
        return '';
    }
}

if(!$is_standalone)
{
    ob_start();
}
require DIR_WS_CONTENT . CONTENT_INVOICE_HTML . '.tpl.php';
if(!$is_standalone)
{
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

// Загрузить
if($is_standalone && array_key_exists('download', $_GET))
{
    header('Content-Disposition: attachement; filename==?UTF-8?B?' . base64_encode(INVOICE_HTML_SAVE_FILENAME) . '?=');
}

\EShopmakers\Http\Response::noIndexNoFollow();
require DIR_WS_INCLUDES . 'application_bottom.php';