<?php
$version = '2.01';
$debug = 0;
$turnover_multiplicator = 1000; # display currency in single units or in thousands, set to 1 or 1000

#################################################################################################
/*
Statistics for osCommerce v2.2 RC2
http://synctables.com/turnover-statistics-for-oscommerce.php

This program does not contain or change code of osCommerce.
Copyright (c) 2008 synctables.com
Released under the GNU General Public License 

=========================
Version History

2009-01-17: 1.0  initial version

2009-01-18: 1.01 MySQL statements corrected for version MySQL 4.0

2009-01-18: 1.02 deleted some unnecessary files from open-flash-chart to make the package smaller

2009-02-02: 1.03 statistics for short period added
                 JSON.php added as an optional file to download for earlier versions of PHP

2009-02-18: 1.04 improved short period statistics
                 debugging mode for data
                 to debug set in line 3: $debug=1;
                 and enter the URL 
                 for long term statistics: statistics.php?action=data
                 for short term statistics: statistics.php?action=data_short

2009-02-22: 2.00 No more flash! Just HTML and Javascript
                 Use of JQuery and Flot for graphics http://code.google.com/p/flot/

2009-02-22: 2.01 small error corrections


=========================
Debugging
-------------------------
debugging mode for data
to debug set in line 3: $debug=1;
and enter the URL 
for long term statistics: statistics.php?action=data
for short term statistics: statistics.php?action=data_short

*/
#################################################################################################


if($_GET[lang] == 'de' || $_GET[lang] == 'en') {
	setcookie ("turnoverlang", $_GET[lang], time()+3600*24*3650, '/');
	$_COOKIE[turnoverlang] = $_GET[lang];
}
$langs = array('en'=>'English', 'de'=>'Deutsch');
foreach($langs as $i=>$lang) {
	if($_COOKIE[turnoverlang] == $i) $selected = 'selected';
	else $selected = '';
	$options .= "<option value=$i $selected>$lang</option>";
}


$T[turnover] = 'Turnover';
$T[turnover_long] = 'Turnover last years';
$T[turnover_short] = 'Turnover last month';
$T[turnoverlast30days] = 'turnover last 30 days';
$T[turnoverlast180days] = 'turnover last 180 days, average per month';
$T[turnoverday] = 'Daily turnover';
$T[turnovertable] = 'Turnover table';
$T[month] = 'month';
$T[actualturnover] = 'actual turnover';
$T[expectedturnover] = 'expected turnover';
$T[changelastyear] = 'change last year';
$T[actualturnoveryear] = 'actual turnover year';
$T[expectedturnoveryear] = 'expected turnover year';

if($_COOKIE[turnoverlang] == 'de') {
	$T[turnover] = 'Umsatz';
	$T[turnover_long] = 'Umsatz der letzten Jahre';
	$T[turnover_short] = 'Umsatz des letzten Monats';
	$T[turnoverlast30days] = 'Umsatz letzten 30 Tage';
	$T[turnoverlast180days] = 'Umsatz letzten 180 Tage, Monatsdurchschnitt';
	$T[turnoverday] = 'Tagesumsatz';
	$T[turnovertable] = 'Umsatztabelle';
	$T[month] = 'Monat';
	$T[actualturnover] = 'Umsatz';
	$T[expectedturnover] = 'erwarteter Umsatz';
	$T[changelastyear] = 'Verдnderung zu Vorjahr';
	$T[actualturnoveryear] = 'Jahresumsatz';
	$T[expectedturnoveryear] = 'erwarteter Jahresumsatz';
}
$js_text = 'text={};';
foreach($T as $i=>$t) {
	$js_text .= "text['$i']='$t';";
}


include_once __DIR__ . '/includes/application_top.php';

$thisyear = date("Y");
$lastyear = $thisyear -1;
$startyear = $thisyear -2;
$startdate = "$startyear-01-01";
$today = date("Y-m-d");
$one_year_ago = "$lastyear-" . date("m-d");
$thismonth = date("Y-m-01");
$thismonth_lastyear = $lastyear . date("-m-01");


