<?php
/*
  $Id: password_funcs.php,v 1.1.1.1 2003/09/18 19:05:07 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// This funstion validates a plain text password with an
// encrpyted password
  function tep_validate_password($plain, $encrypted) {
// HMCS: Begin Autologon	******************************************************************

    global $HTTP_COOKIE_VARS;
	
    if (tep_not_null($plain) && tep_not_null($encrypted)) {
// split apart the hash / salt
      $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) return false;

      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }
    }

    if (tep_not_null($HTTP_COOKIE_VARS['password']) && tep_not_null($encrypted)) {   //Autologon
      if ($HTTP_COOKIE_VARS['password'] == $encrypted) {
        return true;
      }      
    }

// HMCS: End Autologon		******************************************************************
    return false;
  }

////
// This function makes a new password from a plaintext password. 
  function tep_encrypt_password($plain) {
    $password = '';

    for ($i=0; $i<10; $i++) {
      $password .= tep_rand();
    }

    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
  }
?>
