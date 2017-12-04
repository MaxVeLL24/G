<?php
    include_once __DIR__ . '/includes/application_top.php';
    
	$SliderText = $_POST['slider_text'];
    $SliderImage = $_POST['slider_image'];
    $SliderURL = $_POST['slider_link'];
	$SliderOutput = $_POST['output_order'];
    
    $query = "INSERT INTO slider SET SliderText = '".$SliderText."', SliderImage = '".$_FILES['slider_image']['name']."', SliderLink = '".$SliderURL."', output_order = '".$SliderOutput."'";

    mysql_query ($query);
    
    move_uploaded_file($_FILES['slider_image']['tmp_name'], "slider/".$_FILES['slider_image']['name']); 
    move_uploaded_file($SliderImage, "slider/"+basename ($SliderImage));
    
    header("Location: slider.php");

    require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
