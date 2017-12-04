<?php
  $image='images/'.$_GET['thumb'];  // картинка
  $image_size = @getimagesize($image);

    switch ($image_size[2]) {
       case IMAGETYPE_GIF  :
         header('Content-type: image/gif');
       $img = @ImageCreateFromGIF ($image);
       break;
       case IMAGETYPE_JPEG :
         header('Content-type: image/jpeg');
       $img = @ImageCreateFromJPEG ($image);
       break;
       case IMAGETYPE_PNG  :
       header('Content-type: image/png');
       $img = @ImageCreateFromPNG ($image);
       break;
       default : $img = false;
    }


    $fix_to_X = 150;
    $fix_to_Y = 150;
    if(isset($_GET['w'])) $fix_to_X = $_GET['w'];
    if(isset($_GET['h'])) $fix_to_Y = $_GET['h'];

    $current_X = imagesx($img);
    $current_Y = imagesy($img);


    if($current_X>$current_Y) {
      $x = $fix_to_X;            // требуемая длина
      $y = intval($x*$current_Y/$current_X);    // требуемая высота (щас пропорциональна)
    } else {
      $y = $fix_to_Y;
      $x = intval($y*$current_X/$current_Y);    // требуемая высота (щас пропорциональна)

    }

    $thumb = imagecreatetruecolor($x,$y);

    //imagealphablending($thumb,false);
    imagesavealpha($thumb,true);    // ключевая функция для прозрачности

    imagefill($thumb,0,0,IMG_COLOR_TRANSPARENT);
    imagecopyresampled($thumb, $img, 0, 0, 0, 0, $x, $y, $current_X, $current_Y);

    switch ($image_size[2]) {
       case IMAGETYPE_GIF  : imagegif($thumb);  break;
       break;
       case IMAGETYPE_JPEG : imagejpeg($thumb, '', 90);  break;
       break;
       case IMAGETYPE_PNG  : imagepng($thumb);  break;
       default : $im_med = false;
    }
?>