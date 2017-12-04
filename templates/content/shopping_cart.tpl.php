<?php

/**
 * Шаблон страницы товара
 */

/* @var $currencies \currencies */

$is_ajax = \EShopmakers\Http\Request::isAjax();

?>
<h1><?php echo HEADING_TITLE; ?></h1>
<?php if(!empty($products)) : ?>
    <form class="shopping-cart-content" method="POST" action="<?php echo tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'token')) . 'action=update_product'); ?>">
        <input type="hidden" name="token" value="<?php echo tep_escape(\EShopmakers\Security\CSRFToken::getToken()); ?>" />
        <div class="shopping-cart-item item-heading no-image no-name clearfix">
            <div class="price"><?php echo TABLE_HEADING_PRICE; ?></div>
            <div class="quantity"><?php echo TABLE_HEADING_QUANTITY; ?></div>
            <div class="cost"><?php echo TABLE_HEADING_TOTAL; ?></div>
        </div>
        <?php /* Перечень товаров в корзине */ ?>
        <?php foreach($products as $product) : ?>
        <div class="shopping-cart-item<?php if(empty($product['image'])) : ?> no-image<?php endif; ?> clearfix">
            <?php if(!empty($product['image'])) : ?>
            <div class="image">
                <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['id']); ?>"><img src="<?php echo tep_href_link(FILENAME_IMAGE_RESIZER, 'w=100&h=100&thumb=' . rawurlencode($product['image'])); ?>" alt="<?php echo tep_escape($product['name']); ?>" /></a>
            </div>
            <?php endif; ?>
            <div class="name-and-attributes">
                <div class="name">
                    <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['id']); ?>"><?php echo tep_escape($product['name']); ?></a>
                </div>
                <?php if(!empty($product['attributes'])) : ?>
                    <ul>
                        <?php foreach($product['attributes'] as $options_id => $options_values_id) : ?>
                        <li><?php echo tep_escape($options_names[$options_id]), ': ', tep_escape($options_values_names[$options_values_id]); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="price" aria-label="<?php echo TABLE_HEADING_PRICE; ?>"><?php echo $currencies->display_price_nodiscount($product['final_price'], tep_get_tax_rate($product['tax_class_id'])); ?></div>
            <div class="quantity" aria-label="<?php echo TABLE_HEADING_QUANTITY; ?>">
                <input
                    type="hidden"
                    name="products_id[]"
                    value="<?php echo $product['id']; ?>"
                    />
                <input
                    type="number"
                    name="cart_quantity[]"
                    value="<?php echo $product['quantity']; ?>"
                    min="1"
                    step="1"
                    class="form-control"
                    />
                <button
                    type="submit"
                    class="button button-block button-small recalculate-item"
                    ><?php echo TEXT_RECALCULATE; ?></button>
            </div>
            <div class="cost" aria-label="<?php echo TABLE_HEADING_TOTAL; ?>"><?php echo $currencies->display_price_nodiscount($product['final_price'], tep_get_tax_rate($product['tax_class_id']), $product['quantity']); ?></div>
            <div class="remove">
                <button
                    type="submit"
                    class="button button-red remove-item"
                    name="cart_delete[]"
                    value="<?php echo $product['id']; ?>"
                    ><?php echo IMAGE_BUTTON_DELETE; ?></button>
            </div>
        </div>
        <?php endforeach; ?>
    </form>
    <?php /* Итоговая стоимость */ ?>
    <div class="order-totals align-right">
      <div class="order-totals-right-block clearfix">
        <?php echo $order_total_modules->output(); ?>
    <?php /* Купоны */ ?>
    <?php if(CUPONES_MODULE_ENABLED == 'true'): ?>
    <form
        method="POST"
        action="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>"
        class="shopping-cart-coupon-redeem buttons-block align-right"
        >
        <label for="coupon-code-input"><?php echo TEXT_REDEEM_COUPON_LABEL; ?></label>
        <input
            type="text"
            name="gv_redeem_code"
            class="form-control inline"
            required
            placeholder="<?php echo TEXT_REDEEM_COUPON_INPUT_PLACEHOLDER; ?>"
            />
        <button type="submit" class="button button-blue"><?php echo TEXT_REDEEM_COUPON_BUTTON; ?></button>
    </form>
    <?php endif; ?>
      </div>
    </div>
    <div class="clearfix">
    </div>
    <?php /* Кнопки "оформить заказ" и "продолжить покупки" */ ?>
    <div class="buttons-block clearfix">
        <a href="<?php echo tep_href_link(FILENAME_DEFAULT); ?>" class="button button-blue pull-left close-popup"><?php echo TEXT_CONTINUE_SHOPPING; ?></a>
        <a href="<?php echo tep_href_link(FILENAME_CHECKOUT); ?>" class="button pull-right"><?php echo TEXT_CHECKOUT; ?></a>
    </div>
<?php else : ?>
    <?php /* Сообщение о пустой корзине */ ?>
    <div class="alert alert-info" role="alert"><?php echo TEXT_CART_EMPTY; ?></div>
    <div class="buttons-block">
        <a href="<?php echo tep_href_link(FILENAME_DEFAULT); ?>" class="button button-blue close-popup"><?php echo TEXT_CONTINUE_SHOPPING; ?></a>
    </div>
<?php endif; ?>