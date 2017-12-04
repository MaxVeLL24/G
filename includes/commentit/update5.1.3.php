<?php
include ("config.php");
if ($coder==1){$dbcoder="cp1251 COLLATE cp1251_general_ci ";}else {$dbcoder="utf8 COLLATE utf8_unicode_ci ";}
$query ="
ALTER TABLE `$table` CHANGE `url` `url` VARCHAR(255) CHARACTER SET $dbcoder NOT NULL 
";
$query2 ="
ALTER TABLE `$table` ADD INDEX (`url`) 
";
$sort=mysql_query($query) or die(mysql_error());
$sort=mysql_query($query2) or die(mysql_error());
echo "CommentIt 5.1.3 Ajax: Update finished<br />";
?>


