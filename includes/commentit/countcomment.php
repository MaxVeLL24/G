<?php 
	error_reporting(0);
	include_once (dirname(__FILE__).'/config.php');
	function countcomment($url){
		global $table;
		$countcomment=mysql_query("SELECT count(url) FROM `$table` WHERE url='".mysql_real_escape_string($url)."'");
		$out=mysql_result($countcomment,0);
		if (!$out) $out=0;
		return $out;
	} 
?>