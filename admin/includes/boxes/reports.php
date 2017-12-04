<?php
/*
  $Id: reports.php,v 1.3 2003/09/28 23:37:18 anotherlango Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- reports //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_REPORTS,
                     'link'  => tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED, 'selected_box=reports'));

  if ($selected_box == 'reports' || $menu_dhtml == true) {
    $contents[] = array('text'  => 
//Admin begin

                                   tep_admin_files_boxes(FILENAME_STATS_PRODUCTS_VIEWED, BOX_REPORTS_PRODUCTS_VIEWED) .
                                   tep_admin_files_boxes(FILENAME_STATS_PRODUCTS_PURCHASED, BOX_REPORTS_PRODUCTS_PURCHASED) .
//                                   tep_admin_files_boxes(FILENAME_STATS_RECOVER_CART_SALES, BOX_REPORTS_RECOVER_CART_SALES) .
                                   tep_admin_files_boxes(FILENAME_STATS_CUSTOMERS, BOX_REPORTS_ORDERS_TOTAL) .                              
				                       tep_admin_files_boxes(FILENAME_STATS_MONTHLY_SALES, BOX_REPORTS_MONTHLY_SALES) . 
				                       tep_admin_files_boxes(FILENAME_STATS_SALES_REPORT2, BOX_REPORTS_SALES_REPORT2) .                              
                                   tep_admin_files_boxes(FILENAME_STATS_SALES_REPORT, BOX_REPORTS_SALES_REPORT) .                              
                                   tep_admin_files_boxes(FILENAME_STATS_CUSTOMERS_ORDERS, BOX_REPORTS_CUSTOMERS_ORDERS)                              

				   );
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- reports_smend //-->