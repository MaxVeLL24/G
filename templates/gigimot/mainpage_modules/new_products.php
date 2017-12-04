<?php

/**
 * Новинки
 */

$new_products_query = tep_db_query("select distinct cd.categories_name, pd.products_name, p.products_images,p.mankovka_stock, p.lable_3, p.lable_2, p.lable_1, p.products_id, p.products_tax_class_id, p.products_quantity, p.products_quantity_order_min, p.products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd , " . TABLE_PRODUCTS_DESCRIPTION . " pd where c.categories_status='1' and p.products_id = p2c.products_id and p.products_id = pd.products_id and p2c.categories_id = c.categories_id and pd.language_id = '" . (int) $languages_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int) $languages_id . "' and products_status = '1' order by p.products_quantity > 0 desc, (p.products_quantity <= 0 AND p.mankovka_stock > 0) desc,p.products_id desc limit 10");
if(!tep_db_num_rows($new_products_query))
{
    return;
}
$tpl_settings = array(
    'request' => $new_products_query,
    'id' => 'new_products',
    'classes' => array('product_slider'),
    'title' => NEW_PRODUCTS,
);

?>
<div class="mpm-new-products mpm-bg-white-style">
    <div class="header-and-arrows clearfix">
        <div class="header"><?php echo NEW_PRODUCTS; ?></div>
        <div class="arrows">
            <a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=0&sort=new'); ?>" rel="nofollow"><?php echo MPM_NEW_PRODUCTS_ALL; ?></a>
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
            $('.mpm-new-products .listing-tile').slick({
                slidesToShow: 5,
                slidesToScroll: 5,
                infinite: false,
                dots: false,
                appendArrows: '.mpm-new-products .arrows span',
                responsive: [
                    {
                        breakpoint: 920,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 400,
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