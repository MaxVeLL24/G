<?php

/**
 * Страница конкретной статьи
 */

/** @var $breadcrumb \breadcrumb */

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_ARTICLES;
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_ARTICLE_INFO;

// ID статьи
$articles_id = isset($_GET['articles_id']) ? filter_var($_GET['articles_id'], FILTER_VALIDATE_INT, array('min_range' => 1)) : null;
if(!$articles_id)
{
    require FILENAME_NOT_FOUND;
}

// Выгружаем информацию о статье из БД и проверяем, существует ли она на самом деле
$query = tep_db_query("select a.articles_id, a.articles_date_added, a.articles_last_modified, ad.articles_name, ad.articles_description, ad.articles_image, ad.articles_head_title_tag, ad.articles_head_desc_tag, ad.articles_head_keywords_tag from articles as a inner join articles_description as ad on a.articles_id = ad.articles_id and ad.language_id = {$_SESSION['languages_id']} where a.articles_id = {$articles_id} and a.articles_status = 1 and (a.articles_date_available is null or a.articles_date_available = '0000-00-00 00:00:00' or a.articles_date_available > now()) limit 1");
if(!tep_db_num_rows($query))
{
    require FILENAME_NOT_FOUND;
}
$article = tep_db_fetch_array($query);

// Увеличить счётчик просмотров статьи
tep_db_query("update articles_description set articles_viewed = articles_viewed + 1 where articles_id = {$articles_id} and language_id = {$_SESSION['languages_id']}");

// Построить цепочку хлебных крошек
$breadcrumb->add(BREADCRUMBS_ROOT_ITEM_TITLE, tep_href_link(FILENAME_ARTICLES));

// Найти категорию, к которой принадлежит эта статья
$query = tep_db_query("select topics_id from articles_to_topics where articles_id = {$articles_id} limit 1");
$topics_id = tep_db_result($query);
if($topics_id)
{
    // Построить родственные связи между категориями
    $topics_parents = array();
    $query = tep_db_query("select topics_id, parent_id from topics where topics_id = " . $topics_id);
    while(($row = tep_db_fetch_array($query)) !== false)
    {
        $topics_parents[$row['topics_id']] = $row['parent_id'];
    }
    $parents_chain = array($topics_id);
    while(!empty($topics_parents[$topics_id]))
    {
        $topics_id = $topics_parents[$topics_id];
        $parents_chain[] = $topics_id;
    }
    $parents_chain = array_reverse($parents_chain);
    
    // Выгрузить название категорий
    $topics_names = array();
    $query = tep_db_query("select topics_id, topics_name from topics_description where topics_id in (" . implode(', ', $parents_chain) . ") and language_id = " . $_SESSION['languages_id']);
    while(($row = tep_db_fetch_array($query)) !== false)
    {
        $topics_names[$row['topics_id']] = $row['topics_name'];
    }
    
    $t_path = array();
    foreach($parents_chain as $topics_id)
    {
        $t_path[] = $topics_id;
        $breadcrumb->add($topics_names[$topics_id], tep_href_link(FILENAME_ARTICLES, 'tPath=' . implode('_', $t_path)));
    }
}
$breadcrumb->add($article['articles_name']);

if (empty($article['articles_head_desc_tag'])) {
    $page_meta_description = HEAD_DESC_ARTICLE_TAG_ALL;
} else {
    if (HTDA_ARTICLE_INFO_ON == '1') {
        $page_meta_description = $article['articles_head_desc_tag'] . ' ' . HEAD_DESC_ARTICLE_TAG_ALL;
    } else {
        $page_meta_description = $article['articles_head_desc_tag'];
    }
}

if (empty($article['articles_head_keywords_tag'])) {
    $page_meta_keywords = HEAD_KEY_ARTICLE_TAG_ALL;
} else {
    if (HTKA_ARTICLE_INFO_ON == '1') {
        $page_meta_keywords = $article['articles_head_keywords_tag'] . ', ' . HEAD_KEY_ARTICLE_TAG_ALL;
    } else {
        $page_meta_keywords = $article['articles_head_keywords_tag'];
    }
}

if (empty($article['articles_head_title_tag'])) {
    $page_title = HEAD_TITLE_ARTICLE_TAG_ALL . ' - ' . HEAD_TITLE_TAG_ARTICLES;
} else {
    if (HTTA_ARTICLE_INFO_ON == '1') {
        $page_title = HEAD_TITLE_ARTICLE_TAG_ALL . ' - ' . HEAD_TITLE_TAG_ARTICLE_INFO . ' - ' . $topics['topics_name'] . ' - ' . clean_html_comments($article['articles_head_title_tag']);
    } else {
        $page_title = clean_html_comments($article['articles_head_title_tag']);
    }
}

// Изображение
if($article['atricles_image'])
{
    \EShopmakers\Html\Capture::getInstance('header')->startCapture();
    echo '<link rel="image_src" href="', tep_href_link(DIR_WS_IMAGES . rawurlencode($article['atricles_image'])), '">';
    \EShopmakers\Html\Capture::getInstance('header')->stopCapture();
}

// Каноническая страница
if(array_diff(array_keys($_GET), array('articles_id', 'language')))
{
    $page_robots_tag = 'noindex, follow';
    $page_link_canonical = tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $articles_id);
}

$content = CONTENT_ARTICLE_INFO;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');