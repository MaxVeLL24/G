<?php

/**
 * Шаблон главной страницы
 */

// Заголовок страницы
$page_title = HEAD_TITLE_TAG_ALL . '' . HEAD_TITLE_TAG_DEFAULT;

// Ключевые слова
$page_meta_keywords = HEAD_KEY_TAG_DEFAULT;

// Метаописание
$page_meta_description = HEAD_DESC_TAG_DEFAULT;

// Каноническая страница
if(array_diff(array_keys($_GET), array('products_id', 'language')))
{
    $page_robots_tag = 'noindex, follow';
    $page_link_canonical = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id);
}

if (file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/' . $template_name)) {
    $modules_folder = (DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/' . $template_name);
} else {
    $modules_folder = DIR_WS_MODULES . '/mainpage_modules/';
}
$modules = array(
    INCLUDE_MODULE_ONE,
    INCLUDE_MODULE_TWO,
    INCLUDE_MODULE_THREE,
    INCLUDE_MODULE_FOUR,
    INCLUDE_MODULE_FIVE,
    INCLUDE_MODULE_SIX
);

$countModules = count($modules);
for ($i = 0; $i < $countModules; $i++) {
    if (tep_not_null($modules[$i])) {
        include($modules_folder . $modules[$i]);
    }
}