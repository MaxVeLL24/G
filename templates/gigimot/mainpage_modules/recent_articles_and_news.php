<?php

/**
 * Модуль последних новостей и статей для главной страницы
 */

// Выгрузить статьи
if(ARTICLES_MODULE_ENABLED == 'true')
{
    $query_art = "select a.articles_date_added, ad.articles_image, ad.articles_id, ad.articles_name, ad.articles_description, ad.articles_head_desc_tag
                  from " . TABLE_ARTICLES_DESCRIPTION . " ad, " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_TO_TOPICS . " a2t, " . TABLE_TOPICS . " t
                  where a.articles_id=ad.articles_id
                  and ad.articles_id=a2t.articles_id
                  and a2t.topics_id=t.topics_id
                  and ad.articles_name != ''
                  and a.articles_status=1
                  and ad.language_id = " . $languages_id . "
                  order by ad.articles_id DESC LIMIT 3";
    $query_art_info = tep_db_query($query_art);
    $articles = array();
    if(tep_db_num_rows($query_art_info))
    {
        while(($row = tep_db_fetch_array($query_art_info)) !== false)
        {
            $row['articles_description'] = strip_tags($row['articles_description']);
            $row['articles_description'] = mb_substr($row['articles_description'], 0, 160, 'UTF-8') . (mb_strlen($row['articles_description'], 'UTF-8') > 160 ? '…' : '');
            $articles[] = $row;
        }
    }
}

// Выгрузить новости
$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from " . TABLE_NEWSDESK_CONFIGURATION . "");
while (($configuration = tep_db_fetch_array($configuration_query)) !== false)
{
    if(!defined($configuration['cfgKey']))
    {
        define($configuration['cfgKey'], $configuration['cfgValue']);
    }
}
$newsdesk_var_query = tep_db_query(
            'select p.newsdesk_id, pd.language_id, pd.newsdesk_article_name, pd.newsdesk_article_description, pd.newsdesk_article_shorttext, pd.newsdesk_article_url,
            p.newsdesk_image, p.newsdesk_image_two, p.newsdesk_image_three, p.newsdesk_date_added, p.newsdesk_last_modified, pd.newsdesk_article_viewed,
            p.newsdesk_date_available, p.newsdesk_status  from ' . TABLE_NEWSDESK . ' p, ' . TABLE_NEWSDESK_DESCRIPTION . '
            pd WHERE pd.newsdesk_id = p.newsdesk_id and pd.language_id = "' . $languages_id . '" and newsdesk_status = 1 and p.newsdesk_sticky = 0 ORDER BY newsdesk_date_added DESC LIMIT 4');
$news = array();
if(tep_db_num_rows($newsdesk_var_query))
{
    $do_once = false;
    while(($row = tep_db_fetch_array($newsdesk_var_query)) !== false)
    {
        $row['newsdesk_article_description'] = strip_tags($row['newsdesk_article_description']);
        if($do_once)
        {
            $row['newsdesk_article_description'] = mb_substr($row['newsdesk_article_description'], 0, 160, 'UTF-8') . (mb_strlen($row['newsdesk_article_description'], 'UTF-8') > 160 ? '…' : '');
        }
        else
        {
            $do_once = true;
            $row['newsdesk_article_description'] = mb_substr($row['newsdesk_article_description'], 0, 580, 'UTF-8') . (mb_strlen($row['newsdesk_article_description'], 'UTF-8') > 580 ? '…' : '');
        }
        $news[] = $row;
    }
}

?>
<div class="mpm-recent-articles-and-news clearfix">
    <?php if(ARTICLES_MODULE_ENABLED == 'true' && !empty($articles)): ?>
    <div class="column-articles">
        <div class="padding">
            <div class="block-articles">
                <a href="<?php echo tep_href_link(FILENAME_ARTICLES, 'tPath=0'); ?>" class="column-title"><?php echo BOX_NEW_ARTICLES; ?></a>
                <?php foreach($articles as $article) : ?>
                <div class="item clearfix">
                    <?php if($article['articles_image']) : ?>
                    <a
                        class="image"
                        href="<?php echo tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $article['articles_id']); ?>"
                        title="<?php echo tep_escape(sprintf(READMORE_ARTICLE_LINK_TITLE, $article["articles_name"])); ?>"
                        >
                        <img
                            src="<?php echo tep_href_link(FILENAME_IMAGE_RESIZER, 'w=116&h=116&thumb=' . rawurlencode($article['articles_image'])); ?>"
                            alt="<?php echo tep_escape($article["articles_name"]); ?>"
                            />
                    </a>
                    <?php endif; ?>
                    <div class="text">
                        <div class="title">
                            <a
                                href="<?php echo tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $article['articles_id']); ?>"
                                title="<?php echo tep_escape(sprintf(READMORE_ARTICLE_LINK_TITLE, $article["articles_name"])); ?>"
                                ><?php echo tep_escape($article["articles_name"]); ?></a>
                        </div>
                        <div class="description"><?php echo $article['articles_description']; ?></div>
                        <div class="date"><?php echo tep_date_long($article['articles_date_added']); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if($news) : ?>
    <div class="column-news">
        <div class="padding">
            <div class="block-news">
                <a href="<?php echo tep_href_link(FILENAME_NEWSDESK_INDEX, 'newsPath=0'); ?>" class="column-title"><?php echo BOX_HEADING_NEWSDESK_LATEST; ?></a>
                <div class="columns clearfix">
                    <div class="primary-item">
                        <div class="item">
                            <?php if($news[0]['newsdesk_image']) : ?>
                            <a
                                class="image"
                                href="<?php echo tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $news[0]['newsdesk_id']); ?>"
                                title="<?php echo tep_escape(sprintf(READMORE_NEWS_LINK_TITLE, $news[0]["newsdesk_article_name"])); ?>"
                                >
                                <img
                                    src="/articles_images/<?= $news[0]["newsdesk_image"]; ?>"
                                    alt="<?php echo tep_escape($news[0]["newsdesk_article_name"]); ?>"
                                    />
                            </a>
                            <?php endif; ?>
                            <div class="title">
                                <a
                                    href="<?php echo tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $news[0]['newsdesk_id']); ?>"
                                    title="<?php echo tep_escape(sprintf(READMORE_NEWS_LINK_TITLE, $news[0]["newsdesk_article_name"])); ?>"
                                    ><?php echo tep_escape($news[0]["newsdesk_article_name"]); ?></a>
                            </div>
                            <div class="description"><?php echo $news[0]["newsdesk_article_description"]; ?></div>
                            <div class="date"><?php echo tep_date_long($news[0]["newsdesk_date_added"]); ?></div>
                        </div>
                    </div>
                    <?php if(count($news) > 1) : ?>
                    <div class="secondary-items">
                        <?php for($j = 1; $j < count($news); $j++) : ?>
                        <div class="item">
                            <div class="title">
                                <a
                                    href="<?php echo tep_href_link(FILENAME_NEWSDESK_INFO, 'newsdesk_id=' . $news[$j]['newsdesk_id']); ?>"
                                    title="<?php echo tep_escape(sprintf(READMORE_NEWS_LINK_TITLE, $news[$j]["newsdesk_article_name"])); ?>"
                                    ><?php echo tep_escape($news[$j]["newsdesk_article_name"]); ?></a>
                            </div>
                            <div class="description"><?php echo $news[$j]["newsdesk_article_description"]; ?></div>
                            <div class="date"><?php echo tep_date_long($news[$j]["newsdesk_date_added"]); ?></div>
                        </div>
                        <?php endfor; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>