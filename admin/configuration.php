<?php
/*
  $Id: configuration.php,v 1.2 2003/09/24 13:57:05 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

Header("Cache-control: private, no-cache");  
Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); # Past date
Header("Pragma: no-cache");

  include_once __DIR__ . '/includes/application_top.php';

  // #CP - local dir to the template directory where you are uploading the company logo
  $template_query = tep_db_query("select configuration_id, configuration_title, configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
  $template = tep_db_fetch_array($template_query);
  $CURR_TEMPLATE = $template['configuration_value'] . '/';
  
  $upload_fs_dir = DIR_FS_TEMPLATES.$CURR_TEMPLATE.DIR_WS_IMAGES;
  $upload_ws_dir = DIR_WS_TEMPLATES.$CURR_TEMPLATE.DIR_WS_IMAGES;
  // #CP

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        if($_GET['flag']==0) $r_flag = 'false';
        else $r_flag = 'true';
        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $r_flag . "', last_modified = now() where configuration_id = '" . (int)$_GET['cID'] . "'");
        tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cID));
        break;
      case 'save':
          /* One Page Checkout - BEGIN*/
          if ($_GET['gID'] == 7575){
              tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . $_POST['configuration_value'] . "', last_modified = now() where configuration_id = '" . (int)$_GET['cID'] . "'");
              tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $_GET['cID']));
          }
          /* One Page Checkout - END*/
        $configuration_value = tep_db_prepare_input($_POST['configuration_value']);
        $cID = tep_db_prepare_input($_GET['cID']);

        tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = '" . tep_db_input($configuration_value) . "', last_modified = now() where configuration_id = '" . (int)$cID . "'");

// Configuration Cache modification start
//        require ('includes/configuration_cache.php');
// Configuration Cache modification end

        tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cID));
        break;
// #CP - supporting functions to upload company logo to template images directory       
      case 'processuploads':
      
        if (isset($GLOBALS['file_name']) && tep_not_null($GLOBALS['file_name'])) {
        
          $up_load = new upload('file_name', $upload_fs_dir);
          $file_name = $up_load->filename;
          
          if($file_name != "logo.gif"){
          unlink($upload_fs_dir."logo.gif");
          rename($upload_fs_dir.$file_name, $upload_fs_dir."logo.gif");
          }
        }

        tep_redirect(tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID']));
        break;
      case 'upload':
        $directory_writeable = true;
        if (!is_writeable($upload_fs_dir)) {
          $directory_writeable = false;
          $messageStack->add(sprintf(ERROR_DIRECTORY_NOT_WRITEABLE, $upload_fs_dir), 'error');
        }
        break;
    }
// #CP     
  }

  $gID = (isset($_GET['gID'])) ? $_GET['gID'] : 1;

  $cfg_group_query = tep_db_query("select configuration_group_key, configuration_group_title from " . TABLE_CONFIGURATION_GROUP . " where configuration_group_id = '" . (int)$gID . "'");
  $cfg_group = tep_db_fetch_array($cfg_group_query);
  
// check if the template image directory exists
  if (is_dir($upload_fs_dir)) {
    if (!is_writeable($upload_fs_dir)) $messageStack->add(ERROR_TEMPLATE_IMAGE_DIRECTORY_NOT_WRITEABLE . $upload_fs_dir, 'error');
  } else {
    $messageStack->add(ERROR_TEMPLATE_IMAGE_DIRECTORY_DOES_NOT_EXIST . $upload_fs_dir, 'error');
  }
  
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_smend //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_smend //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php if ($cfg_group['configuration_group_key']) { echo constant(strtoupper($cfg_group['configuration_group_key'])); } else { echo "&nbsp;"; } ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo CONFIG_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CONFIGURATION_TITLE; ?></td>
                <td class="dataTableHeadingContent"><?php echo CONFIG_CONST; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CONFIGURATION_VALUE; ?></td>

                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  if((int)$gID == 500){
    $configuration_query = tep_db_query("select configuration_id, configuration_title, configuration_key, configuration_value, use_function from " . TABLE_CONFIGURATION . " where configuration_group_id = '" . (int)$gID . "' order by configuration_title ASC");
  }else{
    $configuration_query = tep_db_query("select configuration_id, configuration_title, configuration_key, configuration_value, use_function from " . TABLE_CONFIGURATION . " where configuration_group_id = '" . (int)$gID . "' order by sort_order");
  }
  
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    if (tep_not_null($configuration['use_function'])) {
      $use_function = $configuration['use_function'];
      if (preg_match('/->/', $use_function)) {
        $class_method = explode('->', $use_function);
        if (!is_object(${$class_method[0]})) {
          include(DIR_WS_CLASSES . $class_method[0] . '.php');
          ${$class_method[0]} = new $class_method[0]();
        }
        $cfgValue = tep_call_function($class_method[1], $configuration['configuration_value'], ${$class_method[0]});
      } else {
        $cfgValue = tep_call_function($use_function, $configuration['configuration_value']);
      }
    } else {
      $cfgValue = $configuration['configuration_value'];
    }

    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $configuration['configuration_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cfg_extra_query = tep_db_query("select configuration_key, configuration_description, date_added, last_modified, use_function, set_function from " . TABLE_CONFIGURATION . " where configuration_id = '" . (int)$configuration['configuration_id'] . "'");
      $cfg_extra = tep_db_fetch_array($cfg_extra_query);

      $cInfo_array = array_merge($configuration, $cfg_extra);
      $cInfo = new objectInfo($cInfo_array);
    }

    if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) {

      if($cInfo->set_function == 'file_upload'){
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=upload') . '\'">' . "\n";
      } else {
      echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit') . '\'">' . "\n";
      }
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $configuration['configuration_id']) . '\'">' . "\n";
    }
