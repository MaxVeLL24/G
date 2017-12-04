<?php
  /*

  WebMakers.com Added: Previous/Next through categories products
  Thanks to Nirvana, Yoja and Joachim de Boer
  Modifications: Linda McGrath osCommerce@WebMakers.com

  /includes/products_next_previous.php

  Syntax:
  <?php include (DIR_WS_INCLUDES . 'products_next_previous.php'); ?>
  Already has its own table and can be included anywhere in product_info.php

  Add to english.php
  // previous next product
  define('PREV_NEXT_PRODUCT', 'Product ');
  define('PREV_NEXT_FROM', ' from ');

  Can now work with cateogies at any depth

  */
?>
<?php
	// calculate the previous and next
    if (isset($_GET['manufacturers_id']) && tep_not_null($_GET['manufacturers_id'])) {
   $products_ids = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p where p.products_status = '1'  and p.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
	$category_name_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
	$category_name_row = tep_db_fetch_array($category_name_query);
	$prev_next_in = PREV_NEXT_MB . ($category_name_row['manufacturers_name']);
	$fPath = 'manufacturers_id=' . (int)$_GET['manufacturers_id'];
    } else {
	if (!$current_category_id) {
		$cPath_query = tep_db_query ("SELECT categories_id FROM " . TABLE_PRODUCTS_TO_CATEGORIES . " WHERE products_id ='" .  (int)$_GET['products_id'] . "'");
		$cPath_row = tep_db_fetch_array($cPath_query);
		$current_category_id = $cPath_row['categories_id'];
	}
	$products_ids = tep_db_query("select p.products_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " ptc where p.products_status = '1'  and p.products_id = ptc.products_id and ptc.categories_id = $current_category_id");
	$category_name_query = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = $current_category_id AND language_id = $languages_id");
	$category_name_row = tep_db_fetch_array($category_name_query);
	$prev_next_in = PREV_NEXT_CAT . ($category_name_row['categories_name']);
	$fPath = 'cPath=' . $cPath;
	}
	while ($product_row = tep_db_fetch_array($products_ids)) {
		$id_array[] = $product_row['products_id'];
	}
	reset ($id_array);
	$counter = 0;
	while (list($key, $value) = each ($id_array)) {
		if ($value == (int)$_GET['products_id']) {
			$position = $counter;
			if ($key == 0)
				$previous = -1; // it was the first to be found
			else
				$previous = $id_array[$key - 1];

			if ($id_array[$key + 1])
				$next_item = $id_array[$key + 1];
			else {
				$next_item = $id_array[0];
			}
		}
		$last = $value;
		$counter++;
	}
	if ($previous == -1)
		$previous = $last;
?>
