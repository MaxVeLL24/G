<?php

// Базовый URL
$base_url = ($request_type === 'SSL') ? HTTPS_SERVER . DIR_WS_HTTPS_CATALOG : HTTP_SERVER . DIR_WS_HTTP_CATALOG;

if (!$_GET['cPath']==null || !$_GET['cPath']==''){
    $p_ath=end(explode('_',$_GET['cPath']));
    $query = tep_db_query("SELECT `categories_status` FROM `categories` WHERE `categories_id`='$p_ath'");
    $cat_status=tep_db_fetch_array($query);
    if (!$cat_status['categories_status']==1){
        require_once 'not_found.php';
    }
}
// Левая боковая колонка
$display_left_column = false;
$left_column_modules = array();
if(DISPLAY_COLUMN_LEFT == 'yes' && $content === CONTENT_INDEX_PRODUCTS)
{
    $query = tep_db_query('select display_in_column as cfgcol, infobox_file_name as cfgtitle, infobox_display as cfgvalue, infobox_define as cfgkey, box_heading, box_template, box_heading_font_color from ' . TABLE_INFOBOX_CONFIGURATION . ' where template_id = ' . TEMPLATE_ID . ' and infobox_display = "yes" and display_in_column = "left" order by location');
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $left_column_modules[] = $row;
        }
        $display_left_column = true;
    }
}

// Правая боковая колонка
$display_right_column = false;
$right_column_modules = array();
if(DISPLAY_COLUMN_RIGHT == 'yes' && $content === CONTENT_INDEX_PRODUCTS)
{
    $query = tep_db_query('select display_in_column as cfgcol, infobox_file_name as cfgtitle, infobox_display as cfgvalue, infobox_define as cfgkey, box_heading, box_template, box_heading_font_color from ' . TABLE_INFOBOX_CONFIGURATION . ' where template_id = ' . TEMPLATE_ID . ' and infobox_display = "yes" and display_in_column = "right" order by location');
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $right_column_modules[] = $row;
        }
        $display_right_column = true;
    }
}

// Пререндер контента
$body_itemtype = 'http://schema.org/WebPage';
ob_start();
if(isset($content_template))
{
    require(DIR_WS_CONTENT . $content_template);
}
else
{
    require(DIR_WS_CONTENT . $content . '.tpl.php');
    switch($content)
    {
        case CONTENT_PRODUCT_INFO :
            $body_itemtype = 'http://schema.org/ItemPage';
            break;
    }
}
$_content = ob_get_contents();
ob_end_clean();

// Robots tag
if(!empty($page_robots_tag))
{
    header('X-Robots-Tag: ' . $page_robots_tag);
}

