<ul>
  <?php
    $query = "select c.categories_id, cd.categories_name
              from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
              where c.categories_id = cd.categories_id 
                and c.categories_status = '1' 
                and c.parent_id = 0
                and cd.language_id='" . $languages_id ."'
              order by sort_order, cd.categories_name";

    $categories_query = tep_db_query($query);

    while ($categories = tep_db_fetch_array($categories_query)) {
    
     $cPath_new = tep_get_path($categories['categories_id']);   
     if($categories['categories_id']==$cPath_array['0']) $current_class='class="active"';
     else $current_class = '';
     
     echo '<li><a '.$current_class.' href="' . tep_href_link(FILENAME_DEFAULT, $cPath_new, 'NONSSL') . '">' . $categories['categories_name'] . '</a></li>';  
    }
  ?>  
</ul>