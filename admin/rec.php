<?php 
$d=$_POST['a'];
echo "Файл изменен".'<br />' ;
echo $d; 
$fil="test.php"; 
$fp=fopen($fil,"w");
$d = stripslashes(stripslashes($d));  
fwrite($fp,$d);
fclose($fp); 
echo "<hr><a href='/admin/define_mainpage.php?lngdir=russian'>Обновить</a>"; 
?>