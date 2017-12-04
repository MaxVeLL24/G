<?php
/*
  $Id: column_right.php,v 1.2 2003/09/24 15:34:33 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/


while ($column = tep_db_fetch_array($column_right_query)) {
	if ( file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle'])) {

		define($column['cfgkey'],$column['box_heading']);
		$infobox_define = $column['box_heading'];
		//$infobox_template = $column['box_template'];
		$font_color = $column['box_heading_font_color'];
		$infobox_class = $column['box_template'];
		require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle']);
	}
}
?>