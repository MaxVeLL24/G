<?php

require('includes/configure.php');
if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
   mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die("Could not connect: " . mysql_error());
   mysql_select_db(DB_DATABASE);

   if ($_REQUEST['act']=='read'){
     $zap='SELECT products_options_values_image FROM products_options_values WHERE products_options_values_id='.$_REQUEST['attr'];
     $res = mysql_query($zap);
     $row = mysql_fetch_array($res); 
     echo $row['products_options_values_image'];
     
   } elseif ($_REQUEST['act']=='write'){
     $zap='UPDATE products_options_values SET products_options_values_image="'.$_REQUEST['img'].'" WHERE products_options_values_id='.$_REQUEST['attr'];
     $res = mysql_query($zap);
     echo 'ok';
     
   } elseif ($_REQUEST['act']=='del'){
     $zap='UPDATE products_options_values SET products_options_values_image="" WHERE products_options_values_id='.$_REQUEST['attr'];
     $res = mysql_query($zap);
     $path='../images/';
     $randintval = $_REQUEST['fn']; 
     if (file_exists($path.$randintval)) {
          unlink($path.$randintval);
          unlink($path."thumb".$randintval);
       echo 'ok';
     }  else echo 'no';
   }
   mysql_close();
//   die; 
}
?>