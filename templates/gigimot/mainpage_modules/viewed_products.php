<?php

/**
 * Просмотренные товары
 */

if(empty($_SESSION['viewed_products']))
{
    return;
}

$products_ids = array_unique(array_filter($_SESSION['viewed_products'], 'is_numeric'));
$_products_ids = implode(', ', $products_ids);

$query_string = <<<SQL
SELECT
    p.products_id,
    p.products_images,
    p.lable_3,
    p.lable_2,
    p.lable_1,
    p.products_tax_class_id,
    p.products_quantity,
    p.products_quantity_order_min,
    p.products_price,
    pd.products_name,
    pd.products_description
FROM products AS p
INNER JOIN products_description AS pd
ON
    pd.products_id = p.products_id AND
    pd.language_id = {$_SESSION['languages_id']}
WHERE
    p.products_id IN ({$_products_ids}) AND
    p.products_status = 1 AND
    (
        p.products_date_available IS NULL OR
        p.products_date_available = '0000-00-00 00:00:00' OR
        p.products_date_available > NOW()
    )
LIMIT 20
SQL;
$query = tep_db_query($query_string);
if(!tep_db_num_rows($query))
{
    return;
}

$tpl_settings = array('request' => $query);

?>
<div class="mpm-viewed-products mpm-bg-white-style">
    <div class="header-and-arrows clearfix">
        <div class="header"><?php echo MPM_VIEWED_PRODUCTS_TITLE; ?></div>
        <div class="arrows">
            <a href="<?php echo tep_href_link(FILENAME_VIEWED_PRODUCTS); ?>" rel="nofollow"><?php echo MPM_VIEWED_PRODUCTS_ALL; ?></a>
            <span></span>
        </div>
    </div>
    <?php include DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/listingTemplate.tpl.php'; ?>
</div>
<?php \EShopmakers\Html\Capture::getInstance('footer')->startCapture(); ?>
<script>
    (function($){
        'use strict';
        $(document).ready(function(){
            // Слайдер новинок на главной
            $('.mpm-viewed-products .listing-tile').slick({
                slidesToShow: 5,
                slidesToScroll: 5,
                infinite: false,
                dots: false,
                appendArrows: '.mpm-viewed-products .arrows span',
                responsive: [
                    {
                        breakpoint: 1060,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4
                        }
                    },
                    {
                        breakpoint: 920,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 321,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        });
    })(window.jQuery || window.Zepto);
</script>
<?php \EShopmakers\Html\Capture::getInstance('footer')->stopCapture(); ?>