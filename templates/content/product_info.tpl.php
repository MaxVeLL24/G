<?php

/**
 * Страница товара
 */

/* @var $currencies \currencies */
/* @var $cart \shoppingCart */

// Разметка Facebook OpenGraph
\EShopmakers\Html\Capture::getInstance('header')->startCapture();
echo '<link rel="stylesheet" href="templates/gigimot/css/product-info.css">';
if($product_info['products_images']) {
    echo '<link rel="image_src" href="', tep_href_link(DIR_WS_IMAGES . rawurlencode($product_info['products_images'][0])), '">';
}
echo '<meta property="og:type" content="product.item">';
echo '<meta property="og:locale" content="', FACEBOOK_OG_LOCALE, '">';
echo '<meta property="og:url" content="', tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id']), '">';
echo '<meta property="og:title" content="', tep_escape($product_info['products_name']), '">';
if($product_info['products_images']) {
    echo '<meta property="og:image" content="', tep_href_link(DIR_WS_IMAGES . rawurlencode($product_info['products_images'][0])), '">';
}
echo '<meta property="product:retailer_item_id" content="', tep_escape($product_info['products_model']), '">';
echo '<meta property="product:price:amount" content="', $currencies->get_price_nodiscount($product_info['final_price'], tep_get_tax_rate($product_info['products_tax_class_id'])), '">';
echo '<meta property="product:price:currency" content="', (empty($_SESSION['currency']) ? DEFAULT_CURRENCY : $_SESSION['currency']), '">';
echo '<meta property="product:availability" content="' , ($product_info['final_availability'] ? 'in stock' : 'out of stock'), '">';
echo '<meta property="product:condition" content="new">';
\EShopmakers\Html\Capture::getInstance('header')->stopCapture();

$_tw = $currencies->taxWrapper;
$currencies->taxWrapper = 'span';

