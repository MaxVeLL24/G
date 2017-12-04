<?php

/**
 * Страница товара
 */

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . '/' . FILENAME_PRODUCT_INFO;

// ID товара
$products_id = isset($_GET['products_id']) ? $_GET['products_id'] : null;
if(!$products_id)
{
    require FILENAME_NOT_FOUND;
}
$products_id = intval(tep_get_prid($products_id));
if(!$products_id)
{
    require FILENAME_NOT_FOUND;
}

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

// Установлена ли для группы этого покупателя специальная цена?
$group_price = '';
if($customer['customers_groups_price'] > 1)
{
    $group_price = '_' . $customer['customers_groups_price'] . ' AS products_price';
}

// Выборка информации о товаре
$query_string = <<<SQL
SELECT
    p.lable_3,
    p.lable_2,
    p.lable_1,
    p.products_id,
    pd.products_name,
    pd.products_viewed,
    pd.products_description,
    p.products_model,
    p.products_quantity,
    p.kiev_stock,
    p.our_stock,
    p.mankovka_stock,
    pd.products_info,
    p.products_images,
    p.products_image,
    p.products_image_med,
    pd.products_url,
    p.products_price{$group_price},
    p.products_tax_class_id,
    p.products_date_added,
    p.products_date_available,
    p.manufacturers_id,
    p.products_quantity_order_min,
    p.products_comments_count,
    p.products_rating,
    m.manufacturers_name,
    pd.products_head_title_tag,
    pd.products_head_desc_tag,
    pd.products_head_keywords_tag

FROM products AS p
        
-- Описание товара
INNER JOIN products_description AS pd
ON
    pd.products_id = p.products_id AND
    pd.language_id = {$_SESSION['languages_id']}
    
-- Производитель
LEFT OUTER JOIN manufacturers AS m
ON
    p.manufacturers_id = m.manufacturers_id
    
WHERE
    p.products_status = 1 AND
    p.products_id = {$products_id}
LIMIT 1
SQL;
$query = tep_db_query($query_string);

// Выводим сообщение об ошибке, если товар не найден
if(!tep_db_num_rows($query))
{
    require FILENAME_NOT_FOUND;
}

$product_info = tep_db_fetch_array($query);

// Добавить в просмотренные товары
if(empty($_SESSION['viewed_products']))
{
    $_SESSION['viewed_products'] = array();
}
$_SESSION['viewed_products'][] = $products_id;
$_SESSION['viewed_products'] = array_slice(array_unique(array_filter($_SESSION['viewed_products'])), -50);

// Наличие товара
$product_info['final_availability'] = $product_info['products_price'] > 0;
$product_info['discount'] = $customer['customers_discount'] + $customer['customers_groups_discount'];

// Категория товара
$query_string = <<<SQL
SELECT
    cd.categories_id,
    cd.categories_name
FROM products_to_categories AS ptc
INNER JOIN categories_description AS cd
ON
    ptc.categories_id = cd.categories_id AND
    cd.language_id = {$_SESSION['languages_id']}