?>              <td class="dataTableContent"><?php //echo $configuration['configuration_id']; ?></td>
                <td class="dataTableContent"><?php echo constant(strtoupper($configuration['configuration_key'].'_TITLE')); ?></td>
                <td class="dataTableContent"><?php //echo $configuration['configuration_key']; ?></td>
                <td class="dataTableContent">
<?php
      if (htmlspecialchars($cfgValue) == 'true') 
        echo '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $configuration['configuration_id'] . '&action=setflag&flag=0') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      elseif(htmlspecialchars($cfgValue) == 'false') 
        echo '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $configuration['configuration_id'] . '&action=setflag&flag=1') . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
      else echo htmlspecialchars($cfgValue); 
?>                
                </td>
                <td class="dataTableContent" align="right">
                  <?php //echo $cInfo->configuration_description; ?>
                <?php //if ( (isset($cInfo) && is_object($cInfo)) && ($configuration['configuration_id'] == $cInfo->configuration_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $configuration['configuration_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();   
  if(defined(strtoupper($cInfo->configuration_key .'_DESC'))) $r_desc = constant(strtoupper($cInfo->configuration_key .'_DESC'));
  else $r_desc = '';
  
  switch ($action) {
    case 'edit':
      $heading[] = array('text' => '<b>' . constant(strtoupper($cInfo->configuration_key .'_TITLE')) . '</b>');

      if ($cInfo->set_function) {
        eval('$value_field = ' . $cInfo->set_function . '"' . htmlspecialchars($cInfo->configuration_value) . '");');
      } else {
        $value_field = tep_draw_input_field('configuration_value', $cInfo->configuration_value);
      }

      /* One Page Checkout - BEGIN */
      if ($cInfo->set_function && $_GET['gID'] == 7575) {
        eval('$value_field = ' . $cInfo->set_function . '"' . $cInfo->configuration_value . '");');
      }
      /* One Page Checkout - END */
      
      $contents = array('form' => tep_draw_form('configuration', FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br><b>' . constant(strtoupper($cInfo->configuration_key .'_TITLE')) . '</b><br>'.$cInfo->configuration_description.'<br>' . $r_desc . '<br>' . $value_field);
//      $contents[] = array('text' => '<br><b>' . $cInfo->configuration_title . '</b><br>' . $cInfo->configuration_description . '<br>' . $value_field);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'upload':
      $directory_writeable = true;
      if (!is_writeable($upload_fs_dir)) {
        $directory_writeable = false;
        $messageStack->add(sprintf(ERROR_DIRECTORY_NOT_WRITEABLE, $upload_fs_dir), 'error');
      }
      
      $heading[] = array('text' => '<b>' . constant(strtoupper($cInfo->configuration_key .'_TITLE')) . '</b>');

      $contents = array('form' => tep_draw_form('file', FILENAME_CONFIGURATION, 'action=processuploads&gID='.$_GET['gID'].'&cID='.$_GET['cID'], 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $file_upload = tep_draw_file_field('file_name') . '<br>';
      $contents[] = array('text' => '<br>' . $file_upload);
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . constant(strtoupper($cInfo->configuration_key .'_TITLE')) . '</b>');

      if ($cInfo->set_function == 'file_upload') {
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=upload') . '">' . tep_image_button('button_upload.gif', IMAGE_EDIT) . '</a>'.'<p>');
        $contents[] = array('align' => 'center', 'text' => tep_image($upload_ws_dir . $cInfo->configuration_value, IMAGE_EDIT));
      } else {
        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $_GET['gID'] . '&cID=' . $cInfo->configuration_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a>');
      }
        $contents[] = array('text' => '<br>' . $r_desc);
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($cInfo->date_added));
        if (tep_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($cInfo->last_modified));
      }

      break;
  }

  if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_smend //-->
  </tr>
</table>
<!-- body_smend //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_smend //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
