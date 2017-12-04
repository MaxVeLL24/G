<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
<?php 
// Set number of columns in listing
define ('NR_COLUMNS', 2);?>
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr> 
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0"> 
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td> 
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>

<!-- body_text //-->
<?php
  if ($_GET['action'] == 'archive') {
    $delete_query = tep_db_query("select orders_products_download_id, orders_id from orders_products_download where orders_products_download_id = '" . $_GET['aID'] . "'");
    $delete = tep_db_fetch_array($delete_query);
	tep_db_query("update orders_products_download set archived = 'yes' where orders_id = '" . $delete['orders_id'] . "' AND orders_products_download_id = '" . $_GET['aID'] . "' LIMIT 1");
?>
     <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
<?php

    $orders_query_raw = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE customers_id = '" . $customer_id . "' ORDER BY orders_id DESC LIMIT 999";
    $orders_query = tep_db_query($orders_query_raw);
    $orders_values = tep_db_fetch_array($orders_query);
    $last_order = $orders_values['orders_id'];
// Now get all downloadable products in that order
  $downloads_query_raw = "SELECT DATE_FORMAT(date_purchased, '%Y-%m-%d') as date_purchased_day, o.orders_id, opd.download_maxdays, op.products_name, op.products_id, opd.orders_products_download_id, opd.orders_products_filename, opd.download_count, opd.download_maxdays
                          FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd
                          WHERE customers_id = '" . $customer_id . "'
                          and o.orders_status >= '" . DOWNLOADS_CONTROLLER_ORDERS_STATUS . "' and o.orders_status != '99999' and o.orders_id = '" . (int)$last_order . "'
                          AND o.orders_id = op.orders_id
                          AND op.orders_id = o.orders_id
                          AND o.orders_status >= '2'
                          AND o.orders_status != 99999
                          AND opd.orders_products_id=op.orders_products_id
                          AND opd.orders_products_filename<>''
						  ORDER BY opd.orders_products_download_id DESC LIMIT 999";
  $downloads_query = tep_db_query($downloads_query_raw);

// Don't display if there is no downloadable product
  if (tep_db_num_rows($downloads_query) > 0) {
?>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
<tr class="infoBoxHeading">
<td class="infoBoxHeading" align="center"><?php echo TEXT_DOWNLOAD_NUMBER ?></td>
<td class="infoBoxHeading" align="center"><?php echo HEADING_DOWNLOAD_HERE ?></td>
<td class="infoBoxHeading" align="center"><?php echo TABLE_HEADING_DOWNLOAD_DATE ?></td>
<td class="infoBoxHeading" align="center"><?php echo TABLE_HEADING_DOWNLOAD_COUNT ?></td>
</tr>
<!-- list of products -->
<?php
    while ($downloads_values = tep_db_fetch_array($downloads_query)) {
    	list($dt_year, $dt_month, $dt_day) = explode('-', $downloads_values['date_purchased_day']);
    	$download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads_values['download_maxdays'], $dt_year);
  	    $download_expiry = date('Y-m-d H:i:s', $download_timestamp);

      if (($downloads_values['download_count'] > 0) && (file_exists(DIR_FS_DOWNLOAD . $downloads_values['orders_products_filename'])) &&
          (($downloads_values['download_maxdays'] == 0) || ($download_timestamp > time()))) {
		  $n = $n+1;
		echo '			  <tr class="infoBoxContents">' . "\n";
		echo '            <td align="center">' . $n .'. ' . $downloads_values['products_name'] . '  </td>' . "\n";
		echo '            <td align="center"><a href="' . tep_href_link(FILENAME_DOWNLOAD, 'order=' . $downloads_values['orders_id'] . '&id=' . $downloads_values['orders_products_download_id']) . '"><b><font color=red>' . TEXT_DOWNLOAD_HERE . '</font></b></a></td>' . "\n";
	    echo '            <td align="center">' . tep_date_long($download_expiry) . '</td>' . "\n";
        echo '            <td align="center">' . $downloads_values['download_count'] . '</td>' . "\n";
        echo '          </tr>' . "\n";
} 
}
?>
            </tr>
          </table>
        </td>
      </tr>
<?php
    if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr><?php
	  echo '          <td class="smalltext" colspan="4"></form>' . "\n";
?>     
	  </tr>
<?php
    }
  }