?>
<div class="images-and-details clearfix">
    <?php if($product_info['products_images']) : ?>
    <div class="images">
        <div class="primary-image">
            <?php /* Лейба */ ?>
            <?php if($product_info['label']) : ?>
            <div class="product-label label-<?php echo $product_info['label'] == 1 ? 'top' : ($product_info['label'] == 2 ? 'new' : 'discount'); ?>"><?php echo $product_info['label'] == 1 ? 'TOP' : ($product_info['label'] == 2 ? 'NEW' : '-' . (empty($product_info['_discount']) ? 0 : $product_info['_discount']) . '%'); ?></div>
            <?php endif; ?>
            <a href="<?php echo tep_href_link(DIR_WS_IMAGES . rawurlencode($product_info['products_images'][0])); ?>" data-lightbox="products-images"><img src="<?php echo tep_href_link(FILENAME_IMAGE_RESIZER, 'w=380&h=380&thumb=' . rawurlencode($product_info['products_images'][0])); ?>" alt="<?php echo tep_escape($product_info['products_name']); ?>" /></a>
        </div>
        <?php if($product_info['products_images']) : ?>
        <div class="secondary-images">
            <?php for($i = 0; $i < count($product_info['products_images']); $i++) : ?>
            <a href="<?php echo tep_href_link(DIR_WS_IMAGES . rawurlencode($product_info['products_images'][$i])); ?>"<?php if(!$i) : ?> class="active"<?php endif; ?>><img src="<?php echo tep_href_link(FILENAME_IMAGE_RESIZER, 'w=100&h=100&thumb=' . rawurlencode($product_info['products_images'][$i])); ?>" alt="<?php echo tep_escape($product_info['products_name']); ?>" /></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="details clearfix">
        <h1><?php echo tep_escape($product_info['products_name']); ?></h1>
        <div class="manufacturer-products-model-and-rating clearfix">
            <div>
                <div class="rating-value value-<?php echo $product_info['products_rating']; ?>"></div>
            </div>
            <?php if($product_info['manufacturers_id']) { ?>
            <div>
                <?php echo TEXT_PRODUCT_MANUFACTURER; ?>
                <a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $product_info['manufacturers_id']); ?>"><?php echo tep_escape($product_info['manufacturers_name']); ?></a>
            </div>
            <?php } ?>
            <?php if($product_info['products_model']) { ?>
            <div class="products-model"><?php echo TEXT_PRODUCT_MODEL, tep_escape($product_info['products_model']); ?></div>
            <?php } ?>
        </div>
        <?php /* Атрибуты */ ?>
        <?php if($attributes_options) : ?>
        <table class="characteristics">
            <tbody>
                <?php foreach($attributes_options as $option_id) : ?>
                <tr>
                    <th><?php echo tep_escape($options[$option_id]); ?>:</th>
                    <td>
                        <?php if(count($options_to_options_values[$option_id]) > 1) : ?>
                            <?php if($options_type[$option_id] == 1) : ?>
                            <select
                                name="id[<?php echo $option_id; ?>]"
                                form="add-to-cart-form"
                                class="custom-select"
                                >
                                <?php foreach($options_to_options_values[$option_id] as $options_values_id => $attribute) : ?>
                                <option
                                    value="<?php echo $options_values_id; ?>"
                                    <?php if(in_array($options_values_id, $selected_options_values)) : ?>selected<?php endif; ?>
                                    data-price="<?php echo $attribute['options_values_price'] * $currencies->get_value(empty($_SESSION['currency']) ? DEFAULT_CURRENCY : $_SESSION['currency']); ?>"
                                    data-prefix="<?php echo $attribute['price_prefix']; ?>"
                                    <?php if($attribute['pa_qty'] > 0) : ?>data-available<?php endif; ?>
                                    ><?php echo tep_escape($attribute['products_options_values_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php elseif($options_type[$option_id] == 6) : ?>
                            <ul class="size-options-list">
                                <?php foreach($options_to_options_values[$option_id] as $options_values_id => $attribute) : ?>
                                <li>
                                    <input
                                        type="radio"
                                        id="id-<?php echo $option_id; ?>-<?php echo $options_values_id; ?>"
                                        name="id[<?php echo $option_id; ?>]"
                                        form="add-to-cart-form"
                                        value="<?php echo $options_values_id; ?>"
                                        <?php if(in_array($options_values_id, $selected_options_values)) : ?>checked<?php endif; ?>
                                        data-price="<?php echo $attribute['options_values_price'] * $currencies->get_value(empty($_SESSION['currency']) ? DEFAULT_CURRENCY : $_SESSION['currency']); ?>"
                                        data-prefix="<?php echo $attribute['price_prefix']; ?>"
                                        <?php if($attribute['pa_qty'] > 0) : ?>data-available<?php endif; ?>
                                        >
                                    <label
                                        for="id-<?php echo $option_id; ?>-<?php echo $options_values_id; ?>"
                                        title="<?php echo tep_escape($attribute['products_options_values_name']); ?>"
                                        ><?php echo tep_escape($attribute['products_options_values_name']); ?></label>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php elseif($options_type[$option_id] == 7) : ?>
                            <ul class="color-options-list">
                                <?php foreach($options_to_options_values[$option_id] as $options_values_id => $attribute) : ?>
                                <li>
                                    <input
                                        type="radio"
                                        id="id-<?php echo $option_id; ?>-<?php echo $options_values_id; ?>"
                                        name="id[<?php echo $option_id; ?>]"
                                        form="add-to-cart-form"
                                        value="<?php echo $options_values_id; ?>"
                                        <?php if(in_array($options_values_id, $selected_options_values)) : ?>checked<?php endif; ?>
                                        data-price="<?php echo $attribute['options_values_price'] * $currencies->get_value(empty($_SESSION['currency']) ? DEFAULT_CURRENCY : $_SESSION['currency']); ?>"
                                        data-prefix="<?php echo $attribute['price_prefix']; ?>"
                                        <?php if($attribute['pa_qty'] > 0) : ?>data-available<?php endif; ?>
                                        >
                                    <label
                                        for="id-<?php echo $option_id; ?>-<?php echo $options_values_id; ?>"
                                        title="<?php echo tep_escape($attribute['products_options_values_name']); ?>"
                                        style="background-color: <?php echo tep_escape($attribute['products_options_values_extra_data']); ?>;"
                                        ></label>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        <?php else : ?>
                        <?php foreach($options_to_options_values[$option_id] as $options_values_id => $attribute) : ?>
                        <input
                            type="hidden"
                            name="id[<?php echo $option_id; ?>]"
                            value="<?php echo $options_values_id; ?>"
                            form="add-to-cart-form"
                            data-price="<?php echo $attribute['options_values_price']; ?>"
                            data-prefix="<?php echo $attribute['price_prefix']; ?>"
                            <?php if($attribute['pa_qty'] > 0) : ?>data-available<?php endif; ?>
                            />
                        <?php echo tep_escape($attribute['products_options_values_name']); ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
        <?php /* Цена */ ?>
        <div class="price-block">
            <?php if(SALES_MODULE_ENABLED == 'true' && $product_info['specials_new_products_price']) : ?>
            <div class="old-price"><?php echo $currencies->display_price_nodiscount($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])); ?></div>
            <?php endif; ?>
            <div class="price"><?php echo $currencies->display_price_nodiscount($product_info['final_price'], tep_get_tax_rate($product_info['products_tax_class_id'])); ?></div>
        </div>
        <?php /* Кнопка "Купить" */ ?>
        <form
            method="POST"
            action="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id'] . '&action=add_product'); ?>"
            class="add-to-cart"
            id="add-to-cart-form"
            name="add_to_cart"
            data-price="<?php echo $currencies->get_price_nodiscount($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])); ?>"
            <?php if(SALES_MODULE_ENABLED == 'true' && !empty($product_info['specials_new_products_price'])) : ?>
            data-special-price="<?php echo $currencies->get_price_nodiscount($product_info['specials_new_products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])); ?>"
            <?php endif; ?>
            <?php if($product_info['products_quantity'] > 0) : ?>data-available<?php endif; ?>
            <?php if($product_info['mankovka_stock'] > 0) : ?>data-mankovka-available<?php endif; ?>
            data-tax="<?php echo tep_get_tax_rate($product_info['products_tax_class_id']); ?>"
            data-product-id="<?php echo $product_info['products_id']; ?>"
            >
            <input
                type="hidden"
                name="products_id"
                value="<?php echo $product_info['products_id']; ?>"
                />
            <div class="quantity-text"> <?php echo TEXT_PRODUCT_QUANTITY; ?></div>     
            <div class="block-quantity">
                <button type="button" class="minus">-</button>
                <input type="text" name="cart_quantity" value="<?php echo empty($product_info['products_quantity_order_min']) ? 1 : $product_info['products_quantity_order_min']; ?>">
                <button type="button" class="plus">+</button>
            </div>
            <button
                type="submit"
                class="button button-add-to-cart"
                <?php if(!$product_info['final_availability']) : ?>style="display: none;"<?php endif; ?>
                ><?php echo IMAGE_BUTTON_ADDTO_CART; ?></button>
            <button
                type="submit"
                class="button button-buy-one-click"
                data-pre-order="false"
                <?php if(!$product_info['final_availability']) : ?>style="display: none;"<?php endif; ?>
                ><?php echo QUICK_ORDER_BUTTON; ?></button>
            <button
                type="submit"
                class="button button-pre-order-click"
                data-pre-order="true"
                <?php if($product_info['final_availability'] and $product_info['mankovka_stock'] > 0) : ?>style="display: none;"<?php endif; ?>
                ><?php echo QUICK_PRE_ORDER_BUTTON; ?></button>
            <div class="delivery-info" style="display:none;"><?php echo PRODUCTS_PRE_ORDER_INFO; ?>
            </div> 
            <span
                class="button button-disabled button-out-of-stock"
                <?php if($product_info['final_availability']) : ?>style="display: none;"<?php endif; ?>
                ><?php echo PRODUCT_NOT_AVIAIlABLE; ?></span>
        </form>
        <?php /* Желания и сравнение */ ?>
        <div class="wishlist-and-compare">
            <div
                class="add-to-wishlist<?php if(isset($_SESSION['wishList']) && $_SESSION['wishList']->in_wishlist($in_cart_products_id)) : ?> in-wishlist<?php endif; ?>"
                data-uprid="<?php echo $in_cart_products_id; ?>"
                ><?php echo isset($_SESSION['wishList']) && $_SESSION['wishList']->in_wishlist($in_cart_products_id) ? PRODUCT_LISTING_IN_WISHLIST : PRODUCT_LISTING_ADD_TO_WISHLIST; ?></div>
            <?php /* <div
                class="add-to-compare<?php if(!empty($_SESSION['compares']) && is_array($_SESSION['compares']) && array_key_exists($in_cart_products_id, $_SESSION['compares'])) : ?> in-comparison<?php endif; ?>"
                data-uprid="<?php echo $in_cart_products_id; ?>"
                ><?php echo !empty($_SESSION['compares']) && is_array($_SESSION['compares']) && array_key_exists($in_cart_products_id, $_SESSION['compares']) ? PRODUCT_LISTING_IN_COMPARISON : PRODUCT_LISTING_ADD_TO_COMPARE; ?></div> */ ?>
        </div>
		<?php /* Соц. сети */ ?>
		<?php /*
	    <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
	    <script src="//yastatic.net/share2/share.js"></script>
	    <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,gplus,twitter,telegram" data-counter="true"></div> 
	    */ ?>
	    <br>
	    <div class="share42init"></div>
		<script src="includes/javascript/share42/share42.js" defer></script>
    </div>
    <noindex>
    <dl class="payment-delivery-waranty common-styled-block">
        <dt><?php echo PAYMENT_TITLE; ?></dt>
        <dd><?php echo PAYMENT_TEXT; ?></dd>
        <dt><?php echo DELIVERY_TITLE; ?></dt>
        <dd><?php echo DELIVERY_TEXT; ?></dd>
        <dt><?php echo WARANTY_TITLE; ?></dt>
        <dd><?php echo WARANTY_TEXT; ?></dd>
    </dl>
    </noindex>
