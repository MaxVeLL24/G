<?php

/**
 * Вывод товаров списком или плиткой
 */

/** @var $currencies \currencies */

if(!tep_db_num_rows($tpl_settings['request']))
{
    // Ничего не выводим, если в выборке нет результатов
    return;
}

$_tw = $currencies->taxWrapper;
$currencies->taxWrapper = 'span';

// Группа покупателя, скидка покупателя, скидка группы покупателя, цена покупателя
$customer = array(
    'customers_id' => 0,
    'customers_discount' => GUEST_DISCOUNT,
    'customers_groups_id' => 0,
    'customers_groups_discount' => 0,
    'customers_groups_price' => 1
);
if(!empty($_SESSION['customer_id']))
{
    $query_string = <<<SQL
SELECT
    c.customers_id,
    c.customers_discount,
    c.customers_groups_id,
    COALESCE(cg.customers_groups_discount, 0) AS customers_groups_discount,
    COALESCE(cg.customers_groups_price, 1) AS customers_groups_price
FROM customers AS c
LEFT OUTER JOIN customers_groups AS cg
ON c.customers_groups_id = cg.customers_groups_id
WHERE c.customers_id = {$_SESSION['customer_id']}
LIMIT 1
SQL;
    $query = tep_db_query($query_string);
    if(tep_db_num_rows($query))
    {
        $customer = tep_db_fetch_array($query);
    }
}

$customer_id_in = array(0);
if($customer['customers_id'])
{
    $customer_id_in[] = $customer['customers_id'];
}
$customer_id_in = implode(', ', $customer_id_in);
$customer_group_id_in = array(0);
if($customer['customers_groups_id'])
{
    $customer_group_id_in[] = $customer['customers_groups_id'];
}
$customer_group_id_in = implode(', ', $customer_group_id_in);

// Установлена ли для группы этого покупателя специальная цена?
$group_price = '';
if($customer['customers_groups_price'] > 1)
{
    $group_price = '_' . $customer['customers_groups_price'] . ' AS products_price';
}

// Выгружаем результаты
$products = array();
$manufacturers_ids = array();
while(($product = tep_db_fetch_array($tpl_settings['request'])) !== false)
{
    // Ссылка на страницу товара
    $product['link'] = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $product['products_id']);
    $product['final_availability'] = $product['products_quantity'] > 0;
    $product['mankovka_stock'] = $product['mankovka_stock'] > 0;
    $product['discount'] = $customer['customers_discount'] + $customer['customers_groups_discount'];
    if(!empty($product['manufacturers_id']))
    {
        $manufacturers_ids[] = $product['manufacturers_id'];
    }
    
    $products[$product['products_id']] = $product;
}

if($products)
{
    $products_ids = implode(', ', array_keys($products));
}

// Скидки
if(SALES_MODULE_ENABLED == 'true' && $products_ids)
{
    $query_string = <<<SQL
SELECT
    products_id,
    specials_new_products_price
FROM specials
WHERE
    products_id IN ({$products_ids}) AND
    status = 1 AND
    customers_groups_id IN ({$customer_group_id_in}) AND
    customers_id IN ({$customer_id_in}) AND
    (expires_date IS NULL OR expires_date = '0000-00-00 00:00:00' OR expires_date > NOW())
ORDER BY specials_new_products_price DESC
SQL;
    $query = tep_db_query($query_string);
    if($query && tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            if(!array_key_exists('specials_new_products_price', $products[$row['products_id']]))
            {
                $products[$row['products_id']]['specials_new_products_price'] = $row['specials_new_products_price'];
            }
        }
    }
}
foreach(array_keys($products) as $products_id)
{
    if(!array_key_exists('specials_new_products_price', $products[$products_id]))
    {
        $products[$products_id]['specials_new_products_price'] = 0;
    }
}

