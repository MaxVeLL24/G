<?php
/* 
  $Id: featured_manufacturers.php,v 1.01 03/10/2004 dd/mm/yyyyy 21:00:00 wolfen Exp $

  Wolfen Featured Sets 1.01 MS2 manufacturers listing module
  241@wolfens.com

Made for:
  osCommerce, Open Source E-Commerce Solutions 
  http://www.oscommerce.com 
  Copyright (c) 2004 osCommerce 
  Released under the GNU General Public License 
  
*/
/*  if ((FEATURED_PRODUCTS_DISPLAY == 'true') && (FEATURED_MANUFACTURERS_DISPLAY == 'true')) {
echo '</td></tr><tr><td>';
  } */
echo '<table border="0" width="100%" cellspacing="2" cellpadding="4"><tr>';
if (sizeof($featured_manufacturers_array) <> '0') {  
  $col = 0; 
  for($i=0; $i<sizeof($featured_manufacturers_array); $i++) {  
  $col++; 
echo '<td valign="top" align="center">';
echo '<table border="0" cellspacing="2" cellpadding="2"><tr><td width="'; SMALL_IMAGE_WIDTH + 25;
echo '" align="center" valign="top" class="main"><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $featured_manufacturers_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_manufacturers_array[$i]['image'], $featured_manufacturers_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><b><a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $featured_manufacturers_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_manufacturers_array[$i]['name'] . '</u></a></b>';
echo '</td><td align="right" valign="top" class="main">' . tep_image(DIR_WS_IMAGES . ('pixel_' . MANUFACTURERS_VLINE_IMAGE_COLOUR . '.gif'), '', '1', MANUFACTURERS_VLINE_IMAGE_HEIGHT) . '</td></tr></table></td>';
if (($col / FEATURED_MANUFACTURERS_COLUMNS) == floor($col / FEATURED_MANUFACTURERS_COLUMNS)) {
echo '</tr><tr><td colspan="' . FEATURED_MANUFACTURERS_COLUMNS . '" align="center" valign="top" class="main"><hr color=#' . MANUFACTURERS_HORIZONTAL_LINE_COLOUR . '></td></tr><tr>'; 
$col = 0; // column reset
}
} // closed "for' loop
}
if (($i+0) != sizeof($featured_manufacturers_array)) { 
    }
echo '</tr></table>'; 
//  if ((FEATURED_PRODUCTS_DISPLAY == 'true') && (FEATURED_MANUFACTURERS_DISPLAY == 'true')) {
//echo '</td></tr></table>';
//}
?>