<?php

if(FEATURED_MODULE_ENABLED !== 'true')
{
    return;
}

$featured_products_category_id = $featured_products_category_id;
$cat_name_query = tep_db_query("select categories_name from categories_description where categories_id = '" . $featured_products_category_id . "' limit 1");
$cat_name_fetch = tep_db_fetch_array($cat_name_query);
$cat_name = $cat_name_fetch['categories_name'];

if((!isset($featured_products_category_id)) || ($featured_products_category_id == '0'))
{
    $featured_products_query = tep_db_query("select pd.products_name, pd.products_description, p.products_images,p.mankovka_stock, p.lable_3, p.lable_2, p.lable_1, p.products_quantity, p.products_quantity_order_min, p.products_id, p.products_tax_class_id, s.status as specstat, s.specials_new_products_price, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id = p.products_id where p.products_status = '1' and pd.language_id = '" . (int) $languages_id . "' and f.status = '1' order by p.products_quantity > 0 desc, (p.products_quantity <= 0 AND p.mankovka_stock > 0) desc,rand() DESC limit 10");
}
else
{
    $featured_products_query = tep_db_query("select distinct pd.products_name, p.products_images, p.lable_3, p.lable_2, p.lable_1, p.products_quantity, p.products_quantity_order_min, p.products_id, p.products_tax_class_id, s.status as specstat, s.specials_new_products_price, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . $featured_products_category_id . "' and p.products_status = '1' and f.status = '1' and pd.language_id = '" . (int) $languages_id . "' order by p.products_quantity > 0 desc, (p.products_quantity <= 0 AND p.mankovka_stock > 0) desc,rand() DESC limit 10");
}
if(tep_db_num_rows($featured_products_query))
{
    $tpl_settings = array(
        'request' => $featured_products_query,
        'id' => 'featured',
        'classes' => array('product_slider'),
        'cols' => 5,
        'title' => BOX_HEADING_FEATURED,
    );
?>
<div class="mpm-featured mpm-bg-white-style">
    <div class="header-and-arrows clearfix">
        <div class="header"><?php echo BOX_HEADING_FEATURED; ?></div>
        <div class="arrows">
            <a rel="nofollow" href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=0&featured=yes'); ?>"><?php echo MPM_FEATURED_ALL; ?></a>
        </div>
    </div>
    <?php include DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/listingTemplate.tpl.php'; ?>
</div>
<?php
}