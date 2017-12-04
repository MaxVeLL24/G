<?php

include_once __DIR__ . '/includes/application_top.php';

/* @var $cart \shoppingCart */

// Редирект на главную, если это не AJAX запрос или CSRF токен недействительный
if(!\EShopmakers\Http\Request::isAjax() && !\EShopmakers\Security\CSRFToken::seekForTokenInRequestAndValidate())
{
    tep_redirect(tep_href_link(FILENAME_DEFAULT));
}

$action = empty($_POST['action']) ? empty($_GET['action']) ? null : $_GET['action'] : $_POST['action'];

\EShopmakers\Http\Response::noIndexFollow();

// Получить общие стоимость и количество товаров в корзине
if($action === 'get_total')
{
    require DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/shopping_cart.php';
}
// Добавить товар в корзину
elseif($action === 'add')
{
    $products_id = filter_input(INPUT_POST, 'product', FILTER_VALIDATE_INT);
    $quantity    = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
    $add_set     = filter_input(INPUT_POST, 'add_set', FILTER_VALIDATE_BOOLEAN);
    if(!$quantity)
    {
        $quantity = 1;
    }
    $options = filter_input(INPUT_POST, 'options', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);
    if($products_id && $quantity)
    {
        // Непосредственно добавление в корзину
        if($add_set)
        {
            $cart->add_cart($products_id, $cart->get_quantity(tep_get_uprid($products_id, is_array($options) ? $options : null)) + $quantity, is_array($options) ? $options : null, false);
        }
        else
        {
            $cart->add_cart($products_id, $quantity, is_array($options) ? $options : null, false);
        }
        \EShopmakers\Http\Response::sendJSON(array(
            'status' => $cart->in_cart(tep_get_uprid($products_id, is_array($options) ? $options : null)),
            'cart_link' => tep_href_link(FILENAME_SHOPPING_CART),
            'button_text' => IMAGE_BUTTON_IN_CART,
            'products' => $cart->get_products()
        ));
    }
    \EShopmakers\Http\Response::sendJSON(array(
        'status' => false
    ));
}
// Удалить товар из корзины
elseif($action === 'remove')
{
    $products_id = tep_get_uprid(filter_input(INPUT_POST, 'product'));
    if($cart->in_cart($products_id))
    {
        $cart->remove($products_id);
        $products_id = tep_get_prid($products_id);
        \EShopmakers\Http\Response::sendJSON(array(
            'status' => true,
            'products_id' => $products_id,
            'button_text' => IMAGE_BUTTON_ADDTO_CART
        ));
    }
    \EShopmakers\Http\Response::sendJSON(array(
        'status' => false
    ));
}

require DIR_WS_INCLUDES . 'application_bottom.php';