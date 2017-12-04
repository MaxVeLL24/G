<?php

/**
 * Страница со списком статей категории
 */
?>
<h1><?php echo $page_header; ?></h1>
<?php if($articles) : ?>
<?php /* Пагинация */ ?>
<?php if($split->number_of_pages > 1) : ?>
<div class="block-pagination common-styled-block">
    <?php echo $split->display_links(10); ?>
</div>
<?php endif; ?>
<?php /* Список статей */ ?>
<?php foreach($articles as $article) : ?>
<article class="article" itemscope itemtype="http://schema.org/Article">
    <meta itemprop="author" content="Gigimot"/>
    <meta itemprop="image" content="https://gigimot.com.ua/images/logo.png">
    <meta itemprop="articleSection" content="Игрушки">
    <h2 itemprop="name headline">
        <a href="<?php echo $article['link']; ?>" itemprop="url"><?php echo tep_escape($article['articles_name']); ?></a>
    </h2>
    <?php if(!empty($article['date'])) : ?>
    <time datetime="<?php echo tep_date_atom($article['date']); ?>" itemprop="datePublished"><?php echo tep_date_long($article['date']); ?></time>
    <?php endif; ?>
    <div class="tab-content common-styled-block" itemprop="articleBody"><?php echo $article['text']; ?></div>
    <div class="readmore text-right">
        <a href="<?php echo $article['link']; ?>" class="button"><?php echo READ_MORE_LINK_TEXT; ?></a>
    </div>
</article>
<?php endforeach; ?>
<?php /* Пагинация */ ?>
<?php if($split->number_of_pages > 1) : ?>
<div class="block-pagination common-styled-block">
    <?php echo $split->display_links(10); ?>
</div>
<?php endif; ?>
<?php /* Сообщение о том, что в категории нет статей */ ?>
<?php else : ?>
<div class="alert alert-info" role="alert"><?php echo NO_ARTICLES_ALERT_TEXT; ?></div>
<?php endif; ?>