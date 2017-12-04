<?php

/*
  $Id: allprods.php,v 1.7 2002/12/02

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce
  Copyright (c) 2002 HMCservices

  Released under the GNU General Public License
 */

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_ARTICLES;

function addPageNumberToPageHeader($header)
{
    global $page, $page_link_prev, $page_link_next, $page_link_canonical, $split, $t_path;
    if($page > 1)
    {
        $header .= sprintf(TITLE_ADD_PAGE_NUMBER, $page);
        $page_link_prev = tep_href_link(FILENAME_ARTICLES, empty($t_path) ? '' : 'tPath=' . $t_path . ($page - 1 > 1 ? '&page=' . ($page - 1) : ''));
        $page_link_canonical = tep_href_link(FILENAME_ARTICLES, empty($t_path) ? '' : 'tPath=' . $t_path);
    }
    if($page < $split->number_of_pages)
    {
        $page_link_next = tep_href_link(FILENAME_ARTICLES, empty($t_path) ? '' : 'tPath=' . $t_path . '&page=' . ($page + 1));
    }
    return $header;
}

function loadArticles($query)
{
    $articles = array();
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            if($row['articles_date_added'] && $row['articles_date_added'] !== '0000-00-00 00:00:00')
            {
                $row['date'] = $row['articles_date_added'];
            }
            elseif($row['articles_last_modified'] && $row['articles_last_modified'] !== '0000-00-00 00:00:00')
            {
                $row['date'] = $row['articles_last_modified'];
            }
            $row['link'] = tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $row['articles_id']);
            $row['text'] = strip_tags($row['articles_description']);
            $text_length = mb_strlen($row['text'], CHARSET);
            $row['text'] = mb_substr($row['text'], 0, 600, CHARSET);
            if($text_length > 600)
            {
                $row['text'] .= '…';
            }
            $articles[] = $row;
        }
    }
    return $articles;
}

// Номер страницы
$page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT) : null;
if($page === null)
{
    $page = 1;
}
elseif($page === 1)
{
    tep_redirect(tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('page'))));
}
elseif($page === false || $page <= 0)
{
    require FILENAME_NOT_FOUND;
}

// Категория
$t_path = array();
if(array_key_exists('tPath', $_GET))
{
    $t_path = array_filter(explode('_', strval($_GET['tPath'])), 'is_numeric');
    if(implode('_', $t_path) !== $_GET['tPath'])
    {
        require FILENAME_NOT_FOUND;
    }
}
else
{
    tep_redirect(tep_href_link(FILENAME_ARTICLES, 'tPath=0'));
}

