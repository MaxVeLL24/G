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
    <?php    
    $sliderlist = mysql_query ("select * from slider where SliderID = ".$_GET['SliderID']);
    $row = mysql_fetch_row($sliderlist);
    
    echo '<form action="slider_editform.php?SliderID=';
    echo $row[0];
    echo '" method="POST" enctype="multipart/form-data">';
    
    echo '<div style="margin-top: 20px; margin-left: 20px;">';
        echo '<h3>Редактирование статьи слайдера</h3>';
                       // echo '<span style="font-weight: bold; font-size: 14px;">Название статьи:</span><input type="text" name="slider_articl_name" style="margin-left: 25px; width: 370px;" value="';
                       //echo $row[1];
                       // echo '"/><br /><br />';
                        echo '<span style="font-weight: bold; font-size: 14px;">Ссылка статьи:</span><input type="text" name="slider_url" style="margin-left: 39px; width: 370px;" value="';
                        echo $row[3];
                        echo '"/><br /><br />';
                        echo '<span style="font-weight: bold; font-size: 14px; display: block; float: left;">Текст статьи:</span><textarea name="slider_text" rows="15" style="margin-left: 55px; width: 370px;" class="ckeditor">';
                        echo $row[2];
                        echo '</textarea>';
                        echo '<div class="clear"></div>';
                        echo '<script type="text/javascript">
                	    var editor = CKEDITOR.replace( \'slider_text\');
                	    CKFinder.setupCKEditor( editor, \'../includes/ckfinder/\' ) ;
                        </script>';
                        echo '<br />
                        <div style="padding: 10px 0;"><span style="font-weight: bold; font-size: 14px;">Загрузка картинок:</span><span style="margin-left: 5px; font-size: 14px;">';
                        echo $row[1];
                        echo '</span><input type="checkbox" name="delete_pic" style="margin-left: 33px;" /><span style="font-size: 14px; margin-left: 2px;">Удалить картинку</span></div>
                        <input type="file" name="slider_image" /><br /><br />
                        <span style="margin-left: 0px; font-size: 16px;">Порядок вывода:</span><input type="text" name="output_order" style="text-align: center; width: 40px; margin-left: 10px;" value="'. $row[4] .'" /><br /><br />
						<div style="margin-top: 10px;"><input type="checkbox" name="delete_articles" /><span style="font-size: 14px; margin-left: 2px;">Удалить статью</span></div>
                    <div style="padding-left:195px; margin-top: 10px;">
                        <input type="submit" value="Сохранить" />
                    </div>';
    ?>
                    <?php echo '<a href="'.$_SERVER['HTTP_REFERER'].'"><span style="font-size: 14px; color: #438CBF;;"><- Назад</span></a>'; ?>
    </div>        
</form>

<!-- body_smend //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_smend //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