WHERE ptc.products_id = {$product_info['products_id']}
LIMIT 1
SQL;
$query = tep_db_query($query_string);
if(tep_db_num_rows($query))
{
    $result = tep_db_fetch_array($query);
    $product_info['categories_id'] = $result['categories_id'];
    $product_info['categories_name'] = $result['categories_name'];
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

// Скидка
if(SALES_MODULE_ENABLED == 'true')
{
    $query_string = <<<SQL
SELECT specials_new_products_price
FROM specials
WHERE
    products_id = {$product_info['products_id']} AND
    status = 1 AND
    customers_groups_id IN ({$customer_group_id_in}) AND
    customers_id IN ({$customer_id_in}) AND
    (expires_date IS NULL OR expires_date = '0000-00-00 00:00:00' OR expires_date > NOW())
ORDER BY specials_new_products_price DESC
LIMIT 1
SQL;
    $query = tep_db_query($query_string);
    if(tep_db_num_rows($query))
    {
        $result = tep_db_fetch_array($query);
        $product_info['specials_new_products_price'] = $result['specials_new_products_price'];
    }
}

// Лейба
if($product_info['lable_1'])
{
    $product_info['label'] = 1;
}
elseif($product_info['lable_2'])
{
    $product_info['label'] = 2;
}
elseif($product_info['lable_3'])
{
    $product_info['label'] = 3;
    // Высчитать процент скидки
    if($product_info['specials_new_products_price'] && SALES_MODULE_ENABLED == 'true')
    {
        $product_info['_discount'] = round((($product_info['products_price'] - $product_info['specials_new_products_price']) / $product_info['products_price']) * 100);
    }
}

// Скидка производителя
if($product_info['manufacturers_id'] && $customer['customers_id'])
{
    $query_string = <<<SQL
SELECT manudiscount_discount
FROM manudiscount
WHERE
    manudiscount_manufacturers_id = {$product_info['manufacturers_id']} AND
    manudiscount_groups_id IN ({$customer_group_id_in}) AND
    manudiscount_customers_id IN ({$customer_id_in})
ORDER BY
    manudiscount_customers_id > 0 DESC,
    manudiscount_groups_id > 0 DESC,
    manudiscount_discount ASC
LIMIT 1
SQL;
    $query = tep_db_query($query_string);
    if(tep_db_num_rows($query))
    {
        $result = tep_db_fetch_array($query);
        $product_info['discount'] = $result['manudiscount_discount'];
    }
}

if($product_info['specials_new_products_price'] && SALES_MODULE_ENABLED == 'true')
{
    $product_info['discount'] = 0;
}

// Цена товара учитывая скидку производителя либо скидку покупателя
$product_info['products_price'] += $product_info['products_price'] * $product_info['discount'] / 100;

// Увеличить счётчик просмотров
tep_db_query("update " . TABLE_PRODUCTS_DESCRIPTION . " set products_viewed = products_viewed+1 where products_id = {$products_id} and language_id = '" . (int) $languages_id . "'");

// Атрибуты
$options                   = array();
$options_ids               = array();
$options_values            = array();
$options_values_ids        = array();
$options_to_options_values = array();
$attributes_options        = array();
$non_attributes_options    = array();
$selected_options_values   = array();
$options_type              = array();
$options_values_images     = array();

// Взаимосвязь опций и их значений
$query_string = <<<SQL
SELECT
    pa.options_id,
    pa.options_values_id,
    pa.options_values_price,
    pa.price_prefix,
    pa.pa_qty,
    pa.pa_imgs,
    po.products_options_type,
    po.products_options_name,
    pov.products_options_values_name,
    pov.products_options_values_image,
    pov.products_options_values_extra_data
FROM products_attributes AS pa
INNER JOIN products AS p
ON p.products_id = pa.products_id
INNER JOIN products_options AS po
ON
    po.products_options_id = pa.options_id AND
    po.language_id = {$_SESSION['languages_id']} AND
    po.products_options_type IN (0, 5)
INNER JOIN products_options_values AS pov
ON
    pov.products_options_values_id = pa.options_values_id AND
    pov.language_id = {$_SESSION['languages_id']}
WHERE pa.products_id = {$product_info['products_id']}
ORDER BY
    pov.products_options_values_sort_order ASC,
    pov.products_options_values_name ASC
SQL;
$query = tep_db_query($query_string);
while(($row = tep_db_fetch_array($query)) !== false)
{
    // Скидка производителя либо скидка покупателя так же распространяется и на наценку
    // атрибута (по аналогии с тем, как это сделано в корзине)
    if($row['options_values_price'])
    {
        $row['options_values_price'] += $row['options_values_price'] * $product_info['discount'] / 100;
    }
    
    $options_ids[]                             = $row['options_id'];
    $options_values_ids[]                      = $row['options_values_id'];
    $options[$row['options_id']]               = $row['products_options_name'];
    $options_values[$row['options_values_id']] = $row['products_options_values_name'];
    $options_type[$row['options_id']]          = $row['products_options_type'];
    
    if(empty($options_to_options_values[$row['options_id']]))
    {
        $options_to_options_values[$row['options_id']] = array();
    }
    $options_to_options_values[$row['options_id']][$row['options_values_id']] = $row;

    if($row['products_options_type'] && !array_key_exists($row['options_id'], $selected_options_values))
    {
        $selected_options_values[$row['options_id']] = $row['options_values_id'];
    }
    
    // Изображения для данного атрибута
    if($row['pa_imgs'])
    {
        $options_values_images[$row['options_values_id']] = explode('|', $row['pa_imgs']);
    }
    
    $non_attributes_options[] = $row['options_id'];
}
$query_string = <<<SQL
SELECT
    pa.options_id,
    pa.options_values_id,
    pa.options_values_price,
    pa.price_prefix,
    pa.pa_qty,
    pa.pa_imgs,
    po.products_options_type,
    po.products_options_name,
    pov.products_options_values_name,
    pov.products_options_values_image,
    pov.products_options_values_extra_data
FROM products_attributes AS pa
INNER JOIN products AS p
ON p.products_id = pa.products_id
INNER JOIN products_options AS po
ON
    po.products_options_id = pa.options_id AND
    po.language_id = {$_SESSION['languages_id']} AND
    po.products_options_type IN (1, 2, 3, 4, 6, 7)
INNER JOIN products_options_values AS pov
ON
    pov.products_options_values_id = pa.options_values_id AND
    pov.language_id = {$_SESSION['languages_id']}
WHERE pa.products_id = {$product_info['products_id']}
ORDER BY
    pa.pa_qty > 0 DESC,
    CASE
        WHEN pa.price_prefix = '-' THEN p.products_price - pa.options_values_price
        WHEN pa.price_prefix = '+' THEN p.products_price + pa.options_values_price
        WHEN pa.price_prefix = '=' THEN pa.options_values_price
        ELSE p.products_price
    END ASC,
    pov.products_options_values_sort_order ASC,
    pov.products_options_values_name ASC
SQL;
$query = tep_db_query($query_string);
while(($row = tep_db_fetch_array($query)) !== false)
{
    // Скидка производителя либо скидка покупателя так же распространяется и на наценку
    // атрибута (по аналогии с тем, как это сделано в корзине)
    if($row['options_values_price'])
    {
        $row['options_values_price'] += $row['options_values_price'] * $product_info['discount'] / 100;
    }
    
    $options_ids[]                             = $row['options_id'];
    $options_values_ids[]                      = $row['options_values_id'];
    $options[$row['options_id']]               = $row['products_options_name'];
    $options_values[$row['options_values_id']] = $row['products_options_values_name'];
    $options_type[$row['options_id']]          = $row['products_options_type'];
    
    if(empty($options_to_options_values[$row['options_id']]))
    {
        $options_to_options_values[$row['options_id']] = array();
    }
    $options_to_options_values[$row['options_id']][$row['options_values_id']] = $row;

    if($row['products_options_type'] && !array_key_exists($row['options_id'], $selected_options_values))
    {
        $selected_options_values[$row['options_id']] = $row['options_values_id'];
    }
    
    // Изображения для данного атрибута
    if($row['pa_imgs'])
    {
        $options_values_images[$row['options_values_id']] = explode('|', $row['pa_imgs']);
    }
    
    $attributes_options[] = $row['options_id'];
}

$options_ids = array_unique($options_ids);
$options_values_ids = array_unique($options_values_ids);
$non_attributes_options = array_unique($non_attributes_options);
$attributes_options = array_unique($attributes_options);

// Выбранные опции
$matches = array();
if(!$_GET['options'] && preg_match_all('/\{(\d+)\}(\d+)/', $_GET['options'], $matches))
{
    $selected_options_values = array();
    for($i = 0; $i < count($matches); $i++)
    {
        // Проверяем, что опция и значение существуют
        if(in_array($matches[1][$i], $options_ids) && in_array($matches[2][$i], $options_values_ids))
        {
            $selected_options_values[$matches[1][$i]] = $matches[2][$i];
        }
    }
}

// Финальная цена
$product_info['final_price'] = !empty($product_info['specials_new_products_price']) ? $product_info['specials_new_products_price'] : $product_info['products_price'];

// Доступность и цена товара с выбранными опциями
foreach($attributes_options as $option_id)
{
    $product_info['final_availability'] = $product_info['final_availability'] && $options_to_options_values[$option_id][$selected_options_values[$option_id]]['pa_qty'] > 0;
    // Пересчитываем цену товара учитывая наценку атрибута
    switch($options_to_options_values[$option_id][$selected_options_values[$option_id]]['price_prefix'])
    {
        case '+' :
            $product_info['final_price'] += $options_to_options_values[$option_id][$selected_options_values[$option_id]]['options_values_price'];
            break;
        case '-' :
            $product_info['final_price'] -= $options_to_options_values[$option_id][$selected_options_values[$option_id]]['options_values_price'];
            break;
        case '=' :
            $product_info['final_price'] = $options_to_options_values[$option_id][$selected_options_values[$option_id]]['options_values_price'];
            break;
    }
    // Заменяем изображение товара изображением атрибута, если такое имеется
    if($options_to_options_values[$selected_options_values[$option_id]]['pa_imgs'])
    {
        $product_info['products_images'] = $options_to_options_values[$selected_options_values[$option_id]]['pa_imgs'];
    }
}

// Картинки товара
$product_info['products_images'] = $product_info['products_images'] ? explode(';', $product_info['products_images']) : array();

// Класс для страницы
$body_class = 'product-page';

// ID товара для определения, находится ли он в корзине
$in_cart_products_id = tep_get_uprid($product_info['products_id'], $selected_options_values);

// Сопутствующие товары
if(RELATED_PRODUCTS_MODULE_ENABLED == 'true')
{
    $limit = MAX_DISPLAY_ALSO_PURCHASED;
    $query_string = <<<SQL
SELECT
    p.products_id,
    p.products_quantity,
    p.products_model,
    p.products_price{$group_price},
    p.products_images,
    p.products_quantity_order_min,
    p.products_tax_class_id,
    p.lable_1,
    p.lable_2,
    p.lable_3,
    p.manufacturers_id,
    pd.products_name,
    cd.categories_name,
    px.discount AS xsell_discount,
    s.specials_new_products_price

FROM products_xsell AS px
INNER JOIN products AS p
ON
    p.products_id = px.xsell_id AND
    p.products_status = 1 AND
    p.products_quantity > 0 AND
    (p.products_date_available IS NULL OR p.products_date_available > NOW())

INNER JOIN products_description AS pd
ON
    p.products_id = pd.products_id AND
    pd.language_id = {$_SESSION['languages_id']}

INNER JOIN products_to_categories AS ptc
ON
    p.products_id = ptc.products_id

INNER JOIN categories AS c
ON
    c.categories_id = ptc.categories_id AND
    c.categories_status = 1

INNER JOIN categories_description AS cd
ON
    c.categories_id = cd.categories_id AND
    cd.language_id = {$_SESSION['languages_id']}
    
LEFT OUTER JOIN specials AS s
ON
    s.products_id = p.products_id AND
    s.status = 1 AND
    s.customers_groups_id IN ({$customer_group_id_in}) AND
    s.customers_id IN ({$customer_id_in}) AND
    (s.expires_date IS NULL OR s.expires_date = '0000-00-00 00:00:00' OR s.expires_date > NOW())
    
WHERE px.products_id = {$product_info['products_id']}

GROUP BY p.products_id

ORDER BY
    p.products_sort_order ASC,
    pd.products_name ASC
    
LIMIT {$limit}
SQL;
    $xsell_products = array();
    $query = tep_db_query($query_string);
    if($query && tep_db_num_rows($query))
    {
        $products_ids = array();
        $manufacturers_ids = array();
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $row['link'] = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row['products_id']);
            $row['final_availability'] = $row['products_quantity'] > 0;
            $row['discount'] = $customer['customers_discount'] + $customer['customers_groups_discount'];
            
            // Лейба
            if($row['lable_1'])
            {
                $row['label'] = 1;
            }
            elseif($row['lable_2'])
            {
                $row['label'] = 2;
            }
            elseif($row['lable_3'])
            {
                $row['label'] = 3;
                // Высчитать процент скидки
                if($row['specials_new_products_price'] && SALES_MODULE_ENABLED == 'true')
                {
                    $row['_discount'] = round((($row['products_price'] - $row['specials_new_products_price']) / $row['products_price']) * 100);
                }
            }
            
            $xsell_products[$row['products_id']] = $row;
            $products_ids[] = $row['products_id'];
            if($row['manufacturers_id'])
            {
                $manufacturers_ids[] = $row['manufacturers_id'];
            }
        }
        $products_ids = implode(', ', $products_ids);
        $manufacturers_ids = implode(', ', array_unique($manufacturers_ids));
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
    (expires_date IS NULL OR expires_date = '0000-00-00 00:00:00' OR expires_date > NOW()) AND
    customers_groups_id IN ({$customer_group_id_in}) AND
    customers_id IN ({$customer_id_in})
ORDER BY specials_new_products_price DESC
SQL;
        $query = tep_db_query($query_string);
        if($query && tep_db_num_rows($query))
        {
            while(($row = tep_db_fetch_array($query)) !== false)
            {
                if(!array_key_exists('specials_new_products_price', $xsell_products[$row['products_id']]))
                {
                    $xsell_products[$row['products_id']]['specials_new_products_price'] = $row['specials_new_products_price'];
                }
            }
        }
    }
    
    foreach(array_keys($xsell_products) as $products_id)
    {
        if(!array_key_exists('specials_new_products_price', $xsell_products[$products_id]))
        {
            $xsell_products[$products_id]['specials_new_products_price'] = 0;
        }
    }
    
    // Скидки производителей
    $manufacturers_discounts = array();
    if($manufacturers_ids)
    {
        $query_string = <<<SQL
SELECT manudiscount_discount
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
    
    // Цена товара учитывая скидку производителя либо скидку покупателя
    foreach(array_keys($xsell_products) as $i)
    {
        if($xsell_products[$i]['specials_new_products_price'])
        {
            $xsell_products[$i]['discount'] = 0;
        }
        elseif(array_key_exists($xsell_products[$i]['manufacturers_id'], $manufacturers_discounts))
        {
            $xsell_products[$i]['discount'] = $manufacturers_discounts[$xsell_products[$i]['manufacturers_id']];
        }
        $xsell_products[$i]['products_price'] += $xsell_products[$i]['products_price'] * $xsell_products[$i]['discount'] / 100;
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
                if(empty($xsell_products[$row['products_id']]['attributes']))
                {
                    $xsell_products[$row['products_id']]['attributes'] = array();
                }
                if(array_key_exists($row['options_id'], $xsell_products[$row['products_id']]['attributes']))
                {
                    continue;
                }
                $xsell_products[$row['products_id']]['attributes'][$row['options_id']] = $row['options_values_id'];
                $xsell_products[$row['products_id']]['final_availability'] = $xsell_products[$row['products_id']]['final_availability'] && $row['pa_qty'] > 0;
                // Пересчитываем цену товара учитывая наценку атрибута
                switch($row['price_prefix'])
                {
                    case '+' :
                        if(!empty($xsell_products[$row['products_id']]['specials_new_products_price']))
                        {
                            $xsell_products[$row['products_id']]['specials_new_products_price'] += ($row['options_values_price'] - abs($row['options_values_price'] * $xsell_products[$row['products_id']]['discount'] / 100));
                        }
                        else
                        {
                            $xsell_products[$row['products_id']]['products_price'] += ($row['options_values_price'] - abs($row['options_values_price'] * $xsell_products[$row['products_id']]['discount'] / 100));
                        }
                        break;
                    case '-' :
                        if(!empty($xsell_products[$row['products_id']]['specials_new_products_price']))
                        {
                            $xsell_products[$row['products_id']]['specials_new_products_price'] -= ($row['options_values_price'] - abs($row['options_values_price'] * $xsell_products[$row['products_id']]['discount'] / 100));
                        }
                        else
                        {
                            $xsell_products[$row['products_id']]['products_price'] -= ($row['options_values_price'] - abs($row['options_values_price'] * $xsell_products[$row['products_id']]['discount'] / 100));
                        }
                        break;
                    case '=' :
                        if(!empty($xsell_products[$row['products_id']]['specials_new_products_price']))
                        {
                            $xsell_products[$row['products_id']]['specials_new_products_price'] = ($row['options_values_price'] - abs($row['options_values_price'] * $xsell_products[$row['products_id']]['discount'] / 100));
                        }
                        else
                        {
                            $xsell_products[$row['products_id']]['products_price'] = ($row['options_values_price'] - abs($row['options_values_price'] * $xsell_products[$row['products_id']]['discount'] / 100));
                        }
                        break;
                }
                // Заменяем изображение товара изображением атрибута, если такое имеется
                if($row['pa_imgs'])
                {
                    $xsell_products[$row['products_id']]['products_images'] = $row['pa_imgs'];
                }
            }
        }
    }
    
    // Картинки товаров и атрибуты
    foreach($xsell_products as $product_id => $product)
    {
        // Выделить первое изображение товара
        $image = '';
        if($product['products_images'])
        {
            $image = explode(';', $product['products_images']);
            if($image)
            {
                $image = $image[0];
            }
            else
            {
                $image = '';
            }
        }
        $product['image'] = $image;
        $xsell_products[$product_id] = $product;
    }
    
    // Финальная цена
    foreach(array_keys($xsell_products) as $product_id)
    {
        $xsell_products[$product_id]['final_price'] = empty($xsell_products[$product_id]['specials_new_products_price']) ? $xsell_products[$product_id]['products_price'] : $xsell_products[$product_id]['specials_new_products_price'];
    }
}