$articles = array();
if(empty($t_path) || $t_path[0] == 0)
{
    $query_string = "select a.articles_id, a.articles_date_added, a.articles_last_modified, ad.articles_name, ad.articles_description from articles as a inner join articles_description as ad on a.articles_id = ad.articles_id and ad.language_id = {$_SESSION['languages_id']} where a.articles_status = 1 and (a.articles_date_available is null or a.articles_date_available = '0000-00-00 00:00:00' or a.articles_date_available > now()) order by a.articles_date_added asc, a.articles_last_modified asc";
    $split = new splitPageResults($query_string, 10);
    if($split->number_of_pages < $page)
    {
        require FILENAME_NOT_FOUND;
    }
    $articles = loadArticles(tep_db_query($split->sql_query));
    
    // Заголовок и метаданные страницы
    $page_title = PAGE_TITLE_DEFAULT;
    $page_meta_keywords = PAGE_META_KEYWORDS_DEFAULT;
    $page_meta_description = PAGE_META_DESCRIPTION_DEFAULT;
    $page_header = PAGE_HEADER_DEFAULT;
    $breadcrumb->add(BREADCRUMBS_ROOT_ITEM_TITLE);
}
else
{
    // Построить взаимосвязи между темами-родителями и темами-потомками
    $query_string = "select t.topics_id, t.parent_id, td.topics_name from topics as t inner join topics_description as td on t.topics_id = td.topics_id and td.language_id = {$_SESSION['languages_id']}";
    $topics_names = array();
    $topics_children = array();
    $query = tep_db_query($query_string);
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $topics_names[$row['topics_id']] = $row['topics_name'];
            if(empty($topics_children[$row['parent_id']]))
            {
                $topics_children[$row['parent_id']] = array();
            }
            $topics_children[$row['parent_id']][] = $row['topics_id'];
        }
    }
    
    // Добавить название категорий верхнего уровня в цепочку навигации
    $breadcrumb->add(BREADCRUMBS_ROOT_ITEM_TITLE, tep_href_link(FILENAME_ARTICLES, '', 'NONSSL'));
    foreach($t_path as $i => $topic_id)
    {
        $breadcrumb->add($topics_names[$topic_id], tep_href_link(FILENAME_ARTICLES, 'tPath=' . implode('_', array_slice($t_path, 0, $i + 1))));
    }
    
    // Выгрузить статьи из текущей темы
    $topic_id = end($t_path);
    $query_string = "select a.articles_id, a.articles_date_added, a.articles_last_modified, ad.articles_name, ad.articles_description from articles_to_topics as att inner join articles as a on a.articles_id = att.articles_id and a.articles_status = 1 and (a.articles_date_available is null or a.articles_date_available = '0000-00-00 00:00:00' or a.articles_date_available > now()) inner join articles_description as ad on a.articles_id = ad.articles_id and ad.language_id = {$_SESSION['languages_id']} where att.topics_id = {$topic_id} order by a.articles_date_added asc, a.articles_last_modified asc";
    $split = new splitPageResults($query_string, 10);
    // Если в текущей теме нет статей, то проверяем, есть ли дочерние темы у
    // текущей темы и пытаемся выгрузить статьи из них
    if(!$split->number_of_rows && !empty($topics_children[$topic_id]))
    {
        $search_in_topics = array();
        $topics_stack = $topics_children[$topic_id];
        while(($_topic_id = array_pop($topics_stack)) !== null)
        {
            $search_in_topics[] = $_topic_id;
            if(!empty($topics_children[$_topic_id]))
            {
                $topics_stack = array_merge($topics_stack, $topics_children[$_topic_id]);
            }
        }
        $search_in_topics = implode(',', array_unique($search_in_topics));
        $query_string = "select a.articles_id, a.articles_date_added, a.articles_last_modified, ad.articles_name, ad.articles_description from articles_to_topics as att inner join articles as a on a.articles_id = att.articles_id and a.articles_status = 1 and (a.articles_date_available is null or a.articles_date_available = '0000-00-00 00:00:00' or a.articles_date_available > now()) inner join articles_description as ad on a.articles_id = ad.articles_id and ad.language_id = {$_SESSION['languages_id']} where att.topics_id IN ({$search_in_topics}) order by a.articles_date_added asc, a.articles_last_modified asc";
        $split = new splitPageResults($query_string, 10);
        if($split->number_of_pages < $page)
        {
            require FILENAME_NOT_FOUND;
        }
        $articles = loadArticles(tep_db_query($split->sql_query));
    }
    else
    {
        if($split->number_of_pages < $page)
        {
            require FILENAME_NOT_FOUND;
        }
        $articles = loadArticles(tep_db_query($split->sql_query));
    }
    
    $page_title = addPageNumberToPageHeader($topics_names[$topic_id]);
    $page_header = $page_title;
}

$page_title .= (@constant('TITLE_DELIMITER') && @constant('HTTA_ARTICLES_ON') && @constant('HEAD_TITLE_TAG_ARTICLES') ? TITLE_DELIMITER . HEAD_TITLE_TAG_ARTICLES : '') . (@constant('TITLE_DELIMITER') && @constant('HEAD_TITLE_TAG_ALL') ? TITLE_DELIMITER . HEAD_TITLE_TAG_ALL : '');

// Метаописание
$page_meta_description = HEAD_DESC_TAG_ARTICLES . ' ' . HEAD_DESC_TAG_DEFAULT;

// Ключевые слова
if(@constant('HTKA_ARTICLES_ON') && @constant('HEAD_KEY_TAG_ARTICLES'))
{
    $page_meta_keywords = HEAD_KEY_TAG_ARTICLES . @constant('HEAD_KEY_TAG_ALL');
}

// Запретить индексировать эту страницу, если в категории нет статей
if(empty($articles))
{
    $page_robots_tag = 'noindex, follow';
}

// Каноническая страница
if(array_diff(array_keys($_GET), array('tPath', 'page', 'language')))
{
    $page_robots_tag = 'noindex, follow';
    $query_parts = array();
    if($t_path)
    {
        $query_parts[] = 'tPath=' . implode('_', $t_path);
    }
    if($page)
    {
        $query_parts[] = 'page=' . $page;
    }
    $page_link_canonical = tep_href_link(FILENAME_ARTICLES, $query_parts ? implode('&', $query_parts) : '');
}

$content = CONTENT_ARTICLES;
$body_class = 'articles-page';

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');