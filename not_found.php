<?php

/**
 * Страница, отображающая сообщение о том, что страница не найдена
 */

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $language . DIRECTORY_SEPARATOR . FILENAME_NOT_FOUND;

ob_clean();
header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);

$page_title = NOT_FOUND_PAGE_TITLE . (@constant('TITLE_DELIMITER') && @constant('HEAD_TITLE_TAG_ALL') ? TITLE_DELIMITER . HEAD_TITLE_TAG_ALL : '');
unset($page_meta_keywords, $page_meta_description, $page_link_canonical, $page_link_prev, $page_link_next);

$content = CONTENT_NOT_FOUND;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

include DIR_WS_INCLUDES . 'application_bottom.php';