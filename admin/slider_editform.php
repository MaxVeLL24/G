<?php

    include_once __DIR__ . '/includes/application_top.php';

    $SlideID = $_GET['SliderID'];
    $SliderText = $_POST['slider_text'];
    $SliderURL = $_POST['slider_url'];
    $Delete = $_POST['delete_pic'];
    $Delete_1 = $_POST['delete_articles'];
	$Order = $_POST['output_order'];
    $UploadImage = $_POST['slider_image'];
    


    $q = "update slider set SliderText = '".$SliderText ."', SliderLink = '".$SliderURL."', output_order = '".$Order."' where SliderID = ".$SliderID;
    mysql_query ($q);
    
    if (isset($Delete))
    {
        $q_1 = "select * from slider where SliderID = ".$SliderID;
        $q_2 = mysql_query ($q_1);
        $row = mysql_fetch_row($q_2);
        
        $file_name = "slider/$row[1]";
        unlink($file_name);

        $q_4 = "update slider set SliderImage = '' where SliderID = ".$SliderID;
        mysql_query ($q_4);
    }
    
    if ($_FILES['slider_image']['size'] != 0)
    {
        $q_8 = "select * from slider where SliderID = ".$SliderID;
        $q_9 = mysql_query ($q_8);
        $row = mysql_fetch_row($q_9);
        
        $file_name54 = 'slider/'.$row[1];
        
        if (is_file($file_name54))
        {
            unlink($file_name54);
            
            $qk = "update slider set SliderImage = '".$_FILES['slider_image']['name']."' where SliderID = ".$SliderID;
            mysql_query($qk);
        
            move_uploaded_file($_FILES['slider_image']['tmp_name'], "slider/".$_FILES['slider_image']['name']); 
            move_uploaded_file($UploadImage, "slider/"+basename ($UploadImage));
        }
        
        else 
        {
            $qk = "update slider set SliderImage = '".$_FILES['slider_image']['name']."' where SliderID = ".$SliderID;
            mysql_query($qk);
        
            move_uploaded_file($_FILES['slider_image']['tmp_name'], "slider/".$_FILES['slider_image']['name']); 
            move_uploaded_file($UploadImage, "slider/"+basename ($UploadImage));
        }  
    
    }
    
    if (isset($Delete_1))
    {
        $q54 = "select SliderImage from slider where SliderID = ".$SliderID;
        $q55 = mysql_query ($q54);
        $row_23 = mysql_fetch_row($q55);    
            
        $file_name_2 = 'slider/'.$row_23[0];
        if (is_file($file_name_2))
        {
            unlink ($file_name_2);
        
            $qk = "delete from slider where SliderID = ".$SliderID;
            mysql_query($qk);
        }
        
        else
        {
            $qk = "delete from slider where SliderID = ".$SliderID;
            mysql_query($qk);
        }

        
    }
    
    header("Location: slider.php");
    
    require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
