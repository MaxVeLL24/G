<?php
    $products_attributes_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
    $products_attributes = tep_db_fetch_array($products_attributes_query);
    if ($products_attributes['total'] > 0) {
// otf 1.71 added width
?>

<div class="prod_attributes_div clearfix">
<?php
// otf 1.71 Update query to pull option_type
      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name, popt.products_options_type, popt.products_options_length, popt.products_options_comment from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id='" . (int)$_GET['products_id'] . "' and pa.options_id !=".$color_id." and pa.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_sort_order, popt.products_options_name");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
            if($products_options_name['products_options_type'] == 1){
              $attr_select = 'attr_select';
            }else{
              $attr_select = 'simple_attribute';
            }
            //для цены javascript
            echo '<div class="'.$attr_select.'">
                  <input class="option_name" type="hidden" name="option_name" value="'.$products_options_name['products_options_id'].'" id="'.$products_options_name['products_options_id'].'">';
            //для цены javascript end

            $selected_attribute = false;
            $products_options_array = array();
            // $products_options_array[] = array('id' => -1, 'text' => 'выберите');
            $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$_GET['products_id'] . "'and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");

            while ($products_options = tep_db_fetch_array($products_options_query)) {
              //print_r('7'.$products_options['products_options_values_id']);
              $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name'],
                'prefix' => $products_options['price_prefix']);
              if ($products_options['options_values_price'] != '0') {
                $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . strip_tags($currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id']))) .') ';
              }
              //для цены javascript
              echo '<input id="id_option_other'.$products_options['products_options_values_id'].'" type="hidden" name="id_option_other" value="'.$products_options['price_prefix'].$products_options['options_values_price'].'">';
              //для цены javascript end
            }

            if (isset($cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']])) {
              $selected_attribute = $cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']];
            } else {
              $selected_attribute = false;
            }
?>          <table class="prod_options" >
            <tr>
              <td align="left" style="width:100px"><?php echo $products_options_name['products_options_name'] . ':'; ?></td>
              <!-- <td align="left" width="100%" id="prod_options2"></td> -->
             <?php if($products_options_array[1]['text']!='') {  // если значений больше чем 1
                                if($products_options_name['products_options_type']=='0') { // Text
                      $optionsNamesStr = '';
                                            foreach($products_options_array as $optionsNames => $value){
                        $optionsNamesStr .= $value['text'].', ';
                      }
                      $optionsNamesStr=substr($optionsNamesStr,0,-2);
                       ?>
              <td align="left" id="prod_options3"><?php echo $optionsNamesStr;  ?></td>
             <?php
                                      } elseif($products_options_name['products_options_type']=='1') {  // Select
                       ?>
             <?php //debug($products_options_array); ?>
              <td id="prod_options" align="left"><?php echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute,'class="select_id_option" id="select_id_'.$products_options_name['products_options_id'].'"',false,true) . $products_options_name['products_options_comment'];  ?></td>
             <?php
                                }
                              } else { ?>
              <td align="left" id="prod_options3"><?php echo $products_options_array[0]['text'];  ?>
                <input name="<?php echo 'id[' . $products_options_name['products_options_id'] . ']' ?>" type="hidden" value="<?php echo $products_options_array[0]['id'];  ?>">
              </td>
            <?php } ?>
            </tr>
            </table>

<?php  echo '</div>';
      }
?>
</div>
<div class="clearfix"></div>
<?php
    } // otf 1.71 end if
?>
