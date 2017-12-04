<?php
/*
  $Id: products_multi.php, v 2.0

  autor: sr, 2003-07-31 / sr@ibis-project.de

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Multi manager');
define('HEADING_TITLE_SEARCH', 'Search:');
define('HEADING_TITLE_GOTO', 'Go to:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_CHOOSE', 'Select');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Categories / Products');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Products model');
define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_PRODUCTS_QUANTITY', 'Quantity');
define('TABLE_HEADING_MANUFACTURERS_NAME', 'Manufacturer');
define('TABLE_HEADING_STATUS', 'Status');

define('DEL_DELETE', 'delete product');
define('DEL_CHOOSE_DELETE_ART', 'How to delete?');
define('DEL_THIS_CAT', 'Only in this category');
define('DEL_COMPLETE', 'Delete completely');

define('TEXT_NEW_PRODUCT', 'New products in &quot;%s&quot;');
define('TEXT_CATEGORIES', 'Categories:');
define('TEXT_ATTENTION_DANGER', '');
/*
define('TEXT_ATTENTION_DANGER', '<br><br><span class="dataTableContentRedAlert">!!! ATTENTION !!! пожалуйста прочтите !!!</span><br><br><span class="dataTableContentRed">Этот инструмент меняет таблицы "products_to_categories" (и в случае  \' полностью удалить товар\' даже "products" и "products_description" among others; через функцию \'tep_remove_product\') - поэтому делать резервную копию этих таблиц перед каждым использованием этого инструмента ОЧЕНЬ рекомендуется. Причины:<br><br>This tool deletes, moves or copies all via checkbox selected products without any interim step or warning, that means immediately after clicking on the go-button.</span><br><br><span class="dataTableContentRedAlert">Please take care:</span><ul><li>Pay very great attention when using <strong>\'delete the complete product\'</strong>. This function deletes all selected products immediately, without interim step or warning, and completely from all tables where these products belong to.</strong></li><li>While choosing <strong>\'delete product only in this category\'</strong>, no products are deleted completely, but only their links to the actually opened category - even when it\'s the only category-link of the product, and without warning, that means: be careful with this delete tool as well.</li><li>While <strong>copying</strong>, products are not duplicated, they are only linked to the new category chosen.</li><li>Products are only <strong>moved</strong> resp. <strong>copied</strong> to a new category in case they do not exist there allready.</li></ul>');
*/
define('TEXT_MOVE_TO', 'move to');
define('TEXT_CHOOSE_ALL', 'select all');
define('TEXT_CHOOSE_ALL_REMOVE', 'discard select');
define('TEXT_SUBCATEGORIES', 'Subcategories:');
define('TEXT_PRODUCTS', 'Products:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Price:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Tax class:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Ср.Оценка:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Quantity:');
define('TEXT_DATE_ADDED', 'Added:');
define('TEXT_DATE_AVAILABLE', 'Availability:');
define('TEXT_LAST_MODIFIED', 'Last modified:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Please insert a new category or product in <br>&nbsp;<br><b>%s</b>');
define('TEXT_PRODUCT_MORE_INFORMATION', 'Please visit <a href="http://%s" target="blank"><u>a page</u></a>of this product for more info.');
define('TEXT_PRODUCT_DATE_ADDED', 'This product was added to our catalog %s.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'This product will be in stock %s.');

define('TEXT_EDIT_INTRO', 'Please make the necessary changes');
define('TEXT_EDIT_CATEGORIES_ID', 'ID:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Name:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Picture:');
define('TEXT_EDIT_SORT_ORDER', 'Sort by:');

define('TEXT_INFO_COPY_TO_INTRO', 'Please select a new category in which you want to copy items');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Existing categories:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'New category');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Change category');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Delete category');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Move category');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Delete product');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Move product');
define('TEXT_INFO_HEADING_COPY_TO', 'Copy to');
define('LINK_TO', 'Link to');

define('TEXT_DELETE_CATEGORY_INTRO', 'Are you sure you want to delete this category?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Are you sure you want to permanently delete this product?');

define('TEXT_DELETE_WARNING_CHILDS', '<b>ATTENTION:</b> %s subcategories are still linked to this category!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<b>ATTENTION:</b> %s goods are still linked to this category!');

define('TEXT_MOVE_PRODUCTS_INTRO', 'Please select the category in which you want to place <b>%s</b>');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Please select the category in which you want to place <b>%s</b>');
define('TEXT_MOVE', 'Move <b>%s</b> to:');

define('TEXT_NEW_CATEGORY_INTRO', 'Please fill in the following information for the new category');
define('TEXT_CATEGORIES_NAME', 'Name:');
define('TEXT_CATEGORIES_IMAGE', 'Picture:');
define('TEXT_SORT_ORDER', 'Sort by:');

define('TEXT_PRODUCTS_STATUS', 'Status:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'In stock:');
define('TEXT_PRODUCT_AVAILABLE', 'In stock');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'Not in stock');
define('TEXT_PRODUCTS_MANUFACTURER', 'Manufacturer:');
define('TEXT_PRODUCTS_NAME', 'Name:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Description:');
define('TEXT_PRODUCTS_QUANTITY', 'Quantity:');
define('TEXT_PRODUCTS_MODEL', 'MOdel :');
define('TEXT_PRODUCTS_IMAGE', 'Picture:');
define('TEXT_PRODUCTS_URL', 'URL:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(without http://)</small>');
define('TEXT_PRODUCTS_PRICE', 'Price:');
define('TEXT_PRODUCTS_WEIGHT', 'Weight:');
define('TEXT_NONE', '--not--');

define('EMPTY_CATEGORY', 'empty category');

define('TEXT_HOW_TO_COPY', 'Copy method:');
define('TEXT_COPY_AS_LINK', 'Link to product');
define('TEXT_COPY_AS_DUPLICATE', 'Duplicate product');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Error: Can not make a link to the product in the same category.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Image folder is not writable: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Image folder does not exist: ' . DIR_FS_CATALOG_IMAGES);
?>