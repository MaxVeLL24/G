<?php
  // Запись/чтение из сесии
  if($_SESSION['visited_products'] != ''){
    $visited_products_ids = explode('|', $_SESSION['visited_products']);
    if(is_array($visited_products_ids)){
      if(!in_array($product_info['products_id'], $visited_products_ids) and count($visited_products_ids) < 10){
        $_SESSION['visited_products'] .= '|'.$product_info['products_id'];
      }
    }
  }else{
    $_SESSION['visited_products'] .= $product_info['products_id'];
  }

  if(count($visited_products_ids)){

    $x = 0;
    $sql_conditions = '(';
    foreach ($visited_products_ids as $id_value) {
      if($x == 0){
          $sql_conditions .= 'p.products_id = '. $id_value;
      }else{
          $sql_conditions .= ' or p.products_id = '. $id_value;
      }
      $x++;
    }
    $sql_conditions .= ')';

    $last_viewed_query = tep_db_query('SELECT
          p.products_id,
          p.products_images,
          pd.products_name,
          p.products_model,
          p.products_price,
          p.products_quantity,
          p.products_quantity_order_min,
          p.products_tax_class_id,
          IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, 
          IF(s.status, s.specials_new_products_price, p.products_price) as final_price, 
          p.'.$customer_price.' as products_price      
      FROM '. TABLE_PRODUCTS . ' p 
          left join '. TABLE_SPECIALS . ' s on p.products_id = s.products_id 
          left join '. TABLE_PRODUCTS_DESCRIPTION . ' pd on pd.products_id = p.products_id
      WHERE '.$sql_conditions.' and p.products_status = 1
          and p.products_id = pd.products_id
          and pd.language_id = '. (int)$languages_id.' limit 15');    
    
    if(tep_db_num_rows($last_viewed_query)){
      $tpl_settings = array(
        'request'=>$last_viewed_query,
        'id'=>'last_viewed',
        'classes'=>array('product_slider'),
        'cols'=>4,
        'title'=>BOX_HEADING_LAST_VIEWED,
      );
      include DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/listingTemplate.tpl.php';
    }
  }
?>

