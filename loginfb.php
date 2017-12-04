<?php
  include_once __DIR__ . '/includes/application_top.php';
  
// Facebook - читается из application_top
   $app_id = $fb_app_id;
   $app_secret = $fb_app_secret;
   $my_url = $fb_url;
   $_SESSION['state'] = $fb_state;
            
//   session_start();
   $code = $_REQUEST["code"];

   if(empty($code)) {
     $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
     $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
       . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
       . $_SESSION['state'];

     echo("<script> top.location.href='" . $dialog_url . "'</script>");
   }

   if($_SESSION['state'] && ($_SESSION['state'] === $_REQUEST['state'])) {
     $token_url = "https://graph.facebook.com/oauth/access_token?"
       . "client_id=" . $app_id . "&redirect_uri=" . urlencode($my_url)
       . "&client_secret=" . $app_secret . "&code=" . $code;

     $response = file_get_contents($token_url);
     $params = null;
     parse_str($response, $params);

     $graph_url = "https://graph.facebook.com/me?access_token=" 
       . $params['access_token'];

     $user = json_decode(file_get_contents($graph_url));
//     echo("Hello " . $user->name);
//     print_r($user);
     
//     echo 'fb'.$user->id.'<br />';
//     echo $user->first_name.' '.$user->last_name.'<br />';
//     echo $user->email.'<br />';
//     echo $user->hometown->name.'<br />';
//     echo '<img src="http://graph.facebook.com/'.$user->id.'/picture" /><br />';
		$firstname = str_replace(' ', '_', iconv('windows-1251', 'UTF-8',$user->name));
     echo '<script type="text/javascript">window.close();window.opener.checkLoginvk("fb_'.$user->id.'","'.$firstname.'","'.$user->last_name.'","http://graph.facebook.com/'.$user->id.'/picture","'.$user->email.'","'.$user->hometown->name.'","")</script>'; 
   }
   else {
     echo("The state does not match. You may be a victim of CSRF.");
   }
// Facebook END

//print_r($_SESSION);
//header('Location: /');
?>
