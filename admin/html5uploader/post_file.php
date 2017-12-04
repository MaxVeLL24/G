<?php
$myfile = 'pic';

chdir('../../');
require('includes/configure.php');

$upload_dir = $_SERVER['DOCUMENT_ROOT'].'/images/';
$r_json_array = array();   
   mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die("Could not connect: " . mysql_error());
   mysql_select_db(DB_DATABASE);

   if ($_REQUEST['act']=='read'){
     if($_REQUEST['opid']=='first') {
      $zap=mysql_query('SELECT products_images FROM products WHERE products_id='.$_REQUEST['pid']);
       $row = mysql_fetch_array($zap);
       $oldarray = explode(';',$row['products_images']); 
		   if($oldarray[0]!='') echo json_encode($oldarray);
     } else {
       $zap=mysql_query('SELECT pa_imgs FROM products_attributes WHERE options_values_id='.$_REQUEST['opid'].' and products_id='.$_REQUEST['pid']);
       $row = mysql_fetch_array($zap); 
       $oldarray = explode('|',$row['pa_imgs']); 
		   if($oldarray[0]!='') echo json_encode($oldarray);
		 }
   }elseif ($_REQUEST['act']=='custom_update'){
    function rearrange_files($arr) {
        foreach($arr as $key => $all) {
            foreach($all as $i => $val) {
                $new_array[$i][$key] = $val;    
            }    
        }
        return $new_array;
    }

    $files_array = rearrange_files($_FILES[$myfile]);
    foreach ($files_array as $_file) {
      // var_dump($_file);
      if($_file['error'] == 0){

      
      $uploadfile = sanit_fname($_file['name']); // проверка на кривые символы
      $tmpfile = $_file['tmp_name'];
      $r_name = check_name($upload_dir,$uploadfile,$tmpfile);
      $file = $r_name;
      $orig_directory = $upload_dir;      //Папка для полноразмерных изображений 
      $thumb_directory = $upload_dir.'thumb';       //Папка для миниатюр 
      
              //Проверяем, что папка открыта и в ней есть файлы
       
      $allowed_types=array('jpg','jpeg','gif','png'); // Список обрабатываемых расширений
      $file_parts=array();
      $ext='';
      $title='';
      $i=0;

      $file = $r_name;

          /* Пропускаем системные файлы: */
          if($file=='.' || $file == '..') continue;
       
          $file_parts = explode('.',$file);     //Разделяем имя файла на части 
          $ext = strtolower(array_pop($file_parts));
       
          /* Используем имя файла (без расширения) как заголовок изображения: */
          $title = implode('.',$file_parts);
          $title = htmlspecialchars($title);
          
          

          /* Если расширение входит в список обрабатываемых: */
          if(in_array($ext,$allowed_types)) {
              resize_image($_REQUEST['img_w'], $_REQUEST['img_h'], $thumb_directory, $file, $orig_directory . $file); // thumb от загруженной кратинки
          }
          
          if($_GET['opid']=='first') {
           $zap=mysql_query('SELECT products_images FROM products WHERE products_id='.$_GET['pid']);
           $row = mysql_fetch_array($zap);
           
           if($row['products_images']!='') {
             $oldnames = $row['products_images'];
             $oldarray = explode(';',$row['products_images']);
             if (!in_array($file, $oldarray)) {
               $oldnames .= ';'.$file;
             }
           } else $oldnames = $file; 
           $zap='UPDATE products SET products_images="'.$oldnames.'" WHERE products_id='.$_GET['pid'];
           $res = mysql_query($zap);
           $r_json_array['products_images'] = $oldnames;

           $r_json_array['status'] = 'go';
           $r_json_array['current'] = $file;
           echo json_encode($r_json_array);
          } else {
             $zap=mysql_query('SELECT pa_imgs FROM products_attributes WHERE options_values_id='.$_GET['opid'].' and products_id='.$_GET['pid']);
             $row = mysql_fetch_array($zap);
           
           if($row['pa_imgs']!='') {
             $oldnames = $row['pa_imgs'];
             $oldarray = explode('|',$row['pa_imgs']);
             if (!in_array($file, $oldarray)) {
               $oldnames .= '|'.$file;
             }
           } else $oldnames = $file; 
           $zap='UPDATE products_attributes SET pa_imgs="'.$oldnames.'" WHERE options_values_id='.$_GET['opid'].' and products_id='.$_GET['pid'];
           $res = mysql_query($zap);
           $r_json_array['products_images'] = $oldnames;

           $r_json_array['status'] = 'go';
           $r_json_array['current'] = $file;
           echo json_encode($r_json_array);
          } 
      }
      echo '<meta http-equiv="refresh" content="0; url=/admin/products.php?pID='.$_REQUEST['pid'].'&action=new_product#images">'; 
    }

    
   } elseif ($_REQUEST['act']=='update'){

$uploadfile = sanit_fname($_FILES[$myfile]['name']); // проверка на кривые символы
$tmpfile = $_FILES[$myfile]['tmp_name'];

$r_name = check_name($upload_dir,$uploadfile,$tmpfile);
$file = $r_name;

$orig_directory = $upload_dir;    	//Папка для полноразмерных изображений 
$thumb_directory = $upload_dir.'thumb';      	//Папка для миниатюр 
 
				//Проверяем, что папка открыта и в ней есть файлы
 
$allowed_types=array('jpg','jpeg','gif','png'); // Список обрабатываемых расширений
$file_parts=array();
$ext='';
$title='';
$i=0;

$file = $r_name;

    /* Пропускаем системные файлы: */
    if($file=='.' || $file == '..') continue;
 
    $file_parts = explode('.',$file);    	//Разделяем имя файла на части 
    $ext = strtolower(array_pop($file_parts));
 
    /* Используем имя файла (без расширения) как заголовок изображения: */
    $title = implode('.',$file_parts);
    $title = htmlspecialchars($title);
 
    /* Если расширение входит в список обрабатываемых: */
    if(in_array($ext,$allowed_types)) {
        resize_image($_REQUEST['img_w'], $_REQUEST['img_h'], $thumb_directory, $file, $orig_directory . $file); // thumb от загруженной кратинки
		}

		if($_GET['opid']=='first') {
     $zap=mysql_query('SELECT products_images FROM products WHERE products_id='.$_GET['pid']);
     $row = mysql_fetch_array($zap);
     
     if($row['products_images']!='') {
		   $oldnames = $row['products_images'];
		   $oldarray = explode(';',$row['products_images']);
       if (!in_array($file, $oldarray)) {
         $oldnames .= ';'.$file;
       }
		 } else $oldnames = $file; 
     $zap='UPDATE products SET products_images="'.$oldnames.'" WHERE products_id='.$_GET['pid'];
     $res = mysql_query($zap);
     $r_json_array['products_images'] = $oldnames;

     $r_json_array['status'] = 'go';
     $r_json_array['current'] = $file;
     echo json_encode($r_json_array);
    } else {
       $zap=mysql_query('SELECT pa_imgs FROM products_attributes WHERE options_values_id='.$_GET['opid'].' and products_id='.$_GET['pid']);
       $row = mysql_fetch_array($zap);
     
     if($row['pa_imgs']!='') {
		   $oldnames = $row['pa_imgs'];
		   $oldarray = explode('|',$row['pa_imgs']);
       if (!in_array($file, $oldarray)) {
         $oldnames .= '|'.$file;
       }
		 } else $oldnames = $file; 
     $zap='UPDATE products_attributes SET pa_imgs="'.$oldnames.'" WHERE options_values_id='.$_GET['opid'].' and products_id='.$_GET['pid'];
     $res = mysql_query($zap);
     $r_json_array['products_images'] = $oldnames;

     $r_json_array['status'] = 'go';
     $r_json_array['current'] = $file;
     echo json_encode($r_json_array);
		} 

    
   } elseif ($_REQUEST['act']=='del'){
    if($_REQUEST['opid']=='first' or $_REQUEST['opid']=='' or $_REQUEST['opid']=='undefined') {
     $zap=mysql_query('SELECT products_images FROM products WHERE products_id='.$_REQUEST['pid']);
     $row = mysql_fetch_array($zap);
     
     if($row['products_images']!='') {
		   $oldnames = $row['products_images'];
		   $oldarray = explode(';',$row['products_images']);
       if (in_array($_REQUEST['img'], $oldarray)) {
         $key = array_search($_REQUEST['img'], $oldarray); 
         unset($oldarray[$key]); 
       }
       $oldnames = implode(';',$oldarray);
		 } 
     $zap='UPDATE products SET products_images="'.$oldnames.'" WHERE products_id='.$_REQUEST['pid'];
     $res = mysql_query($zap);
    } else {
     $zap=mysql_query('SELECT pa_imgs FROM products_attributes WHERE options_values_id='.$_REQUEST['opid'].' and products_id='.$_REQUEST['pid']);
     $row = mysql_fetch_array($zap);
     
     if($row['pa_imgs']!='') {
		   $oldnames = $row['pa_imgs'];
		   $oldarray = explode('|',$row['pa_imgs']);
       if (in_array($_REQUEST['img'], $oldarray)) {
         $key = array_search($_REQUEST['img'], $oldarray); 
         unset($oldarray[$key]); 
       }
       $oldnames = implode('|',$oldarray);
		 } 
		 $zap='UPDATE products_attributes SET pa_imgs="'.$oldnames.'" WHERE options_values_id='.$_REQUEST['opid'].' and products_id='.$_REQUEST['pid'];
     $res = mysql_query($zap);
		} 
     $r_json_array['status'] = $key;
     echo json_encode($r_json_array);
     
		 $file2del_lrg = $_SERVER['DOCUMENT_ROOT'].'/images/'.$_REQUEST['img'];
		 $file2del_sma = $_SERVER['DOCUMENT_ROOT'].'/images/thumb'.$_REQUEST['img'];

     if (file_exists($file2del_lrg) or file_exists($file2del_sma)) {
          unlink($file2del_lrg);
          unlink($file2del_sma);
     }
     
   } elseif ($_REQUEST['act']=='sort'){

     if($_REQUEST['opid']=='first' or $_REQUEST['opid']=='' or $_REQUEST['opid']=='undefined') {
		   $oldnames = str_replace(',', ';', $_REQUEST['order']);
			 $zap='UPDATE products SET products_images="'.$oldnames.'" WHERE products_id='.$_REQUEST['pid'];
       $res = mysql_query($zap);
     } else {
       $oldnames = str_replace(',', '|', $_REQUEST['order']);
		   $zap='UPDATE products_attributes SET pa_imgs="'.$oldnames.'" WHERE options_values_id='.$_REQUEST['opid'].' and products_id='.$_REQUEST['pid'];
       $res = mysql_query($zap);
		 }
     $r_json_array['status'] = 'ok';
     $r_json_array['opid'] = $_REQUEST['opid'];
     echo json_encode($r_json_array);
     
  } elseif ($_REQUEST['act']=='crop') { // -----------ОБРЕЗАНИЕ-----------
    $tmp_array = explode('?',$_REQUEST['fn']);
    $_REQUEST['fn'] = $tmp_array[0];
    
    $_REQUEST['v_w'] = (int)$_REQUEST['v_w'];
    $_REQUEST['v_h'] = (int)$_REQUEST['v_h'];
    $_REQUEST['v_x'] = (int)$_REQUEST['v_x'];
    $_REQUEST['v_y'] = (int)$_REQUEST['v_y'];
    
		$source = $upload_dir.$_REQUEST['fn'];  
    
		$stype = explode(".", $source);
    $stype = $stype[count($stype)-1]; 
 
    switch($stype) {
      case 'gif':$img_r = imagecreatefromgif($source);break;
      case 'jpg':$img_r = imagecreatefromjpeg($source);break;
      case 'jpeg':$img_r = imagecreatefromjpeg($source); break;
      case 'png':$img_r = imagecreatefrompng($source);break;
    }

  	$dst_r = ImageCreateTrueColor( $_REQUEST['v_w'], $_REQUEST['v_h'] );  
        imagealphablending($dst_r, false); // красивая прозрачность для временной картинки
        imagesavealpha($dst_r, true);
        $background = imagecolorallocate($dst_r, 0, 0, 0);
        ImageColorTransparent($dst_r, $background);   	

  	imagecopyresampled($dst_r,$img_r,0,0,$_REQUEST['v_x'],$_REQUEST['v_y'],
  	$_REQUEST['v_w'],$_REQUEST['v_h'],$_REQUEST['v_w'],$_REQUEST['v_h']);

     $r_json_array['status'] = $_REQUEST['v_x'];
     echo json_encode($r_json_array);

    switch($stype) {
      case 'gif':imagegif($dst_r,$source);break;
      case 'jpg':imagejpeg($dst_r,$source,80);break;
      case 'jpeg':imagejpeg($dst_r,$source,80);break;
      case 'png':imagepng($dst_r,$source);break;
    }

	  $thumb_name = 'thumb'.$_REQUEST['fn'];
    
    resize_image($_REQUEST['img_w'], $_REQUEST['img_h'], $upload_dir, $thumb_name, $source); // thumb от обрезанной кратинки

  }
  
   mysql_close();


