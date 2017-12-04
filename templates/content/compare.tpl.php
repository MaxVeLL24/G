<?php

/**
 * Шаблон страницы сравнения товаров
 */

/* @var $currencies \currencies */
/* @var $cart \shoppingCart */

\EShopmakers\Html\Capture::getInstance('header')->startCapture();
echo '<link rel="stylesheet" href="templates/gigimot/css/compare.css">';
\EShopmakers\Html\Capture::getInstance('header')->stopCapture();

?>
<h1><?php echo COMPARE_HEADING_TITLE; ?></h1>
<?php if($compare_products) : ?>
<?php foreach($compare_products_to_categories as $category_id => $uprids) : ?>
<div class="tabs">
    <a class="tab active" href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=' . implode('_', \EShopmakers\Data\CategoriesTree::getParentsChain($category_id))); ?>"><?php echo $categories[$category_id]; ?></a>
</div>
<div class="tab-content common-styled-block">
    <div class="compare-slide">
        <div class="compare-table">
            <?php /* Картинка */ ?>
            <div class="row">
                <div class="cell heading-cell">&nbsp;</div>
                <?php foreach($uprids as $uprid) : ?>
                <div class="cell image-cell text-center">
                    <a href="<?php echo $compare_products[$uprid]['link']; ?>">
                        <?php if($compare_products[$uprid]['image']) : ?>
                        <img
                            src="<?php echo tep_href_link(FILENAME_IMAGE_RESIZER, 'w=180&h=180&thumb=' . rawurlencode($compare_products[$uprid]['image'])); ?>"
                            alt="<?php echo $products[$_SESSION['compares'][$uprid]['products_id']]['products_name']; ?>"
                            />
                        <?php endif; ?>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php /* Название */ ?>
            <div class="row">
                <div class="cell heading-cell"><?php echo COMPARE_ROW_PRODUCT_NAME; ?></div>
                <?php foreach($uprids as $uprid) : ?>
                <div class="cell">
                    <a href="<?php echo $compare_products[$uprid]['link']; ?>"><?php echo $products[$_SESSION['compares'][$uprid]['products_id']]['products_name']; ?></a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php /* Модель/код товара */ ?>
            <div class="row">
                <div class="cell heading-cell"><?php echo COMPARE_ROW_PRODUCT_MODEL; ?></div>
                <?php foreach($uprids as $uprid) : ?>
                <div class="cell"><?php echo $products[$_SESSION['compares'][$uprid]['products_id']]['products_model'] ? $products[$_SESSION['compares'][$uprid]['products_id']]['products_model'] : '&nbsp;'; ?></div>
                <?php endforeach; ?>
            </div>
            <?php /* Цена */ ?>
            <div class="row">
                <div class="cell heading-cell"><?php echo COMPARE_ROW_PRODUCT_PRICE; ?></div>
                <?php foreach($uprids as $uprid) : ?>
                <div class="cell price-cell text-center"><?php echo $currencies->display_price_nodiscount($compare_products[$uprid]['final_price'], $products[$_SESSION['compares'][$uprid]['products_id']]['products_tax_class_id']); ?></div>
                <?php endforeach; ?>
            </div>
            <?php /* Добавить в корзину */ ?>
            <div class="row">
                <div class="cell heading-cell">&nbsp;</div>
                <?php foreach($uprids as $uprid) : ?>
                <div class="cell buttons-cell text-center">
                    <?php if($compare_products[$uprid]['availability']) : ?>
                    <form
                        name="add_to_cart_<?php echo str_replace(array('{', '}'), array('_', '_'), $uprid); ?>"
                        id="add-to-cart-<?php echo str_replace(array('{', '}'), array('-', '-'), $uprid); ?>"
                        class="add-to-cart"
                        >
                        <input type="hidden" name="products_id" value="<?php echo $products[$_SESSION['compares'][$uprid]['products_id']]['products_id']; ?>" />
                        <input type="hidden" name="cart_quantity" value="<?php echo empty($products[$_SESSION['compares'][$uprid]['products_id']]['products_quantity_order_min']) ? 1 : $products[$_SESSION['compares'][$uprid]['products_id']]['products_quantity_order_min']; ?>" />
                        <button type="submit" class="button"><?php echo IMAGE_BUTTON_ADDTO_CART; ?></button>
                        <a
                            class="button button-red"
                            href="<?php echo tep_href_link(FILENAME_COMPARE, 'action=remove&products_id=' . $uprid); ?>"
                            title="<?php echo COMPARE_BUTTON_REMOVE_TITLE; ?>"
                            ><?php echo COMPARE_BUTTON_REMOVE; ?></a>
                    </form>
                    <?php else : ?>
                    <span class="button button-disabled"><?php echo PRODUCT_NOT_AVIAIlABLE; ?></span>
                    <a
                        class="button button-red"
                        href="<?php echo tep_href_link(FILENAME_COMPARE, 'action=remove&products_id=' . $uprid); ?>"
                        title="<?php echo COMPARE_BUTTON_REMOVE_TITLE; ?>"
                        ><?php echo COMPARE_BUTTON_REMOVE; ?></a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php /* Атрибуты */ ?>
            <?php if(!empty($options_to_categories[$category_id])) : ?>
            <?php foreach($options_to_categories[$category_id] as $option_id) : ?>
            <div class="row">
                <div class="cell heading-cell"><?php echo $products_options[$option_id]['products_options_name']; ?></div>
                <?php foreach($uprids as $uprid) : ?>
                <div class="cell">
                    <?php /* Название всех значений текущей опции текущего товара через запятую */ ?>
                    <?php
                    
                    if(!empty($options_values_to_products_options[$_SESSION['compares'][$uprid]['products_id'] . '_' . $option_id]))
                    {
                        $options_values_names = array();
                        foreach($options_values_to_products_options[$_SESSION['compares'][$uprid]['products_id'] . '_' . $option_id] as $options_values_id)
                        {
                            $options_values_names[] = $products_options_values[$options_values_id]['products_options_values_name'];
                        }
                        echo tep_escape(implode(', ', $options_values_names));
                    }
                    
                    ?>
                    <?php if(isset($_SESSION['compares'][$uprid]['attributes'][$option_id])) : ?>
                    <?php /* Если это не текстовая опция и товар в наличии */ ?>
                    <?php if($products_options[$option_id]['products_options_type'] && $compare_products[$uprid]['availability']) : ?>
                    <input
                        type="hidden"
                        name="id[<?php echo $option_id; ?>]"
                        value="<?php echo $_SESSION['compares'][$uprid]['attributes'][$option_id]; ?>"
                        form="add-to-cart-<?php echo str_replace(array('{', '}'), array('-', '-'), $uprid); ?>"
                        />
                    <?php endif; ?>
                    <?php else : ?>
                    &nbsp;
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<br>
<?php endforeach; ?>
<?php else : ?>
<div class="alert alert-info" role="alert"><?php echo COMPARE_EMPTY_COMPARE_LIST_ALERT; ?></div>
<?php endif; ?>
<?php

\EShopmakers\Html\Capture::getInstance('footer')->startCapture();
echo '<script src="templates/gigimot/js/compare.js"></script>';
\EShopmakers\Html\Capture::getInstance('footer')->stopCapture();
