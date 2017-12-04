<?php

if ($_GET['products_id']) { 
$xsell_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name, p.products_tax_class_id, products_price from " . TABLE_PRODUCTS_XSELL . " xp, " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where xp.products_id = '" . $_GET['products_id'] . "' and xp.xsell_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_status = '1' order by xp.sort_order asc limit " . MAX_DISPLAY_ALSO_PURCHASED); 
$num_products_xsell = tep_db_num_rows($xsell_query); 
if ($num_products_xsell >= MIN_DISPLAY_XSELL) { 
?> 
<?php 
    $section_query = $xsell_query ;
    $section_template_id = 'featured';
    $section_template_title = TEXT_XSELL_PRODUCTS;
    $render_class = 'node-product';
    $_compare = true;
    $_wishlist = true;
    include DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/listingTemplate.tpl.php';

?>




<?php
    }
  }
?>
