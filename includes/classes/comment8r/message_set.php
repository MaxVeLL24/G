<?php
/*
  $Id: catalog/includes/classes/comment8r/message_set.php,v 1.0 20:57:52 NCB Exp $
 	easyCommentz by hanuman at Open Source Services
  Support forum at http://www.open-source-services.com/Forum/easyCommentz-EZC-v0.1-free-osCommerce/
*/

class message_set {
	
	var $db = '';
	var $p_id = '';
	var $arr_messages = array(array());
	var $arr_settings = array();

	
	function message_set($product_id){
		include_once(DIR_WS_CLASSES . 'comment8r/dao.php');
		$this->db = new dao();
		$this->getMSetProperties();
		$this->p_id = $product_id;
	}
	
	function getMSetProperties(){
		$this->arr_settings = $this->db->getGlobalSets();
	}
	
	function getMessages4Prod(){
		return $this->db->getMessagesForProduct($this->p_id);
	}
	
	function getNewMessageEditor(){
		$output = '';
		$output .= $this->getValidation();
		
		$bg_rgb = $this->arr_settings['bg_rgb'];
		$sz_captcha = 'captcha';
		$sz_cap_file = 'securimage/securimage_show.php?';
		$reload_captcha = '<a href="#" onclick="document.getElementById(\'' . $sz_captcha . '\').src =\'' . $sz_cap_file . '\' + Math.random(); return false">Обновить</a>';
		$action = tep_href_link('ossCommentz.php', 'action=addComment', 'NONSSL');
		$form = '<form name="addOSSComment" action="' . $action . '" method="post" onSubmit="return isValid();">';
		
		$output .= $form . '<br /><table width=100% class="comment8r" cellpadding="0" cellspacing="3">';

		$output .= '<tr><td width="80"><span>Имя:</span></td><td><input type=text id=name name=name size="30" maxlength="30"/></td></tr>';
//		$output .= '<tr><td>Message Title: </td><td><input type=text id=title name=title size=50 maxlength=100 /></td></tr>';
		$output .= '<tr><td valign="top"><span>Сообщение:</span>
    <br />
        <center>
          <span style="font-size: 10;"><br />' . $reload_captcha .'</span>
          <img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA" />
		      <br /><input type="text" name="captcha_code" size="10" maxlength="6" />
		    </center>  
    </td><td><textarea id=msg name=msg rows=5></textarea></td></tr>';
		$output .= '<tr><td align="right" colspan="2">

		<input type="hidden" name="url" value="' . HTTP_SERVER . $_SERVER['REQUEST_URI'] . '" />
		<input type="hidden" name="p_id" value="' . $this->p_id . '" />
		<input type="hidden" name="title" value="lol" />
		<input type="submit" value="Отправить" /></td></tr>';
		
		$output .= '</table></form>';
		return $output;
	}
	
	function getValidation(){
		
		$output = '';
		$name = 'name';
		$name_err = 'Имя автора должно быть не меньше 3 символов';//translate
		$title = 'title';
		$title_err = 'Please make sure the MESSAGE TITLE is at least 2 characters long';//translate
		$msg = 'msg';
		$msg_err = 'Комментарий должен быть не короче 5 символов';//translate
		$output .= '<script language="javascript">';
		$output .= 'function isValid(){';
		$output .= 'if(document.getElementById(\'' . $name . '\').value.length < 3){alert(\'' . $name_err . '\');return false;}';
		$output .= 'if(document.getElementById(\'' . $title . '\').value.length < 2){alert(\'' . $title_err . '\');return false;}';
		$output .= 'if(document.getElementById(\'' . $msg . '\').value.length < 5){alert(\'' . $msg_err . '\');return false;}';
		$output .= 'return true;}</script>';
		return $output;
	}
	
	function getMessageSet($captcha_ok){
		
		$output = '';
		
		$area_width = $this->arr_settings['area_width'] . "%";
		$area_align = $this->arr_settings['area_align'];
		$head_txt = $this->arr_settings['head_txt'];
		$head_align = $this->arr_settings['head_align'];
		$txt_rgb = $this->arr_settings['txt_rgb']; 
		$bg_rgb = $this->arr_settings['bg_rgb']; 
		
		$mess_query = $this->getMessages4Prod();
		
		$mess_count = 0;
		while($message = @tep_db_fetch_array($mess_query)){
	
			$this->arr_messages[$mess_count]['msg_id'] = $message['msg_id'];
			$this->arr_messages[$mess_count]['name'] = $message['name'];
			$this->arr_messages[$mess_count]['msg_title'] = $message['msg_title'];
			$this->arr_messages[$mess_count]['msg'] = $message['msg'];
			$this->arr_messages[$mess_count]['msg_date'] = $message['msg_date'];
		
			$mess_count++;
		}
		
		if(count($this->arr_messages) > 0){
		//	$output .= '<table align=' . $area_align . ' width=' . $area_width . '>';
      
      if($captcha_ok == 'false'){
      	$output .= '</font><b><font color="red" style="font-weight: bold;">Вы ввели неверный код. Попробуйте еще раз.</font></b>
      <font color=' . $txt_rgb . ' >';
      	
      }
            
			for($i = 0; $i < count($this->arr_messages);$i++){
if(strlen($this->arr_messages[$i]['msg_title']) > 0){				
				$msge = $this->arr_messages[$i]['msg'];
				
				$msge = preg_replace("/(\r\n¦\n¦\r)/", "\n", $msge); 
				$msge = preg_replace("/\n\n+/", "\n\n", $msge); 
				$msge = preg_replace('/\n?(.+?)(\n\n¦\z)/s', "<p>$1</p>", $msge);
				$msge = preg_replace('¦(?<!</p>)\s*\n¦', "<br />", $msge);
				
				$output .= '<table width=100% border=0 ><tr><td width="86%">';
				$output .= '<b style="font-size:12px;color:#349ddb;">' . stripslashes($this->arr_messages[$i]['name']) . ':</b>';
				$output .= '</td><td><font style="font-size: 10px;color:#999;">' . $this->arr_messages[$i]['msg_date'] . '</font></td></tr>';
				$output .= '<tr><td colspan="2" style="font-size:12px;color:#333;padding-bottom:10px;border-bottom:1px solid #349ddb;">';
				$output .= stripslashes($msge);
				$output .= '</td></tr></table>';
				
			}
		}		
			$output .= $this->getNewMessageEditor();
	//		$output .= '</td></tr></table>';
		}		
		return $output;
	}
}