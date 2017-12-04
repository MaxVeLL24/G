<?php

/**
 * Меню в шапке
 */

echo '<ul class="menu">';

$information_pages_query = tep_db_query("SELECT b.alias, pb.block_id, pb.sort, p.pages_id, pd.pages_name from pages p, pages_blocks pb, blocks b, pages_description pd
            where p.pages_status = '1'
            and b.id = pb.block_id
            and p.pages_id = pd.pages_id
            and p.pages_id = pb.page_id
            and pd.language_id = " . (int) $languages_id . " order by pb.sort, pd.pages_name");

while($information_pages = tep_db_fetch_array($information_pages_query))
{
    $info_pages[$information_pages['alias']][$information_pages['pages_id']] = $information_pages;
    unset($info_pages[$information_pages['alias']][$information_pages['pages_id']]['block_id']);
    unset($info_pages[$information_pages['alias']][$information_pages['pages_id']]['alias']);
}

echo '<li><a href="/">' . HEADER_LINKS_DEFAULT . '</a></li>';

foreach($info_pages['top-links'] as $page)
{
    echo '<li><a href="' . tep_href_link(FILENAME_INFORMATION, 'pages_id=' . $page['pages_id']) . '">' . $page['pages_name'] . '</a></li>';
}

if(PHOTOGALLERY_MODULE_ENABLED == 'true')
{
    echo '<li><a href="/photogallery.php">' . PHOTOGALLERY . '</a></li>';
}

echo '</ul>';