// Информация об оплате и доставке
$delivery_info = '';
$payment_info = '';
$load_infopages_ids = array();
if(defined('PRODUCT_PAGE_DELIVERY_INFO_PAGE_ID') && PRODUCT_PAGE_DELIVERY_INFO_PAGE_ID) {
    $load_infopages_ids[] = PRODUCT_PAGE_DELIVERY_INFO_PAGE_ID;
}
if(defined('PRODUCT_PAGE_PAYMENT_INFO_PAGE_ID') && PRODUCT_PAGE_PAYMENT_INFO_PAGE_ID) {
    $load_infopages_ids[] = PRODUCT_PAGE_PAYMENT_INFO_PAGE_ID;
}
if($load_infopages_ids) {
    $load_infopages_ids = implode(', ', array_unique($load_infopages_ids));
    $query = tep_db_query("SELECT pages_id, pages_description FROM pages_description WHERE pages_id IN ({$load_infopages_ids}) AND language_id = {$_SESSION['languages_id']}");
    if(tep_db_num_rows($query)) {
        while(($row = tep_db_fetch_array($query)) !== false) {
            if($row['pages_id'] == PRODUCT_PAGE_DELIVERY_INFO_PAGE_ID) {
                $delivery_info = $row['pages_description'];
            }
            if($row['pages_id'] == PRODUCT_PAGE_PAYMENT_INFO_PAGE_ID) {
                $payment_info = $row['pages_description'];
            }
        }
    }
}

