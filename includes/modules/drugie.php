<?php 
    $drugie_query = tep_db_query("select 
        pd.products_name, 
        p.products_images, 
        p.lable_3, 
        p.lable_2, 
        p.lable_1,
        p.products_quantity, 
        p.products_id, 
        p.products_tax_class_id, 
        s.status as specstat, 
        s.specials_new_products_price, 
        p.products_price 
    from " . TABLE_PRODUCTS . " p 
        left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id 
        left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on pd.products_id = p.products_id 
        left join ". TABLE_PRODUCTS_TO_CATEGORIES . " p2c on p2c.products_id = p.products_id , " . TABLE_CATEGORIES . " c  
    where p.products_status = '1' 
        and pd.language_id = '" . (int)$languages_id . "'
         and p.products_id != '".$_GET['products_id']."' 
         and p2c.categories_id = c.categories_id
         and c.categories_id = '" . (int)$current_category_id . "'
        order by rand() DESC limit 30");
    if(tep_db_num_rows($drugie_query)>0){
      $tpl_settings = array(
        'request'=>$drugie_query,
        'id'=>'drugie',
        'cols'=>5,
        'classes'=>array('node-product','product_slider'),
        'title'=>DRUGIE_HEAD_TITLE,
      );
       
      include DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/listingTemplate.tpl.php';
      
    }
?>