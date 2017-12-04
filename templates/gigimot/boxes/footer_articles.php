<?php

/**
 * Блок с ссылками на статьи в футере
 */

$articles = array();
$query = tep_db_query("select ad.articles_id, ad.articles_name from articles_description ad, articles a, articles_to_topics a2t, topics t where a.articles_id = ad.articles_id and ad.language_id = {$languages_id} and ad.articles_id = a2t.articles_id and a2t.topics_id = t.topics_id and a.articles_status = 1 group by a.articles_id order by ad.articles_id desc limit 5");
if(!tep_db_num_rows($query)) {
    return;
}
while(($row = tep_db_fetch_array($query)) !== false) {
    $articles[$row['articles_id']] = $row['articles_name'];
}

?>
<ul>
    <?php foreach($articles as $articles_id => $articles_name) { ?>
    <li>
        <a href="<?php echo tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $articles_id); ?>"><?php echo tep_escape($articles_name); ?></a>
    </li>
    <?php } ?>
</ul>