</div>
<?php /* Вкладки */ ?>

<?php

$this_page_link = tep_href_link(FILENAME_PRODUCT_INFO, tep_get_all_get_params(array('action', 'token')));

?>

<div class="tabs">
    <div class="tab" data-target=".tab-about"><?php echo TAB_ABOUT; ?></div>
    <div class="tab" data-target=".tab-reviews"><?php echo TAB_REVIEWS, $product_info['products_comments_count'] ? ' <span class="counter">(' . $product_info['products_comments_count'] . ')</span>' : ''; ?></div>
<!--    <?php //if($delivery_info) { ?><div class="tab" data-target=".tab-delivery"><?php echo TAB_DELIVERY; ?></div><?php //} ?>
    <?php //if($payment_info) { ?><div class="tab" data-target=".tab-payment"><?php echo TAB_PAYMENT; ?></div><?php //} ?>   -->
    <?php if(RELATED_PRODUCTS_MODULE_ENABLED == 'true' && !empty($xsell_products)) : ?>
        <div class="tab related" data-target=".tab-about2"><?= TEXT_TAB_RELATED_PRODUCTS; ?></div>
    <?php endif; ?>
    <?
    $art_rel_query=tep_db_query("SELECT ax.articles_id,ad.articles_name,ad.articles_description,ax.xsell_id,ad.articles_id FROM  `articles_xsell` as ax , `articles_description` as ad WHERE ax.xsell_id='{$product_info['products_id']}' and ad.articles_id=ax.articles_id and language_id={$_SESSION['languages_id']}");
    $art_rel_num=tep_db_num_rows($art_rel_query);
    if($art_rel_num>0){
        echo '<div class="tab" data-target=".tab-related-articles">'.TAB_REL_ARTICLES.'</div>';
    }
    ?>
