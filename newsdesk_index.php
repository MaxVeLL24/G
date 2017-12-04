<?php

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . DIRECTORY_SEPARATOR . FILENAME_NEWSDESK_INDEX;

$news_path_array = array();
if(!array_key_exists('newsPath', $_GET))
{
    tep_redirect(tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=0'));
}
$news_path_array = explode('_', $_GET['newsPath']);
if(empty($news_path_array))
{
    require FILENAME_NOT_FOUND;
}
$news_path_array = array_filter($news_path_array, 'is_numeric');
if(empty($news_path_array))
{
    require FILENAME_NOT_FOUND;
}

// Текущая страница
$page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT) : null;
if($page === null)
{
    $page = 1;
}
elseif($page === false || $page < 1)
{
    require FILENAME_NOT_FOUND;
}
elseif($page === 1)
{
    // Если в ссылке фигурирует номер страницы и он равен 1, то редиректим
    // пользователя на страницу вообще без номера станицы в ссылке для
    // исключения дублей
    tep_redirect(tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=' . implode('_', $news_path_array)));
}

// Построить зависимости родитель - потомок для всех категорий новостей
$categories_children = array();
$categories_parents = array();
$categories_names = array();
$query_string = <<<SQL
SELECT
    nc.categories_id,
    nc.parent_id,
    ncd.categories_name
FROM newsdesk_categories AS nc
INNER JOIN newsdesk_categories_description AS ncd
ON
    nc.categories_id = ncd.categories_id AND
    ncd.language_id = {$_SESSION['languages_id']}
WHERE nc.catagory_status = 1
SQL;
$query = tep_db_query($query_string);
if(tep_db_num_rows($query))
{
    while(($row = tep_db_fetch_array($query)) !== false)
    {
        if(empty($categories_children[$row['parent_id']]))
        {
            $categories_children[$row['parent_id']] = array();
        }
        $categories_children[$row['parent_id']][] = $row['categories_id'];
        $categories_parents[$row['categories_id']] = $row['parent_id'];
        $categories_names[$row['categories_id']] = $row['categories_name'];
    }
}

// Проверить, что запрошенная цепочка категорий действительно существует
$selected_category_id = end($news_path_array);
$selected_categories_real_stack = array($selected_category_id);
$tmp = $selected_category_id;
while($tmp && !empty($categories_parents[$tmp]))
{
    $tmp = $categories_parents[$tmp];
    $selected_categories_real_stack[] = $tmp;
}
$selected_categories_real_stack = array_reverse($selected_categories_real_stack);
if($selected_categories_real_stack != $news_path_array)
{
    require FILENAME_NOT_FOUND;
}

// Выгрузить новости
$query_string = "select n.newsdesk_id, n.newsdesk_date_added, nd.newsdesk_article_name, nd.newsdesk_article_shorttext from newsdesk_to_categories as ntc inner join newsdesk as n on ntc.newsdesk_id = n.newsdesk_id and n.newsdesk_status = 1 and (n.newsdesk_date_available is null or n.newsdesk_date_available = '0000-00-00 00:00:00' or n.newsdesk_date_available >= now()) inner join newsdesk_description as nd on nd.newsdesk_id = n.newsdesk_id and nd.language_id = {$_SESSION['languages_id']}" . (empty($selected_category_id) ? "" : " where ntc.categories_id = {$selected_category_id}") . " order by n.newsdesk_date_added desc";
$split = new splitPageResults($query_string, 10);
if($split->number_of_pages < $page)
{
    require FILENAME_NOT_FOUND;
}
$news = array();
$query = tep_db_query($split->sql_query);
if(tep_db_num_rows($query))
{
    while(($row = tep_db_fetch_array($query)) !== false)
    {
        $row['link'] = tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $row['newsdesk_id']);
        $news[] = $row;
    }
}

// Заголовок страницы и хлебные крошки
if($selected_category_id)
{
    // Если мы находимся в какой-то категории
    $page_header = $page_title = $categories_names[$selected_category_id];
    $breadcrumb->add(BREADCRUMBS_ROOT_ITEM_TITLE, tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=0'));
    for($i = 0; $i < count($selected_categories_real_stack); $i++)
    {
        if($selected_categories_real_stack[$i] == $selected_category_id)
        {
            $breadcrumb->add($categories_names[$selected_categories_real_stack[$i]]);
        }
        else
        {
            $breadcrumb->add($categories_names[$selected_categories_real_stack[$i]], tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=' . implode('_', array_slice($selected_categories_real_stack, 0, $i + 1))));
        }
    }
}
else
{
    // Если мы находимся в корне
    $page_title = PAGE_TITLE_DEFAULT . ' ' . HEAD_TITLE_TAG_DEFAULT;
    $page_header = PAGE_HEADER_DEFAULT;
    $page_meta_description = HEAD_DESC_TAG_DEFAULT;
    $breadcrumb->add(BREADCRUMBS_ROOT_ITEM_TITLE);
}
if($page > 1)
{
    $page_title .= sprintf(TITLE_ADD_PAGE_NUMBER, $page);
}
$page_title .= (@constant('TITLE_DELIMITER') && @constant('HEAD_TITLE_TAG_ALL') ? TITLE_DELIMITER . HEAD_TITLE_TAG_ALL : '');


// Каноничность страницы
$is_canonical_page = !array_diff(array_keys($_GET), array('newsPath'));
if(!$is_canonical_page)
{
    $page_link_canonical = tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=' . implode('_', $news_path_array));
}

// Предыдущая страница
if($split->current_page_number > 2)
{
    $page_link_prev = tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=' . implode('_', $news_path_array) . ($split->current_page_number - 1 > 1 ? '&page=' . $split->current_page_number - 1 : ''));
}

// Следующая страница
if($split->current_page_number < $split->number_of_pages)
{
    $page_link_next = tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=' . implode('_', $news_path_array) . '&page=' . $split->current_page_number + 1);
}

$content = CONTENT_NEWSDESK_INDEX;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');