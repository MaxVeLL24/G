<?php

/*
  $Id: account_history.php,v 1.2 2003/09/24 13:57:00 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

include_once __DIR__ . '/includes/application_top.php';

if(!tep_session_is_registered('customer_id'))
{
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

include_once DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_ACCOUNT_HISTORY;

$breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));

$page_title = HEADING_TITLE . (defined('HEAD_TITLE_TAG_ALL') && HEAD_TITLE_TAG_ALL ? ' - ' . HEAD_TITLE_TAG_ALL : '');
$page_robots_tag = 'noindex, follow';
unset($page_meta_description, $page_meta_keywords, $page_link_canonical, $page_link_prev, $page_link_next);
$content = CONTENT_ACCOUNT_HISTORY;

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');