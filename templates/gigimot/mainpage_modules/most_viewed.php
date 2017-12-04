<?php

if(TOP_VIEWERS_MODULE_ENABLED !== 'true')
{
    return;
}

$most_viewed = tep_db_query("SELECT
		p.products_id,
		p.products_images,
		pd.products_name,
		p.products_model,
		p.mankovka_stock,
        p.products_quantity, 
        p.products_quantity_order_min,
		p.products_price,
		p.products_tax_class_id
	FROM " . TABLE_PRODUCTS . " p, " .
	TABLE_PRODUCTS_DESCRIPTION . " pd
	where
	p.products_status = '1'
	and pd.products_viewed > 0
	and p.products_id = pd.products_id
	and pd.language_id = '" . (int)$languages_id . "'
	order by pd.products_viewed DESC, pd.products_name limit 5");

   $tpl_settings = array(
      'request'=>$most_viewed,
      'id'=>'most_viewed',
      'classes'=>array('front_section'),
      'cols'=>5,
      'title'=>BOX_HEADING_MOSTVIEWED,
    );
?>
<div class="mpm-most-viewed"><?php include DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/listingTemplate.tpl.php'; ?></div>