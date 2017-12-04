<?php

// Страница подписки на рассылку

/* @var $breadcrumb \breadcrumb */
/* @var $messageStack \messageStack */

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $language . '/' . FILENAME_SUBSCRIBE;

$form_data = array(
    'email'
);

if(isset($_POST['email']))
{
    // Token
    if(!\EShopmakers\Security\CSRFToken::seekForTokenInRequestAndValidate())
    {
        $messageStack->add(CONTENT_SUBSCRIBE, SUBSCRIBE_BAD_TOKEN_ERROR);
    }
    
    // Email
    $email = filter_var(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
    if(!$email)
    {
        $messageStack->add(CONTENT_SUBSCRIBE, SUBSCRIBE_BAD_EMAIL_ERROR);
    }
    
    if(!$messageStack->size(CONTENT_SUBSCRIBE))
    {
        // Ищем, нет ли у нас зарегистрированного клмиента с таким адресом электронной почты
        $query = tep_db_query("SELECT customers_id, customers_newsletter FROM customers WHERE customers_email_address = '" . tep_db_input($email) . "' LIMIT 1");
        // Клиент есть
        if(tep_db_num_rows($query))
        {
            $result = tep_db_fetch_array($query);
            // Уже подписан
            if($result['customers_newsletter'])
            {
                $messageStack->add_session(CONTENT_SUBSCRIBE, SUBSCRIBE_ALREADY_SUBSCRIBED, 'info');
                tep_redirect(tep_href_link(FILENAME_SUBSCRIBE));
            }
            // Не подписан - оформляем подписку
            else
            {
                tep_db_query("UPDATE customers SET customers_newsletter = 1 WHERE customers_id = " . $result['customers_id']);
                $messageStack->add_session(CONTENT_SUBSCRIBE, SUBSCRIBE_SUCCESS, 'success');
                tep_redirect(tep_href_link(FILENAME_SUBSCRIBE));
            }
        }
        // Клиента нет
        else
        {
            // Ищем email клиента среди уже подписавшихся безымянных подписчиков
            $query = tep_db_query("SELECT subscribers_id FROM newsletter_subscribers WHERE subscribers_email = '" . tep_db_input($email) . "' LIMIT 1");
            if(tep_db_num_rows($query))
            {
                $messageStack->add_session(CONTENT_SUBSCRIBE, SUBSCRIBE_ALREADY_SUBSCRIBED, 'info');
                tep_redirect(tep_href_link(FILENAME_SUBSCRIBE));
            }
            else
            {
                tep_db_query("INSERT INTO newsletter_subscribers SET subscribers_email = '" . tep_db_input($email) . "'");
                $messageStack->add_session(CONTENT_SUBSCRIBE, SUBSCRIBE_SUCCESS, 'success');
                tep_redirect(tep_href_link(FILENAME_SUBSCRIBE));
            }
        }
    }
}

$content = CONTENT_SUBSCRIBE;
$page_robots_tag = 'noindex, follow';
$page_title = SUBSCRIBE_PAGE_HEADER;
$breadcrumb->add(SUBSCRIBE_PAGE_HEADER);

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');