<?php
/*
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
  
  Featured Products V1.1
  Displays a list of featured products, selected from admin
  For use as an Infobox instead of the "New Products" Infobox  
*/



?>
<!-- featured_products //-->
<?php
 if(FEATURED_PRODUCTS_DISPLAY == true)
 {
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left', 'text' => 'Мы рекомендуем');
    $featured_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, s.status as specstat, s.specials_new_products_price, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id left join " . TABLE_FEATURED . " f on p.products_id = f.products_id where p.products_status = '1' and f.status = '1' order by rand() DESC limit 5");

  $row = 0;
  $col = 0; 
  $num = 0;
  while ($featured_products = tep_db_fetch_array($featured_products_query)) {
    $num ++; if ($num == 1) { new infoBoxHeading($info_box_contents, false, false, tep_href_link(FILENAME_FEATURED_PRODUCTS));}
    $featured_products['products_name'] = tep_get_products_name($featured_products['products_id']);
	if ($featured_price = tep_get_products_special_price($featured_products['products_id'])) {
     $featured_products['products_price'] = $featured_price; // Обычная цена
     $featured_products['specials_featured_products_price'] = tep_xppp_getproductprice($featured_products['products_id']); // Спец. цена
	  $info_box_contents[$row][$col] = array('align' => 'center',
                                       'params' => 'class="smallText" width="33%" valign="top"',
                                       'text' => '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name'] . '</a><br><s>' . $currencies->display_price_nodiscount($featured_products['specials_featured_products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])) . '</s><br><span class="productSpecialPrice">' . 
                                           $currencies->display_price_nodiscount($featured_products['products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])));
    } else {
     $featured_products['products_price'] = $featured_price; // Обычная цена
     $featured_products['specials_featured_products_price'] = tep_xppp_getproductprice($featured_products['products_id']); // Спец. цена
	  $info_box_contents[$row][$col] = array('params' => 'style="width:19.7%;padding:10px 0;"',
                                       'text' => '<a class="nobg" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $featured_products['products_image'], $featured_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products['products_id']) . '">' . $featured_products['products_name'] . '</a><br>' . $currencies->display_price($featured_products['specials_featured_products_price'], tep_get_tax_rate($featured_products['products_tax_class_id'])));
    }
    $col ++;
    if ($col > 5) {
      $col = 0;
      $row ++;
    }
  }
  if($num) {
      
      new contentBox($info_box_contents);
  }
 } else // If it's disabled, then include the original New Products box
 {
   include (DIR_WS_MODULES . FILENAME_featured_products);
 }
?>
<!-- featured_products_smend //-->