$maxdate = checkinstall();

# data for flash
if($_GET[action] == 'data') data();
if($_GET[action] == 'data_short') data_short();


#####################################
$sql="delete from turnover_daysum
where dd>='$maxdate'";
tep_db_query($sql);

#####################################
$sql="insert into turnover_daysum
select substring(orders.date_purchased,1,10) datum, sum(value) daysum 
from orders, orders_total 
where orders.orders_id=orders_total.orders_id
and class='ot_subtotal'
and orders.date_purchased>='$maxdate' 
group by substring(orders.date_purchased,1,10)";
tep_db_query($sql);

####################################
$sql="insert into turnover_daysum
select turnover_alldate.dd, 0 daysum
from turnover_daysum
right outer join turnover_alldate on turnover_daysum.dd=turnover_alldate.dd
where isnull(turnover_daysum.dd)
and turnover_alldate.dd<CURDATE()";
tep_db_query($sql);

####################################
$sql = "DROP TABLE IF EXISTS turnover_period";
tep_db_query($sql);

####################################
/* ONLY for MySQL5:
$sql="CREATE TABLE turnover_period 
SELECT sales2.dd,
round(SUM(CASE WHEN turnover_daysum.dd>sales2.ddstart30 AND turnover_daysum.dd<=sales2.dd THEN turnover_daysum.daysum ELSE 0 END)) AS 'sum30' ,
round(SUM(CASE WHEN turnover_daysum.dd>sales2.ddstart180 AND turnover_daysum.dd<=sales2.dd THEN turnover_daysum.daysum ELSE 0 END)/6) AS 'sum180' 
from turnover_daysum
inner join
(
select dd, DATE_ADD(dd,INTERVAL -1 DAY) yest, DATE_ADD(dd,INTERVAL -30 DAY) ddstart30, DATE_ADD(dd,INTERVAL -180 DAY) ddstart180 from turnover_daysum
) as sales2
group by sales2.dd"; 
*/

####################################
$sql = "DROP TABLE IF EXISTS turnover_temp";
tep_db_query($sql);
$sql = "CREATE TABLE turnover_temp
select dd, DATE_ADD(dd,INTERVAL -1 DAY) yest, DATE_ADD(dd,INTERVAL -30 DAY) ddstart30, DATE_ADD(dd,INTERVAL -180 DAY) ddstart180 from turnover_daysum";
tep_db_query($sql);

####################################
$sql="CREATE TABLE turnover_period
SELECT turnover_temp.dd,
round(SUM(CASE WHEN turnover_daysum.dd>turnover_temp.ddstart30 AND turnover_daysum.dd<=turnover_temp.dd THEN turnover_daysum.daysum ELSE 0 END)) AS 'sum30' ,
round(SUM(CASE WHEN turnover_daysum.dd>turnover_temp.ddstart180 AND turnover_daysum.dd<=turnover_temp.dd THEN turnover_daysum.daysum ELSE 0 END)/6) AS 'sum180' 
from turnover_daysum inner join turnover_temp
where turnover_daysum.dd<curdate()
group by turnover_temp.dd";
tep_db_query($sql);

####################################
$sql="drop table IF EXISTS turnover_temp";
tep_db_query($sql);

####################################
$sql = "(select 1,sum(daysum) ss from turnover_daysum
where dd>='$lastyear-01-01' and dd<'$one_year_ago')
union
(select 2,sum(daysum) ss from turnover_daysum
where dd>='$thisyear-01-01' and dd<'$today')
union
(select 3,sum(daysum) ss from turnover_daysum
where dd>='$thismonth_lastyear' and dd<'$one_year_ago')
union
(select 4,sum(daysum) ss from turnover_daysum
where dd>='$thismonth' and dd<'$today')";
#d($sql);
$rows = stats_db_get_all($sql);
#d($rows);
$tmp = array_shift($rows);
$sum_lastyear = $tmp[ss];
$tmp = array_shift($rows);
$sum_thisyear = $tmp[ss];
if($sum_lastyear >0) $factor_year = $sum_thisyear/$sum_lastyear;
$tmp = array_shift($rows);
$sum_lastyearmonth = $tmp[ss];
$tmp = array_shift($rows);
$sum_thisyearmonth = $tmp[ss];
if($sum_lastyearmonth >0) $factor_month = $sum_thisyearmonth/$sum_lastyearmonth;