// Скидки производителей (только для авторизированных пользователей)
$manufacturers_discounts = array();
if($manufacturers_ids && $customer['customers_id'])
{
    $manufacturers_ids = implode(', ', array_unique($manufacturers_ids));
    $query_string = <<<SQL
SELECT
    manudiscount_manufacturers_id,
    manudiscount_discount
FROM manudiscount
WHERE
    manudiscount_manufacturers_id IN ({$manufacturers_ids}) AND
    manudiscount_groups_id IN ({$customer_group_id_in}) AND
    manudiscount_customers_id IN ({$customer_id_in})
ORDER BY
    manudiscount_customers_id > 0 DESC,
    manudiscount_groups_id > 0 DESC,
    manudiscount_discount ASC
SQL;
    $query = tep_db_query($query_string);
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            if(!array_key_exists($row['manudiscount_manufacturers_id'], $manufacturers_discounts))
            {
                $manufacturers_discounts[$row['manudiscount_manufacturers_id']] = $row['manudiscount_discount'];
            }
        }
    }
}

foreach(array_keys($products) as $products_id)
{
    // Финальная цена
    $products[$products_id]['final_price'] = 0;
    if(!empty($products[$products_id]['specials_new_products_price']))
    {
        $products[$products_id]['final_price'] = $products[$products_id]['specials_new_products_price'];
    }
    else
    {
        // Установить скидку производителя на этот товар, если она есть, вместо суммы скидки покупателя и группы покупателя.
        // Скидка производителя распространяется только на базовую цену и цену атрибутов!
        if($manufacturers_discounts && array_key_exists($products[$products_id]['manufacturers_id'], $manufacturers_discounts))
        {
            $products[$products_id]['discount'] = $manufacturers_discounts[$products[$products_id]['manufacturers_id']];
        }

        // Базовая цена товара с учётом скидки
        $products[$products_id]['products_price'] += $products[$products_id]['products_price'] * $products[$products_id]['discount'] / 100;
        
        // Финальная цена с учётом скидки производителя или покупателя
        $products[$products_id]['final_price'] = $products[$products_id]['products_price'];
    }
}

// Атрибуты
if($products_ids)
{
    $query_string = <<<SQL
SELECT
    pa.products_id,
    pa.options_id,
    pa.options_values_id,
    pa.options_values_price,
    pa.price_prefix,
    pa.pa_imgs,
    pa.pa_qty
FROM products_attributes AS pa
INNER JOIN products AS p
ON p.products_id = pa.products_id
INNER JOIN products_options AS po
ON
    po.products_options_id = pa.options_id AND
    -- 1 - select
    -- 2 - radio
    -- 6 - size
    -- 7 - color
    po.products_options_type IN (1, 2, 6, 7) AND
    po.language_id = {$_SESSION['languages_id']}
INNER JOIN products_options_values AS pov
ON
    pov.products_options_values_id = pa.options_values_id AND
    pov.language_id = {$_SESSION['languages_id']}
WHERE
    pa.products_id IN ({$products_ids})
ORDER BY
    pa.pa_qty > 0 DESC,
    CASE
        WHEN pa.price_prefix = '-' THEN p.products_price - pa.options_values_price
        WHEN pa.price_prefix = '+' THEN p.products_price + pa.options_values_price
        WHEN pa.price_prefix = '=' THEN pa.options_values_price
        ELSE p.products_price
    END ASC,
    pov.products_options_values_sort_order ASC
SQL;
    $query = tep_db_query($query_string);
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            if(empty($products[$row['products_id']]['attributes']))
            {
                $products[$row['products_id']]['attributes'] = array();
            }
            if(array_key_exists($row['options_id'], $products[$row['products_id']]['attributes']))
            {
                continue;
            }
            $products[$row['products_id']]['attributes'][$row['options_id']] = $row['options_values_id'];
            $products[$row['products_id']]['final_availability'] = $products[$row['products_id']]['final_availability'] && $row['pa_qty'] > 0;
            // Пересчитываем цену товара учитывая наценку атрибута
            switch($row['price_prefix'])
            {
                case '+' :
                    $products[$row['products_id']]['final_price'] += ($row['options_values_price'] - abs($row['options_values_price'] * $products[$row['products_id']]['discount'] / 100));
                    break;
                case '-' :
                    $products[$row['products_id']]['final_price'] -= ($row['options_values_price'] - abs($row['options_values_price'] * $products[$row['products_id']]['discount'] / 100));
                    break;
                case '=' :
                    $products[$row['products_id']]['final_price'] = ($row['options_values_price'] - abs($row['options_values_price'] * $products[$row['products_id']]['discount'] / 100));
                    break;
            }
            // Заменяем изображение товара изображением атрибута, если такое имеется
            if($row['pa_imgs'])
            {
                $products[$row['products_id']]['products_images'] = $row['pa_imgs'];
            }
        }
    }
}