?>
<!DOCTYPE html>
<html <?= HTML_PARAMS; ?>>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <base href="<?php echo $base_url; ?>" />
        <link rel="stylesheet" href="templates/gigimot/css/stylesheet.css" />
        <?php
        
        if(!empty($page_title) || !empty($page_meta_keywords) || !empty($page_meta_description) || !empty($page_link_canonical) || !empty($page_link_prev) || !empty($page_link_next) || !empty($page_robots_tag))
        {
            ?>
        <?php if(!empty($page_title)) : ?>
        <title><?php echo $page_title; ?></title>
        <?php endif; ?>
        <?php if(!empty($page_meta_keywords)) : ?>
        <meta name="keywords" content="<?php echo $page_meta_keywords; ?>" />
        <?php endif; ?>
        <?php if(!empty($page_meta_description)) : ?>
        <meta name="description" content="<?php echo $page_meta_description; ?>" />
        <?php endif; ?>
        <?php if(!empty($page_robots_tag)) : ?>
        <meta name="robots" content="<?php echo $page_robots_tag; ?>" />
        <?php endif; ?>
        <?php if(!empty($page_link_canonical)) : ?>
        <link rel="canonical" href="<?php echo $page_link_canonical; ?>" />
        <?php endif; ?>
        <?php if(!empty($page_link_prev)) : ?>
        <link rel="prev" href="<?php echo $page_link_prev; ?>" />
        <?php endif; ?>
        <?php if(!empty($page_link_next)) : ?>
        <link rel="next" href="<?php echo $page_link_next; ?>" />
        <?php endif; ?>
            <?php
        }
        elseif(file_exists(DIR_WS_INCLUDES . 'header_tags.php'))
        {
            require(DIR_WS_INCLUDES . 'header_tags.php');
        }
        else
        {
            echo '<title>'.TITLE.'</title>';
        }
        
        \EShopmakers\Html\Capture::getInstance('header')->display();
        
        if(!is_object($lng))
        {
            include_once(DIR_WS_CLASSES . 'language.php');
            $lng = new language;
            $lng->set_language_by_id($_SESSION['languages_id']);
        }
        foreach($lng->catalog_languages as $key => $value)
        {
            if($value['directory'] === $lng->language['directory'])
            {
                continue;
            }
            echo '<link rel="alternate" type="text/html" hreflang="', ($key === 'ua' ? 'uk' : $key), '" href="', tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, empty($connection) ? 'NONSSL' : $connection), '">';
        }

        ?>
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
            ga('create', 'UA-93927029-1', 'auto');
            ga('send', 'pageview');
        </script>
    </head>
    <body itemscope itemtype="<?php echo $body_itemtype; ?>"<?php if(!empty($body_class)) : ?> class="<?php echo $body_class; ?>"<?php endif; ?>>
        <?php /* Шапка */ ?>
        <header class="page-header" itemscope itemtype="http://schema.org/WPHeader">
            <div class="level-1">
                <div class="main-width clearfix">
                    <?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/top_links.php'); ?>
                    <div class="toolbar clearfix">
                        <span class="counters">
                            <?php require DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/viewed_products.php'; ?>
                            <?php require DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/wishlist.php'; ?>
                            <?php /* require DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/compare.php'; */ ?>
                        </span>
                        <?php

                        require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/loginbox.php');
                        if(LANGUAGE_SELECTOR_MODULE_ENABLED == 'true')
                        {
                            require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/languages.php');
                        }

                        ?>
                    </div>
                </div>
            </div>
            <div class="level-2">
                <div class="main-width">
                    <div class="columns clearfix">
                        <?php /* Логотип */ ?>
                        <a href="<?php echo tep_href_link(FILENAME_DEFAULT); ?>" class="logo"><?php echo LOGO_TEXT; ?></a>
                        <div class="phones">
                            <?php /* Заказ обратного звонка */ ?>
                            <?php if(CANT_CALL == 'true'): ?>
                            <div class="callback"><a href="<?php echo tep_href_link(FILENAME_CONTACT_US); ?>"><?php echo CALL_PROBLEM_TITLE; ?></a></div>
                            <?php endif; ?>
                            <?php include ('admin/phone_numbers.php'); ?>
                        </div>
                        <?php /* Поиск */ ?>
                        <div class="search"><?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/search.php'); ?></div>
                        <?php /* Корзина */ ?>
                        <div class="shopping-cart"><?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/shopping_cart.php'); ?></div>
                    </div>
                </div>
            </div>
        </header>
        <?php /* Навигация */ ?>
        <?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/categories_menu.php'); ?>
        <?php
        
        // Слайдер только на главной странице
        if($content === CONTENT_INDEX_DEFAULT)
        {
            require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/slider_main.php');
        }
            
        ?>
        <?php /* Центральная часть с контентом */ ?>
        <div class="main-width">
            <?php /* Хлебные крошки на всех страницах кроме главной */ ?>
            <?php if($content != CONTENT_INDEX_DEFAULT) echo $breadcrumb->trail(); ?>
            <div class="page-content clearfix<?php if($left_column_modules) : ?> with-left-column<?php endif; ?><?php if($right_column_modules) : ?> with-right-column<?php endif; ?>">
                <?php /* Левая боковая колонка */ ?>
                <?php if($left_column_modules) : ?>
                <aside class="side-column column-left" itemscope itemtype="http://schema.org/WPSideBar">
                    <div class="column-wrapper">
                        <div class="close-column"></div>
                        <?php

                        foreach($left_column_modules as $column)
                        {
                            if(file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle']))
                            {
                                define($column['cfgkey'], $column['box_heading']);
                                $infobox_define   = $column['box_heading'];
                                $infobox_template = $column['box_template'];
                                $font_color       = $column['box_heading_font_color'];
                                $infobox_class    = $column['box_template'];
                                require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle']);
                            }
                        }

                        ?>
                    </div>
                </aside>
                <?php endif; ?>
                <?php /* Основной контент страницы */ ?>
                <main class="column-center" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/WebPageElement"><?php echo $_content; ?></main>
                <?php /* Правая боковая колонка */ ?>
                <?php if($right_column_modules) : ?>
                <aside class="side-column column-right" itemscope itemtype="http://schema.org/WPSideBar">
                    <div class="column-wrapper">
                        <div class="close-column"></div>
                        <?php

                        foreach($right_column_modules as $column)
                        {
                            if(file_exists(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle']))
                            {
                                define($column['cfgkey'], $column['box_heading']);
                                $infobox_define   = $column['box_heading'];
                                $infobox_template = $column['box_template'];
                                $font_color       = $column['box_heading_font_color'];
                                $infobox_class    = $column['box_template'];
                                require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/' . $column['cfgtitle']);
                            }
                        }

                        ?>
                    </div>
                </aside>
                <?php endif; ?>
            </div>
        </div>
        <?php /* Производители, статьи, новости только на главной странице */ ?>
        <?php if($content === CONTENT_INDEX_DEFAULT) : ?>
        <?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/manufacturers.php'); ?>
        <div class="main-width">
            <?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/recent_articles_and_news.php'); ?>
        </div>
        <?php endif; ?>
        <?php /* Подвал */ ?>
        <footer class="page-footer" itemscope itemtype="http://schema.org/WPFooter">
            <div class="level-1">
                <div class="main-width">
                    <div class="columns clearfix">
                        <div class="column column-about-us">
                            <div class="column-title"><?php echo FOOTER_COLUMN_ABOUT_US_TITLE; ?></div>
                            <div class="column-content"><?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/footer_links.php'); ?></div>
                        </div>
                        <?php if (ARTICLES_MODULE_ENABLED == 'true'): ?>
                        <div class="column column-categories">
                            <div class="column-title"><?php echo FOOTER_COLUMN_CATEGORIES_TITLE; ?></div>
                            <div class="column-content"><?php require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/footer_categories_menu.php'); ?></div>
                        </div>
                        <?php endif; ?>
                        <div class="column column-subscribe">
                            <div class="column-title"><?php echo FOOTER_COLUMN_SUBSCRIBE_TITLE; ?></div>
                            <div class="column-content">
                                <form name="subscribe" method="POST" action="<?php echo tep_href_link('subscribe.php'); ?>">
                                    <input type="hidden" name="token" value="<?php echo \EShopmakers\Security\CSRFToken::getToken(); ?>" />
                                    <input
                                        type="email"
                                        name="email"
                                        placeholder="<?php echo FOOTER_SUBSCRIBE_FORM_EMAIL_INPUT_PLACEHOLDER; ?>"
                                        class="form-control"
                                        required
                                        />
                                    <div class="description"><?php echo FOOTER_SUBSCRIBE_DESCRIPTION; ?></div>
                                    <button type="submit" class="button"><?php echo TEXT_SUBSCRIBE; ?></button>
                                    <div class="social-subscribe">
                                        <div class="instagram"><a rel="nofollow" href="https://www.instagram.com/gigimot_com_ua/" target="_blank"><img src="templates/gigimot/images/instagram.png" alt="Instagram"></a></div>
                                        <div class="fb"><a rel="nofollow" href="https://www.facebook.com/%D0%98%D0%BD%D1%82%D0%B5%D1%80%D0%BD%D0%B5%D1%82-%D0%BC%D0%B0%D0%B3%D0%B0%D0%B7%D0%B8%D0%BD-%D0%B8%D0%B3%D1%80%D1%83%D1%88%D0%B5%D0%BA-Gigimot-319009261906514/" target="_blank"><img src="templates/gigimot/images/fb.png" alt="Facebook"></a></div>
                                        <div class="youtube"><a rel="nofollow" href="https://www.youtube.com/channel/UCKwlVKcJesgOctWon0aM4QA" target="_blank"><img src="templates/gigimot/images/youtube.png" alt="Youtube"></a></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="level-2 main-width clearfix">
                <div class="column column-copyright">
                    &copy; <?php echo date('Y'); ?> <?php echo STORE_NAME; ?> <?php echo ALL_RIGHTS; ?>
                    <a href="<?php echo tep_href_link('price.php'); ?>"><?php echo SITEMAP; ?></a>
                </div>
                <div class="column column-developer"><?php echo MADE_BY; ?> <a href="http://eshopmakers.com/" target="_blank">eShopmakers</a></div>
                <div class="sub-link"><a href='<?php
                    if($_SESSION['languages_id']=='1'){
                        echo 'https://gigimot.com.ua/p-773-nastolnaya-igra-detskaya-dream-makers-1503h';
                    }else{
                        echo 'https://gigimot.com.ua/ua/p-773-nastilna-gra-dityacha-dream-makers-1503h';
                    }?>'><?php
                        if($_SESSION['languages_id']=='1'){
                           echo 'Настольная игра «Кошка на стене»';
                        }else{
                            echo 'Настільна гра «Киця на стіні»';
                        }?>
                    </a></div>
            </div>
        </footer>
        <?php /* HTML5 IE фикс */ ?>
        <!--[if IE]>
        <script>
            document.createElement('header');
            document.createElement('footer');
            document.createElement('main');
            document.createElement('figure');
            document.createElement('figcaption');
            document.createElement('aside');
            document.createElement('time');
            document.createElement('article');
            document.createElement('section');
        </script>
        <![endif]-->
        <script>
            var base_url        = '<?php echo addslashes($base_url); ?>',
                vk_app_id       = '<?php echo addslashes($vk_app_id); ?>',
                facebook_app_id = '<?php echo addslashes($fb_app_id); ?>',
                language        = '<?php echo addslashes(\language::getCodeByID($_SESSION['languages_id'])); ?>',
                currencies      = {
                    currencies: <?php echo json_encode($currencies->currencies); ?>,
                    currentCurrency: '<?php echo empty($_SESSION['currency']) ? DEFAULT_CURRENCY : $_SESSION['currency']; ?>',
                    get: function(price, quantity) {
                        quantity = quantity || 1;
                        return this.currencies[this.currentCurrency].symbol_left + (price * quantity * this.currencies[this.currentCurrency].value).toFixed(this.currencies[this.currentCurrency].decimal_places || 0) + '<span class="currency">' + this.currencies[this.currentCurrency].symbol_right + '</span>';
                    }
                };
        </script>
        <script src="<?php echo tep_href_link('dictionary.php'); ?>"></script>
        <script src="<?php echo tep_href_link('templates/gigimot/js/global.js'); ?>"></script>
        <?php EShopmakers\Html\Capture::getInstance('footer')->display(); ?>
        
        <?php /*
        <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
            (function (d, w, c) {
                (w[c] = w[c] || []).push(function() {
                    try {
                        w.yaCounter43522399 = new Ya.Metrika({
                            id:43522399,
                            clickmap:true,
                            trackLinks:true,
                            accurateTrackBounce:true
                        });
                    } catch(e) { }
                });

                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () { n.parentNode.insertBefore(s, n); };
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://mc.yandex.ru/metrika/watch.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else { f(); }
            })(document, window, "yandex_metrika_callbacks");
        </script>
        <noscript><div><img src="https://mc.yandex.ru/watch/43522399" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika counter -->
        */ ?>
        <!-- BEGIN JIVOSITE CODE {literal} -->
        <script type='text/javascript'>
        (function(){ var widget_id = 'oacQanGLwf';var d=document;var w=window;function l(){
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = '//code.jivosite.com/script/widget/'+widget_id; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();</script>
        <!-- {/literal} END JIVOSITE CODE -->
    </body>
</html>