</div>
<?php /* Описание и характеристики */ ?>
<?php
if ($art_rel_num > 0) {
    echo "<div class=\"tab-content tab-related-articles common-styled-block\">";
    while ($rel_article_res = tep_db_fetch_array($art_rel_query)) {
        echo '<div class="related-art-item">
<span class="art-name">'.$rel_article_res['articles_name'].'</span>
<span class="art-short-desc">'.mb_substr(strip_tags($rel_article_res['articles_description']),'0','450','utf-8').'...</span>
<div class="watch-all">
<a href="/a-'.$rel_article_res['articles_id'].'" class="art-watch-full">'.READ_MORE_LINK_TEXT.'</a>
</div>
</div>';
    }
    echo "</div>";
}
?>

<div class="tab-content tab-about common-styled-block">
    <?php echo $product_info['products_description']; ?>
    <?php if($non_attributes_options) : ?>
    <table class="attributes-table">
        <tbody>
            <?php foreach($non_attributes_options as $option_id) : ?>
            <tr>
                <th><?php echo tep_escape($options[$option_id]); ?></th>
                <td>
                    <?php
                    
                    $tmp = array();
                    foreach($options_to_options_values[$option_id] as $option) {
                        $tmp[] = $option['products_options_values_name'];
                    }
                    echo tep_escape(implode(', ', $tmp));
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
<?php if(RELATED_PRODUCTS_MODULE_ENABLED == 'true' && !empty($xsell_products)) : ?>
    <div class="tab-content tab-about2 common-styled-block">
        <?php if(RELATED_PRODUCTS_MODULE_ENABLED == 'true' && !empty($xsell_products)) : ?>
                <div class="related-products">
                    <?php foreach($xsell_products as $product) : ?>
                        <div class="related-product">
                            <?php
                            if(empty($primary_product))
                            {
                                $capture = \EShopmakers\Html\Capture::getInstance('related_products');
                                $capture->startCapture();
                                ?>
                                <div class="product product-<?php echo $product_info['products_id']; ?>">
                                    <?php /* Лейба */ ?>
                                    <?php if(PRODUCT_LABELS_MODULE_ENABLED != "false") : ?>
                                        <?php if($product_info['label']) : ?>
                                            <div class="label label-<?php echo $product_info['label']; ?>"><?php /* if($product_info['label'] == 3 && !empty($listing['discount'])) echo '-', $listing['discount'], '%'; */ ?></div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php /* Картинка */ ?>
                                    <a class="image" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id']); ?>"><?php if(count($product_info['products_images'])) : ?><img src="<?php echo tep_href_link(FILENAME_IMAGE_RESIZER, 'w=150&h=150&thumb=' . rawurlencode($product_info['products_images'][0])); ?>" alt="<?php echo tep_escape($product_info['products_images'][0]); ?>" /><?php endif; ?></a>
                                    <?php /* Название категории */ ?>
<!--                                    --><?php //if(!empty($product_info['categories_name'])) : ?>
<!--                                        <div class="category-name">--><?php //echo tep_escape($product_info['categories_name']); ?><!--</div>-->
<!--                                    --><?php //endif; ?>
                                    <?php /* Название товара */ ?>
                                    <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product_info['products_id']); ?>" class="product-name"><?php echo tep_escape($product_info['products_name']); ?></a>
                                    <?php /* Цена и кнопка "Купить" */ ?>
                                    <div class="price">
                                        <?php if(SALES_MODULE_ENABLED == 'true' && !empty($product_info['specials_new_products_price'])) : ?>
                                            <div class="price-old">
                                                <?php echo $currencies->display_price_nodiscount($product_info['products_price'], tep_get_tax_rate($product_info['products_tax_class_id'])); ?>
                                            </div>
                                            <div class="price-new">
                                                <?php echo $currencies->display_price_nodiscount($product_info['final_price'], tep_get_tax_rate($product_info['products_tax_class_id'])); ?>
                                            </div>
                                        <?php else : ?>
                                            <?php echo $currencies->display_price_nodiscount($product_info['final_price'], tep_get_tax_rate($product_info['products_tax_class_id'])); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php
                                $primary_product = $capture->stopCapture()->getLast();
                                unset($capture);
                                \EShopmakers\Html\Capture::deleteInstance('related_products');
                            }
                            echo $primary_product;

                            ?>
                            <div class="plus"></div>
                            <div class="product product-<?php echo $product['products_id']; ?>">
                                <?php /* Лейба */ ?>
                                <?php if(PRODUCT_LABELS_MODULE_ENABLED != "false") : ?>
                                    <?php if($product['label']) : ?>
                                        <div class="label label-<?php echo $product['label']; ?>"><?php /* if($product['label'] == 3 && !empty($listing['discount'])) echo '-', $listing['discount'], '%'; */ ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php /* Картинка */ ?>
                                <a class="image" href="<?php echo $product['link']; ?>"><?php if($product['image']) : ?><img src="<?php echo tep_href_link(FILENAME_IMAGE_RESIZER, 'w=150&h=150&thumb=' . rawurlencode($product['image'])); ?>" alt="<?php echo tep_escape($product['products_name']); ?>" /><?php endif; ?></a>
                                <?php /* Название категории */ ?>
