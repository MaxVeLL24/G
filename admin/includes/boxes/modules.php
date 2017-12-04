<?php
/*
  $Id: modules.php,v 1.2 2003/09/24 13:57:07 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- modules //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_MODULES,
                     'link'  => tep_href_link(FILENAME_MODULES, 'set=payment&selected_box=modules'));

  if ($selected_box == 'modules' || $menu_dhtml == true) {

    if (POLLS_MODULE_ENABLED == 'true') 
    $polls_link = '<a href="' . tep_href_link(FILENAME_POLLS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_POLLS_POLLS . '</a><br>
  <a href="' . tep_href_link(FILENAME_POLLS, 'info=1&action=config', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_POLLS_CONFIG . '</a>';
    
    if (LANGUAGE_SELECTOR_MODULE_ENABLED == 'true') {
      $lang_link = tep_admin_files_boxes(FILENAME_LANGUAGES, BOX_LOCALIZATION_LANGUAGES) . '</a><br>';
    }




    $contents[] = array('text'  => '<a href="' . tep_href_link(FILENAME_MODULES, 'set=payment', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_MODULES_PAYMENT . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_MODULES, 'set=shipping', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_MODULES_SHIPPING . '</a><br>' .
                                   '<a href="' . tep_href_link(FILENAME_MODULES, 'set=ordertotal', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_MODULES_ORDER_TOTAL . '</a><br>' .
                                   tep_admin_files_boxes(FILENAME_CURRENCIES, BOX_LOCALIZATION_CURRENCIES) . '</a><br>' .
                                    $lang_link.$polls_link);
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- modules_smend //-->