$sql = "select date_format(dd, '%Y-%m') dd, round(sum(daysum)) ss 
from turnover_daysum
where dd>'$startyear-01-01'
group by date_format(dd, '%Y-%m')
order by dd";
$rows = stats_db_get_all($sql);
foreach($rows as $i=>$row) {
	if(substr($row[dd], 5,2) == '01') {
		if($i>1) $sums[$lastdd][sumyear] = $sumyear;
		$sumlastyear = $sumyear;
		$sumyear = 0;
	}
	else {
	}
	$sums[$row[dd]][ss] = $row[ss]; 
	if(($row[dd].'-01') == $thismonth) {
		$sums[$row[dd]][ssexpected] = $sums[substr($thismonth_lastyear,0,7)][ss] * $factor_month;
	}
	$sumyear += $row[ss];
	$lastdd = $row[dd];
}
for($i=date("m")+1; $i<=12; $i++) {
	$ii = sprintf("%02d", $i);
	$sums[ $thisyear.'-'.$ii ][sumyear] = '';
}
$sums[$thisyear.'-12'][sumyear] = $sumyear;
$sums[$thisyear.'-12'][sumyearexpected] = $sumlastyear * $factor_year;

foreach($sums as $dd=>$sum) {
	if($sum[ssexpected] >0) $monthsum[] = $sum[ssexpected];
	else $monthsum[] = $sum[ss];
	if($monthsum[count($monthsum)-13] >0 && $monthsum[count($monthsum)-1] >0) {
		$change_m = round($monthsum[count($monthsum)-1]/$monthsum[count($monthsum)-13]*100-100);
		if($change_m >0) $change_m = '+'.$change_m;
		$change_m .= '%';
	}
	else $change_m = '';
	if($sum[sumyearexpected] >0) $yearsum[] = $sum[sumyearexpected];
	else $yearsum[] = $sum[sumyear];
	if($yearsum[count($yearsum)-13] >0 && $yearsum[count($yearsum)-1] >0) {
		$change_y = round($yearsum[count($yearsum)-1]/$yearsum[count($yearsum)-13]*100-100);
		if($change_y >0) $change_y = '+'.$change_y;
		$change_y .= '%';
	}
	else $change_y = '';

	foreach(array('ss', 'ssexpected', 'sumyear', 'sumyearexpected') as $i) {
		if($sum[$i]>0) $sum[$i] = number_format2($sum[$i]);
	}
	$out .= "<tr><td>$dd</td><td>$sum[ss]</td><td>$sum[ssexpected]</td><td>$change_m</td><td>$sum[sumyear]</td><td>$sum[sumyearexpected]</td><td>$change_y</td></tr>";	
}
$out = "<table border=0 cellpadding=0 cellspacing=0 class=right><tr><th>$T[month]</th><th>$T[actualturnover]</th><th>$T[expectedturnover]</th><th>$T[changelastyear]</th><th>$T[actualturnoveryear]</th></th><th>$T[expectedturnoveryear]</th><th>$T[changelastyear]</th></tr>$out</table>";

$header = pageheader();

$extrastats = extrastats();

