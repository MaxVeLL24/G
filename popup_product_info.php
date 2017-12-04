<?php 
  if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') include_once __DIR__ . '/includes/application_top.php';

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRODUCT_INFO);  

  $product_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
  $product_check = tep_db_fetch_array($product_check_query);
  
  if ($product_check['total'] >= 1) {

    $product_info_query = tep_db_query("select pd.products_name, p.products_quantity, pd.products_info, p.products_images, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "'");
    $product_info = tep_db_fetch_array($product_info_query);

	  $product_info['products_price'] = tep_xppp_getproductprice($product_info['products_id']);

    if ($new_price = tep_get_products_special_price($product_info['products_id'])) {
      $query_special_prices_hide_result = SPECIAL_PRICES_HIDE; 
      // Disable specials price if module SALES is disabled
      if ($query_special_prices_hide_result == 'true' || SALES_MODULE_ENABLED != 'true') {
	 	    $products_price = '<div class="productSpecialPrice">' . $currencies->display_price_nodiscount($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</div>'; 
	    } else {
	      $products_price = '<div class="productSpecialPrice">' . $currencies->display_price_nodiscount($new_price, tep_get_tax_rate($product_info['products_tax_class_id'])) . '</div><s>' . $currencies->display_price_nodiscount($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) . '</s>' ;
	    }
    } else {
      $products_price =  '<div class="productSpecialPrice">'.$currencies->display_price($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])).'</div>' ;
    }
    
    $products_name = '<span>'.$product_info['products_name']. '</span>';
  
    global $kupit_products_name;
    $kupit_products_name = $products_name;

  }
?>

<?php include(DIR_WS_MODULES . 'additional_images_popup.php'); ?>   
