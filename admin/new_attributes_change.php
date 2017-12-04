<?
// I found the easiest way to do this is just delete the current attributes & start over =)

// ########################## added for download
MYSQL_QUERY( "DELETE products_attributes_download FROM products_attributes,products_attributes_download 
WHERE 
products_attributes.products_id = '$current_product_id' AND
products_attributes_download.products_attributes_id=products_attributes.products_attributes_id" );
// ########################## end added for download

MYSQL_QUERY( "DELETE FROM products_attributes WHERE products_id = '$current_product_id'" );

// Simple, yet effective.. loop through the selected Option Values.. find the proper price & prefix.. insert.. yadda yadda yadda.
for ($i = 0; $i < sizeof($optionValues); $i++) {

    $query = "SELECT * FROM products_options_values_to_products_options where products_options_values_id = '$optionValues[$i]'";

    $result = mysql_query($query) or die(mysql_error());

    $matches = mysql_num_rows($result);

       while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                                               	
            $optionsID = $line['products_options_id'];
            
       }


    $value_price =  $_POST[$optionValues[$i] . '_price'];
    $value_prefix = $_POST[$optionValues[$i] . '_prefix'];
    $value_weight =  		$_POST[$optionValues[$i] . '_weight'];
    $value_prefix_weight = 	$_POST[$optionValues[$i] . '_prefix_weight'];
    $value_sort = 		$_POST[$optionValues[$i] . '_sort'];

// ########################## added for download
    $value_filename = $_POST[$optionValues[$i] . '_dlfile'];
    if($value_filename!='')
    {
      $value_maxdays = $_POST[$optionValues[$i] . '_dldays'];
      $value_maxcount = $_POST[$optionValues[$i] . '_dlcount'];
    }


    MYSQL_QUERY( "INSERT INTO products_attributes ( products_id, options_id, options_values_id, options_values_price, price_prefix, products_attributes_weight, products_attributes_weight_prefix, products_options_sort_order ) VALUES( '$current_product_id', '$optionsID', '$optionValues[$i]', '$value_price', '$value_prefix', '$value_weight', '$value_prefix_weight', '$value_sort' )" ) or die(mysql_error());

// ########################## added for download
        $products_attributes_id = tep_db_insert_id();
        if($value_filename !='')
        {
	        MYSQL_QUERY( "INSERT INTO products_attributes_download ( products_attributes_id, products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount)
                      VALUES( '$products_attributes_id', '$value_filename', '$value_maxdays', '$value_maxcount')" ) or die(mysql_error());
        }
// ########################## end added for download
    }             



?>
