<?php
    include_once __DIR__ . '/includes/application_top.php';
   /* echo '<pre>';
	print_r($_FILES);
    echo '</pre>';die;  */
	$AlbumName = $_POST['album_name'];
	$ImageFile = $_FILES['image']['name'];
    $Date = date ('Y-m-d G:i:s');
    if(isset($AlbumName) && !empty($AlbumName)){
		mkdir("photoalbum/".$AlbumName);	
		chmod("photoalbum/".$AlbumName, 0777);
			mkdir("photoalbum/".$AlbumName."/full");
			chmod("photoalbum/".$AlbumName."/full", 0777);
				mkdir("photoalbum/".$AlbumName."/small");
				chmod("photoalbum/".$AlbumName."/small", 0777);	
		
		$q = "INSERT INTO photoalbum SET PhotoAlbumName = '".$AlbumName."', date = '".$Date."'";
		mysql_query ($q);
			if(!$_FILES['error'] && (isset($AlbumName) && !empty($AlbumName)) && (isset($ImageFile) && !empty($ImageFile))){
				
				$p = "SELECT PhotoAlbumID from photoalbum where PhotoAlbumName= '".$AlbumName."'";
				$a = mysql_query ($p);
				$row = mysql_fetch_array($a);
				for ($i=0; $i<count($_FILES['image']['name']); $i++)
				{
					$old_name = explode(".", $_FILES['image']['name'][$i]);
					$new_name = $old_name[0]."_med.jpg";
					
					$q = "INSERT INTO photo SET PhotoFullURL = '".$_FILES['image']['name'][$i]."', PhotoPreviewURL = '".$new_name."', PhotoAlbumID = '".$row[0]."', date = '".$Date."'";
								
					mysql_query ($q);
							  
					move_uploaded_file($_FILES['image']['tmp_name'][$i], "photoalbum/".$AlbumName."/full/".$_FILES['image']['name'][$i]); 
					// move_uploaded_file($ImageFile, "photoalbum/".$AlbumName+"/full/"+basename ($ImageFile));  
					move_uploaded_file($ImageFile, "photoalbum/".$AlbumName."/full/".$ImageFile);  

					$max_thumb_width = 215;

					$source = imagecreatefromjpeg("photoalbum/".$AlbumName."/full/".$_FILES['image']['name'][$i]);
					
					$src = $source;

					$w_src = imagesx($src);
					$h_src = imagesy($src);

					$w = $max_thumb_width;

					if ($w_src > $w)
					{
						$ratio = $w_src/$w;
						$w_dest = round($w_src/$ratio);
						$h_dest = round($h_src/$ratio);

						$dest = imagecreatetruecolor($w_dest, $h_dest);
						
						imagecopyresampled($dest, $src, 0, 0, 0, 0, $w_dest, $h_dest, $w_src, $h_src);
						imagejpeg($dest, 'temp/' . $_FILES['image']['name'][$i]);
						imagedestroy($dest);
						imagedestroy($src); 
					}
					else
					{
						imagejpeg($src, 'temp/' . $_FILES['image']['name'][$i]);
						imagedestroy($src);
					}

					$name = $_FILES['image']['name'][$i];

					@copy('temp/' . $name, 'photoalbum/'.$AlbumName.'/small/'.$name);
					$old_name = explode(".", $name);
					rename ('photoalbum/'.$AlbumName.'/small/' . $name, 'photoalbum/'.$AlbumName.'/small/' . $old_name[0] . '_med.jpg');
				}
			}
	}
    header("Location: photogallery.php");

    require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