<!--                                --><?php //if(!empty($product['categories_name'])) : ?>
<!--                                    <div class="category-name">--><?php //echo tep_escape($product['categories_name']); ?><!--</div>-->
<!--                                --><?php //endif; ?>
                                <?php /* Название товара */ ?>
                                <a href="<?php echo $product['link']; ?>" class="product-name"><?php echo tep_escape($product['products_name']); ?></a>
                                <?php /* Цена и кнопка "Купить" */ ?>
                                <div class="price">
                                    <?php if(SALES_MODULE_ENABLED == 'true' && !empty($product['specials_new_products_price'])) : ?>
                                        <div class="price-old">
                                            <?php echo $currencies->display_price_nodiscount($product['products_price'], tep_get_tax_rate($product['products_tax_class_id'])); ?>
                                        </div>
                                        <div class="price-new">
                                            <?php echo $currencies->display_price_nodiscount($product['final_price'], tep_get_tax_rate($product['products_tax_class_id'])); ?>
                                        </div>
                                    <?php else : ?>
                                        <?php echo $currencies->display_price_nodiscount($product['final_price'], tep_get_tax_rate($product['products_tax_class_id'])); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="equals"></div>
                            <form class="add-to-cart" method="POST" action="<?php echo tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'token')) . 'action=add_product'); ?>">
                                <?php /* Первичный товар */ ?>
                                <input type="hidden" name="products_id[]" value="<?php echo $product_info['products_id']; ?>" />
                                <input type="hidden" name="cart_quantity[<?php echo $product_info['products_id']; ?>]" value="<?php echo empty($product_info['products_quantity_order_min']) ? 1 : $product_info['products_quantity_order_min']; ?>" />
                                <?php foreach($selected_options_values as $options_id => $options_values_id) : ?>
                                    <input type="hidden" name="id[<?php echo $product_info['products_id']; ?>][<?php echo $options_id; ?>]" value="<?php echo $options_values_id; ?>" />
                                <?php endforeach; ?>
                                <?php /* Вторичный товар */ ?>
                                <input type="hidden" name="products_id[]" value="<?php echo $product['products_id']; ?>" />
                                <input type="hidden" name="cart_quantity[<?php echo $product['products_id']; ?>]" value="<?php echo empty($product['products_quantity_order_min']) ? 1 : $product['products_quantity_order_min']; ?>" />
                                <?php if(!empty($product['attributes'])) : ?>
                                    <?php foreach($product['attributes'] as $options_id => $options_values_id) : ?>
                                        <input type="hidden" name="id[<?php echo $product['products_id']; ?>][<?php echo $options_id; ?>]" value="<?php echo $options_values_id; ?>" />
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <?php /* Сравнение цен с групповой скидкой и без */ ?>
                                <?php if($product['xsell_discount']) : ?>
                                    <div class="discount"><?php echo $currencies->display_price_nodiscount($product['xsell_discount'], tep_get_tax_rate($product['products_tax_class_id'])); ?></div>
                                    <div class="price-new"><?php echo $currencies->display_price_nodiscount($product_info['final_price'] + $product['final_price'] - $product['xsell_discount'], $product['products_tax_class_id']); ?></div>
                                    <div class="price-old"><?php echo $currencies->display_price_nodiscount($product_info['final_price'] + $product['final_price'], $product['products_tax_class_id']); ?></div>
                                <?php else : ?>
                                    <div class="price-new"><?php echo $currencies->display_price_nodiscount($product_info['final_price'] + $product['final_price'], $product['products_tax_class_id']); ?></div>
                                <?php endif; ?>
                                <div class="buttons">
                                    <?php if($product['final_availability'] && $product_info['final_availability']) : ?>
                                        <button
                                                type="submit"
                                                class="button button-big button-add-to-cart"
                                        ><?php echo IMAGE_BUTTON_ADDTO_CART; ?></button>
                                    <?php else : ?>
                                        <span
                                                class="button button-big button-disabled button-out-of-stock"
                                        ><?php echo PRODUCT_NOT_AVIAIlABLE; ?></span>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
        <?php endif;?>
            </div>