// Другие товары из раздела
$query_string = <<<SQL
SELECT
    p.products_id,
    p.products_tax_class_id,
    p.products_quantity,
    p.products_quantity_order_min,
    p.products_price,
    p.products_images,
    p.lable_3,
    p.lable_2,
    p.lable_1,
    pd.products_name
        
FROM products_to_categories AS ptc
INNER JOIN products AS p
ON
    p.products_id = ptc.products_id AND
    p.products_status = 1 AND
    (
        p.products_date_available IS NULL OR
        p.products_date_available = '0000-00-00 00:00:00' OR
        p.products_date_available > NOW()
    )
INNER JOIN products_description AS pd
ON
    pd.products_id = p.products_id AND
    pd.language_id = {$_SESSION['languages_id']}
WHERE
    ptc.categories_id = {$product_info['categories_id']} AND
    ptc.products_id != {$products_id}
ORDER BY
    p.products_sort_order ASC,
    pd.products_name ASC
LIMIT 20
SQL;
$siblings_products_query = tep_db_query($query_string);

// Метаописание
if (empty($product_info['products_head_desc_tag'])) {
    $page_meta_description = sprintf(HEAD_DESC_TAG_PRODUCT_INFO, $product_info['products_name']);
} else {
    $page_meta_description = $product_info['products_head_desc_tag'];
}

// Ключевые слова
if (empty($product_info['products_head_keywords_tag'])) {
    $page_meta_keywords = HEAD_KEY_TAG_ALL;
} else {
    $page_meta_keywords = $product_info['products_head_keywords_tag'];
}

// Тайтл
if (empty($product_info['products_head_title_tag'])) {
    $page_title = sprintf(HEAD_TITLE_TAG_PRODUCT_INFO, $product_info['products_name']);
} else {
    $page_title = $product_info['products_head_title_tag'];
}

// Запретить индексацию страницы, если в запросе присутствуют какие-то парматеры помимо products_id
if(array_diff(array_keys($_GET), array('products_id', 'language')))
{
	$page_robots_tag = 'noindex, follow';
	$page_link_canonical = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_id);
}

$content = CONTENT_PRODUCT_INFO;
require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');