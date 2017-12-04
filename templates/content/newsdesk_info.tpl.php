<?php
/**
 * Страница конкретной новости
 */
?>

<article class="article" itemscope itemtype="http://schema.org/Article">
    <h1 itemprop="name headline"><?php echo tep_escape($news['newsdesk_article_name']); ?></h1>
    <time datetime="<?php echo tep_date_atom($news['newsdesk_date_added']); ?>" itemprop="datePublished"><?php echo tep_date_long($news['newsdesk_date_added']); ?></time>
    <div class="tab-content common-styled-block" itemprop="articleBody"><?php echo $news['newsdesk_article_description']; ?></div>
</article>
