<?php

/**
 * Страница, отображающая сообщение о том, что доступ к запрашиваемой странице запрещён
 */

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . DIRECTORY_SEPARATOR . FILENAME_FORBIDDEN;

header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden', true, 403);

$page_title = FORBIDDEN_PAGE_TITLE . (@constant('TITLE_DELIMITER') && @constant('HEAD_TITLE_TAG_ALL') ? TITLE_DELIMITER . HEAD_TITLE_TAG_ALL : '');
unset($page_meta_keywords, $page_meta_description, $page_link_canonical, $page_link_prev, $page_link_next);

$content = CONTENT_FORBIDDEN;
require DIR_WS_TEMPLATES . TEMPLATE_NAME . DIRECTORY_SEPARATOR . TEMPLATENAME_MAIN_PAGE;
require DIR_WS_INCLUDES . 'application_bottom.php';