// Helper functions

function exit_status($str){
  $r_json_array['status'] = $str;
	echo json_encode($r_json_array);
	exit;
}

function get_extension($file_name){
	$ext = explode('.', $file_name);
	$ext = array_pop($ext);
	return strtolower($ext);
}

function check_name($uploaddir, $uploadfile,$tmpfile) {

if (file_exists($uploaddir.$uploadfile)) {
      $rexplode = explode('.', $uploaddir.$uploadfile); // разрезаем имя по точкам
      $ri = count($rexplode) - 1; // извращаемся на случай, если в названии файла были еще точки
      $rextension = $rexplode[$ri]; // расширение файла нашли
      $rlen = strlen($rextension)+1;
      $new_name = substr($uploadfile, 0, -$rlen);        
      $picture = $new_name.'_.'.$rextension;
       
      return check_name($uploaddir, $picture,$tmpfile);
    } else {
        $stype = explode(".", $uploadfile);
        $stype = $stype[count($stype)-1]; 
      if($stype=='gif' or $stype=='jpg' or $stype=='jpeg' or $stype=='png') { 
			  resize_image(1000, 1000, $uploaddir, $uploadfile, $tmpfile);
      } else {
			  move_uploaded_file($tmpfile, $uploaddir.$uploadfile);
			  chmod($uploaddir.$uploadfile, 0777);
      }
			
      return $uploadfile;
    }
}

