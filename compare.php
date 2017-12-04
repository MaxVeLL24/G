<?php

/**
 * Страница сравнения товаров
 */

include_once __DIR__ . '/includes/application_top.php';
require FILENAME_FORBIDDEN;
include_once DIR_WS_LANGUAGES . $_SESSION['language'] . DIRECTORY_SEPARATOR . FILENAME_COMPARE;

if(empty($_SESSION['compares']))
{
    $_SESSION['compares'] = array();
}

$action = isset($_GET['action']) ? $_GET['action'] : null;
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : null;

if($action === 'add')
{
    // Проверяем, указан ли ID товара
    $products_id = isset($_GET['products_id']) ? $_GET['products_id'] : null;
    if(!$products_id)
    {
        if(\EShopmakers\Http\Request::isAjax())
        {
            \EShopmakers\Http\Response::sendJSON(array(
                'status' => false,
                'reason' => 'Bad products_id'
            ));
        }
        tep_redirect(tep_href_link($redirect ? $redirect : FILENAME_COMPARE));
    }
    
    // Если такой товар уже есть в списке сравнения
    if(array_key_exists($products_id, $_SESSION['compares']))
    {
        if(\EShopmakers\Http\Request::isAjax())
        {
            \EShopmakers\Http\Response::sendJSON(array(
                'status' => true,
                'reason' => 'OK'
            ));
        }
        tep_redirect(tep_href_link($redirect ? $redirect : FILENAME_COMPARE));
    }
    
    // Парсим ID товара
    $product = tep_parse_uprid($products_id);
    if(!$product['products_id'])
    {
        if(\EShopmakers\Http\Request::isAjax())
        {
            \EShopmakers\Http\Response::sendJSON(array(
                'status' => false,
                'reason' => 'Bad products_id'
            ));
        }
        tep_redirect(tep_href_link($redirect ? $redirect : FILENAME_COMPARE));
    }
    
    // Проверяем, что такой товар существует
    $query = tep_db_query("SELECT COUNT(*) AS count FROM products WHERE products_id = " . $product['products_id']);
    $result = tep_db_fetch_array($query);
    if(!$result['count'])
    {
        if(\EShopmakers\Http\Request::isAjax())
        {
            \EShopmakers\Http\Response::sendJSON(array(
                'status' => false,
                'reason' => 'Product does not exist'
            ));
        }
        tep_redirect(tep_href_link($redirect ? $redirect : FILENAME_COMPARE));
    }
    
    // Если есть опции, то проверяем, что такие опции существуют
    if($product['attributes'])
    {
        $options_ids = implode(', ', array_unique(array_keys($product['attributes'])));
        $options_values_ids = implode(', ', array_unique(array_values($product['attributes'])));
        $query = tep_db_query("SELECT options_id, options_values_id FROM products_attributes WHERE products_id = {$product['products_id']} AND options_id IN ({$options_ids}) AND options_values_id IN ({$options_values_ids})");
        $real_options_ids = array();
        $real_options_values_ids = array();
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $real_options_ids[] = $row['options_id'];
            $real_options_values_ids[] = $row['options_values_ids'];
        }
        if(array_diff(array_keys($product['attributes'], $real_options_ids)) || array_diff(array_values($product['attributes'], $real_options_values_ids)))
        {
            if(\EShopmakers\Http\Request::isAjax())
            {
                \EShopmakers\Http\Response::sendJSON(array(
                    'status' => false,
                    'reason' => 'Not all specified options_ids are actually exist'
                ));
            }
            tep_redirect(tep_href_link($redirect ? $redirect : FILENAME_COMPARE));
        }
    }
    
    // Добавляем товар в список сравнения
    $_SESSION['compares'][$products_id] = $product;
    if(\EShopmakers\Http\Request::isAjax())
    {
        \EShopmakers\Http\Response::sendJSON(array(
            'status' => true,
            'reason' => 'OK'
        ));
    }
    tep_redirect(tep_href_link($redirect ? $redirect : FILENAME_COMPARE));
}
elseif($action === 'remove')
{
    // Проверяем, указан ли ID товара
    $products_id = isset($_GET['products_id']) ? $_GET['products_id'] : null;
    if(!$products_id)
    {
        if(\EShopmakers\Http\Request::isAjax())
        {
            \EShopmakers\Http\Response::sendJSON(array(
                'status' => false,
                'reason' => 'Bad products_id'
            ));
        }
        tep_redirect(tep_href_link($redirect ? $redirect : FILENAME_COMPARE));
    }
    
    // Удаляем товар из списка сравнения
    unset($_SESSION['compares'][$products_id]);
    if(\EShopmakers\Http\Request::isAjax())
    {
        \EShopmakers\Http\Response::sendJSON(array(
            'status' => true,
            'reason' => 'OK'
        ));
    }
    tep_redirect(tep_href_link($redirect ? $redirect : FILENAME_COMPARE));
}

