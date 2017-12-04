<?php

/* @var $cart \shoppingCart */
/* @var $currencies \currencies */

$products = $cart->get_products();
$options_ids = array();
$options_values_ids = array();
foreach($products as $i => $product)
{
    if(empty($product['attributes']))
    {
        continue;
    }
    $options_ids = array_merge($options_ids, array_keys($product['attributes']));
    $options_values_ids = array_merge($options_values_ids, array_values($product['attributes']));
}
$options_ids = array_unique(array_filter($options_ids, 'is_numeric'));
$options_values_ids = array_unique(array_filter($options_values_ids, 'is_numeric'));

$options_names = array();
if($options_ids)
{
    $options_ids = implode(', ', $options_ids);
    $query = tep_db_query("SELECT products_options_id, products_options_name FROM products_options WHERE language_id = {$_SESSION['languages_id']} AND products_options_id IN ({$options_ids})");
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $options_names[$row['products_options_id']] = $row['products_options_name'];
        }
    }
}

$options_values_names = array();
if($options_ids)
{
    $options_values_ids = implode(', ', $options_values_ids);
    $query = tep_db_query("SELECT products_options_values_id, products_options_values_name FROM products_options_values WHERE language_id = {$_SESSION['languages_id']} AND products_options_values_id IN ({$options_values_ids})");
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $options_values_names[$row['products_options_values_id']] = $row['products_options_values_name'];
        }
    }
}

?>
<div class="checkout-cart">
    <h2><?php echo MY_ORDER; ?></h2>
    <?php foreach($cart->get_products() as $product) : ?>
    <div class="cart-item clearfix">
        <?php if($product['image']) : ?>
        <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['id']); ?>" class="item-image"><img src="<?php echo tep_href_link(FILENAME_IMAGE_RESIZER, 'w=100&h=100&thumb=' . rawurlencode($product['image'])); ?>" alt="<?php echo tep_escape($product['name']); ?>" /></a>
        <?php endif; ?>
        <div class="everything-else">
            <div class="item-name"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['id']); ?>"><?php echo tep_escape($product['name']); ?></a></div>
            <?php if($product['attributes']) : ?>
            <ul class="item-attributes">
                <?php foreach($product['attributes'] as $option_id => $option_value_id) : ?>
                <li><?php echo $options_names[$option_id], ': ', $options_values_names[$option_value_id]; ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <div class="item-total">
                <li><b><?php echo CHECKOUT_CART_TEXT_PRICE; ?></b>: <?php echo $currencies->display_price_nodiscount($product['final_price'], tep_get_tax_rate($product['tax_class_id'])); ?></li>
                <li><b><?php echo CHECKOUT_CART_TEXT_QUANTITY; ?></b>: <?php echo $product['quantity']; ?></li>
                <li><b><?php echo CHECKOUT_CART_TEXT_TOTAL; ?></b>: <?php echo $currencies->display_price_nodiscount($product['final_price'], tep_get_tax_rate($product['tax_class_id']), $product['quantity']); ?></li>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>