// ---------------перевод с кирилицы и всякие проверки-------------------------
function sanit_fname($string) {
			$cyrillic = array("Q","W","E","R","T","Y","U","I","O","P","A","S","D","F","G","H","J","K","L","Z","X","C","V","B","N","M","ж", "ё", "й","ю", "ь","ч", "щ", "ц","у","к","е","н","г","ш", "з","х","ъ","ф","ы","в","а","п","р","о","л","д","э","я","с","м","и","т","б","Ё","Й","Ю","Ч","Ь","Щ","Ц","У","К","Е","Н","Г","Ш","З","Х","Ъ","Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э","Я","С","М","И","Т","Б");
			$translit = array("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m","zh","yo","i","yu","'","ch","sh","c","u","k","e","n","g","sh","z","h","'",  "f",  "y",  "v",  "a",  "p",  "r",  "o",  "l",  "d",  "yе", "jа", "s",  "m",  "i",  "t",  "b",  "yo", "i",  "yu", "ch", "'",  "sh", "c",  "u",  "k",  "e",  "n",  "g",  "sh", "z",  "h",  "'",  "f",  "y",  "v",  "a",  "p",  "r",  "o",  "l",  "d",  "zh", "ye", "ja", "s",  "m",  "i",  "t",  "b");
			$string = str_replace($cyrillic, $translit, $string);
			$string = preg_replace(array('@\s@','@[^a-z0-9\-_\.]+@',"@_+\-+@","@\-+_+@","@\-\-+@","@__+@"), array('_', '', "-","-","-","_"), $string);
			$string = mb_strtolower($string);
			$string = preg_replace('/ /','_',$string); // пробел
			$string = preg_replace('#\(?(\w)\)?#s','$1',$string); // замена скобок
			return($string);
} 