// Вывод товара, находящегося в списке сравнения
if(!empty($_SESSION['compares']))
{
    $products_ids = array();
    $options_ids = array();
    $options_values_ids = array();
    foreach($_SESSION['compares'] as $uprid => $product)
    {
        $products_ids[] = $product['products_id'];
        foreach($product['attributes'] as $options_id => $options_value_id)
        {
            $options_ids[] = $options_id;
            $options_values_ids[] = $options_value_id;
        }
    }
    $products_ids = implode(', ', array_unique($products_ids));
    $options_ids = implode(', ', array_unique($options_ids));
    $options_values_ids = implode(', ', array_unique($options_values_ids));
    
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
    
    // ID покупателя для использования в запросе
    $in_customers_ids = array(0);
    if($customer['customers_id'])
    {
        $in_customers_ids[] = $customer['customers_id'];
    }
    $in_customers_ids = implode(', ', $in_customers_ids);
    
    // ID группы покупателя для использования в запросе
    $in_groups_ids = array(0);
    if($customer['customers_groups_id'])
    {
        $in_groups_ids[] = $customer['customers_groups_id'];
    }
    $in_groups_ids = implode(', ', $in_groups_ids);
    
    // Выгружаем товары
    $products = array();
    $categories_ids = array();
    $manufacturers_ids = array();
    $query_string = <<<SQL
SELECT
    p.products_id,
    p.products_model,
    p.products_images,
    p.products_price{$group_price},
    p.manufacturers_id,
    p.products_quantity_order_min,
    p.products_quantity,
    s.specials_new_products_price,
    pd.products_name,
    ptc.categories_id
FROM products AS p
INNER JOIN products_to_categories AS ptc
ON
    p.products_id = ptc.products_id
INNER JOIN products_description AS pd
ON
    p.products_id = pd.products_id AND
    pd.language_id = {$_SESSION['languages_id']}
LEFT OUTER JOIN specials AS s
ON
    s.products_id = p.products_id AND
    s.status = 1 AND
    s.customers_groups_id IN ({$in_groups_ids}) AND
    s.customers_id IN ({$in_customers_ids}) AND
    (
        s.expires_date IS NULL OR
        s.expires_date = '0000-00-00 00:00:00' OR
        s.expires_date >= NOW()
    )
WHERE
    p.products_id IN ({$products_ids}) AND
    p.products_status = 1 AND
    (
        p.products_date_available IS NULL OR
        p.products_date_available = '0000-00-00 00:00:00' OR
        p.products_date_available >= NOW()
    )
GROUP BY p.products_id
SQL;
    $query = tep_db_query($query_string);
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $products[$row['products_id']] = $row;
            $categories_ids[] = $row['categories_id'];
            if($row['manufacturers_id'])
            {
                $manufacturers_ids[] = $row['manufacturers_id'];
            }
        }
    }
    
    // Выгружаем атрибуты
    $products_attributes = array();
    $options_to_categories = array();
    $_options_ids = array();
    $_options_values_ids = array();
    $options_values_to_products_options = array();
    if($products_ids)
    {
        $query_string = <<<SQL
SELECT
    products_id,
    options_id,
    options_values_id,
    options_values_price,
    price_prefix,
    products_options_sort_order,
    pa_imgs,
    pa_qty
FROM products_attributes
WHERE
    products_id IN ({$products_ids})
SQL;
        $query = tep_db_query($query_string);
        if(tep_db_num_rows($query))
        {
            while(($row = tep_db_fetch_array($query)) !== false)
            {
                $products_attributes[$row['products_id'] . '_' . $row['options_id'] . '_' . $row['options_values_id']] = $row;
                if(empty($options_values_to_products_options[$row['products_id'] . '_' . $row['options_id']]))
                {
                    $options_values_to_products_options[$row['products_id'] . '_' . $row['options_id']] = array();
                }
                $options_values_to_products_options[$row['products_id'] . '_' . $row['options_id']][] = $row['options_values_id'];
                // Принадлежность опций к категориям
                if(empty($options_to_categories[$products[$row['products_id']]['categories_id']]))
                {
                    $options_to_categories[$products[$row['products_id']]['categories_id']] = array();
                }
                $options_to_categories[$products[$row['products_id']]['categories_id']][] = $row['options_id'];
                $_options_ids[] = $row['options_id'];
                $_options_values_ids[] = $row['options_values_id'];
            }
        }
    }
    $_options_ids = implode(', ', array_unique($_options_ids));
    $_options_values_ids = implode(', ', array_unique($_options_values_ids));
    
    // Выгружаем названия опций
    $products_options = array();
    if($_options_ids)
    {
        $query_string = <<<SQL
SELECT
    products_options_id,
    products_options_name,
    products_options_type
FROM products_options
WHERE
    products_options_id IN ({$_options_ids}) AND
    language_id = {$_SESSION['languages_id']}
SQL;
        $query = tep_db_query($query_string);
        if(tep_db_num_rows($query))
        {
            while(($row = tep_db_fetch_array($query)) !== false)
            {
                $products_options[$row['products_options_id']] = $row;
            }
        }
    }
    
    // Выгружаем названия значений опций
    $products_options_values = array();
    if($_options_values_ids)
    {
        $query_string = <<<SQL
SELECT
    products_options_values_id,
    products_options_values_name,
    products_options_values_sort_order
FROM products_options_values
WHERE
    products_options_values_id IN ({$_options_values_ids}) AND
    language_id = {$_SESSION['languages_id']}
SQL;
        $query = tep_db_query($query_string);
        if(tep_db_num_rows($query))
        {
            while(($row = tep_db_fetch_array($query)) !== false)
            {
                $products_options_values[$row['products_options_values_id']] = $row;
            }
        }
    }
    
    // Выгружаем названия категорий
    $categories = array();
    if($categories_ids)
    {
        $categories_ids = implode(', ', array_unique($categories_ids));
        $query_string = <<<SQL
SELECT
    categories_id,
    categories_name
FROM categories_description
WHERE
    categories_id IN ({$categories_ids}) AND
    language_id = {$_SESSION['languages_id']}
SQL;
        $query = tep_db_query($query_string);
        if(tep_db_num_rows($query))
        {
            while(($row = tep_db_fetch_array($query)) !== false)
            {
                $categories[$row['categories_id']] = $row['categories_name'];
            }
        }
    }
    
    // Выгружаем скидки брендов
    $manufacturers_discounts = array();
    if($manufacturers_ids)
    {
        $manufacturers_ids = implode(', ', array_unique($manufacturers_ids));
        $query_string = <<<SQL
SELECT
    manudiscount_manufacturers_id,
    manudiscount_discount
FROM manudiscount
WHERE
    manudiscount_manufacturers_id IN ({$manufacturers_ids}) AND
    manudiscount_groups_id IN ({$in_groups_ids}) AND
    manudiscount_customers_id IN ({$in_customers_ids})
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
    
    // Формируем массив товаров на странице сравнения
    $compare_products = array();
    $compare_products_to_categories = array();
    foreach($_SESSION['compares'] as $uprid => $product)
    {
        $image = explode(';', $products[$product['products_id']]['products_images']);
        $compare_products[$uprid] = array(
            'final_price'  => $products[$product['products_id']]['specials_new_products_price'] ? $products[$product['products_id']]['specials_new_products_price'] : $products[$product['products_id']]['products_price'],
            'availability' => $products[$product['products_id']]['products_quantity'] > 0,
            'image'        => $image ? $image[0] : '',
            'link'         => tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $uprid)
        );
        
        // Принадлежность товаров в категориям
        if(empty($compare_products_to_categories[$products[$product['products_id']]['categories_id']]))
        {
            $compare_products_to_categories[$products[$product['products_id']]['categories_id']] = array();
        }
        $compare_products_to_categories[$products[$product['products_id']]['categories_id']][] = $uprid;
        
        foreach($product['attributes'] as $option_id => $option_value_id)
        {
            // Наценка атрибута
            $attribute_id = $product['products_id'] . '_' . $option_id . '_' . $option_value_id;
            if(isset($products_attributes[$attribute_id]))
            {
                switch($products_attributes[$attribute_id]['price_prefix'])
                {
                    case '+' :
                        $compare_products[$uprid]['final_price'] += $products_attributes[$attribute_id]['options_values_price'];
                        break;
                    case '-' :
                        $compare_products[$uprid]['final_price'] -= $products_attributes[$attribute_id]['options_values_price'];
                        break;
                    case '=' :
                        $compare_products[$uprid]['final_price'] = $products_attributes[$attribute_id]['options_values_price'];
                        break;
                }
                if($products_attributes[$attribute_id]['pa_img'])
                {
                    $image = explode(';', $products_attributes[$attribute_id]['pa_img']);
                    if($image)
                    {
                        $compare_products[$uprid]['image'] = $image[0];
                    }
                }
                $compare_products[$uprid]['availability'] = $compare_products[$uprid]['availability'] && $products_attributes[$attribute_id]['pa_qty'] > 0;
            }
        }
    }
    
    // Уникализировать массив связей опций и каткгорий
    foreach($options_to_categories as $category_id => $options_ids)
    {
        $options_to_categories[$category_id] = array_unique($options_ids);
    }
    
    // Учесть скидку
    foreach($_SESSION['compares'] as $uprid => $product)
    {
        $compare_products[$uprid]['final_price'] += $compare_products[$uprid]['final_price'] * (empty($manufacturers_discounts[$products[$product['products_id']]['manufacturers_id']]) ? $customer['customers_discount'] + $customer['customers_groups_discount'] : $manufacturers_discounts[$products[$product['products_id']]['manufacturers_id']]);
    }
}

$content = CONTENT_COMPARE;
$breadcrumb->add(COMPARE_BREADCRUMB_TITLE, tep_href_link(FILENAME_COMPARE));
$page_robots_tag = 'noindex, follow';
$page_title = COMPARE_HEADING_TITLE;

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
include DIR_WS_INCLUDES . 'application_bottom.php';