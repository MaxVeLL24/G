<?php $query = "select telephone from telephone";
$query1 = mysql_query($query);
$row = mysql_fetch_row($query1);
echo($row[0]); ?>