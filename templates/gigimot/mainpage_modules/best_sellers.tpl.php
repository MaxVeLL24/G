<?php

$best_sellers_query_raw = "select p.products_id, p.products_images, pd.products_name, p.products_model, p.products_quantity, p.products_quantity_order_min, p.lable_1, p.lable_2, p.lable_3, p.products_image, p.products_price, p.products_tax_class_id, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on (p.manufacturers_id = m.manufacturers_id), " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '" . (int) $languages_id . "' order by p.products_ordered DESC, pd.products_name";
$best_sellers_split = new splitPageResults($best_sellers_query_raw, 5);

if($best_sellers_split->number_of_rows > 0)
{

    $best_sellers_query = tep_db_query($best_sellers_split->sql_query);
    $tpl_settings = array(
        'request' => $best_sellers_query,
        'id' => 'best_sellers',
        'classes' => array('front_section'),
        'cols' => 5,
        'title' => BOX_HEADING_BESTSELLERS,
    );
    ?><div class="mpm-best-sellers"><?php include DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/listingTemplate.tpl.php'; ?></div><?php
}