?>
			</td>
          </tr>
        </table></td>
      </tr>
     </table></td>
<?php
} else {
?>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
     <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td>
<?php

    $orders_query_raw = "SELECT orders_id FROM " . TABLE_ORDERS . " WHERE customers_id = '" . $customer_id . "' ORDER BY orders_id DESC LIMIT 999";
    $orders_query = tep_db_query($orders_query_raw);
    $orders_values = tep_db_fetch_array($orders_query);
    $last_order = $orders_values['orders_id'];
// Now get all downloadable products in that order
  $downloads_query_raw = "SELECT DATE_FORMAT(date_purchased, '%Y-%m-%d') as date_purchased_day, o.orders_id, opd.download_maxdays, op.products_name, opd.orders_products_download_id, opd.orders_products_filename, opd.download_count, opd.download_maxdays
                          FROM " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd
                          WHERE customers_id = '" . $customer_id . "'
                          and o.orders_status >= '" . DOWNLOADS_CONTROLLER_ORDERS_STATUS . "' and o.orders_status != '99999' and o.orders_id = '" . (int)$last_order . "'
                          AND o.orders_id = op.orders_id
                          AND op.orders_id = o.orders_id
                          AND o.orders_status >= '2'
                          AND o.orders_status != 99999
                          AND opd.orders_products_id=op.orders_products_id
                          AND opd.orders_products_filename<>''
						  ORDER BY opd.orders_products_download_id DESC LIMIT 999";
  $downloads_query = tep_db_query($downloads_query_raw);

// Don't display if there is no downloadable product
  if (tep_db_num_rows($downloads_query) > 0) {
?>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
<tr class="infoBoxHeading">
<td class="infoBoxHeading" align="center"><?php echo TEXT_DOWNLOAD_NUMBER ?></td>
<td class="infoBoxHeading" align="center"><?php echo HEADING_DOWNLOAD_HERE ?></td>
<td class="infoBoxHeading" align="center"><?php echo TABLE_HEADING_DOWNLOAD_DATE ?></td>
<td class="infoBoxHeading" align="center"><?php echo TABLE_HEADING_DOWNLOAD_COUNT ?></td>
</tr>
<!-- list of products -->
<?php
    while ($downloads_values = tep_db_fetch_array($downloads_query)) {
    	list($dt_year, $dt_month, $dt_day) = explode('-', $downloads_values['date_purchased_day']);
    	$download_timestamp = mktime(23, 59, 59, $dt_month, $dt_day + $downloads_values['download_maxdays'], $dt_year);
  	    $download_expiry = date('Y-m-d H:i:s', $download_timestamp);

      if (($downloads_values['download_count'] > 0) && (file_exists(DIR_FS_DOWNLOAD . $downloads_values['orders_products_filename'])) &&
          (($downloads_values['download_maxdays'] == 0) || ($download_timestamp > time()))) {
		  $n = $n+1;
		echo '			  <tr class="infoBoxContents">' . "\n";
		echo '            <td align="center">' . $n .' ' . $downloads_values['products_name'] . '  </td>' . "\n";
		echo '            <td align="center"><a href="' . tep_href_link(FILENAME_DOWNLOAD, 'order=' . $downloads_values['orders_id'] . '&id=' . $downloads_values['orders_products_download_id']) . '"><b><font color=red>' . TEXT_DOWNLOAD_HERE . '</font></b></a></td>' . "\n";
	    echo '            <td align="center">' . tep_date_long($download_expiry) . '</td>' . "\n";
        echo '            <td align="center">' . $downloads_values['download_count'] . '</td>' . "\n";
        echo '          </tr>' . "\n";
} 
}
?>
            </tr>
          </table>
        </td>
      </tr>
<?php
    if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr><?php
	  echo '          <td class="smalltext" colspan="4"></form>' . "\n";
?>     
	  </tr>
<?php
    }
  }
?>
			</td>
          </tr>
        </table></td>
      </tr>
     </table></td>
<?php
}
?>
<!-- body_text_smend //-->
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>


   </table>
