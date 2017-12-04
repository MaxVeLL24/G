<?php
    include_once __DIR__ . '/includes/application_top.php';
    require('../includes/classes/class.URLify.php');
    URLify::add_chars(
		array (
			' ' =>'-',
			'.'=>'',
			'\''=>'',
			'"'=>'',
			','=>'',
			'*'=>'-',
			'('=>'',
			')'=>'',
			'%'=>'',
			'&laquo;'=>'',
			'&raquo;'=>'',
			'&#8249;'=>'',
			'&#8250;'=>'',
		)
	);

	$PhotoAlbumID = $_GET['PhotoAlbumID'];
    $AlbumName = $_POST['photo_album_name'];
    $alias_new = stripslashes(strtolower(URLify::transliterate($AlbumName)));

    $description = $_POST['description'];
	$ImageFile = $_POST['image'];
    $Delete = $_POST['del_image'];
    $Delete_Gal = $_POST['delete_gallery'];
    $Date = date ('Y-m-d G:i:s');

    $PhotoAlbumNameList = tep_db_query("SELECT * from photoalbum where PhotoAlbumID = ".$PhotoAlbumID);
    $a = tep_db_fetch_array($PhotoAlbumNameList);
    // echo '<pre>',var_dump(),'</pre>';
    // die();
    if(tep_db_num_rows($PhotoAlbumNameList)){
		$q = "update photoalbum set
		PhotoAlbumName = '".$AlbumName."',
		alias = '".$alias_new."',
		description = '".$description."'
		where PhotoAlbumID = ".$PhotoAlbumID;
		tep_db_query($q);

		rename("photoalbum/".$a['alias'], "photoalbum/".$alias_new);

		if (isset ($Delete))
		{
			$Photo = "select * from photo where PhotoID in ('".implode("','",$Delete)."')";
			$q = mysql_query($Photo);
			while($b = mysql_fetch_row($q))
			{
				$file_name = "photoalbum/".$alias_new."/full/".$b[1];
				unlink($file_name);
				$file_name3 = "photoalbum/".$alias_new."/small/".$b[2];
				unlink($file_name3);
			}

			$d = "delete from photo where PhotoID in ('".implode("','",$Delete)."')";
			mysql_query ($d);
		}

		if ($_FILES['image']['size'][0] != 0)
		{
			// echo '<pre>',var_dump(alias_new),'</pre>'; die();
			for ($i=0; $i<count($_FILES['image']['name']); $i++)
			{
				$old_name = explode(".", $_FILES['image']['name'][$i]);
				$new_name = $old_name[0]."_med.jpg";

				$q = "INSERT INTO photo SET PhotoFullURL = '".$_FILES['image']['name'][$i]."', PhotoPreviewURL = '".$new_name."', PhotoAlbumID = '".$PhotoAlbumID."', date = '".$Date."' ";

				mysql_query ($q);

				move_uploaded_file($_FILES['image']['tmp_name'][$i], "photoalbum/".$alias_new."/full/".$_FILES['image']['name'][$i]);
				move_uploaded_file($ImageFile, "photoalbum/".$alias_new."/full/"+basename ($ImageFile));

				$max_thumb_width = 215;

				$source = imagecreatefromjpeg("photoalbum/".$alias_new."/full/".$_FILES['image']['name'][$i]);

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

				@copy('temp/' . $name, 'photoalbum/'.$alias_new.'/small/'.$name);
				$old_name = explode(".", $name);
				rename ('photoalbum/'.$alias_new.'/small/' . $name, 'photoalbum/'.$alias_new.'/small/' . $old_name[0] . '_med.jpg');
			}
		}

		if (isset($Delete_Gal))
		{
			$query_2 = "SELECT * FROM photoalbum where PhotoAlbumID=".$PhotoAlbumID;
			$query_3 = mysql_query($query_2);
			$row_3 = mysql_fetch_assoc($query_3);

			$query_6 = "SELECT * FROM photo where PhotoAlbumID=".$PhotoAlbumID;
			$query_7 = mysql_query($query_6);
			while($row_4 = mysql_fetch_array($query_7))
			{
				unlink("photoalbum/".$row_3['alias']."/full/".$row_4[1]);
				unlink("photoalbum/".$row_3['alias']."/small/".$row_4[2]);
			}

			rmdir("photoalbum/".$row_3['alias'].'/small');
			rmdir("photoalbum/".$row_3['alias'].'/full');
			rmdir("photoalbum/".$row_3['alias']);

			$qk = "delete from photo where PhotoAlbumID = ".$PhotoAlbumID;
			mysql_query($qk);


			$qq = "delete from photoalbum where PhotoAlbumID = ".$PhotoAlbumID;
			mysql_query($qq);

		}
	}
    header("Location: ".$_SERVER['HTTP_REFERER']);

    require(DIR_WS_INCLUDES . 'application_bottom.php');
?>