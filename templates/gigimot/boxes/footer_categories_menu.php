<ul>
  <?php
    $query_footer_cat_menu = "select c.categories_id, cd.categories_name
              from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
              where c.categories_id = cd.categories_id 
                and c.categories_status = '1' 
                and c.parent_id = 0
                and cd.language_id='" . $languages_id ."'
              order by sort_order, cd.categories_name";

    $footer_categories_menu = tep_db_query($query_footer_cat_menu);

    while ($f_cat_menu = tep_db_fetch_array($footer_categories_menu)) {
     if($f_cat_menu['categories_id']==$cPath_array['0']) $current_class='class="active"';
     else $current_class = '';
     
     echo '<li><a '.$current_class.' href="' . tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', \EShopmakers\Data\CategoriesTree::getParentsChain($f_cat_menu['categories_id'])), 'NONSSL') . '">' . $f_cat_menu['categories_name'] . '</a></li>';  
    }
  ?>  
</ul>