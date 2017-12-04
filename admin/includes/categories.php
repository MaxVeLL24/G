<!-- show_subcategories //-->

<?php

  $query = "select c.categories_id, cd.categories_name, c.parent_id
            from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
            where c.categories_id = cd.categories_id 
              and c.categories_status = '1' 
              and c.parent_id = 0
              and cd.language_id='" . $languages_id ."'
            order by sort_order, cd.categories_name";

  $categories_query = tep_db_query($query);

  while ($categories = tep_db_fetch_array($categories_query)) {
     $temp_cPath_array = $cPath_array;  //Johan's solution - kill the array but save it for the rest of the site
     unset($cPath_array);
     $cPath_new = tep_get_path($categories['categories_id']);   
     $text_subcategories = '';
  // вывод ПОДкатегорий  
     if($categories['categories_id']==$temp_cPath_array['0']) {
     $sub_query = "select c.categories_id, cd.categories_name, c.parent_id
            from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
            where c.categories_id = cd.categories_id 
              and c.categories_status = '1' 
              and c.parent_id = '".$categories['categories_id']."'
              and cd.language_id='" . $languages_id ."'
            order by sort_order, cd.categories_name";
     $subcategories_query = tep_db_query($sub_query);     
     while ($subcategories = tep_db_fetch_array($subcategories_query)) {
       $cPath_new_sub = "cPath="  . $categories['categories_id'] . "_" . $subcategories['categories_id'];
       if($subcategories['categories_id']==$temp_cPath_array['1']) $r_current_color = 'style="font-weight:bold;"';
       else $r_current_color = ''; 
       $text_subcategories .= '<a '.$r_current_color.' href="' . tep_href_link(FILENAME_TABLEDATA, $cPath_new_sub, 'NONSSL') . '" class="menusubcateg">' . '&nbsp;&nbsp;&nbsp;&bull;&nbsp;'. $subcategories['categories_name'] . '</a>';
       $current_parent = $categories['categories_id'];
  // вывод подПОДкатегорий
     if($subcategories['categories_id']==$temp_cPath_array['1']) {
       $sub_sub_query = "select c.categories_id, cd.categories_name, c.parent_id
            from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
            where c.categories_id = cd.categories_id 
              and c.categories_status = '1' 
              and c.parent_id = '".$temp_cPath_array['1']."'
              and c.parent_id = '".$subcategories['categories_id']."'
              and cd.language_id='" . $languages_id ."'
            order by sort_order, cd.categories_name";
                        $sub2cat_query = tep_db_query($sub_sub_query);
                         while ($sub2cat = tep_db_fetch_array($sub2cat_query)) {
                                $cPath_new_sub2 = "cPath="  . $categories['categories_id'] . "_" .$subcategories['categories_id'] . "_" . $sub2cat['categories_id'];
                                if($sub2cat['categories_id']==$temp_cPath_array['2']) $r_current2_color = 'style="font-weight:bold;"';
                                else $r_current2_color = ''; 
                                $text_subcategories .= '<span class="sub2cat2">&bull;&nbsp;</span>' . '<a '.$r_current2_color.' href="' . tep_href_link(FILENAME_TABLEDATA, $cPath_new_sub2, 'NONSSL') . '" class="sub2cat">' . ''. $sub2cat['categories_name'] . '</a><div class="clear"></div>' . "";
                         }  
     }                                
   // конец - вывод подПОДкатегорий                                                       
     }
    } 
   // конец - вывод ПОДкатегорий    
   echo '<a href="' . tep_href_link(FILENAME_TABLEDATA, $cPath_new, 'NONSSL') . '" class="menucateg">' . $categories['categories_name'] . '</a>' . $text_subcategories.$text_sub2cat;  
     $cPath_array = $temp_cPath_array; //Re-enable the array for the rest of the code

  }
?>          


<!-- show_subcategories_eof //-->