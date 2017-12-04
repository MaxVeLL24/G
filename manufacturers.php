<?php

// Страница со списком всех производителей

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $language . '/' . FILENAME_MANUFACTURERS;

$query_string = <<<SQL
SELECT
    manufacturers_id,
    manufacturers_name,
    manufacturers_image
FROM manufacturers
SQL;

$latin_alphabet = array(
    'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
    'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
    'other'
);

$query = tep_db_query($query_string);
$manufacturers = array();
$manufacturers_to_letters = array();
while(($row = tep_db_fetch_array($query)) !== false)
{
    $manufacturers[$row['manufacturers_id']] = $row;
    $first_letter = mb_strtolower($row['manufacturers_name'][0]);
    if(!in_array($first_letter, $latin_alphabet))
    {
        $first_letter = 'other';
    }
    if(empty($manufacturers_to_letters[$first_letter]))
    {
        $manufacturers_to_letters[$first_letter] = array();
    }
    $manufacturers_to_letters[$first_letter][] = $row['manufacturers_id'];
}

$content = CONTENT_MANUFACTURERS;
$page_title = MANUFACTURERS_PAGE_TITLE .' '. HEAD_TITLE_TAG_DEFAULT;
$page_meta_keywords = MANUFACTURERS_PAGE_META_KEYWORDS;
$page_meta_description = MANUFACTURERS_PAGE_TITLE .' '. HEAD_DESC_TAG_DEFAULT;
$breadcrumb->add(MANUFACTURERS_PAGE_TITLE);

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');