<?php 
	  $column_left_query = tep_db_query('select display_in_column as cfgcol, infobox_file_name as cfgtitle, infobox_display as cfgvalue, infobox_define as cfgkey, box_heading, box_template, box_heading_font_color from ' . TABLE_INFOBOX_CONFIGURATION . ' where template_id = ' . TEMPLATE_ID . ' and infobox_display = "yes" and display_in_column = "left" order by location');
	  $column_right_query = tep_db_query('select display_in_column as cfgcol, infobox_file_name as cfgtitle, infobox_display as cfgvalue, infobox_define as cfgkey, box_heading, box_template, box_heading_font_color from ' . TABLE_INFOBOX_CONFIGURATION . ' where template_id = ' . TEMPLATE_ID . ' and infobox_display = "yes" and display_in_column = "right" order by location');
	  if (tep_db_num_rows($column_left_query) == 0) {
	  	$sidebar_left = false;
	  }
	  if (tep_db_num_rows($column_right_query) == 0) {
    	$sidebar_right = false;
	  }
?>