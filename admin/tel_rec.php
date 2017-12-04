<?php
    include_once __DIR__ . '/includes/application_top.php';
    
	$Telephone = $_POST['a'];
    
    $query = "UPDATE telephone SET telephone = '".$Telephone."'";
    mysql_query ($query);
    
    header("Location: telephone.php");

    require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
