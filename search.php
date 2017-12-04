<?php

/**
 * Быстрый поиск
 */

include_once __DIR__ . '/includes/application_top.php';

// Если не AJAX запрос или невалидный токен, то переадресовываем на главную страницу
if(!\EShopmakers\Http\Request::isAjax() || !\EShopmakers\Security\CSRFToken::seekForTokenInRequestAndValidate())
{
    tep_redirect(tep_href_link(FILENAME_DEFAULT));
}

$result = array();

if(!empty($_GET['q']) && ($q = trim($_GET['q'])))
{
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



    // Выборка товаров
    $q = tep_db_input($q);
    $query_string = <<<SQL
SELECT
    p.products_id,
    p.products_model,
    p.products_images,
    p.products_price{$group_price},
    p.products_tax_class_id,
    p.manufacturers_id,
    pd.products_name     
FROM products AS p
INNER JOIN products_description AS pd
ON
    p.products_id = pd.products_id AND
    pd.language_id = {$languages_id}
WHERE
    p.products_status = 1 AND
    (p.products_model LIKE '%{$q}%' OR pd.products_name LIKE '%{$q}%')
GROUP BY
    p.products_id
ORDER BY
    p.products_quantity > 0 DESC,
    p.products_sort_order ASC,
    pd.products_name ASC
LIMIT 7
SQL;
    $query = tep_db_query($query_string);
    $products = array();
    $manufacturers_ids = array();
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $row['link'] = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row['products_id']);
            $row['final_availability'] = $row['products_quantity'] > 0;
            $row['discount'] = $customer['customers_discount'] + $customer['customers_groups_discount'];
            $products[$row['products_id']] = $row;
            if(!empty($row['manufacturers_id']))
            {
                $manufacturers_ids[] = $row['manufacturers_id'];
            }
        }
    }

    if($products)
    {
        $products_ids = implode(', ', array_keys($products));
    }

    $customers_id_in = array(0);
    $customers_groups_id_in = array(0);
    if(!empty($customer['customers_id']))
    {
        $customers_id_in[] = $customer['customers_id'];
    }
    if(!empty($customer['customers_groups_id']))
    {
        $customers_groups_id_in[] = $customer['customers_groups_id'];
    }
    $customers_id_in = implode(', ', $customers_id_in);
    $customers_groups_id_in = implode(', ', $customers_groups_id_in);

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
    customers_groups_id IN ({$customers_groups_id_in}) AND
    customers_id IN ({$customers_id_in})
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

    // Скидки производителей
    $manufacturers_ids = implode(', ', array_unique($manufacturers_ids));
    $manufacturers_discounts = array();
    if($manufacturers_ids)
    {
        $query_string = <<<SQL
SELECT manudiscount_manufacturers_id, manudiscount_discount
FROM manudiscount
WHERE
    manudiscount_manufacturers_id IN ({$manufacturers_ids}) AND
    manudiscount_groups_id IN ({$customers_groups_id_in}) AND
    manudiscount_customers_id IN ({$customers_id_in})
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
    foreach(array_keys($products) as $products_id)
    {
        if(array_key_exists($products[$products_id]['manufacturers_id'], $manufacturers_discounts))
        {
            $products[$products_id]['discount'] = $manufacturers_discounts[$products[$products_id]['manufacturers_id']];
        }
        $products[$products_id]['products_price'] += $products[$products_id]['products_price'] * $products[$products_id]['discount'] / 100;
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
    po.products_options_type IN (1, 2) AND
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
                        if(!empty($products[$row['products_id']]['specials_new_products_price']))
                        {
                            $products[$row['products_id']]['specials_new_products_price'] += ($row['options_values_price'] - abs($row['options_values_price'] * $products[$row['products_id']]['discount'] / 100));
                        }
                        else
                        {
                            $products[$row['products_id']]['products_price'] += ($row['options_values_price'] - abs($row['options_values_price'] * $products[$row['products_id']]['discount'] / 100));
                        }
                        break;
                    case '-' :
                        if(!empty($products[$row['products_id']]['specials_new_products_price']))
                        {
                            $products[$row['products_id']]['specials_new_products_price'] -= ($row['options_values_price'] - abs($row['options_values_price'] * $products[$row['products_id']]['discount'] / 100));
                        }
                        else
                        {
                            $products[$row['products_id']]['products_price'] -= ($row['options_values_price'] - abs($row['options_values_price'] * $products[$row['products_id']]['discount'] / 100));
                        }
                        break;
                    case '=' :
                        if(!empty($products[$row['products_id']]['specials_new_products_price']))
                        {
                            $products[$row['products_id']]['specials_new_products_price'] = ($row['options_values_price'] - abs($row['options_values_price'] * $products[$row['products_id']]['discount'] / 100));
                        }
                        else
                        {
                            $products[$row['products_id']]['products_price'] = ($row['options_values_price'] - abs($row['options_values_price'] * $products[$row['products_id']]['discount'] / 100));
                        }
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

    // Результирующий массив
    foreach($products as $row)
    {
        $images = explode(';', $row['products_images']);
        $result[] = array(
            $row['products_id'],
            $row['products_name'],
            tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row['products_id']),
            empty($images[0]) ? null : tep_href_link(FILENAME_IMAGE_RESIZER, 'w=50&h=50&thumb=' . rawurlencode($images[0])),
            $row['categories_name'],
            $row['specials_new_products_price'] ? $currencies->display_price_nodiscount($row['specials_new_products_price'], tep_get_tax_rate($row['products_tax_class_id'])) : $currencies->display_price($row['products_price'], tep_get_tax_rate($row['products_tax_class_id'])),
            $row['specials_new_products_price'] ? $currencies->display_price($row['products_price'], tep_get_tax_rate($row['products_tax_class_id'])) : null,
            $row['products_model'],
            $row['manufacturers_name']
        );
    }
}

\EShopmakers\Http\Response::sendJSON($result);