<?php  
	      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name, popt.products_options_type, popt.products_options_length, popt.products_options_comment from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id='" . (int)$_GET['products_id'] . "' and pa.options_id = popt.products_options_id and popt.products_options_id!=24 and popt.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");   
        while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
            $selected_attribute = false;
        		$products_options_array = array();
        		$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$_GET['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");
            $count = tep_db_num_rows($products_options_query);
        		while ($products_options = tep_db_fetch_array($products_options_query)) {
          		$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
          		if ($products_options['options_values_price'] != '0') {
            		$products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
          		}
        		}

          if (isset($cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']])) {
            $selected_attribute = $cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']];
          } else {
            $selected_attribute = false;
          }
        ?>          
                    <div class="left">
                    
                       <div class="left" style="width:150px;padding:3px 0;border-bottom:1px dotted #e0e0e0;"><b><?php echo $products_options_name['products_options_name'] . ''; ?></b></div>
           

                  <?php if($products_options_array[1]['text']!='') { ?>
                       <div class="left" style="width:450px;padding:3px 0;border-bottom:1px dotted #e0e0e0;"><?php 
                      //echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute) . $products_options_name['products_options_comment']; 
                      $i = 0;
                       foreach($products_options_array as $poa_val) {
                          // echo $poa_val['text'].', ';
                          if ($i == $count - 1) {
                              echo $poa_val['text'];
                          }else{
                              echo $poa_val['text'].', ';
                          }
                          $i++;
                       }
                       ?>
                       </div>
                       <div class="clear"></div>
                    </div>  
                  <?php } else { ?>
                       <div class="left" style="width:450px;padding:3px 0;border-bottom:1px dotted #e0e0e0;"><?php echo $products_options_array[0]['text'];  ?></div>
                       <div class="clear"></div>
                    </div>
                   
                    
          <?php
         } 
        }
?>