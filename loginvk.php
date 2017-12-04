<?php

  include_once __DIR__ . '/includes/application_top.php';

  $vk_client_id = $vk_app_id;
  $vk_secret = $vk_app_secret; 
  $vk_uri = $vk_url; 
  
  $r = json_decode(file_get_contents("https://oauth.vk.com/access_token?client_id=".$vk_client_id."&redirect_uri=".$vk_uri."&client_secret=".$vk_secret."&code=" . $_GET["code"]));
  $_SESSION["VK_UID"] = $r->user_id;

  $vkResponse = json_decode(@file_get_contents("https://api.vkontakte.ru/method/getProfiles?uid={$r->user_id}&access_token={$r->access_token}&fields=photo,city,contacts"))->response;

//  echo 'vk'.$vkResponse[0]->uid.'<br />';
//  echo ''.iconv('UTF-8', 'windows-1251', $vkResponse[0]->first_name).' '
//                 .iconv('UTF-8', 'windows-1251', $vkResponse[0]->last_name).'<br />';
//  echo '<img src="'.$vkResponse[0]->photo.'" /><br />';  
  $vk_first_name = iconv('windows-1251', 'UTF-8', $vkResponse[0]->first_name); 
  $vk_last_name = iconv('windows-1251', 'UTF-8', $vkResponse[0]->last_name);  
  $vk_photo = $vkResponse[0]->photo;
  
  echo '<script type="text/javascript">
          window.close();window.opener.checkLoginvk("vk_'.$vkResponse[0]->uid.'","'.$vk_first_name.'","'.$vk_last_name.'","'.$vk_photo.'","","","");
        </script>';    
              
//print_r($vkResponse);
//header('Location: /');

?>
