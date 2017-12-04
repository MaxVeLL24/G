<?php
error_reporting(0);
include_once (dirname(__FILE__).'/config.php');
include_once (dirname(__FILE__).'/func.php');

$listid=array();

$query = mysql_query("SELECT * FROM `rand_comment` WHERE url='$url'");
if (mysql_num_rows($query)){$row = mysql_fetch_array($query);}
else{
$SQL = mysql_query("SELECT num FROM `$table` WHERE moder=0");
while ($myrow = mysql_fetch_array($SQL)) {$listid[]=$myrow['num'];}

shuffle($listid);shuffle($listid);shuffle($listid);
$row['num']=implode(',',array_slice($listid,0, $massparam['lastcomment']));
mysql_query("INSERT INTO `rand_comment` (`url`, `num`) VALUES('".$url."', '".$row['num']."');") or mysql_error();
}

$query = mysql_query("SELECT * FROM `$table` WHERE num in(".$row['num'].")");
while ($myrow = mysql_fetch_row($query))
{
$urlz=$hostsite.''.$myrow[1].'#commentit-'.$myrow[0];
$names=$myrow[2];
$comment_msg=$myrow[3];
$date=mont(date($massparam['formatdate'],strtotime($massparam['correcttime'],strtotime($myrow[4]))));
$comment_msg=cuthtml($comment_msg);
$comment_msg=cutbb($comment_msg);
$comment_msg = preg_replace("#\[url=(.*?)\](.*?)\[/url\]#si", "\\1", $comment_msg);  
$comment_msg = preg_replace("#\[url\](.*?)\[/url\]#si", "\\1", $comment_msg); 
$comment_msg=wordwrap($comment_msg, $massparam['wordcut'], " ", 1); 
$comment_msg=viewworld($comment_msg,$massparam['lastsum']).$massparam['lastend'];
$comment_msg=parsesmile($comment_msg);
$comment_msg = str_replace('zzzxaqwedasdsad', '<img src="/'.$wwp.'', $comment_msg );
if ($myrow[15]==1) {$names=$massparam['nameadmin'];}
echo xparse("".$_SERVER['DOCUMENT_ROOT']."/".$wwp."/skin/last.php");
}
?>

