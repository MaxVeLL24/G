<?php

/**
 * Список желаний
 */

/* @var $wishList \wishlist */

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . DIRECTORY_SEPARATOR . FILENAME_WISHLIST;

$is_ajax = \EShopmakers\Http\Request::isAjax();
$action = isset($_GET['action']) ? $_GET['action'] : null;
$products_id = isset($_GET['products_id']) ? $_GET['products_id'] : null;
$return_to = isset($_GET['return_to']) ? $_GET['return_to'] : null;

// Если выбрано действие
if($action)
{
    // Добавить
    if($action === 'add')
    {
        $uprid_parsed = tep_parse_uprid($products_id);
        $status = false;
        if($uprid_parsed)
        {
            $wishList->add_wishlist($uprid_parsed['products_id'], $uprid_parsed['attributes']);
            $status = true;
        }
    }
    // Удалить
    elseif($action === 'remove')
    {
        $status = false;
        if($products_id)
        {
            $wishList->remove($products_id);
            $status = true;
        }
    }
    
    // Ответ или редирект
    if($is_ajax)
    {
        \EShopmakers\Http\Response::sendJSON(array(
            'status' => $status
        ));
    }
    elseif($return_to)
    {
        tep_redirect(tep_href_link($return_to));
    }
    else
    {
        tep_redirect(tep_href_link(FILENAME_WISHLIST));
    }
}

// Выгрузка товаров
$products_ids = array();
$wishlist_id_to_products_id = array();
$wishlist_id_to_products_attributes = array();
foreach(array_keys($wishList->wishID) as $wishlist_id) {
    $uprid_part = explode('{', $wishlist_id);
    $products_ids[] = $uprid_part[0];
}
$products_ids = array_unique($products_ids);

// Группа покупателя, скидка покупателя, скидка группы покупателя, цена покупателя
$customer = array(
    'customers_id' => 0,
    'customers_discount' => GUEST_DISCOUNT,
    'customers_groups_id' => 0,
    'customers_groups_discount' => 0,
    'customers_groups_price' => 1
);
if(!empty($_SESSION['customer_id']))
{
    $query_string = <<<SQL
SELECT
    c.customers_id,
    c.customers_discount,
    c.customers_groups_id,
    COALESCE(cg.customers_groups_discount, 0) AS customers_groups_discount,
    COALESCE(cg.customers_groups_price, 1) AS customers_groups_price
FROM customers AS c
LEFT OUTER JOIN customers_groups AS cg
ON c.customers_groups_id = cg.customers_groups_id
WHERE c.customers_id = {$_SESSION['customer_id']}
LIMIT 1
SQL;
    $query = tep_db_query($query_string);
    if(tep_db_num_rows($query))
    {
        $customer = tep_db_fetch_array($query);
    }
}

$customer_id_in = array(0);
if($customer['customers_id'])
{
    $customer_id_in[] = $customer['customers_id'];
}
$customer_id_in = implode(', ', $customer_id_in);
$customer_group_id_in = array(0);
if($customer['customers_groups_id'])
{
    $customer_group_id_in[] = $customer['customers_groups_id'];
}
$customer_group_id_in = implode(', ', $customer_group_id_in);

// Установлена ли для группы этого покупателя специальная цена?
$group_price = '';
if($customer['customers_groups_price'] > 1)
{
    $group_price = '_' . $customer['customers_groups_price'] . ' AS products_price';
}

// Запрос для выгрузки товаров
if($products_ids) {
    $products_ids = implode(', ', $products_ids);
    $query_string = <<<SQL
SELECT
    p.products_id,
    p.products_tax_class_id,
    p.products_quantity,
    p.products_quantity_order_min,
    p.products_price{$group_price},
    p.products_images,
    p.lable_3,
    p.lable_2,
    p.lable_1,
    pd.products_name

FROM products AS p
INNER JOIN products_description AS pd
ON
    pd.products_id = p.products_id AND
    pd.language_id = {$_SESSION['languages_id']}
WHERE
    p.products_id IN ({$products_ids}) AND
    p.products_status = 1 AND
    (
        p.products_date_available IS NULL OR
        p.products_date_available = '0000-00-00 00:00:00' OR
        p.products_date_available > NOW()
    )
ORDER BY
    p.products_sort_order ASC,
    pd.products_name ASC
LIMIT 20
SQL;
    $listing_query = tep_db_query($query_string);
}

// Подгрузить шаблон
$page_title = WISHLIST_PAGE_TITLE . (defined('HEAD_TITLE_TAG_ALL') && HEAD_TITLE_TAG_ALL ? ' - ' . HEAD_TITLE_TAG_ALL : '');
$page_robots_tag = 'noindex, follow';
unset($page_meta_description, $page_meta_keywords, $page_link_canonical, $page_link_prev, $page_link_next);
$breadcrumb->add(WISHLIST_BREADCRUMB_TITLE);

$content = CONTENT_WISHLIST;

require DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE;
require DIR_WS_INCLUDES . 'application_bottom.php';