<?php endif; ?>
<?php /* Описание и характеристики */ ?>
<div class="tab-content tab-reviews common-styled-block"><?php require DIR_WS_INCLUDES . 'commentit/comment.php'; ?></div>
<!--<?php /* Информация о доставке в отдельной вкладке */ ?>
<?php //if($delivery_info) { ?><div class="tab-content tab-delivery common-styled-block"><?php echo $delivery_info; ?></div><?php // } ?>
<?php /* Информация об оплате в отдельной вкладке */ ?>
<?php //if($payment_info) { ?><div class="tab-content tab-payment common-styled-block"><?php echo $payment_info; ?></div><?php //} ?>          -->
<?php /* Другие товары из раздела */ ?>
<?php

if(!empty($siblings_products_query) && tep_db_num_rows($siblings_products_query)) {
    $tpl_settings = array(
        'request' => $siblings_products_query
    );
    
?>
<div class="siblings-products mpm-bg-white-style">
    <div class="header-and-arrows clearfix">
        <div class="header">
            <?php echo OTHER_PRODUCTS_FROM_THIS_CATEGORY; ?>
            <a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', \EShopmakers\Data\CategoriesTree::getParentsChain($product_info['categories_id']))); ?>"><?php echo tep_escape($product_info['categories_name']); ?></a>
        </div>
        <div class="arrows">
            <a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', \EShopmakers\Data\CategoriesTree::getParentsChain($product_info['categories_id']))); ?>"><?php echo OTHER_PRODUCTS_ALL; ?></a>
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
            $('.siblings-products .listing-tile').slick({
                slidesToShow: 5,
                slidesToScroll: 5,
                infinite: false,
                dots: false,
                appendArrows: '.siblings-products .arrows span',
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
<?php } ?>
<?php /* Просмотренные товары */ ?>
<?php require DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/viewed_products.php'; ?>
<?php /* Дополнительные скрипты для этой страницы */ ?>
<?php EShopmakers\Html\Capture::getInstance('footer')->startCapture(); ?>
<script src="templates/gigimot/js/product-info.js"></script>
<script>
    var default_images = <?php echo json_encode($product_info['products_images']); ?>,
        options_images = <?php echo json_encode($options_values_images); ?>;
</script>
<?php

EShopmakers\Html\Capture::getInstance('footer')->stopCapture();
$currencies->taxWrapper = $_tw;