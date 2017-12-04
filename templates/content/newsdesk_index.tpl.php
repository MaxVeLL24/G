<?php

/**
 * Страница со списком новостей категории
 */

?>
<h1><?php echo $page_header; ?></h1>
<?php if($news) : ?>
<?php /* Пагинация */ ?>
<?php if($split->number_of_pages > 1) : ?>
<div class="block-pagination common-styled-block">
    <?php echo $split->display_links(10); ?>
</div>
<?php endif; ?>
<?php /* Список новостей */ ?>
<?php foreach($news as $new) : ?>
<article class="article" itemscope itemtype="http://schema.org/Article">
    <h2 itemprop="name headline">
        <a href="<?php echo $new['link']; ?>" itemprop="url"><?php echo tep_escape($new['newsdesk_article_name']); ?></a>
    </h2>
    <time datetime="<?php echo tep_date_atom($new['newsdesk_date_added']); ?>" itemprop="datePublished"><?php echo tep_date_long($new['newsdesk_date_added']); ?></time>
    <div class="tab-content common-styled-block" itemprop="articleBody"><?php echo $new['newsdesk_article_shorttext']; ?></div>
    <div class="readmore text-right">
        <a href="<?php echo $new['link']; ?>" class="button"><?php echo READ_MORE_LINK_TEXT; ?></a>
    </div>
</article>
<?php endforeach; ?>
<?php /* Пагинация */ ?>
<?php if($split->number_of_pages > 1) : ?>
<div class="block-pagination common-styled-block">
    <?php echo $split->display_links(10); ?>
</div>
<?php endif; ?>
<?php /* Сообщение о том, что в категории нет новостей */ ?>
<?php else : ?>
<div class="alert alert-info" role="alert"><?php echo NO_ARTICLES_ALERT_TEXT; ?></div>
<?php endif; ?>