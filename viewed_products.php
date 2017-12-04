<?php

/**
 * Страница, отображающая сообщение о том, что страница не найдена
 */

/* @var $breadcrumb \breadcrumb */

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . DIRECTORY_SEPARATOR . FILENAME_VIEWED_PRODUCTS;

$products = array();
if(!empty($_SESSION['viewed_products']))
{
    $products_ids = implode(', ', $_SESSION['viewed_products']);
    $listing_sql = "select p.products_id, p.products_tax_class_id, p.products_quantity, p.products_quantity_order_min, p.products_price, p.products_images, p.lable_3, p.lable_2, p.lable_1, pd.products_name from products as p inner join products_description as pd on pd.products_id = p.products_id and pd.language_id = {$_SESSION['languages_id']} where p.products_id in ({$products_ids}) and p.products_status = 1 and ( p.products_date_available is null or p.products_date_available = '0000-00-00 00:00:00' or p.products_date_available > now() ) order by p.products_sort_order asc, pd.products_name asc";
    $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');
}

$page_title = VIEWED_PRODUCTS_PAGE_TITLE;
if(!empty($listing_split) && $listing_split->current_page_number > 1) {
    $page_title .= sprintf(TITLE_ADD_PAGE_NUMBER, $listing_split->current_page_number);
}
$page_robots_tag = 'noindex, follow';
unset($page_meta_description, $page_meta_keywords, $page_link_canonical, $page_link_prev, $page_link_next);
$breadcrumb->add(VIEWED_PRODUCTS_BREADCRUMB_TITLE);

$content = CONTENT_VIEWED_PRODUCTS;

require DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE;
require DIR_WS_INCLUDES . 'application_bottom.php';