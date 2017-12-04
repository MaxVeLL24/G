<?php

/**
 * Шаблон страницы конктретной новости
 */

?>
<article class="article" itemscope itemtype="http://schema.org/Article">
    <meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="<?=HTTP_SERVER.$_SERVER['REQUEST_URI'] ?>" content="<?= $product_info_values['newsdesk_article_name']; ?>"/>
    <h1 itemprop="name headline"><?php echo tep_escape($article['articles_name']); ?></h1>
    <meta itemprop="publisher" itemtype="Organization" content="Gigimot"/>
    <meta itemprop="author" content="Gigimot"/>
    <meta itemprop="image" content="https://gigimot.com.ua/images/logo.png">
    <time datetime="<?php echo tep_date_atom($article['articles_last_modified'] ? $article['articles_last_modified'] : $article['articles_date_added']); ?>" itemprop="datePublished"><?php echo tep_date_long($article['articles_last_modified'] ? $article['articles_last_modified'] : $article['articles_date_added']); ?></time>
    <div class="tab-content common-styled-block" itemprop="articleBody"><?php echo $article['articles_description']; ?></div>
    <div class="related-article-products">
        <?php
        if ( (USE_CACHE == 'true') && !SID) {
            include(DIR_WS_MODULES . FILENAME_ARTICLES_XSELL);
        } else {
            include(DIR_WS_MODULES . FILENAME_ARTICLES_XSELL);
        }
        ?>
    </div>
</article>