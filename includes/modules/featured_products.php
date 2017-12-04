<?php
/* 
  $Id: featured_products.php v1.0 03/10/2004 dd/mm/yyyy 10:00:00 wolfen Exp $
  
  Wolfen Featured Products Sets 1.01 MS2 products listing module
  241@wolfens.com

Made for:
  osCommerce, Open Source E-Commerce Solutions 
  http://www.oscommerce.com 
  Copyright (c) 2004 osCommerce 
  Released under the GNU General Public License 
  
*/
?>
<?php 
 /* if ((FEATURED_PRODUCTS_DISPLAY == 'true') && (FEATURED_MANUFACTURERS_DISPLAY == 'true')) {
echo '<table border="0" width="100%" cellspacing="2" cellpadding="4"><tr><td>';
  }*/
?>
<?php
echo '<table border="0" width="100%" cellspacing="2" cellpadding="4"><tr>';
  if (sizeof($featured_products_array) <> '0') { 
   $col = 0; 
    for($i=0; $i<sizeof($featured_products_array); $i++) { 
      if (($featured_products_array[$i]['specials_price']) && ($featured_products_array[$i]['specials_status'] == '1')) { 
        $products_price = '<s>' .  $currencies->display_price($featured_products_array[$i]['price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</s>&nbsp;&nbsp;<span class="productSpecialPrice">' . $currencies->display_price($featured_products_array[$i]['specials_price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])) . '</span>'; 
      } else { 
        $products_price = $currencies->display_price($featured_products_array[$i]['price'], tep_get_tax_rate($featured_products_array[$i]['tax_class_id'])); 
      } 
   $col++; 
echo '<td valign="top" align="center">';
?>
<?php
  if ((FEATURED_SET == '1') && (FEATURED_SET_STYLE == '1')) { 
echo '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="'; SMALL_IMAGE_WIDTH + 25;
echo '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td><td align="left" valign="top" class="main"><div align="left"><b><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div>';
  if ($featured_products_array[$i]['shortdescription'] != '') { 
      echo $featured_products_array[$i]['shortdescription']; 
  } else { 
   $bah = explode(" ", $featured_products_array[$i]['description']); 
   for($desc=0 ; $desc<MAX_FEATURED_WORD_DESCRIPTION ; $desc++) 
      { 
      echo "$bah[$desc] "; 
      }  
  } 
echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><font color="#FF0000">' . TEXT_MORE_INFO . '</font></a>&nbsp;</td><td align="left" valign="top" class="main">' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td></tr></table>';
}
?>
<?php
  if ((FEATURED_SET == '1') && (FEATURED_SET_STYLE == '2')) {
     $info_box_contents = array();
     $info_box_contents[] = array('text' => '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="' . (SMALL_IMAGE_WIDTH + 25) . '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td><td align="left" valign="top" class="main"><div align="left"><b><a href="' . 
tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div>' . $featured_products_array[$i]['shortdescription'] . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><font color="#FF0000">' . TEXT_MORE_INFO . 
'</font></a>&nbsp;</td><td align="left" valign="top" class="main">' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td></tr></table>');
  new infoBox($info_box_contents);
}
?>
<?php
  if ((FEATURED_SET == '1') && (FEATURED_SET_STYLE == '3')) {
echo '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="'; SMALL_IMAGE_WIDTH + 25;
echo '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td><td align="left" valign="top" class="main"><div align="left"><b><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div>';
  if ($featured_products_array[$i]['shortdescription'] != '') { 
      echo $featured_products_array[$i]['shortdescription']; 
  } else { 
   $bah = explode(" ", $featured_products_array[$i]['description']); 
   for($desc=0 ; $desc<MAX_FEATURED_WORD_DESCRIPTION ; $desc++) 
      { 
      echo "$bah[$desc] "; 
      }  
  } 
echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><font color="#FF0000">' . TEXT_MORE_INFO . '</font></a>&nbsp;</td><td align="left" valign="top" class="main">' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td><td align="right" valign="top" class="main">' . tep_image(DIR_WS_IMAGES . ('pixel_' . VLINE_IMAGE_COLOUR . '.gif'), '', '1', VLINE_IMAGE_HEIGHT) . '<td></tr></table>';
}
?>
<?php
  if ((FEATURED_SET == '1') && (FEATURED_SET_STYLE == '4')) {
     $info_box_contents = array();
     $info_box_contents[] = array('text' => '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="' . (SMALL_IMAGE_WIDTH + 25) . '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td><td align="left" valign="top" class="main"><div align="left"><b><a href="' . 
tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div>' . $featured_products_array[$i]['shortdescription'] . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><font color="#FF0000">' . TEXT_MORE_INFO . 
'</font></a>&nbsp;</td><td align="left" valign="top" class="main">' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td></tr></table>');
  new infoBox($info_box_contents);
echo '<IMG SRC="images/info_box_' . FEATURED_SET_STYLE_SHADOW . '_shadow.gif" WIDTH=100% HEIGHT=13>';
}
?>
<?php
  if ((FEATURED_SET == '2') && (FEATURED_SET_STYLE == '1')) { 
echo '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="'; SMALL_IMAGE_WIDTH + 25;
echo '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td></tr><tr><td width="25%" align="center" valign="top" class="main"><div align="left"><b><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div></td></tr><tr><td valign="top" class="main" width="25%">';
  if ($featured_products_array[$i]['shortdescription'] != '') { 
      echo $featured_products_array[$i]['shortdescription']; 
  } else { 
   $bah = explode(" ", $featured_products_array[$i]['description']); 
   for($desc=0 ; $desc<MAX_FEATURED_WORD_DESCRIPTION ; $desc++) 
      { 
      echo "$bah[$desc] "; 
      }  
  } 
echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><font color="#FF0000">' . TEXT_MORE_INFO . '</font></a>&nbsp;</td></tr><tr><td align="left" valign="top" class="main">' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td></tr></table>';
  }
?>
<?php
  if ((FEATURED_SET == '2') && (FEATURED_SET_STYLE == '2')) {
     $info_box_contents = array();
     $info_box_contents[] = array('text' => '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="' . (SMALL_IMAGE_WIDTH + 25) . '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td></tr><tr><td width="25%" align="center" valign="top" class="main"><div align="left"><b><a href="' . 
tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div></td></tr><tr><td valign="top" class="main" width="25%">' . $featured_products_array[$i]['shortdescription'] . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><font color="#FF0000">' . TEXT_MORE_INFO . 
'</font></a>&nbsp;</td></tr><tr><td align="left" valign="top" class="main">' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td></tr></table>');
  new infoBox($info_box_contents);  
  }
?>
<?php
  if ((FEATURED_SET == '2') && (FEATURED_SET_STYLE == '3')) {
echo '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="'; SMALL_IMAGE_WIDTH + 25;
echo '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br><div align="left"><b><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div><br>';
  if ($featured_products_array[$i]['shortdescription'] != '') { 
      echo $featured_products_array[$i]['shortdescription']; 
  } else { 
   $bah = explode(" ", $featured_products_array[$i]['description']); 
   for($desc=0 ; $desc<MAX_FEATURED_WORD_DESCRIPTION ; $desc++) 
      { 
      echo "$bah[$desc] "; 
      }  
  } 
echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><font color="#FF0000">' . TEXT_MORE_INFO . '</font></a>&nbsp;<br>' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td><td align="right" valign="top" class="main">' . tep_image(DIR_WS_IMAGES . ('pixel_' . VLINE_IMAGE_COLOUR . '.gif'), '', '1', VLINE_IMAGE_HEIGHT) . '<td></tr></table>';
  }
?>
<?php
  if ((FEATURED_SET == '2') && (FEATURED_SET_STYLE == '4')) {
     $info_box_contents = array();
     $info_box_contents[] = array('text' => '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="' . (SMALL_IMAGE_WIDTH + 25) . '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td></tr><tr><td width="25%" align="center" valign="top" class="main"><div align="left"><b><a href="' . 
tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div></td></tr><tr><td valign="top" class="main" width="25%">' . $featured_products_array[$i]['shortdescription'] . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><font color="#FF0000">' . TEXT_MORE_INFO . 
'</font></a>&nbsp;</td></tr><tr><td align="left" valign="top" class="main">' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td></tr></table>');
  new infoBox($info_box_contents);
echo '<IMG SRC="images/info_box_' . FEATURED_SET_STYLE_SHADOW . '_shadow.gif" WIDTH=100% HEIGHT=13>';
  }
?>
<?php
  if ((FEATURED_SET == '3') && (FEATURED_SET_STYLE == '1')) {
echo '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="'; SMALL_IMAGE_WIDTH + 25;
echo '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br>' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td><td align="left" valign="top" class="main"><div align="left"><b><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div>';
  if ($featured_products_array[$i]['shortdescription'] != '') { 
      echo $featured_products_array[$i]['shortdescription']; 
  } else { 
   $bah = explode(" ", $featured_products_array[$i]['description']); 
   for($desc=0 ; $desc<MAX_FEATURED_WORD_DESCRIPTION ; $desc++) 
      { 
      echo "$bah[$desc] "; 
      }  
  } 
echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><font color="#FF0000">' . TEXT_MORE_INFO . '</font></a>&nbsp;</td></tr></table>';
}
?>
<?php
  if ((FEATURED_SET == '3') && (FEATURED_SET_STYLE == '2')) {

     $info_box_contents = array();
     $info_box_contents[] = array('text' => '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="' . (SMALL_IMAGE_WIDTH + 25) . '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br>' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . 
tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td><td align="left" valign="top" class="main"><div align="left"><b><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div>' . $featured_products_array[$i]['shortdescription'] . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><font color="#FF0000">' . TEXT_MORE_INFO . '</font></a>&nbsp;</td></tr></table>');
  new infoBox($info_box_contents);
}
?>
<?php
  if ((FEATURED_SET == '3') && (FEATURED_SET_STYLE == '3')) {
 
echo '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="'; SMALL_IMAGE_WIDTH + 25;
echo '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br>' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td><td align="left" valign="top" class="main"><div align="left"><b><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div>';
  if ($featured_products_array[$i]['shortdescription'] != '') { 
      echo $featured_products_array[$i]['shortdescription']; 
  } else { 
   $bah = explode(" ", $featured_products_array[$i]['description']); 
   for($desc=0 ; $desc<MAX_FEATURED_WORD_DESCRIPTION ; $desc++) 
      { 
      echo "$bah[$desc] "; 
      }  
  } 
echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><font color="#FF0000">' . TEXT_MORE_INFO . '</font></a>&nbsp;</td><td align="right" valign="top" class="main">' . tep_image(DIR_WS_IMAGES . ('pixel_' . VLINE_IMAGE_COLOUR . '.gif'), '', '1', VLINE_IMAGE_HEIGHT) . '<td></tr></table>';
}
?>
<?php
  if ((FEATURED_SET == '3') && (FEATURED_SET_STYLE == '4')) {

     $info_box_contents = array();
     $info_box_contents[] = array('text' => '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="' . (SMALL_IMAGE_WIDTH + 25) . '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br>' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . 
tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td><td align="left" valign="top" class="main"><div align="left"><b><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div>' . $featured_products_array[$i]['shortdescription'] . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><font color="#FF0000">' . TEXT_MORE_INFO . '</font></a>&nbsp;</td></tr></table>');
  new infoBox($info_box_contents);
echo '<IMG SRC="images/info_box_' . FEATURED_SET_STYLE_SHADOW . '_shadow.gif" WIDTH=100% HEIGHT=13>';
}
?>
<?php
  if ((FEATURED_SET == '4') && (FEATURED_SET_STYLE == '1')) {
echo '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="'; SMALL_IMAGE_WIDTH + 25;
echo '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br>' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br><div align="left"><b><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div><br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td></tr></table>';
}
?>
<?php
  if ((FEATURED_SET == '4') && (FEATURED_SET_STYLE == '2')) {
     $info_box_contents = array();
     $info_box_contents[] = array('text' => '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br>' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . 
'<br><div align="left"><b><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div><br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td></tr></table>');
  new infoBox($info_box_contents);
}
?>
<?php
  if ((FEATURED_SET == '4') && (FEATURED_SET_STYLE == '3')) {
echo '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td width="'; SMALL_IMAGE_WIDTH + 25;
echo '" align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br>' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . '<br><div align="left"><b><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div><br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td><td align="right" valign="top" class="main">' . tep_image(DIR_WS_IMAGES . ('pixel_' . VLINE_IMAGE_COLOUR . '.gif'), '', '1', VLINE_IMAGE_HEIGHT) . '<td></tr></table>';
}
?>
<?php
  if ((FEATURED_SET == '4') && (FEATURED_SET_STYLE == '4')) {
     $info_box_contents = array();
     $info_box_contents[] = array('text' => '<table border="0" width="100%" cellspacing="2" cellpadding="2"><tr><td align="left" valign="top" class="main"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image(DIR_WS_IMAGES . $featured_products_array[$i]['image'], $featured_products_array[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br>' . tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '5') . 
'<br><div align="left"><b><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '"><u>' . $featured_products_array[$i]['name'] . '</u></a></b></div><br>' . TABLE_HEADING_PRICE . ': ' . $products_price . '<br><a href="' . tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $featured_products_array[$i]['id'], 'NONSSL') . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a></td></tr></table>');
  new infoBox($info_box_contents);
echo '<IMG SRC="images/info_box_' . FEATURED_SET_STYLE_SHADOW . '_shadow.gif" WIDTH=100% HEIGHT=13>';
}
?>
<?php
  if (($col / FEATURED_PRODUCTS_COLUMNS) == floor($col / FEATURED_PRODUCTS_COLUMNS)) { 
     if (((FEATURED_SET == '1') && (FEATURED_SET_STYLE == '3')) or ((FEATURED_SET == '2') && (FEATURED_SET_STYLE == '3')) or ((FEATURED_SET == '3') && (FEATURED_SET_STYLE == '3')) or ((FEATURED_SET == '4') && (FEATURED_SET_STYLE == '3'))){
echo '</td></tr><tr><td colspan="' . FEATURED_PRODUCTS_COLUMNS . '" align="center" valign="top" class="main"><hr color=#' . HORIZONTAL_LINE_COLOUR . '></td></tr><tr>'; 
  }else{
echo '</td></tr><tr><td colspan="' . FEATURED_PRODUCTS_COLUMNS . '" class="main"></td></tr><tr>'; 
  }
  }
if (($i+1) != sizeof($featured_products_array)) { 
      } 
    } 
  }  
echo '</table>';
?>