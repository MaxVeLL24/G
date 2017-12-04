<?php
/*
  $Id: define_mainpage.php,v 1.2 2003/09/24 13:57:05 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  //////////////////////////////////////////////////////////////////////////
 
  define_mainpage.php Version 1.2
  
  DESCRIPTION:
  
  An extremely simple hack of define_languages.php, this file
  ALWAYS looks for the file mainpage.php in the language directory.
  This is a nice example to show how easy you can adapt existing code
  to your needs; apart from the translation and filename(s) I only had to
  add ONE SINGLE LINE to define_languages.php to make it into this.

  06/04/02 Matthijs (mattice@xs4all.nl)
  
  This file has been edit to be up to date with the current snapshot...some
  minor and slight additions where added and simple little things where fixed:
  July 29, 2002
  
  Steven Pignataro (steven_joseph_p@yahoo.com)

  /////////////////////////////////////////////////////////////////////////

  Released under the GNU General Public License
*/

  include_once __DIR__ . '/includes/application_top.php';

// This will cause it to look for 'mainpage.php'

  $_GET['filename'] = 'mainpage.php';

  switch ($_GET['action']) {
    case 'save':
      if ( ($_GET['lngdir']) && ($_GET['filename']) ) {
        if ($_GET['filename'] == $language . '.php') {
          $file = DIR_FS_CATALOG_LANGUAGES . $_GET['filename'];
        } else {
          $file = DIR_FS_CATALOG_LANGUAGES . $_GET['lngdir'] . '/' . $_GET['filename'];
        }
        if (file_exists($file)) {
          if (file_exists('bak' . $file)) {
            @unlink('bak' . $file);
          }
          @rename($file, 'bak' . $file);
          $new_file = fopen($file, 'w');
          $file_contents = stripslashes($_POST['file_contents']);
          fwrite($new_file, $file_contents, strlen($file_contents));
          fclose($new_file);
        }
        tep_redirect(tep_href_link(FILENAME_DEFINE_MAINPAGE, 'lngdir=' . $_GET['lngdir']));
      }
      break;
  }

  if (!$_GET['lngdir']) $_GET['lngdir'] = $language;

  $languages_array = array();
  $languages = tep_get_languages();
  $lng_exists = false;
  for ($i=0; $i<sizeof($languages); $i++) {
    if ($languages[$i]['directory'] == $_GET['lngdir']) $lng_exists = true;

    $languages_array[] = array('id' => $languages[$i]['directory'],
                               'text' => $languages[$i]['name']);
  }
  if (!$lng_exists) $_GET['lngdir'] = $language;
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/menu.js"></script>
<script type="text/javascript" src="../includes/ckeditor/ckeditor.js"></script>        
<script type="text/javascript" src="../includes/ckfinder/ckfinder.js"></script>
<script type="text/javascript" src="colorpicker/js/jquery.js"></script>
<script type="text/javascript" src="colorpicker/js/colorpicker.js"></script>
<script type="text/javascript" src="colorpicker/js/eye.js"></script>
<script type="text/javascript" src="colorpicker/js/utils.js"></script>
<script type="text/javascript" src="colorpicker/js/layout.js?ver=1.0.2"></script>

<link rel="stylesheet" media="screen" type="text/css" href="colorpicker/css/layout.css" />
<link rel="stylesheet" media="screen" type="text/css" href="colorpicker/css/colorpicker.css" /> 
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_smend //-->

<!-- body //--> 
  <table>
		<tr>
			<td>
				<a href="/admin/slider_add_item.php" class="link_slider">Добавить новый слайд</a>
			</td>
		</tr>
          <tr>
            <td>
                <?php 

            $slider = mysql_query ('select * from slider');
            $num_rows = mysql_num_rows($slider);
            if ($num_rows != null)
            {
            
            ?>
                <br /><span style="font-size: 16px; font-weight: bold; display: block; width: 400px; margin-left: 17px; text-align: center;">Загруженые</span>
                <?php
                    
                    $i = 0;
                    $slider = mysql_query ('select * from slider order by output_order');
                    while  ($row = mysql_fetch_row($slider))
                    {
                        $i++;
                        echo '<p>';
                       // echo '.</span>&nbsp;<span style="font-size: 14px; ">';
                       // echo $row[1];
                        echo '<span style="font-size: 14px; margin-left: 17px; display: block; width: 400px; text-align: center;"><span style="padding: 2px 5px; border-radius: 10px; background: #969696; color: white;">'.$row[4].'</span></span>';
                        echo '<div style="margin-left: 17px; font-size: 14px; width: 400px; display: block; font-family: Verdana;">';
                        echo $row[2];
                        echo '</div>';
                        echo tep_image('/admin/slider/'.$row[1], '', 400, 150, 'style="border: 1px solid #B9C5CC; border-radius: 5px; margin-top: 2px; margin-left: 17px;"');
                        echo '<br />';
                        echo '<a href="slider_edit.php?SliderID=';
                        echo $row[0];
                        echo '" style="color: #4A87BD; float: left; margin-left: 336px;">Редактировать</a>';
                        echo '</p>';
                        echo '<div class="clear"></div><br /><br />';
                    }
                }
                ?>
                
            </td>
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