function resize_image($new_w, $new_h, $uploaddir, $uploadfile, $source) {
    
        $stype = explode(".", $uploadfile);
        $stype = $stype[count($stype)-1]; 
		    $dest = $uploaddir . $uploadfile;
 
        $size = getimagesize($source);
        $w = $size[0];
        $h = $size[1];
        
			if($new_w > $w and $new_h > $h) move_uploaded_file($source, $dest); // если размер картинки меньше чем тот что мы задали то НЕ растягиваем
			else {
			
        switch($stype) {
            case 'gif':$simg = imagecreatefromgif($source);break;
            case 'jpg':$simg = imagecreatefromjpeg($source);break;
            case 'jpeg':$simg = imagecreatefromjpeg($source);break;
            case 'png':$simg = imagecreatefrompng($source);break;
        }
        
        if($w > $h) {
            $r_height = $new_w*$h/$w;
            $r_width = $new_w;
        } else {
            $r_height = $new_h;
            $r_width = $new_h*$w/$h;
        } 
        
        $dimg = imagecreatetruecolor($r_width, $r_height);
        imagealphablending($dimg, false); // красивая прозрачность для временной картинки
        imagesavealpha($dimg, true);
        $background = imagecolorallocate($dimg, 0, 0, 0);
        ImageColorTransparent($dimg, $background); 		
        imagecopyresampled($dimg,$simg,0,0,0,0,$r_width,$r_height,$w,$h);										        
                
        switch($stype) {
          case 'gif':imagegif($dimg,$dest);break;
          case 'jpg':imagejpeg($dimg,$dest,80);break;
          case 'jpeg':imagejpeg($dimg,$dest,80);break;
          case 'png':imagepng($dimg,$dest);break;
        }
      }  
			 	
       chmod($uploaddir.$uploadfile, 0777);
} 
?>