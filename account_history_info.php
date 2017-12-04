<?php

/*
  $Id: account_history_info.php,v 1.2 2003/09/24 13:57:00 wilt Exp $

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

// Проверяем, что такой заказ существует и он принадлежит текущему пользователю
$order_id = isset($_GET['order_id']) ? filter_var($_GET['order_id'], FILTER_VALIDATE_INT, array('min_range' => 1)) : null;
if(!$order_id)
{
    require FILENAME_NOT_FOUND;
}
$query = tep_db_query("SELECT COUNT(*) AS `count` FROM orders WHERE orders_id = {$order_id} AND customers_id = {$_SESSION['customer_id']} LIMIT 1");
$result = tep_db_fetch_array($query);
if(!$result['count'])
{
    require FILENAME_NOT_FOUND;
}

require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ACCOUNT_HISTORY_INFO);

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
$breadcrumb->add(sprintf(NAVBAR_TITLE_2, $order_id));

require(DIR_WS_CLASSES . 'order.php');
$order = new order($_GET['order_id']);

$page_title = sprintf(PAGE_TITLE, $order_id) . (defined('HEAD_TITLE_TAG_ALL') && HEAD_TITLE_TAG_ALL ? ' - ' . HEAD_TITLE_TAG_ALL : '');
$page_robots_tag = 'noindex, follow';
unset($page_meta_description, $page_meta_keywords, $page_link_canonical, $page_link_prev, $page_link_next);

$content = CONTENT_ACCOUNT_HISTORY_INFO;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');