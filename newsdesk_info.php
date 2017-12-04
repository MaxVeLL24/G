<?php

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_NEWSDESK_INDEX;
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_NEWSDESK_INFO;

// Статья
$newsdesk_id = isset($_GET['newsdesk_id']) ? filter_var($_GET['newsdesk_id'], FILTER_VALIDATE_INT, array('min_range' => 1)) : null;
if(!$newsdesk_id)
{
    require FILENAME_NOT_FOUND;
}
$news_query = tep_db_query("select p.newsdesk_id, p.newsdesk_date_added, p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, pd.newsdesk_article_name, pd.newsdesk_article_description from " . TABLE_NEWSDESK . " as p inner join " . TABLE_NEWSDESK_DESCRIPTION . " as pd on pd.newsdesk_id = p.newsdesk_id and pd.language_id = {$_SESSION['languages_id']} where p.newsdesk_id = {$newsdesk_id}");
if(!tep_db_num_rows($news_query))
{
    require FILENAME_NOT_FOUND;
}
$news = tep_db_fetch_array($news_query);

// Хлебные крошки
$breadcrumb->add(BREADCRUMBS_ROOT_ITEM_TITLE, tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=0'));
$query = tep_db_query("select categories_id from newsdesk_to_categories where newsdesk_id = " . $newsdesk_id);
if(tep_db_num_rows($query))
{
    $categories_id = tep_db_fetch_array($query);
    $categories_id = $categories_id['categories_id'];
    if($categories_id)
    {
        // Выстраиваем родительские связи
        $query = tep_db_query("select categories_id, parent_id from newsdesk_categories");
        $categories_parents = array();
        $categories_chain = array();
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $categories_parents[$row['categories_id']] = $row['parent_id'];
        }
        $categories_chain[] = $categories_id;
        while(!empty($categories_parents[$categories_id]))
        {
            $categories_id = $categories_parents[$categories_id];
            $categories_chain[] = $categories_id;
        }
        $categories_chain = array_reverse($categories_chain);
        
        // Выгружаем названия категорий
        $categories_names = array();
        $query = tep_db_query("select categories_name, categories_id from newsdesk_categories_description where categories_id in (" . implode(', ', $categories_chain) . ") and language_id = " . $_SESSION['languages_id']);
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $categories_names[$row['categories_id']] = $row['categories_name'];
        }
        $c_path = array();
        
        // Строим цепочку хлебных крошек из категорий
        foreach($categories_chain as $categories_id)
        {
            $c_path[] = $categories_id;
            $breadcrumb->add($categories_names[$categories_id], tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=' . implode('_', $c_path)));
        }
    }
}
$breadcrumb->add($news['newsdesk_article_name']);

// Заголовок страницы
$page_title = $news['newsdesk_article_name'];

// Ключевые слова
$page_meta_keywords = HEAD_KEY_TAG_ALL;

// Метаописание
$page_meta_description = substr(strip_tags($news['newsdesk_article_description']),0,200);

// Изображение
if($news['newsdesk_image'] || $news['newsdesk_image_two'] || $news['newsdesk_image_three'])
{
    \EShopmakers\Html\Capture::getInstance('header')->startCapture();
    echo '<link rel="image_src" href="', tep_href_link(DIR_WS_IMAGES . rawurlencode(getFirstNoneEmpty($news['newsdesk_image'], $news['newsdesk_image_two'], $news['newsdesk_image_three']))), '">';
    \EShopmakers\Html\Capture::getInstance('header')->stopCapture();
}

// Неканоничная страница, если в параметрах запроса есть что-то помимо newsdesk_id
if(array_diff(array_keys($_GET), array('newsdesk_id', 'language')))
{
    $page_robots_tag = 'noindex, follow';
    $page_link_canonical = tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $newsdesk_id);
}

$content = CONTENT_NEWSDESK_INFO;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');