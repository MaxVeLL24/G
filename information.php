<?php

/**
 * Инфостраницы
 */

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_INFORMATION;

// ID просматриваемой страницы
$pages_id = isset($_GET['pages_id']) ? filter_var($_GET['pages_id'], FILTER_VALIDATE_INT, array('min_range' => 0)) : null;
if(!$pages_id)
{
    require FILENAME_NOT_FOUND;
}

// Выгружаем информацию о странице, убеждаемся, что она действительно существует
$page_info_query = tep_db_query("select p.pages_id, pd.pages_name, pd.pages_description, p.pages_image, p.pages_date_added from " . TABLE_PAGES . " p, " . TABLE_PAGES_DESCRIPTION . " pd where p.pages_status = '1' and p.pages_id = {$pages_id} and pd.pages_id = p.pages_id and pd.language_id = {$_SESSION['languages_id']}");
if($page_info_query && tep_db_num_rows($page_info_query))
{
    $page_info = tep_db_fetch_array($page_info_query);
}
else
{
    require FILENAME_NOT_FOUND;
}

// Хлебные крошки
$breadcrumb->add($page_info['pages_name']);

// Заголовок страницы
$page_title = $page_info['pages_name'].' '.HEAD_TITLE_TAG_DEFAULT ;

// Ключевые слова
$page_meta_keywords = HEAD_KEY_TAG_ALL ;

// Метаописание
$page_meta_description = $page_info['pages_name'] . ' ' . HEAD_DESC_TAG_DEFAULT;

// Изображение
if($page_info['pages_image'])
{
    \EShopmakers\Html\Capture::getInstance('header')->startCapture();
    echo '<link rel="image_src" href="', tep_href_link(DIR_WS_IMAGES . rawurlencode($page_info['pages_image'])), '">';
    \EShopmakers\Html\Capture::getInstance('header')->stopCapture();
}

// Каноническая страница
if(array_diff(array_keys($_GET), array('pages_id', 'language')))
{
    $page_robots_tag = 'noindex, follow';
    $page_link_canonical = tep_href_link(FILENAME_INFORMATION, 'pages_id=' . $pages_id);
}

$content = CONTENT_INFORMATION;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');