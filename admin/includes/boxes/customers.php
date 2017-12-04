<?php
/*
  $Id: customers.php,v 1.2 2003/09/24 13:57:07 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- customers //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CUSTOMERS,
                     'link'  => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers'));

  if ($selected_box == 'customers' || $menu_dhtml == true) {
    if (CUPONES_MODULE_ENABLED == 'true') {
        $cupons = tep_admin_files_boxes(FILENAME_COUPON_ADMIN, BOX_COUPON_ADMIN);
      }
    $contents[] = array('text'  =>
//Admin begin
      
                                   tep_admin_files_boxes(FILENAME_CUSTOMERS, BOX_CUSTOMERS_CUSTOMERS) .
                                   tep_admin_files_boxes(FILENAME_ORDERS, BOX_CUSTOMERS_ORDERS) .
                                   tep_admin_files_boxes(FILENAME_GROUPS, BOX_CUSTOMERS_GROUPS) .
                            //       tep_admin_files_boxes(FILENAME_MANUAL_DISCOUNTS, BOX_MANUDISCOUNT) .
                                   tep_admin_files_boxes(FILENAME_CREATE_ACCOUNT, BOX_MANUAL_ORDER_CREATE_ACCOUNT) .
                                   tep_admin_files_boxes(FILENAME_CREATE_ORDER, BOX_MANUAL_ORDER_CREATE_ORDER).
                                   tep_admin_files_boxes(FILENAME_ORDERS_STATUS, BOX_LOCALIZATION_ORDERS_STATUS). $cupons
                                   ); 
                                   
                                 
                                   
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- customers_smend //-->
