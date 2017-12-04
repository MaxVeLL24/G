<?php
// echo '<pre>',var_dump($_POST),'</pre>';
// error_reporting(E_ALL);
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
    $AlbumName = $_POST['album_name'];
    $alias = stripslashes(strtolower(URLify::transliterate($AlbumName)));


    $AlbumDescription = $_POST['description'];
    $ImageFile = $_POST['image'];
    $Date = date('Y-m-d G:i:s');
    // echo '<pre>',var_dump($alias),'</pre>';die();


    /*$q = 'INSERT INTO photoalbum SET PhotoAlbumName = "'.$AlbumName.'", date = "'.$Date.'", description = "'.$AlbumDescription.'",
    alias = "'.$alias.'"';*/
    $insert = tep_db_query('INSERT INTO photoalbum SET
        PhotoAlbumName = "'.$AlbumName.'", date = "'.$Date.'", description = "'.$AlbumDescription.'",
    alias = "'.$alias.'"');
    // echo '<pre>',var_dump($insert),'</pre>';
    // die();
    if($insert){

        mkdir("photoalbum/".$alias);
        chmod("photoalbum/".$alias, 0777);
            mkdir("photoalbum/".$alias."/full");
            chmod("photoalbum/".$alias."/full", 0777);
                mkdir("photoalbum/".$alias."/small");
                chmod("photoalbum/".$alias."/small", 0777);

        $p = "SELECT PhotoAlbumID from photoalbum where alias= '".$alias."'";
        $a = tep_db_query($p);
        $row = mysql_fetch_array($a);

        if($_FILES['image']['name'][0]){
            for ($i=0; $i<count($_FILES['image']['name']); $i++)
            {

                $old_name = explode(".", $_FILES['image']['name'][$i]);
                $new_name = $old_name[0]."_med.jpg";

                $q = "INSERT INTO photo SET PhotoFullURL = '".$_FILES['image']['name'][$i]."', PhotoPreviewURL = '".$new_name."', PhotoAlbumID = '".$row[0]."', date = '".$Date."'";

                tep_db_query($q);

                move_uploaded_file($_FILES['image']['tmp_name'][$i], "photoalbum/".$alias."/full/".$_FILES['image']['name'][$i]);
                move_uploaded_file($ImageFile, "photoalbum/".$alias+"/full/"+basename ($ImageFile));



                $max_thumb_width = 320;

                $source = imagecreatefromjpeg("photoalbum/".$alias."/full/".$_FILES['image']['name'][$i]);

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

                @copy('temp/' . $name, 'photoalbum/'.$alias.'/small/'.$name);
                $old_name = explode(".", $name);
                rename ('photoalbum/'.$alias.'/small/' . $name, 'photoalbum/'.$alias.'/small/' . $old_name[0] . '_med.jpg');
            }

        }
    }

    header("Location: photogallery.php");

    require(DIR_WS_INCLUDES . 'application_bottom.php');
?>