echo
<<<EOF
<html>
<head>
<title>OsCommerce Turnover Statistics by synctables.com</title>
<style>
body,h1,p,td,th {font-family:arial;} 
.right {font-size:9pt; text-align:right; border:black solid 1px; border-collapse:collapse; width:900px;}
.right th, .right td {padding: 2px 2px 2px 2px;}
.right th {padding: 2px 5px 2px 5px; border-bottom:black solid 1px; }
tr.alt td {background:#E7E8FF;}
tr.over td {background:#FFEC81;}
.tickLabel {font-size:8pt;}
</style> 
<script>var version='$version';</script>
<script type="text/javascript" src="includes/statistics/jquery-1.2.1.pack.js"></script>
<script type="text/javascript" src="includes/statistics/jquery.flot.pack.js"></script>
<script type="text/javascript" src="includes/statistics/statistics_flot.js"></script>
<script type="text/javascript" src="includes/statistics/jquery.json-1.3.min.js"></script>
<!--[if IE]><script language="javascript" type="text/javascript" src="includes/statistics/excanvas.pack.js"></script><![endif]-->
<script>
$js_text
</script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body>
<div style='position:absolute; top:130px; left:800px; font-size:10pt;'>
<form action=? method=GET>Language/Sprache:<select name=lang onchange="this.form.submit()">$options</select></form>
</div>
$header 
<div style='margin-left:20px; margin-right:20px; margin-bottom:50px;'>
$stats_before
<h1>$T[turnover_long]</h1>
<div id="turnover_long" style="width:600px;height:300px"></div>

<h1>$T[turnover_short]</h1>
<div id="turnover_short" style="width:600px;height:300px"></div>


<h1>$T[turnovertable]</h1>
$out 
</div> 
<p style='text-align:center; font-size:9pt;'>OsCommerce Statistics Plugin V$version by <a href='http://synctables.com/turnover-statistics-for-oscommerce.php' style='font-family:arial; font-size:9pt; color:blue; text-decoration:underline;'>synctables.com</p>
</body>
</html>
EOF;


###############################################################
function data() {
	global $T, $startyear, $lastyear, $thisyear, $debug, $turnover_multiplicator;

	$sql = "SET time_zone = '+0:00';";
	tep_db_query($sql);
# exclude certain dates because open-flash-chart displays grid all 30 days
	$sql = "select concat(unix_timestamp(dd),'000') dd,sum30/$turnover_multiplicator sum30, sum180/$turnover_multiplicator sum180 
from turnover_period 
where dd>='$startyear-01-01'
";
	$rows = stats_db_get_all($sql);

	if($debug == 1) echo "<p>" . count($rows) . " rows found in turnover_period<p>";

	foreach($rows as $row) {
		$out30 .= "[$row[dd], $row[sum30]],";
		$out180 .= "[$row[dd], $row[sum180]],";
	}
	$out30 = '[' . rtrim($out30, ' ,') . ']';
	$out180 = '[' . rtrim($out180, ' ,') . ']';
	$out = "{days30:$out30, days180:$out180}";
	echo $out;
	exit;
}


###############################################################
function data_short() {
	global $T, $startyear, $lastyear, $thisyear, $debug;
	
	$sql = "SET time_zone = '+0:00';";
	tep_db_query($sql);
	$sql = "select concat(unix_timestamp(dd),'000') dd, round(daysum) daysum from turnover_daysum
order by dd desc
limit 30
";
	$rows = stats_db_get_all($sql);

	if($debug == 1) echo "<p>" . count($rows) . " rows found in turnover_daysum<p>";

	foreach($rows as $row) {
		$out .= "[$row[dd], $row[daysum]],";
	}
	$out = '[' . rtrim($out, ' ,') . ']';
	echo $out;
	exit;
}


#########################################################
function pageheader() {
	ob_start();
?>
<table border="0" cellspacing="0" cellpadding="0" style="width:350px;">
  <tr>
    <td><?php echo tep_image(DIR_WS_IMAGES . 'oscommerce.gif', 'osCommerce', '204', '50'); ?></td>
  </tr>
  <tr class="headerBar">
    <td class="headerBarContent">&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_TOP . '</a>'; ?></td>
  </tr>
</table>
<?
	$out = ob_get_clean();
	return $out;
}

#########################################################
function checkinstall() {
	global $startdate;
	
	if(!is_callable('tep_db_query')) {
		$error = 1;
		$errormsg =
<<<EOF
<p>This is not an installation of osCommerce 2.2rc2a.
<p>It does not have the function tep_db_query.
EOF;
	}
	if($error ==1) show_error($errormsg);
	
	###############################
	$sql = "show tables";
	$rows = stats_db_get_all($sql);
	$tables_ok = 0;
	$table_daysum = 0;
	$table_alldate = 0;
	foreach($rows as $row) {
		$table = array_shift($row);
		if($table == 'orders') $tables_ok++;
		if($table == 'orders_total') $tables_ok++;
		if($table == 'turnover_daysum') $table_daysum++;
		if($table == 'turnover_alldate') $table_alldate++;
	}
	if($tables_ok != 2) {
		$errormsg =
<<<EOF
<p>This is not an installation of osCommerce 2.2rc2a.
<p>The tables orders and orders_total are missing.
EOF;
		show_error($errormsg);
	}

	###############################
	if($table_alldate == 0) {
		$sql =
<<<EOF
CREATE TABLE turnover_alldate (                                                              
id int(10) unsigned NOT NULL auto_increment,                                      
dd date,                                         
PRIMARY KEY  (id)                                                                 
)
EOF;
		tep_db_query($sql);
		$start = 1104567890; # 2005-01-01
		for($i=0; $i<3650; $i++) {
			$dd = date("Y-m-d", $start + $i*24*3600);
			$sql = "insert into turnover_alldate (dd) values('$dd')"; 
			tep_db_query($sql);
		}
	}

	###############################
	if($table_daysum > 0) {
		$sql = 	"select date_sub(max(dd), INTERVAL 2 DAY) ddmax from turnover_daysum";
		$row = stats_db_get_row($sql);
		$maxdate = $row[ddmax];
	}
	else {
		$sql =
<<<EOF
CREATE TABLE turnover_daysum (           
dd date default NULL,            
daysum double(19,2) default NULL,
UNIQUE KEY dd (dd)
)
EOF;
		tep_db_query($sql);
		$maxdate = $startdate;
	}

	return $maxdate;
}	
	
#########################################################
function extrastats() {
	if(file_exists('statistics_before.php')) {
		include_once('statistics_before.php');
		$GLOBALS[stats_before] = $out;
	}
}

#########################################################
function check_json() {
	if (!function_exists('json_encode'))
	{
		if(!file_exists('includes/statistics/JSON.php')) {
			echo "<b>You use an older version of PHP.<br>You need the additional file 'JSON.php' for this statistics contribution. <br>Please download the file here: <a href=http://synctables.com/install/json.zip>download json.zip</a><br>and put the extracted file 'JSON.php' into the directory admin/includes/statistics.";
			exit;
		}
	}
	
}

#########################################################
function show_error($msg) {
	$header = pageheader();
	echo
<<<EOF
<html>
<head>
<style>
body,h1,p,td,th {font-family:arial;} 
</style> 
<script type="text/javascript" src="includes/statistics/statistics_packed.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body>
$header 
<h1 style='margin-left:20px; margin-top:40px;'>Error</h1>
 
$msg
	
<p style='margin:20px;'>Please write any questions, comments and requests here: <a href="http://donauweb.at/2009/01/16/turnover-statistics-for-oscommerce/" style='font-size:11pt; color:blue; text-decoration:underline;'>http://donauweb.at/2009/01/16/turnover-statistics-for-oscommerce/</a>
	
</body>
</html>
EOF;
	exit;
}


#################################################################
  function number_format2($number) {
		if($_COOKIE[turnoverlang] == 'de') {
			return number_format($number, 0, ',', '.');
		}
		else {
			return number_format($number);
		}
	}
	
#################################################################
  function stats_db_get_all($db_query, $useindex=0, $getrows=0) {
	  $rs = tep_db_query($db_query);
	  while ($row = tep_db_fetch_array($rs)) {
			if($useindex==0) $rows[]=$row;
			else {
				$index=array_shift($row);
				if(count($row)==1) $row=array_shift($row);
				$rows[$index]=$row;
			}
		}
		if($getrows==1) $rows=$rows[0];
    return $rows;
  }

#################################################################
  function stats_db_get_row($db_query) {
		return stats_db_get_all($db_query, 0, 1);
	}
	




