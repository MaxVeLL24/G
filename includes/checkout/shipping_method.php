<?php

/**
 * Методы доставки
 */

/* @var $cart \shoppingCart */
/* @var $currencies \currencies */
/* @var $shipping_modules \shipping */

$modules = $shipping_modules->quote();

if(empty($_SESSION['shipping']))
{
    $_SESSION['shipping'] = $shipping_modules->cheapest();
}

?>
<div id="shippingMethods" class="shipping-and-payment-methods">
    <?php foreach($modules as $module) : ?>
    <?php foreach($module['methods'] as $method) : ?>
    <div class="method-item clearfix">
        <div class="custom-radiobox">
            <input
                type="radio"
                name="shipping"
                value="<?php echo $module['id'] . '_' . $method['id']; ?>"
                id="checkout-form-shipping-method-<?php echo $module['id'], '-', $method['id']; ?>"
                <?php if($_SESSION['shipping']['id'] === $module['id'] . '_' . $method['id']) : ?>checked<?php endif; ?>
                />
            <label for="checkout-form-shipping-method-<?php echo $module['id'], '-', $method['id']; ?>"></label>
        </div>
        <label for="checkout-form-shipping-method-<?php echo $module['id'], '-', $method['id']; ?>" class="everything-else">
            <div class="module-title"><?php echo tep_escape($module['module']); ?></div>
            <?php if(!empty($method['title'])) : ?>
            <div class="method-title"><?php echo tep_escape($method['title']); ?></div>
            <?php endif; ?>
            <?php if(!empty($method['cost'])) : ?>
            <div class="method-cost"><?=SUB_TITLE_FROM . $currencies->format($method['cost']); ?></div>
            <?php endif; ?>
        </label>
    </div>
    <?php endforeach; ?>
    <?php endforeach; ?>
</div>