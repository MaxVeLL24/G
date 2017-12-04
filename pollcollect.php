<?php
include_once __DIR__ . '/includes/application_top.php';
$pollid=$_POST['pollid'];
$voteid=$_POST['voteid'];
$forwarder=$_POST['forwarder'];
$ip = getenv("REMOTE_ADDR");
$past = time()-90800;
$votevalid=1;
$query="DELETE FROM phesis_poll_check WHERE time < ".$past;
tep_db_query($query);
if ($voteid) {
	$result=tep_db_query("select poll_type, poll_open from phesis_poll_desc where pollid='".$pollid."'");
	$poll=tep_db_fetch_array($result);
	if ($poll['poll_open']=='1') {
		$votevalid=0;
		$warn="_POLLCLOSED";
		}
	if ($poll['poll_type']=='1' && !isset($customer_id)) {
		$votevalid=0;
		$warn="_POLLPRIVATE";
		}
	if ($votevalid==1 && POLL_SPAM==0) {
		$query="SELECT ip FROM phesis_poll_check WHERE ip='".$ip."' and pollid='".$pollid."'";
		$result=tep_db_query($query);
		$result1=tep_db_fetch_array($result);
		$ips=$result1['ip'];
		$ctime = time();
		if ($ip == $ips) {
			$votevalid = 0;
			$warn="_ALREADY_VOTED";
		    	} else {
		      $query="INSERT INTO phesis_poll_check (ip, time, pollid) VALUES ('".$ip."', '".$ctime."' , '".$pollid."')";
		      tep_db_query($query);
			$votevalid = 1;
    		}
	}
}
if (!$voteid){
 	$votevalid=0;
	$warn="_NO_VOTE_SELECTED";
	}
if($votevalid>0) {
        $query1="UPDATE phesis_poll_data SET optionCount=optionCount+1 WHERE (pollid='".$pollid."') AND (voteid='".$voteid."') and (language_id='".$languages_id."')";
        $query2="UPDATE phesis_poll_desc SET voters=voters+1 WHERE pollid='".$pollid."'";
        $result1=tep_db_query($query1);
        $result2=tep_db_query($query2);
        Header("Location: $forwarder");
    } else {
        $forwarder .= "/warn/" . $warn; 

        Header("Location: $forwarder");
    }  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD>
<META http-equiv=Content-Type content="text/html; charset=windows-1253"></HEAD>
<BODY></BODY></HTML>
