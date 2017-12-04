<?php
/*
 unisender.php,v 1.0 2012/02/23 
 http://www.site4u.com.ua
 Copyright (c) 2006 site4u
*/
 require('includes/configure.php');
   require('unisender_api.php');
    if(isset($_REQUEST['action']))
    {
       switch ($_REQUEST['action']) {
       case 'getlist':
        $uniapi= new UniSenderApi($_REQUEST['api_key']);
        $server_lists=$uniapi->getLists();
        echo iconv('utf-8','cp1251',$server_lists);
       break;
       case 'import':
         $email_list_ids=$_REQUEST['list'];
         mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD) or die("Could not connect: " . mysql_error());
         mysql_select_db(DB_DATABASE);
         if ($_REQUEST['gr']!=0) {$sgr='and customers_groups_id='.$_REQUEST['gr'];} else {$sgr='';}
          $check_query = mysql_query('select customers_firstname, customers_lastname,	customers_email_address,customers_telephone from customers where customers_email_address!="" '.$sgr);
    		$args_default = array (
  	  	 'field_names[0]' => 'email',
	 		   'field_names[1]' => 'Name',
		   	 'field_names[2]' => 'phone',
         'field_names[3]' => 'email_list_ids', 
         'field_names[4]' => 'phone_list_ids',
  			 'double_optin' => '1'
	         	);
		$args=$args_default;
		$i=0;
		$inserted=0;
		$updated=0;
		$new_emails=0;
	  while ($users = mysql_fetch_array($check_query)) {
			if ($i>=500){
				$result=$uniapi->ImportContacts($args);
				$args=$args_default;
				$i=0;
				$inserted+=$result->inserted;
				$updated+=$result->updated;
				$new_emails+=$result->new_emails;
			            }
		 	$args['data[' . $i .'][0]']=$users['customers_email_address'];
			$args['data[' . $i .'][1]']=iconv('cp1251','utf-8',$users['customers_firstname']).' '.iconv('cp1251','utf-8',$users['customers_lastname']);
			$args['data[' . $i .'][2]']=$users['customers_telephone'];
      $args['data[' . $i .'][3]']=$email_list_ids;
      $args['data[' . $i .'][4]']=$email_list_ids;
			$i++;
		}
    $uniapi= new UniSenderApi($_REQUEST['api_key']);
		$result=$uniapi->ImportContacts($args);
    $r=json_decode($result);
		$inserted+=$r->result->inserted;
		$updated+=$r->result->updated;
		$new_emails+=$r->result->new_emails;
    mysql_close(); 
    echo  json_encode(array('inserted'=>$inserted,'updated'=>$updated,'new_emails'=>$new_emails));
      break;
     }
  }
  
  else { 
  include_once __DIR__ . '/includes/application_top.php';
  $CONFIG;
  $path='uniscfg.php';
    $gr_query = tep_db_query("select customers_groups_id, customers_groups_name from customers_groups ");
     $gr_sel='<select id="gr" style="width:300px;"><option value="0" selected> '.UNI_SEL_ALL.'</option>';
     while ($gr = tep_db_fetch_array($gr_query))
      {
        $gr_sel.='<option value="'.$gr['customers_groups_id'].'"> '.$gr['customers_groups_name'].'</option>';
       }
     $gr_sel.='</select>';
      
  if ($_REQUEST["api"]) {
   $CONFIG['api']=$_REQUEST['api'];
        $f=fopen($path, 'w');
        fwrite($f,serialize($CONFIG));
        fclose($f);
   $messageStack->add_session(MSG_FILECFG_SAVE, 'success');
  tep_redirect('unisender.php');
  }
  
 if (file_exists($path)) { 
            $CONFIG=unserialize(file_get_contents($path)); 
            $api_key=$CONFIG['api'];
            } else  $messageStack->add_session(MSG_FILECFG_ERROR. $path, 'error');  
 ?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script> 
<script language="javascript" ">
 $(document).ready(function(){
 
  getlist(); 
  
}); 

function getlist(){

 document.getElementById('sel').innerHTML='<td colspan=3 align="center"><img id="uniload" src="uniload.gif"></td>';
 var api_k=document.getElementById('api').value;
  $.getJSON("unisender.php",{action:"getlist",api_key:api_k},function(data){
   document.getElementById('sel').innerHTML='<td> <?php echo TABLE_HEADING_LIST; ?> </td> <td>  <select name="lists" id="lists" style="width:300px;">  </select> </td><td align="right"> <input type="button" value="<?php echo UNI_TRANSFER; ?>" id="button" onClick=json()></td>';
   var myselect=document.getElementById("lists"); 
 if (data.result[0]) {
 for (i = 0; i < data.result.length; i++) { 
  
try{
 myselect.add(new Option(data.result[i].title, data.result[i].id), null) 
}
catch(e){ 
 myselect.add(new Option(data.result[i].title, data.result[i].id)) 

}
  }} else {
            document.getElementById('sel').innerHTML='<td colspan=3 align="center" ><?php echo TABLE_API_ERROR; ?></td>';} 
  
  }); 

}

 function json(){ 
 document.getElementById('detail').innerHTML='<div style="background-color:red;text-align:center;color:white;"><?php echo UNI_LOADING; ?><br><img src="uniload.gif"></div>';
 $.getJSON("unisender.php",{action:"import",api_key:document.getElementById('api').value,list:document.getElementById('lists').value,gr:document.getElementById('gr').value},function(data){document.getElementById('detail').innerHTML='<div style="background-color:green;text-align:center;color:white;"> <?php echo UNI_ADDED; ?> '+data.new_emails+'</div>';
 
 if (document.getElementById('wndchk').checked) window.open('http://cp.unisender.com/ru/list_frm/'+document.getElementById('lists').value,'_self');
 });
 
 };
 
</script> 
<style>
 body {font-family:Arial;}
 td {font-size:12px;}
</style>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_smend //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_smend //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>

      <tr>
      <td>
     
   <?php
       echo tep_draw_form('unisender', 'unisender.php');
       // Ваш ключ доступа к API (из Личного Кабинета)


?>  
 <br>
     <fieldset style="width:550px;border:2px solid #00B3FF;border-radius:5px;">
 <legend> <?php echo UNI_ACC; ?>   <b>UniSender</b>:</legend>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr > <td><?php echo UNI_API; ?></td> <td><?php echo '<input type="text" value="'.$api_key.'" name="api" id="api" style="width:300px;">'; ?> </td><td align="right"><input type="submit" value="<?php echo UNI_BTN_WRITE; ?>" id="button" > </td> </tr>
 <tr> <td><?php echo UNI_OSC_GROUP; ?></td><td> <?php echo $gr_sel; ?> </td>  </tr>
<tr id="sel"> 
   </tr>
  
  </table> 
  <div id="detail"></div>
</fieldset>
  <br>
   <input id="wndchk" type="checkbox" /><?php echo UNI_CHK_OPENWINDOW; ?> 
</form>
      </td>
      </tr>
    </table></td>
  </tr>
</table>
<?php require(DIR_WS_INCLUDES . 'footer.php'); 
?>
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');

} ?>

