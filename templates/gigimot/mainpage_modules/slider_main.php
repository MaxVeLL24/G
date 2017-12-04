<?php

/**
 * Слайдер на главной странице
 */

if(MAIN_SLIDER_MODULE_ENABLED !== 'true')
{
    return;
}

// Выгрузить слайды
$query = tep_db_query('select * from slider order by output_order');
$slides = array();
if(tep_db_num_rows($query))
{
    while(($row = tep_db_fetch_array($query)) !== false)
    {
        // Проверка на внешнюю ссылку
        $row['isExternalLink'] = false;
        if($row['SliderLink'])
        {
            $linkHost = parse_url($row['SliderLink'], PHP_URL_HOST);
            if($linkHost && $linkHost !== ($request_type === 'NONSSL' ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN))
            {
                $row['isExternalLink'] = true;
            }
        }
        $slides[] = $row;
    }
}

?>
<div class="mpm-slider-main">
    <div class="main-width">
        <div class="padding-box clearfix">
            <a class="special-offer-left" href="/p-773-nastolnaya-igra-detskaya-dream-makers-1503h">
                <img
                    src="userfiles/images/small_picture_cat.jpg"
                    width="230"
                    height="359"
                    alt=""
                    />
            </a>
            <a class="special-offer-right" href="/p-400-hasbro-play-doh-mister-zubastik-b5520">
                <img
                    src="userfiles/images/small_right.jpg"
                    width="230"
                    height="359"
                    alt=""
                    />
            </a>
            <div class="slides">
                <?php foreach($slides as $slide) : ?>
                <?php if(!empty($slide['SliderLink'])) : ?>
                <a href="<?php echo tep_escape($slide['SliderLink']); ?>"<?php if($slide['isExternalLink']) : ?> target="_blank" rel="nofollow external"<?php endif; ?>><img src="<?php echo tep_escape('admin/slider/' . $slide['SliderImage']); ?>" alt="" /></a>
                <?php else : ?>
                <img src="<?php echo tep_escape('admin/slider/' . $slide['SliderImage']); ?>" alt="" />
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php \EShopmakers\Html\Capture::getInstance('footer')->startCapture(); ?>
<script>
    (function($){
        'use strict';
        $(document).ready(function(){
            // Слайдер на главной
            $('.mpm-slider-main .slides').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay:true,
                autoplaySpeed:3000,
                speed: 450,
                infinite: true,
                dots: true,
                arrows: false
            });
        });
    })(window.jQuery || window.Zepto);
</script>
<?php \EShopmakers\Html\Capture::getInstance('footer')->stopCapture(); ?>