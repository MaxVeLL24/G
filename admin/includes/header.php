<?php
/*
  $Id: header.php,v 1.2 2003/09/24 13:57:07 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }

?>
    	<?php 
        	// #CP - point logos to come from selected template's images directory
		    $template_query = tep_db_query("select configuration_id, configuration_title, configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
  			$template = tep_db_fetch_array($template_query);
  			$CURR_TEMPLATE = $template['configuration_value'] . '/';
        ?>

<div>
  <div style="float:left;"><a href="index.php"><img src="images/logo.jpg" border="0" /></a></div>
  <div style="float:left;padding: 40px 6px 0 6px;"><span><?php echo INDEX_HOLA; ?>, <b><?php echo $_SESSION['login_first_name']; ?></b>.</span>
  <a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'NONSSL');?>" ><?php echo HEADER_TITLE_LOGOFF; ?></a>
  </div>
  <div style="clear:both;"></div>
</div>
<script language="javascript" src="includes/menu.js"></script>
<?php if (MENU_DHTML == 'true') echo '<link rel="stylesheet" type="text/css" href="includes/menu.css">'; ?>
<?php if (MENU_DHTML == 'true') require(DIR_WS_INCLUDES . 'header_navigation.php'); ?>