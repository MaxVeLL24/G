<?php

/*
  $Id: contact_us.php,v 1.2 2003/09/24 15:34:26 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

include_once __DIR__ . '/includes/application_top.php';

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);

// BOF: BugFix: Spam mailer exploit
$sanita = array("|([\r\n])[\s]+|", "@Content-Type:@");
$_POST['email'] = $_POST['email'] = preg_replace($sanita, " ", $_POST['email']);
$_POST['name'] = $_POST['name'] = preg_replace($sanita, " ", $_POST['name']);
$_POST['phone'] = $_POST['phone'] = preg_replace($sanita, " ", $_POST['phone']);
// EOF: BugFix: Spam mailer exploit

$error = false;
$fields_with_errors = array();
if(isset($_GET['action']) && ($_GET['action'] == 'send'))
{
    $name          = tep_db_prepare_input($_POST['name']);
    $email_address = tep_db_prepare_input($_POST['email']);
    $phone         = tep_db_prepare_input($_POST['phone']);
    $enquiry       = tep_db_prepare_input($_POST['enquiry']);

    $enquiry = ENTRY_PHONE . " " . $phone . "<br /> " . ENTRY_NAME . " " . $name . "<br /> " . ENTRY_ENQUIRY . " " . $enquiry;

    if(tep_validate_email($email_address))
    {

        if(CONTACT_US_LIST != '')
        {
            $send_to_array = explode(",", CONTACT_US_LIST);
            //	preg_match('/\<[^>]+\>/', $send_to_array[$send_to], $send_email_array);
            //	$send_to_email= preg_replace (">", "", $send_email_array[0]);
            //	$send_to_email= preg_replace ("<", "", $send_to_email);
            $send_to_email = $send_to_array[$send_to];
            tep_mail(preg_replace('/\<[^*]*/', '', $send_to_array[$send_to]), STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $enquiry, $name, $email_address);
        }
        else
        {
            tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $enquiry, $name, $email_address);
        }
        if(\EShopmakers\Http\Request::isAjax())
        {
            \EShopmakers\Http\Response::sendJSON(array(
                'success' => true,
                'message' => TEXT_SUCCESS
            ));
        }
        //tep_redirect(tep_href_link(FILENAME_CONTACT_US, 'action=success'));
        tep_redirect(tep_href_link(FILENAME_DEFAULT));
    }
    else
    {
        $error = true;
        $fields_with_errors[] = 'email';
        if(\EShopmakers\Http\Request::isAjax())
        {
            \EShopmakers\Http\Response::sendJSON(array(
                'success' => false,
                'fields_with_errors' => $fields_with_errors,
                'message' => CONTACT_US_SEND_ERROR
            ));
        }
        $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
    }
}

$enquiry = "";
$name    = "";
$email   = "";

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_CONTACT_US));
$content = CONTENT_CONTACT_US;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

require(DIR_WS_INCLUDES . 'application_bottom.php');