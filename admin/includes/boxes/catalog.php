<?php
/*
  $Id: catalog.php,v 1.2 2003/09/24 13:57:07 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- catalog //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CATALOG,
                     'link'  => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog'));

  if ($selected_box == 'catalog' || $menu_dhtml == true) {
     if(SALES_MODULE_ENABLED == 'true'){
                                      $sales_var .= tep_admin_files_boxes(FILENAME_SPECIALS, BOX_CATALOG_SPECIALS);
                                     $sales_var .= tep_admin_files_boxes(FILENAME_MANUAL_DISCOUNTS, BOX_MANUDISCOUNT);
                                    $sales_var .=  tep_admin_files_boxes(FILENAME_SALEMAKER, BOX_CATALOG_SALEMAKER);
                                   }
      if (FEATURED_MODULE_ENABLED == 'true') {
        $featured_link_include = tep_admin_files_boxes(FILENAME_FEATURED, BOX_CATALOG_FEATURED);
      }

      if(RELATED_PRODUCTS_MODULE_ENABLED == 'true'){
        $related_link_include = tep_admin_files_boxes(FILENAME_XSELL_PRODUCTS, BOX_CATALOG_XSELL_PRODUCTS);
      }
      if(EXCEL_IMPORT_MODULE_ENABLED == 'true'){
        $excel_import_export = tep_admin_files_boxes(FILENAME_EASYPOPULATE, BOX_CATALOG_EASYPOPULATE);
      }
        $parser = tep_admin_files_boxes(FILENAME_PARSER, BOX_CATALOG_PARSER);

    $contents[] = array('text'  =>
                                   tep_admin_files_boxes(FILENAME_CATEGORIES, BOX_CATALOG_CATEGORIES_PRODUCTS) .
                                   tep_admin_files_boxes(FILENAME_TABLEDATA, TBL_LINK_TITLE) .
                                   tep_admin_files_boxes(FILENAME_PRODUCTS_MULTI, BOX_CATALOG_CATEGORIES_PRODUCTS_MULTI) .
                                   tep_admin_files_boxes(FILENAME_MANUFACTURERS, BOX_CATALOG_MANUFACTURERS) .
                                   tep_admin_files_boxes(FILENAME_PRODUCTS_ATTRIBUTES, BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES) .
                                   $sales_var.
                                   tep_admin_files_boxes(FILENAME_EXTRA_PRODUCT_PRICE, BOX_EXTRA_PRODUCT_PRICE) .
                                   tep_admin_files_boxes(FILENAME_QUICK_UPDATES, BOX_CATALOG_QUICK_UPDATES) .
                                   $parser.
                                   $excel_import_export.
                                   $featured_link_include.
                                   $related_link_include.
                                   tep_admin_files_boxes(FILENAME_PRODUCTS_EXPECTED, BOX_CATALOG_PRODUCTS_EXPECTED));

  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_smend //-->
