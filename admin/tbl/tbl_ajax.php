<?php
chdir('../../');
include_once __DIR__ . '/includes/application_top.php';

$val=trim($_POST['value']);
$value_post = (isset($val) ? $val : '');
$product_id_post = (isset($_POST['row_id']) ? $_POST['row_id'] : '');
$column_post = (isset($_POST['column']) ? $_POST['column'] : '');

if (tep_not_null($column_post)) {
	switch ($column_post) {
		case 1: //product name
			 if (tep_not_null($product_id_post)) {
				 if (tep_not_null($value_post)) {
					$sql_data_array = array('products_name' => tep_db_prepare_input($value_post));
					if(tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$product_id_post . "' and language_id = '" . (int)$languages_id . "'")) echo $value_post;
				}
			}
		break;
		case 2: //product model
			 if (tep_not_null($product_id_post)) {
				 if (tep_not_null($value_post)) {
					$sql_data_array = array('products_model' => tep_db_prepare_input($value_post),'products_last_modified' => 'now()');
            				if(tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$product_id_post . "'")) echo $value_post;
				}
			}
		break;
		case 3: //product quantity
			 if (tep_not_null($product_id_post)) {
				 if (tep_not_null($value_post)) {
					if(is_numeric((int)$value_post)){
						$sql_data_array = array('products_quantity' => (int)tep_db_prepare_input($value_post),'products_last_modified' => 'now()');
		    				if(tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$product_id_post . "'")) echo $value_post;
					}else echo 'no  int';
				}
			}
		break;
		case 4: //product price
			 if (tep_not_null($product_id_post)) {
				 if (tep_not_null($value_post)) {
					if(is_numeric($value_post)){
						$sql_data_array = array('products_price' => (float)tep_db_prepare_input($value_post),'products_last_modified' => 'now()');
		    				if(tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$product_id_post . "'")) echo $value_post;
					}else echo 'no  float';
				}
			}
		break;
	}
}
?>
