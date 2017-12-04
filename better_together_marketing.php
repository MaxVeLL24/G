<?php 
  // Better Together Discount Marketing
// error_reporting(E_ALL);
  $value = "ot_better_together.php";
  require_once(DIR_WS_LANGUAGES . $language .  '/modules/order_total/'. $value);
  require_once(DIR_WS_MODULES . "order_total/" . $value);
  $discount = new ot_better_together();
  $bt_strings = array();
  
 //  // проверяет все, которые установлены для этого товара как скидки
$xsell_query = tep_db_query('select x.xsell_id, x.discount from ' . TABLE_PRODUCTS . ' p, ' . TABLE_PRODUCTS_XSELL . ' x where p.products_status = 1 and x.products_id = p.products_id and x.discount!="" and x.products_id = "'.$_GET['products_id'].'"');

while ($xsell = tep_db_fetch_array($xsell_query)) {$xsell_array1[$xsell['xsell_id']] = $xsell; }

 // проверяет все, для которых этот товар установлен как скидка
 $xsell_query = tep_db_query('select x.products_id as xsell_id, x.discount from ' . TABLE_PRODUCTS . ' p, ' . TABLE_PRODUCTS_XSELL . ' x where p.products_status = 1 and x.products_id = p.products_id and x.discount!="" and x.xsell_id = "'.$_GET['products_id'].'"');
//debug('select x.products_id as xsell_id, x.discount from ' . TABLE_PRODUCTS . ' p, ' . TABLE_PRODUCTS_XSELL . ' x where p.products_status = 1 and x.products_id = p.products_id and x.discount!="" and x.xsell_id = "'.$_GET['products_id'].'"');
  while ($xsell = tep_db_fetch_array($xsell_query)) { 
    $xsell_array2[$xsell['xsell_id']] = $xsell; 
  }
  if($xsell_array2!='')$xsell_array = array_merge_recursive_unique($xsell_array1,$xsell_array2);
 else $xsell_array = $xsell_array1;
  // debug($xsell_array1);
  // debug($xsell_array2);
  //$xsell_array = array_merge_recursive_unique($xsell_array1,$xsell_array2);
   
if(isset($xsell_array)) {
  foreach($xsell_array as $xsell) {
    if(preg_match('/%/',$xsell['discount'])) {
      $procent = '%';
      $xsell['discount'] = preg_replace('/%/','',$xsell['discount']);
    } else $procent = '$';

    $discount->add_prod_to_prod($_GET['products_id'], $xsell['xsell_id'], $procent, $xsell['discount']); 
  }

  if ($discount->check() > 0) { 
 
     $resp = $discount->get_discount_info($_GET['products_id']);
     $resp = array_unique($resp);
     
//     $rresp = $discount->get_reverse_discount_info($_GET['products_id']);
     
     if ( (count($resp) > 0) || (count($rresp) > 0) ) {
        for ($i=0, $n=count($resp); $i<$n; $i++) {
              $bt_strings[] = $resp[$i];
        }
        
    //  -------------Купите Кольцо 1, получите этот товар -20%  -------------------
    //    for ($i=0, $n=count($rresp); $i<$n; $i++) {
    //          $bt_strings[] = $rresp[$i];
    //    }
     }
  }
}  
  
?>