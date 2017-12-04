<?php

/*
  $Id: address_book.php,v 1.2 2003/09/24 13:57:00 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

include_once('includes/application_top.php');

if(!tep_session_is_registered('customer_id'))
{
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADDRESS_BOOK);

$breadcrumb->add(NAVBAR_TITLE);
$page_title = HEADING_TITLE . (defined('HEAD_TITLE_TAG_ALL') && HEAD_TITLE_TAG_ALL ? ' - ' . HEAD_TITLE_TAG_ALL : '');
$page_robots_tag = 'noindex, follow';
unset($page_meta_description, $page_meta_keywords, $page_link_canonical, $page_link_prev, $page_link_next);

$content = CONTENT_ADDRESS_BOOK;
$body_class = 'address-book-page';

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');