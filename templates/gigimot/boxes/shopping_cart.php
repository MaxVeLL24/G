<?php

$cart_products = $cart->get_products(); //Корзина
$cart_count    = $cart->count_contents(); //Товаров в корзине
$cart_total    = $currencies->format($cart->show_total()); //Сумма покупок 1000 + грн

$is_ajax = \EShopmakers\Http\Request::isAjax();
if($is_ajax)
{
    ob_start();
}

?>
<a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="show-cart">
    <div class="title"><?php echo HEADER_LINKS_CART; ?></div>
    <?php if($cart_count > 0) : ?>
    <?php echo sprintf(BOX_SHOPPING_CART_PRODUCTS, $cart_count, $cart_total); ?>
    <?php else : ?>
    <?php echo BOX_SHOPPING_CART_EMPTY; ?>
    <?php endif; ?>
</a>
<?php

if($is_ajax)
{
    $content = str_replace(array("\r", "\n"), array('\r', '\n'), addslashes(ob_get_contents()));
    ob_end_clean();
    header('Content-Type: application/javascript; charset=' . CHARSET);
    echo '$(".page-header .shopping-cart").html("', $content, '");$("nav .show-cart span").text("', $cart_count, '");';
}