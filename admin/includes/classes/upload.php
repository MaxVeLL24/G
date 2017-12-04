<?php
/*
  $Id: upload.php,v 1.2 2003/06/20 00:18:30 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class upload {
    var $file, $filename, $destination, $permissions, $extensions, $tmp_filename, $message_location;

    function upload($file = '', $destination = '', $permissions = '777', $extensions = array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'swf', 'sql', 'pdf')) {
      $this->set_file($file);
      $this->set_destination($destination);
      $this->set_permissions($permissions);
      $this->set_extensions($extensions);

      $this->set_output_messages('direct');

      if (tep_not_null($this->file) && tep_not_null($this->destination)) {
        $this->set_output_messages('session');
      if ($both='false') {
        if ( ($this->parse() == true) && ($this->save() == true) ) {
          return true;
        } else {
// self destruct
while(list($key,) = each($this)) {
  $this->$key = null;
}

          return false;
        }
       }
       else {
        if ( ($this->parse() == true) && ($this->save_both() == true) ) {
          return true;
        } else {
// self destruct
while(list($key,) = each($this)) {
  $this->$key = null;
}

          return false;
        }       
       } 
      }
    }


    function parse() {
      global $messageStack;

      if (isset($_FILES[$this->file])) {
        $file = array('name' => $_FILES[$this->file]['name'],
                      'type' => $_FILES[$this->file]['type'],
                      'size' => $_FILES[$this->file]['size'],
                      'tmp_name' => $_FILES[$this->file]['tmp_name']);
      } elseif (isset($GLOBALS['HTTP_POST_FILES'][$this->file])) {
        global $HTTP_POST_FILES;

        $file = array('name' => $HTTP_POST_FILES[$this->file]['name'],
                      'type' => $HTTP_POST_FILES[$this->file]['type'],
                      'size' => $HTTP_POST_FILES[$this->file]['size'],
                      'tmp_name' => $HTTP_POST_FILES[$this->file]['tmp_name']);
      } else {
        $file = array('name' => (isset($GLOBALS[$this->file . '_name']) ? $GLOBALS[$this->file . '_name'] : ''),
                      'type' => (isset($GLOBALS[$this->file . '_type']) ? $GLOBALS[$this->file . '_type'] : ''),
                      'size' => (isset($GLOBALS[$this->file . '_size']) ? $GLOBALS[$this->file . '_size'] : ''),
                      'tmp_name' => (isset($GLOBALS[$this->file]) ? $GLOBALS[$this->file] : ''));
      }

      if ( tep_not_null($file['tmp_name']) && ($file['tmp_name'] != 'none') && is_uploaded_file($file['tmp_name']) ) {
        if (sizeof($this->extensions) > 0) {
          if (!in_array(strtolower(substr($file['name'], strrpos($file['name'], '.')+1)), $this->extensions)) {
            if ($this->message_location == 'direct') {
              $messageStack->add(ERROR_FILETYPE_NOT_ALLOWED, 'error');
            } else {
              $messageStack->add_session(ERROR_FILETYPE_NOT_ALLOWED, 'error');
            }

            return false;
          }
        }

        $this->set_file($file);
        $this->set_filename($file['name']);
        $this->set_tmp_filename($file['tmp_name']);

        return $this->check_destination();
      } else {


        return false;
      }
    }

    function save($filename,$r_wi='',$r_he='',$proporcii='true') {
      global $messageStack;

      if (!$overwrite and file_exists($this->destination . $this->filename)) {
            $messageStack->add_session(TEXT_IMAGE_OVERWRITE_WARNING . $this->filename, 'caution');
            return true;
      } else {

      if (substr($this->destination, -1) != '/') $this->destination .= '/';

      if (move_uploaded_file($this->file['tmp_name'], $this->destination . $this->filename)) {
        chmod($this->destination . $this->filename, $this->permissions);

        if ($this->message_location == 'direct') {
          $messageStack->add(SUCCESS_FILE_SAVED_SUCCESSFULLY, 'success');
        } else {
          $messageStack->add_session(SUCCESS_FILE_SAVED_SUCCESSFULLY, 'success');
        }

$img = $this->destination . $this->filename;


$jpg_quality = 90; // Yuzde olarak JPG kalitesi
$constrain = 1;  // 1 veya 0 : en veya boy her ikiside verilmi?se 1 ayarlay?n, yoksa en veya boydan daralma olur
if($r_wi==''&&$r_he==''){
  $fw = 150;  // k???lt?lecek resmin eni
  $fh = 150;  // k???lt?lecek resmin yuksekligi
}
else {
  $fw = $r_wi;  
  $fh = $r_he; 
}
  $w = $fw;  
  $h = $fh; 
$x = @getimagesize($img); // imaj?n boyutunu ve tipini bulal?m

$sw = $x[0]; // yuklenen resmin eni
$sh = $x[1]; // yuklenen resmin boyu

if ($sw > $w || $sh > $h) { // e?er y?klenen resmin en veya boyu istedi?imiz boyuttan b?y?kse i?leme devm edelim
	if (isset ($w) AND !isset ($h)) { // sadece y?kseklik de?eri verilmi?se
		$h = (100 / ($sw / $w)) * .01;
		$h = @round ($sh * $h);
	} elseif (isset ($h) AND !isset ($w)) { // sadece en de?eri verilmi?se
		$w = (100 / ($sh / $h)) * .01;
		$w = @round ($sw * $w);
	} elseif (isset ($h) AND isset ($w) AND isset ($constrain)) {
		// $constrain de?eri ve olu?turulacak resmin boyutunun en ve boy de?eri beraber verilmi?se hangisi uygun ise o boyuta g?re ayarlan?r
		$hx = (100 / ($sw / $w)) * .01;
		$hx = @round ($sh * $hx);

		$wx = (100 / ($sh / $h)) * .01;
		$wx = @round ($sw * $wx);

		if ($hx < $h) {
			$h = (100 / ($sw / $w)) * .01;
			$h = @round ($sh * $h);
		} else {
			$w = (100 / ($sh / $h)) * .01;
			$w = @round ($sw * $w);
		}
	}

	if (function_exists( 'exif_imagetype' )) $img_type = exif_imagetype($img); // bu fonksiyon varm??
	else $img_type = $x[2];

	switch ($img_type) {
	   case IMAGETYPE_GIF  : $im = @ImageCreateFromGIF ($img); break;
	   case IMAGETYPE_JPEG : $im = @ImageCreateFromJPEG ($img); break;
	   case IMAGETYPE_PNG  : $im = @ImageCreateFromPNG ($img); break;
//	   case IMAGETYPE_WBMP : $im = @ImageCreateFromwbmp ($img); break;
	   default : $im = false; // E?er imaj JPEG, PNG, wBMP veya GIF de?ilse
	   }

	if ($im) {
      if($proporcii=='true') {
		$thumb = @ImageCreateTrueColor ($w, $h);
//---------------------raid--------------------------//
		$thumb2 = @ImageCreateTrueColor ($fw, $fh);

        $flol = imagecolorallocate($thumb2, 255, 255, 255); 
		@imageFilledRectangle ($thumb2,0,0,$fw, $fh,$flol);

		@ImageCopyResampled ($thumb, $im, 0, 0, 0, 0, $w, $h, $sw, $sh);
		@ImageCopyResampled ($thumb2, $thumb, ($fw-$w)/2, ($fh-$h)/2, 0, 0, $w, $h, $w, $h);
		@ImageJPEG ($thumb2, $img, $jpg_quality); // boyutland?r?lm?? imaj? olu?tural?m
		@imagedestroy($thumb2);
        }
      else
 {
		$thumb = @ImageCreateTrueColor ($w, $h);
		@ImageCopyResampled ($thumb, $im, 0, 0, 0, 0, $w, $h, $sw, $sh);
		@ImageJPEG ($thumb, $img, $jpg_quality); // boyutland?r?lm?? imaj? olu?tural?m
		@imagedestroy($thumb);
//---------------------raid---END----------------------//
        }
	}
}



        return true;
      } else {
        if ($this->message_location == 'direct') {
          $messageStack->add(ERROR_FILE_NOT_SAVED, 'error');
        } else {
          $messageStack->add_session(ERROR_FILE_NOT_SAVED, 'error');
        }

        return false;
      }
    }
}
    function set_file($file) {
      $this->file = $file;
    }

    function set_destination($destination) {
      $this->destination = $destination;
    }

    function set_permissions($permissions) {
      $this->permissions = octdec($permissions);
    }

    function set_filename($filename) {
      $this->filename = $this->sanit_fname($filename);
    }

    function set_tmp_filename($filename) {
      $this->tmp_filename = $filename;
    }

    function set_extensions($extensions) {
      if (tep_not_null($extensions)) {
        if (is_array($extensions)) {
          $this->extensions = $extensions;
        } else {
          $this->extensions = array($extensions);
        }
      } else {
        $this->extensions = array();
      }
    }

    function check_destination() {
      global $messageStack;

      if (!is_writeable($this->destination)) {
        if (is_dir($this->destination)) {
          if ($this->message_location == 'direct') {
            $messageStack->add(sprintf(ERROR_DESTINATION_NOT_WRITEABLE, $this->destination), 'error');
          } else {
            $messageStack->add_session(sprintf(ERROR_DESTINATION_NOT_WRITEABLE, $this->destination), 'error');
          }
        } else {
          if ($this->message_location == 'direct') {
            $messageStack->add(sprintf(ERROR_DESTINATION_DOES_NOT_EXIST, $this->destination), 'error');
          } else {
            $messageStack->add_session(sprintf(ERROR_DESTINATION_DOES_NOT_EXIST, $this->destination), 'error');
          }
        }

        return false;
      } else {
        return true;
      }
    }

    function set_output_messages($location) {
      switch ($location) {
        case 'session':
          $this->message_location = 'session';
          break;
        case 'direct':
        default:
          $this->message_location = 'direct';
          break;
      }
    }
    
    function sanit_fname($string) {
			$cyrillic = array("Q","W","E","R","T","Y","U","I","O","P","A","S","D","F","G","H","J","K","L","Z","X","C","V","B","N","M","ж", "ё", "й","ю", "ь","ч", "щ", "ц","у","к","е","н","г","ш", "з","х","ъ","ф","ы","в","а","п","р","о","л","д","э","я","с","м","и","т","б","Ё","Й","Ю","Ч","Ь","Щ","Ц","У","К","Е","Н","Г","Ш","З","Х","Ъ","Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э","Я","С","М","И","Т","Б");
			$translit = array("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m","zh","yo","i","yu","'","ch","sh","c","u","k","e","n","g","sh","z","h","'",  "f",  "y",  "v",  "a",  "p",  "r",  "o",  "l",  "d",  "yе", "jа", "s",  "m",  "i",  "t",  "b",  "yo", "i",  "yu", "ch", "'",  "sh", "c",  "u",  "k",  "e",  "n",  "g",  "sh", "z",  "h",  "'",  "f",  "y",  "v",  "a",  "p",  "r",  "o",  "l",  "d",  "zh", "ye", "ja", "s",  "m",  "i",  "t",  "b");
			$string = str_replace($cyrillic, $translit, $string);
			$string = preg_replace(array('@\s@','@[^a-z0-9\-_\.]+@',"@_+\-+@","@\-+_+@","@\-\-+@","@__+@"), array('_', '', "-","-","-","_"), $string);
			$string = strtolower($string);
			return($string);
    } 
    
       
 // raid: ----------------------------------------------   
    function upload_both($file = '', $destination = '', $permissions = '777', $extensions = array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'swf', 'sql', 'pdf')) {
      $this->set_file($file);
      $this->set_destination($destination);
      $this->set_permissions($permissions);
      $this->set_extensions($extensions);

      $this->set_output_messages('direct');

      if (tep_not_null($this->file) && tep_not_null($this->destination)) {
        $this->set_output_messages('session');

        if ( ($this->parse() == true) && ($this->save_both() == true) ) {
          return true;
        } else {
// self destruct

while(list($key,) = each($this)) {
  $this->$key = null;
}

          return false;
        }
      }
    }
    function save_both($filename,$type='sma_',$r_wi_sma='',$r_he_sma='',$proporcii_sma='true',$r_wi_med='',$r_he_med='',$proporcii_med='false') {
  // types(raid): sma_, med_, both_ 

      global $messageStack;

      if (!$overwrite and file_exists($this->destination . $this->filename)) {
            $messageStack->add_session(TEXT_IMAGE_OVERWRITE_WARNING . $this->filename, 'caution');
            return true;
      } else {

      if (substr($this->destination, -1) != '/') $this->destination .= '/';

      if(($type=='both_')or( $type=='sma_')) $sma = 'sma_';
      else $sma = 'med_';  

      if (move_uploaded_file($this->file['tmp_name'], $this->destination . $sma.$this->filename)) {
        chmod($this->destination . $sma.$this->filename, $this->permissions);
      if($type=='both_') {  
        copy($this->destination . 'sma_'.$this->filename, $this->destination . 'med_'.$this->filename);
        chmod($this->destination . 'med_'.$this->filename, $this->permissions);
      }  
  //      rename($this->destination . $this->filename, $this->destination . 'sma_'.$this->filename);
  //      chmod($this->destination . 'med_'.$this->filename, $this->permissions);

        if ($this->message_location == 'direct') {
          $messageStack->add(SUCCESS_FILE_SAVED_SUCCESSFULLY, 'success');
        } else {
          $messageStack->add_session(SUCCESS_FILE_SAVED_SUCCESSFULLY, 'success');
        }

if(($type=='med_')or($type=='both_')) {
$img_med = $this->destination . 'med_'.$this->filename;

$jpg_quality = 90; 
$constrain = 1;  
if($r_wi_med==''&&$r_he_med==''){
  $fw_med = 300;
  $fh_med = 300;  
}
else {
  $fw_med = $r_wi_med;  
  $fh_med = $r_he_med; 
}
  $w_med = $fw_med;  
  $h_med = $fh_med; 
$x_med = @getimagesize($img_med); 

$sw_med = $x_med[0];$sh_med = $x_med[1]; 

if ($sw_med >= $w_med || $sh_med >= $h_med || $sw_med <= $w_med || $sh_med <= $h_med) { 
	if (isset ($w_med) AND !isset ($h_med)) { 
		$h_med = (100 / ($sw_med / $w_med)) * .01;
		$h_med = @round ($sh_med * $h_med);
	} elseif (isset ($h_med) AND !isset ($w_med)) { 
		$w_med = (100 / ($sh_med / $h_med)) * .01;
		$w_med = @round ($sw_med * $w_med);
	} elseif (isset ($h_med) AND isset ($w_med) AND isset ($constrain)) {
		
		$hx_med = (100 / ($sw_med / $w_med)) * .01;
		$hx_med = @round ($sh_med * $hx_med);

		$wx_med = (100 / ($sh_med / $h_med)) * .01;
		$wx_med = @round ($sw_med * $wx_med);

		if ($hx_med < $h_med) {
			$h_med = (100 / ($sw_med / $w_med)) * .01;
			$h_med = @round ($sh_med * $h_med);
		} else {
			$w_med = (100 / ($sh_med / $h_med)) * .01;
			$w_med = @round ($sw_med * $w_med);
		}
	}
}
	if (function_exists( 'exif_imagetype' )) $img_type_med = exif_imagetype($img_med); 
	else $img_type_med = $x_med[2];

	switch ($img_type_med) {
	   case IMAGETYPE_GIF  : $im_med = @ImageCreateFromGIF ($img_med); break;
	   case IMAGETYPE_JPEG : $im_med = @ImageCreateFromJPEG ($img_med); break;
	   case IMAGETYPE_PNG  : $im_med = @ImageCreateFromPNG ($img_med); break;
//	   case IMAGETYPE_WBMP : $im = @ImageCreateFromwbmp ($img); break;
	   default : $im_med = false; 
	   }

	if ($im_med) {
      if($proporcii_med=='true') {

		$thumb_med = @ImageCreateTrueColor ($w_med, $h_med);
//---------------------raid--------------------------//
		$thumb2_med = @ImageCreateTrueColor ($fw_med, $fh_med);

        $flol_med = imagecolorallocate($thumb2_med, 255, 255, 255); 
		@imageFilledRectangle ($thumb2_med,0,0,$fw_med, $fh_med,$flol_med);
		@ImageCopyResampled ($thumb_med, $im_med, 0, 0, 0, 0, $w_med, $h_med, $sw_med, $sh_med);
		@ImageCopyResampled ($thumb2_med, $thumb_med, ($fw_med-$w_med)/2, ($fh_med-$h_med)/2, 0, 0, $w_med, $h_med, $w_med, $h_med);

		@ImageJPEG ($thumb2_med, $img_med, $jpg_quality); 
		@imagedestroy($thumb2_med);
        }
      else
 {
 
		$thumb_med = @ImageCreateTrueColor ($w_med, $h_med);
		@ImageCopyResampled ($thumb_med, $im_med, 0, 0, 0, 0, $w_med, $h_med, $sw_med, $sh_med);
		@ImageJPEG ($thumb_med, $img_med, $jpg_quality); 
		@imagedestroy($thumb_med);
		
//---------------------raid---END----------------------//
        }
	}


}

if(($type=='sma_')or($type=='both_')) {
$img_sma = $this->destination . 'sma_'.$this->filename;  

$jpg_quality = 90; 
$constrain = 1;  
if($r_wi_sma==''&&$r_he_sma==''){
  $fw_sma = 150;
  $fh_sma = 150;  
}
else {
  $fw_sma = $r_wi_sma;  
  $fh_sma = $r_he_sma; 
}
  $w_sma = $fw_sma;  
  $h_sma = $fh_sma; 
$x_sma = @getimagesize($img_sma); 

$sw_sma = $x_sma[0];$sh_sma = $x_sma[1]; 

if ($sw_sma >= $w_sma || $sh_sma >= $h_sma) { 
	if (isset ($w_sma) AND !isset ($h_sma)) { 
		$h_sma = (100 / ($sw_sma / $w_sma)) * .01;
		$h_sma = @round ($sh_sma * $h_sma);
	} elseif (isset ($h_sma) AND !isset ($w_sma)) { 
		$w_sma = (100 / ($sh_sma / $h_sma)) * .01;
		$w_sma = @round ($sw_sma * $w_sma);
	} elseif (isset ($h_sma) AND isset ($w_sma) AND isset ($constrain)) {
		
		$hx_sma = (100 / ($sw_sma / $w_sma)) * .01;
		$hx_sma = @round ($sh_sma * $hx_sma);

		$wx_sma = (100 / ($sh_sma / $h_sma)) * .01;
		$wx_sma = @round ($sw_sma * $wx_sma);

		if ($hx_sma < $h_sma) {
			$h_sma = (100 / ($sw_sma / $w_sma)) * .01;
			$h_sma = @round ($sh_sma * $h_sma);
		} else {
			$w_sma = (100 / ($sh_sma / $h_sma)) * .01;
			$w_sma = @round ($sw_sma * $w_sma);
		}
	}

	if (function_exists( 'exif_imagetype' )) $img_type_sma = exif_imagetype($img_sma); 
	else $img_type_sma = $x_sma[2];
   
	switch ($img_type_sma) {
	   case IMAGETYPE_GIF  : $im_sma = @ImageCreateFromGIF ($img_sma); break;
	   case IMAGETYPE_JPEG : $im_sma = @ImageCreateFromJPEG ($img_sma); break;
	   case IMAGETYPE_PNG  : $im_sma = @ImageCreateFromPNG ($img_sma); break;
//	   case IMAGETYPE_WBMP : $im = @ImageCreateFromwbmp ($img); break;
	   default : $im_sma = false; 
	   }
  
	if ($im_sma) {
      if($proporcii_sma=='true') { 
       
//---------------------raid--------------------------//
		    $thumb_sma = @ImageCreateTrueColor ($w_sma, $h_sma);
          imagealphablending($thumb_sma, false); // красивая прозрачность для временной картинки
          imagesavealpha($thumb_sma, true);
          $background = imagecolorallocate($thumb_sma, 0, 0, 0);
          ImageColorTransparent($thumb_sma, $background); 
          		    
        $thumb2_sma = @ImageCreateTrueColor ($fw_sma, $fh_sma); // создание временной картинки
          imagealphablending($thumb2_sma, false); // красивая прозрачность для временной картинки
          imagesavealpha($thumb2_sma, true);
          $background = imagecolorallocate($thumb2_sma, 0, 0, 0);
          ImageColorTransparent($thumb2_sma, $background); 

if($img_type_sma!=IMAGETYPE_PNG) {
    $flol_sma = imagecolorallocate($thumb2_sma, 255, 255, 255); 
		@imageFilledRectangle ($thumb2_sma,0,0,$fw_sma, $fh_sma,$flol_sma);
}
     //   @ImageCopyResampled ($thumb2_sma, $im_sma, 0, 0, 0, 0, $w_sma, $h_sma, $sw_sma, $sh_sma);
        @ImageCopyResampled ($thumb_sma, $im_sma, 0, 0, 0, 0, $w_sma, $h_sma, $sw_sma, $sh_sma);
        @ImageCopyResampled ($thumb2_sma, $thumb_sma, ($fw_sma-$w_sma)/2, ($fh_sma-$h_sma)/2, 0, 0, $w_sma, $h_sma, $w_sma, $h_sma);


   	if($img_type_sma==IMAGETYPE_JPEG) @ImageJPEG ($thumb2_sma, $img_sma, $jpg_quality);
    elseif($img_type_sma==IMAGETYPE_PNG) @ImagePNG ($thumb2_sma, $img_sma, 0);
    elseif($img_type_sma==ImageCreateFromGIF) @ImageGIF ($thumb2_sma, $img_sma, 0); 
		@imagedestroy($thumb2_sma);
        }
      else
 {
 
		$thumb_sma = @ImageCreateTrueColor ($w_sma, $h_sma);
		@ImageCopyResampled ($thumb_sma, $im_sma, 0, 0, 0, 0, $w_sma, $h_sma, $sw_sma, $sh_sma);
		@ImageJPEG ($thumb_sma, $img_sma, $jpg_quality); 
		@imagedestroy($thumb_sma);    
//---------------------raid---END----------------------//
        }
	}
}
}


        return true;
      } else {
        if ($this->message_location == 'direct') {
          $messageStack->add(ERROR_FILE_NOT_SAVED, 'error');
        } else {
          $messageStack->add_session(ERROR_FILE_NOT_SAVED, 'error');
        }

        return false;
      }
    }
}
    
 // end raid: ----------------------------------------------   
  
    
  }
?>
