<?php
require('includes/configure.php');
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
  mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die("Could not connect: " . mysql_error());
  mysql_select_db(DB_DATABASE);
  if ($_REQUEST['act']=='read') { // -----------ЧТЕНИЕ-----------
    $zap='SELECT products_images FROM products WHERE products_id='.$_REQUEST['pid'].';';
    $res = mysql_query($zap);
    $row = mysql_fetch_array($res);
    echo json_encode(explode('|',$row['products_images']));

  } elseif ($_REQUEST['act']=='write') {  // -----------ЗАПИСЬ-----------
    $zap='UPDATE products SET products_images = "'.$_REQUEST['img'].'" WHERE products_id='.$_REQUEST['pid'].';';
    $res = mysql_query($zap);
    echo json_encode('ok');
    
  } elseif ($_REQUEST['act']=='del') {  // -----------УДАЛЕНИЕ-----------
    $zap='UPDATE products SET products_images = "'.$_REQUEST['img'].'" WHERE products_id='.$_REQUEST['pid'].';';
    $res = mysql_query($zap);
    $path='../images/';
    $randintval = $_REQUEST['fn']; 
    if (file_exists($path.$randintval)) {
         unlink($path.$randintval);
         unlink($path."thumb".$randintval);
      echo json_encode('ok');
    } else echo json_encode('no');
    
  } elseif ($_REQUEST['act']=='crop') { // -----------ОБРЕЗАНИЕ-----------
    $tmp_array = explode('?',$_REQUEST['fn']);
    $_REQUEST['fn'] = $tmp_array[0];
    
    $_REQUEST['v_w'] = (int)$_REQUEST['v_w'];
    $_REQUEST['v_h'] = (int)$_REQUEST['v_h'];
    $_REQUEST['v_x'] = (int)$_REQUEST['v_x'];
    $_REQUEST['v_y'] = (int)$_REQUEST['v_y'];
           
  	$img_r = imagecreatefromjpeg($_REQUEST['fn']);
  	$dst_r = ImageCreateTrueColor( $_REQUEST['v_w'], $_REQUEST['v_h'] );

  	imagecopyresampled($dst_r,$img_r,0,0,$_REQUEST['v_x'],$_REQUEST['v_y'],
  	$_REQUEST['v_w'],$_REQUEST['v_h'],$_REQUEST['v_w'],$_REQUEST['v_h']);

  	imagejpeg($dst_r,$_REQUEST['fn'],80);
  	
  	// -----------Обрезание миниатюры
  	$nw = 80;
    $nh = 80;
//    $proporcii = $_REQUEST['v_w'] / $nw;
//    $thumb_w = $_REQUEST['v_w'] / $proporcii;
//    $thumb_h = $_REQUEST['v_h'] / $proporcii;
    
    if($_REQUEST['v_h']>$_REQUEST['v_w']) {
      $thumb_h = $nh; 
      $thumb_w = $nh*$_REQUEST['v_w']/$_REQUEST['v_h']; 
    } else {
      $thumb_w = $nw; 
      $thumb_h = $nw*$_REQUEST['v_h']/$_REQUEST['v_w']; 
    }
    
  	$thumb_dst = ImageCreateTrueColor($thumb_w, $thumb_h);
  	imagecopyresampled($thumb_dst,$dst_r,0,0,0,0,
  	$thumb_w,$thumb_h,$_REQUEST['v_w'],$_REQUEST['v_h']);
  	
  	$thumb_array = explode('/',$_REQUEST['fn']);
  	$thumb_path = '';
  	for($i=0;$i<count($thumb_array)-1;$i++) {
      $thumb_path .= $thumb_array[$i].'/';  
    }
    $thumb_name = $thumb_array[count($thumb_array)-1];
  	imagejpeg($thumb_dst,$thumb_path.'thumb'.$thumb_name,80);
  	
  //	if (file_exists($_REQUEST['fn'])) echo $thumb_path.'thumb'.$thumb_name;
  }
  mysql_close();
}
?>