// Картинки товаров
foreach(array_keys($products) as $product_id)
{
    // Выделить первое изображение товара
    $image = '';
    if($products[$product_id]['products_images'])
    {
        $image = explode(strpos($products[$product_id]['products_images'], ';') === false ? '|' : ';', $products[$product_id]['products_images']);
        if($image)
        {
            $products[$product_id]['image'] = tep_href_link(FILENAME_IMAGE_RESIZER, 'w=190&h=190&thumb=' . rawurlencode($image[0]));
        }
        else
        {
            $products[$product_id]['image'] = '';
        }
    }
    
    // Уникальный ID товара для корзины
    $products[$product_id]['uprid'] = !empty($products[$product_id]['attributes']) ? tep_get_uprid($product_id, $products[$product_id]['attributes']) : $product_id;
    
    // Лейба
    if($products[$product_id]['lable_1'])
    {
        $products[$product_id]['label'] = 1;
    }
    elseif($products[$product_id]['lable_2'])
    {
        $products[$product_id]['label'] = 2;
    }
    elseif($products[$product_id]['lable_3'])
    {
        $products[$product_id]['label'] = 3;
        // Высчитать процент скидки
        if($products[$product_id]['specials_new_products_price'] && SALES_MODULE_ENABLED == 'true')
        {
            $products[$product_id]['_discount'] = round((($products[$product_id]['products_price'] - $products[$product_id]['specials_new_products_price']) / $products[$product_id]['products_price']) * 100);
        }
    }
}

$add_to_cart_form_action = tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action', 'ajaxloading')) . 'action=add_product');

