<?php

require('includes/configure.php');
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
   mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die("Could not connect: " . mysql_error());
   mysql_select_db(DB_DATABASE);

   if ($_REQUEST['act']=='read'){
     $zap='SELECT pa_imgs FROM products_attributes WHERE products_attributes_id='.$_REQUEST['attr'];
     $res = mysql_query($zap);
     $row = mysql_fetch_array($res); 
     echo $row['pa_imgs'];
   //  echo explode('|',$row['pa_imgs']);
     
   } elseif ($_REQUEST['act']=='write'){
     $zap='UPDATE products_attributes SET pa_imgs="'.$_REQUEST['img'].'" WHERE products_attributes_id='.$_REQUEST['attr'];
     $res = mysql_query($zap);
     echo 'ok';
     
   } elseif ($_REQUEST['act']=='del'){
     $zap='UPDATE products_attributes SET pa_imgs="'.$_REQUEST['img'].'" WHERE products_attributes_id='.$_REQUEST['attr'];
     $res = mysql_query($zap);
     $path='../images/';
     $randintval = $_REQUEST['fn']; 
     if (file_exists($path.$randintval)) {
          unlink($path.$randintval);
       echo 'ok';
     }  else echo 'no';
   }
   mysql_close();
}
?>