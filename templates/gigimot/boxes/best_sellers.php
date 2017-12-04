<?php
/*
  $Id: best_sellers.php,v 1.1.1.1 2003/09/18 19:05:50 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- best_sellers //-->
<?php
  if ($cPath) {
    $best_sellers_query = tep_db_query("select distinct p.products_id, p.products_image, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_status = '1' and c.categories_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and '" . (int)$current_category_id . "' in (c.categories_id, c.parent_id) order by p.products_ordered desc, pd.products_name limit 3");
  } else {
		$best_sellers_query = tep_db_query("select p.products_id, p.products_image, pd.products_info, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.products_status = '1' and c.categories_status = '1' and p.products_ordered > 0 and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id order by p.products_ordered desc, pd.products_name limit 3");
	}

  
?>

<?php
    $info_box_contents = array();
    $info_box_contents[] = array('text'  => 'TOP продаж');
    new infoBoxHeading($info_box_contents, false, false, '');
//    new infoBoxHeading($info_box_contents, false, false, tep_href_link('best_sellers.php'));
    
if (tep_db_num_rows($best_sellers_query) >= MIN_DISPLAY_BESTSELLERS) {
    $rows = 0;
//    $r_c_arr = array('4b0033','620043','760051','8b005f','9a0069','ad0076','ad0076','ad0076','ad0076','ad0076','ad0076');
    $info_box_contents = array();
    while ($best_sellers = tep_db_fetch_array($best_sellers_query)) {
      $rows++;
      $r_size=11/$rows+8;
      $r_color=$r_c_arr[$rows];
      $info_box_contents[] = array('params' => 'style="padding:7px 0;"',
                                   'text'  => '<div style="float:left;width:28px;color:#fff;font-size:17px;text-align:center;background:#349ddb; border-radius:5px;padding:2px 2px;">'.tep_row_number_format($rows) . '</div>
                                               <div style="float:left;width:172px;margin-left:-28px;font-size:12px;padding-top:0px;"><div style="margin-left:30px;text-align:center;"><a style="color:#333;" href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $best_sellers['products_id'], 'NONSSL') . '">' . $best_sellers['products_name'] . '
                                               <br /><br />'.tep_image(DIR_WS_IMAGES . $best_sellers['products_image'], $best_sellers['products_name'], 80, 80).'
                                               </a></div></div>
                                               <div class="clear"></div>
                                               ');
    }

   new contentBox($info_box_contents);
 
?>

<?php
  } else echo '<div id="net">В данном разделе пока нет лидеров продаж</div>';
?>
<!-- best_sellers_smend //-->