?>
<div class="products-listing <?php echo !empty($tpl_settings['display']) && $tpl_settings['display'] === 'list' ? 'list-like' : 'tile-like'; ?>">
    <div class="listing-tile">
        <?php foreach($products as $product) : ?>
        <div class="product-item product-<?php echo $product['products_id']; ?>">
            <div class="item-border">
                <div class="item-padding clearfix">
                    <?php /* Лейба */ ?>
                    <?php if($product['label']) : ?>
                    <div class="product-label label-<?php echo $product['label'] == 1 ? 'top' : ($product['label'] == 2 ? 'new' : 'discount'); ?>"><?php echo $product['label'] == 1 ? 'TOP' : ($product['label'] == 2 ? 'NEW' : '-' . (empty($product['_discount']) ? 0 : $product['_discount']) . '%'); ?></div>
                    <?php endif; ?>
                    <?php /* Картинка */ ?>
                    <a class="image" href="<?php echo $product['link']; ?>"><?php if($product['image']) : ?><img src="<?php echo $product['image']; ?>" alt="<?php echo tep_escape($product['products_name']); ?>" /><?php endif; ?></a>
                    <div class="product-info">
                        <?php /* Название категории */ ?>
                        <?php if(!empty($product['categories_name'])) : ?>
                        <div class="category-name"><?php echo tep_escape($product['categories_name']); ?></div>
                        <?php endif; ?>
                        <?php /* Название товара */ ?>
                        <a href="<?php echo $product['link']; ?>" class="product-name"><?php echo tep_escape($product['products_name']); ?></a>
                        <?php /* Цена и кнопка "Купить" */ ?>
                        <div class="price-and-add-to-cart clearfix">
                            <div class="price">
                                <?php if(SALES_MODULE_ENABLED == 'true' && !empty($product['specials_new_products_price'])) : ?>
                                <span class="price-old"><?php echo $currencies->display_price_nodiscount($product['products_price'], tep_get_tax_rate($product['products_tax_class_id'])); ?></span>
                                <span class="price-new"><?php echo $currencies->display_price_nodiscount($product['final_price'], tep_get_tax_rate($product['products_tax_class_id'])); ?></span>
                                <?php else : ?>
                                <?php echo $currencies->display_price_nodiscount($product['final_price'], tep_get_tax_rate($product['products_tax_class_id'])); ?>
                                <?php endif; ?>
                            </div>
                            <form class="add-to-cart clearfix" method="POST" action="<?php echo $add_to_cart_form_action; ?>">
                                <input type="hidden" name="products_id" value="<?php echo $product['products_id']; ?>" />
                                <div class="block-quantity">
                                    <button type="button" class="minus">-</button>
                                    <input
                                        type="text"
                                        name="cart_quantity"
                                        value="<?php echo empty($product['products_quantity_order_min']) ? 1 : $product['products_quantity_order_min']; ?>"
                                        />
                                    <button type="button" class="plus">+</button>
                                </div>
                                <?php if(!empty($product['attributes'])) : ?>
                                <?php foreach($product['attributes'] as $options_id => $options_values_id) : ?>
                                <input type="hidden" name="id[<?php echo $options_id; ?>]" value="<?php echo $options_values_id; ?>" />
                                <?php endforeach; ?>
                                <?php endif; ?>
                                <?php if(!$product['final_availability'] && !$product['mankovka_stock']) : ?>
                                    <span class="button button-disabled"><?php echo PRODUCT_NOT_AVIAIlABLE; ?></span>
                                <?php elseif(!$product['final_availability'] && $product['mankovka_stock']>0) : ?>
                                <a href="<? echo $product['link']; ?>" class="button button-green"> <?php echo PRODUCT_LISTING_PRE_ORDER?></a>
                                <?php elseif($cart->in_cart($product['uprid'])) : ?>
                                <a href="<?php echo tep_href_link(FILENAME_SHOPPING_CART); ?>" class="button button-blue show-cart"><?php echo IMAGE_BUTTON_IN_CART; ?></a>
                                <?php else : ?>
                                <button type="submit" class="button"><?php echo IMAGE_BUTTON_ADDTO_CART; ?></button>
                                <?php endif; ?>
                            </form>
                        </div>
                        <div class="wishlist-compare-and-reviews clearfix">
                            <?php /* Желания и сравнение */ ?>
                            <div
                                class="add-to-wishlist<?php if(isset($_SESSION['wishList']) && $_SESSION['wishList']->in_wishlist($product['uprid'])) : ?> in-wishlist<?php endif; ?>"
                                data-uprid="<?php echo $product['uprid']; ?>"
                                ><?php echo isset($_SESSION['wishList']) && $_SESSION['wishList']->in_wishlist($product['uprid']) ? PRODUCT_LISTING_IN_WISHLIST : PRODUCT_LISTING_ADD_TO_WISHLIST; ?></div>
                            <?php /* <div
                                class="add-to-compare<?php if(!empty($_SESSION['compares']) && is_array($_SESSION['compares']) && array_key_exists($product['uprid'], $_SESSION['compares'])) : ?> in-comparison<?php endif; ?>"
                                data-uprid="<?php echo $product['uprid']; ?>"
                                ><?php echo !empty($_SESSION['compares']) && is_array($_SESSION['compares']) && array_key_exists($product['uprid'], $_SESSION['compares']) ? PRODUCT_LISTING_IN_COMPARISON : PRODUCT_LISTING_ADD_TO_COMPARE; ?></div> */ ?>
                            <?php /* Отзывы */ ?>
                            <div class="reviews">
                                <a href="<?php echo $product['link']; ?>"><?php echo sprintf(PRODUCT_LISTING_COMMENTS_COUNT, countCommentList($product['products_id'])); ?></a>
                            </div>
                        </div>
                        <?php /* Описание */ ?>
                        <?php /* if($product['products_description']) : ?>
                        <div class="description"><?php echo mb_substr(strip_tags($product['products_description']), 0, 400, CHARSET); ?></div>
                        <?php endif; */ ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php

$currencies->taxWrapper = $_tw;