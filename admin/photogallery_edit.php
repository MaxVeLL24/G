<?php
// phpinfo();
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

<script type="text/javascript">
function question()
{
     if (checkbox_1.checked)
     {
        confirm("Хотите удалить галерею ?");
     }

}
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_smend //-->

<!-- body //-->

<div style="padding: 20px;">
<form action="photogallery_editform.php?PhotoAlbumID=<?php echo $_GET['PhotoAlbumID']; ?>" method="POST" enctype="multipart/form-data">
<?php
  $a = "SELECT * FROM photoalbum where PhotoAlbumID = ".$_GET['PhotoAlbumID'];
  $b = tep_db_query($a);
  $row = tep_db_fetch_array($b);
  $c_1 = "SELECT PhotoAlbumName, alias FROM photoalbum where PhotoAlbumID=".$_GET['PhotoAlbumID'];
  $c_2 = tep_db_query($c_1);
  $row_2 = mysql_fetch_assoc($c_2);

  $b_2 = rawurlencode($row_2['alias']);

  $c = "SELECT * FROM photo where PhotoAlbumID = ".$_GET['PhotoAlbumID'];
  $d = mysql_query($c);
?>

<h2>Название фотоальбома:</h2>
<p><input type="text" style="width: 270px;" name="photo_album_name" value="<?php echo htmlspecialchars($row['PhotoAlbumName']); ?>" ></p>
<p><input type="file" name="image[]" multiple="true" /></p>
<p><textarea name="description" style="width: 270px; height: 150px;"><?php echo $row['description']; ?></textarea> </p>
<?php

// }
//echo '<div style="width:100%;border:1px solid #000;">';


echo '<div>';
$x = 0;
while ($row_1 = tep_db_fetch_array($d))
{
  if($x == 7){
    echo '<div class="clear"></div>';
    $x = 0;
  }
    echo '<div  style="padding:5px; float:left; border:1px solid #ccc;"><label>';
    echo '<div style="height:180px;"><img src="/admin/photoalbum/'.$b_2.'/small/'.rawurlencode($row_1['PhotoPreviewURL']).'" width="150px"/></div>';
    echo '<p><input type="checkbox" name="del_image[]" value="'.$row_1['PhotoID'].'"/>удалить картинку</p>

    </label></div>';
  $x++;
}
echo '</div>';
echo '<div class="clear"><input type="checkbox" name="delete_gallery" onclick="return confirm(\'Вы точно хотите удалить галерею?\')" />&nbsp;<span>Удалить галерею</span></div>';
?>
   <div class="clear"></div>
   <div class="left">
     <input type="submit" value="Сохранить" style="margin-top: 5px; margin-left: 5px;"/>
     </form>
   </div>
</div>
<br /><?php echo '<a href="photogallery.php"><span style="font-size: 14px; color: #438CBF;;">< Назад</span></a>'; ?>
<!-- body_smend //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_smend //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
