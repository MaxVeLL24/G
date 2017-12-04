<?php
/*
  $Id: manufacturers.php,v 1.1.1.1 2003/09/18 19:05:50 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2001 osCommerce

  Released under the GNU General Public License
*/

//###############################################
//  if ( (USE_CACHE == 'true') && !defined('SID')) {
//    echo tep_cache_manufacturers_box();
//  } else {
//##############################################
?>
<!-- manufacturers //-->

<?php
  $info_box_contents = array();
   $info_box_contents[] = array('text'  => '' . BOX_HEADING_MANUFACTURERS . '');
  new infoBoxHeading($info_box_contents, false, false);

  $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " WHERE status = 1 order by manufacturers_name");

  if (tep_db_num_rows($manufacturers_query) <= MAX_DISPLAY_MANUFACTURERS_IN_A_LIST) {
// Display a list
    $manufacturers_list = '';
    ($_GET['manufacturers_id']==''?($marker_class='manuf_bg_current'):($marker_class='manuf_bg'));
      $manufacturers_list .= '<div class="'.$marker_class.'">
                                <a href="index.php">
                                  Все</a>
                              </div>';
    while ($manufacturers_values = tep_db_fetch_array($manufacturers_query)) {  
      ($manufacturers_values['manufacturers_id']==$_GET['manufacturers_id']?($marker_class='manuf_bg_current'):($marker_class='manuf_bg'));
      $manufacturers_list .= '<div class="'.$marker_class.'">
                                <a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers_values['manufacturers_id'], 'NONSSL') . '">
                                  ' . substr($manufacturers_values['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '</a>
                              </div>';
    }

    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                 'text'  => $manufacturers_list);
  } else {
// Display a drop-down
    $select_box = '<select name="manufacturers_id" onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" style="width: 160px;padding:0px;margin:0px;">';
    if (MAX_MANUFACTURERS_LIST < 2) {
      $select_box .= '<option value="">' . PULL_DOWN_DEFAULT . '</option>';
    }
    while ($manufacturers_values = tep_db_fetch_array($manufacturers_query)) {
      $select_box .= '<option value="' . $manufacturers_values['manufacturers_id'] . '"';
      if ($_GET['manufacturers_id'] == $manufacturers_values['manufacturers_id']) $select_box .= ' SELECTED';
      $select_box .= '>' . substr($manufacturers_values['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '</option>';
    }
    $select_box .= "</select>";
    $select_box .= tep_hide_session_id();

    $info_box_contents = array();
    $info_box_contents[] = array('form'  => '<form name="manufacturers" method="get" action="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false) . '">',
                                 'align' => 'left',
                                 'text'  => $select_box);
  }

new infoBox($info_box_contents);


?>

<?php
//}
?>
<!-- manufacturers_smend //-->
