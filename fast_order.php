<?php

include_once __DIR__ . '/includes/application_top.php';

function fail($reason = '')
{
    \EShopmakers\Http\Response::sendJSON(array(
        'success' => false,
        'reason'  => $reason
    ));
}

if (\EShopmakers\Http\Request::isAjax() && \EShopmakers\Security\CSRFToken::seekForTokenInRequestAndValidate()) {
    // Удаляем всё кроме цифр.
    $customer_phone = preg_replace('/\D/', '', $_POST['telephone']);
    if(!$customer_phone)
    {
        fail('empty_phone');
    }
    if($customer_phone[0] === '3' && $customer_phone[1] === '8' && $customer_phone[2] === '0')
    {
        $customer_phone = '+' . $customer_phone;
    }
    elseif($customer_phone[0] === '0')
    {
        $customer_phone = '+38' . $customer_phone;
    }
    else
    {
        $customer_phone = '+380' . $customer_phone;
    }
    
    if(strlen($customer_phone) !== 13)
    {
        fail('bad_phone');
    }
    
    $customer_phone = tep_db_input($customer_phone);

    // Если пользователь авторизирован, оформляем заказ от имени авторизированного пользователя
    if(!empty($_SESSION['customer_id']))
    {
        $query_string = <<<SQL
SELECT
    c.*,
    COALESCE(cg.customers_groups_discount, 0) AS customers_groups_discount,
    COALESCE(cg.customers_groups_price, 1) AS customers_groups_price
FROM customers AS c
LEFT OUTER JOIN customers_groups AS cg
ON c.customers_groups_id = cg.customers_groups_id
WHERE customers_id = {$_SESSION['customer_id']}
LIMIT 1
SQL;
        $query = tep_db_query($query_string);
        if(tep_db_num_rows($query))
        {
            $customer = tep_db_fetch_array($query);
        }
    }
    
    // Если пользователь не авторизирован, то пробуем отыскать пользователя по номеру телефона
    if(empty($customer))
    {
        $query_string = <<<SQL
SELECT
    c.*,
    COALESCE(cg.customers_groups_discount, 0) AS customers_groups_discount,
    COALESCE(cg.customers_groups_price, 1) AS customers_groups_price
FROM customers AS c
LEFT OUTER JOIN customers_groups AS cg
ON c.customers_groups_id = cg.customers_groups_id
WHERE customers_telephone = '{$customer_phone}'
LIMIT 1
SQL;
        $query = tep_db_query($query_string);
        if(tep_db_num_rows($query))
        {
            $customer = tep_db_fetch_array($query);
        }
    }
    
    // Если не удалось отыскать пользователя, регистрируем нового
    if(empty($customer))
    {
        // Создать пользователя
        $query_string = <<<SQL
INSERT INTO customers
SET
    customers_firstname = '{$customer_phone}',
    customers_telephone = '{$customer_phone}'
SQL;
        if(tep_db_query($query_string))
        {
            $customers_id = tep_db_insert_id();
            $query_string = <<<SQL
SELECT
    c.*,
    COALESCE(cg.customers_groups_discount, 0) AS customers_groups_discount,
    COALESCE(cg.customers_groups_price, 1) AS customers_groups_price
FROM customers AS c
LEFT OUTER JOIN customers_groups AS cg
ON c.customers_groups_id = cg.customers_groups_id
WHERE customers_id = {$customers_id}
LIMIT 1
SQL;
            $query = tep_db_query($query_string);
            if(tep_db_num_rows($query))
            {
                $customer = tep_db_fetch_array($query);
            }
        }
    }
    
    // Если не получилось зарегистрировать нового клиента, выдаём сообщение об ошибке
    if(empty($customer))
    {
        fail('cant_create_new_client');
    }
    
    // Специальная цена товара, если указано для группы пользователя
    $product_price = '';
    if($customer['customers_groups_price'] > 1)
    {
        $product_price = sprintf('_%d AS products_price', $customer['customers_groups_price']);
    }
    
    $_currency = tep_db_input(empty($_SESSION['currency']) ? DEFAULT_CURRENCY : $_SESSION['currency']);
    $default_orders_status_id = DEFAULT_ORDERS_STATUS_ID;
    $customers_name = tep_db_input(implode(' ', array_filter(array(
        $customer['customers_firstname'],
        $customer['customers_lastname']
    ))));
    $customers_email_address = tep_db_input($customer['customers_email_address']);
    
    // Созадть новый заказ
    $query_string = <<<SQL
INSERT INTO orders
SET
    customers_id = {$customer['customers_id']},
    customers_groups_id = {$customer['customers_groups_id']},
    customers_name = '{$customers_name}',
    customers_telephone = '{$customer_phone}',
    customers_preorder = '{$_POST['preOrderStatus']}',
    customers_email_address = '{$customers_email_address}',
    delivery_name = '{$customers_name}',
    billing_name = '{$customers_name}',
    date_purchased = NOW(),
    last_modified = NOW(),
    orders_status = {$default_orders_status_id},
    currency = '{$_currency}',
    currency_value = {$currencies->get_value($_currency)}
SQL;

    $orders_add_query = tep_db_query($query_string);
    $orders_add = tep_db_fetch_array($orders_add_query);
    $order_id = tep_db_insert_id();
    
    // Данные из запроса
    $products_ids = array();
    $products_quantity = array();
    $products_attributes = array();
    if(!empty($_POST['products']) && is_array($_POST['products']))
    {
        foreach($_POST['products'] as $product_id => $product_data)
        {
            $product_id = intval($product_id);
            $attributes = array();
            if($product_id < 1)
            {
                continue;
            }
            if(empty($product_data['quantity']) || ($quantity = intval($product_data['quantity'])) < 1)
            {
                continue;
            }
            if(!empty($product_data['options']) && is_array($product_data['options']))
            {
                foreach($product_data['options'] as $option_id => $option_value_id)
                {
                    $option_id = intval($option_id);
                    $option_value_id = intval($option_value_id);
                    if($option_id < 1 || $option_value_id < 1)
                    {
                        continue;
                    }
                    $attributes[$option_id] = $option_value_id;
                }
            }
            $unique_product_id = tep_get_uprid($product_id, $attributes);
            $products_ids[$unique_product_id] = $product_id;
            $products_quantity[$unique_product_id] = $quantity;
            $products_attributes[$unique_product_id] = $attributes;
        }
    }
    if(empty($products_ids))
    {
        fail('Empty products list 1');
    }
    $products_ids = array_unique($products_ids);
    
    // Найти реально существующие товары
    $_products_ids = implode(', ', $products_ids);
    $real_products = array();
    $manufacturers_ids = array();
    $query_string = <<<SQL
SELECT
    p.products_id,
    p.products_model,
    pd.products_name,
    p.products_price,
    p.products_tax_class_id,
    p.manufacturers_id,
    p.products_quantity_order_min,
    p.products_quantity
            
FROM products AS p
INNER JOIN products_description AS pd
ON
    p.products_id = pd.products_id AND
    pd.language_id = {$_SESSION['languages_id']}
WHERE
    p.products_id IN ({$_products_ids}) AND
    (p.products_quantity > 0 OR p.mankovka_stock > 0) AND
    p.products_status = 1
SQL;

    $query = tep_db_query($query_string);
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $row['discount'] = $customer['customers_discount'] + $customer['customers_groups_discount'];
            $real_products[$row['products_id']] = $row;
            if($row['manufacturers_id'] && !in_array($row['manufacturers_id'], $manufacturers_ids))
            {
                $manufacturers_ids[] = $row['manufacturers_id'];
            }
        }
    }
    else
    {
        fail('Empty products list 2');
    }
    
    // Найти скидки производителей
    $manufacturers_discounts = array();
    if($manufacturers_ids)
    {
        $manufacturers_ids = implode(', ', $manufacturers_ids);
        // Если группа не задана
        if(empty($customer['customers_groups_id']))
        {
            $query_string = <<<SQL
SELECT manudiscount_discount
FROM manudiscount
WHERE
    manudiscount_manufacturers_id IN ({$manufacturers_ids}) AND
    manudiscount_groups_id = 0 AND
    manudiscount_customers_id IN (0, {$customer['customers_id']})
ORDER BY
    manudiscount_customers_id > 0 DESC,
    manudiscount_discount ASC
SQL;
        }
        // Если группа задана
        else
        {
            $query_string = <<<SQL
SELECT manudiscount_discount
FROM manudiscount
WHERE
    manudiscount_manufacturers_id IN ({$manufacturers_ids}) AND
    manudiscount_groups_id IN (0, {$customer['customers_groups_id']}) AND
    manudiscount_customers_id IN (0, {$customer['customers_id']})
ORDER BY
    manudiscount_customers_id > 0 DESC,
    manudiscount_groups_id > 0 DESC,
    manudiscount_discount ASC
SQL;
        }
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
    foreach(array_keys($real_products) as $i)
    {
        if(array_key_exists($real_products[$i]['manufacturers_id'], $manufacturers_discounts))
        {
            $real_products[$i]['discount'] = $manufacturers_discounts[$real_products[$i]['manufacturers_id']];
        }
        $real_products[$i]['products_price'] += $real_products[$i]['products_price'] * $real_products[$i]['discount'] / 100;
    }
    
    // Отфильтровать несуществующие товары
    foreach($products_ids as $unique_product_id => $product_id)
    {
        if(!array_key_exists($product_id, $real_products))
        {
            unset($products_ids[$unique_product_id]);
            unset($products_quantity[$unique_product_id]);
            unset($products_attributes[$unique_product_id]);
        }
    }
    if(empty($products_ids))
    {
        fail('Empty products list 3');
    }
    
    // Найти реально существующие атрибуты
    $_products_ids = implode(', ', $products_ids);
    $_options_ids = array();
    $_options_values_ids = array();
    $real_products_attributes = array();
    foreach($products_attributes as $unique_product_id => $attributes)
    {
        $_options_ids = array_merge($_options_ids, array_keys($attributes));
        $_options_values_ids = array_merge($_options_values_ids, array_values($attributes));
    }
    if($_options_ids && $_options_values_ids)
    {
        $_options_ids = implode(', ', array_unique($_options_ids));
        $_options_values_ids = implode(', ', array_unique($_options_values_ids));
        $query_string = <<<SQL
SELECT
    pa.products_id,
    pa.options_id,
    pa.options_values_id,
    pa.options_values_price,
    pa.price_prefix,
    pa.pa_qty,
    po.products_options_name,
    pov.products_options_values_name
FROM products_attributes AS pa
INNER JOIN products_options AS po
ON
    pa.options_id = po.products_options_id AND
    po.language_id = {$_SESSION['languages_id']} AND
    po.products_options_type IN (1, 2)
INNER JOIN products_options_values AS pov
ON
    pa.options_values_id = pov.products_options_values_id AND
    pov.language_id = {$_SESSION['languages_id']}
WHERE
    pa.products_id IN ({$_products_ids}) AND
    pa.options_id IN ({$_options_ids}) AND
    pa.options_values_id IN ({$_options_values_ids}) AND
    pa.pa_qty > 0
SQL;
        $query = tep_db_query($query_string);
        if(tep_db_num_rows($query))
        {
            while(($row = tep_db_fetch_array($query)) !== false)
            {
                $real_products_attributes[] = $row;
            }
        }
    }
    
//    header('Content-Type: text/plain; charset=UTF-8');
//    var_dump($real_products_attributes);
//    exit();
    
    // Удалить несуществующие атрибуты
    foreach($products_attributes as $unique_product_id => $attributes)
    {
        foreach($attributes as $option_id => $option_value_id)
        {
            $found = false;
            foreach($real_products_attributes as $_attributes)
            {
                if($_attributes['products_id'] == $products_ids[$unique_product_id] && $_attributes['options_id'] == $option_id && $_attributes['options_values_id'] == $option_value_id)
                {
                    $found = true;
                    break;
                }
            }
            if(!$found)
            {
                unset($products_ids[$unique_product_id]);
                unset($products_quantity[$unique_product_id]);
                unset($products_attributes[$unique_product_id]);
            }
        }
    }
    if(empty($products_ids))
    {
        fail('Empty products list 4');
    }
    
    // Выгрузить скидку
    $_products_ids = implode(', ', array_values($products_ids));
    if($_products_ids)
    {
        if(empty($customer['customers_groups_id']))
        {
            $query_string = <<<SQL
SELECT
    products_id,
    specials_new_products_price
FROM specials
WHERE
    products_id IN ({$_products_ids}) AND
    status = 1 AND
    (expires_date IS NULL OR expires_date = '0000-00-00 00:00:00' OR expires_date > NOW()) AND
    customers_groups_id = 0 AND
    customers_id IN (0, {$customer['customers_id']})
ORDER BY specials_new_products_price DESC
LIMIT 1
SQL;
        }
        // Если группа задана
        else
        {
            $query_string = <<<SQL
SELECT
    products_id,
    specials_new_products_price
FROM specials
WHERE
    products_id IN ({$_products_ids}) AND
    status = 1 AND
    (expires_date IS NULL OR expires_date = '0000-00-00 00:00:00' OR expires_date > NOW()) AND
    customers_groups_id IN (0, {$customer['customers_groups_id']}) AND
    customers_id IN (0, {$customer['customers_id']})
ORDER BY specials_new_products_price DESC
LIMIT 1
SQL;
        }
        $query = tep_db_query($query_string);
        if(tep_db_num_rows($query))
        {
            while(($row = tep_db_fetch_array($query)) !== false)
            {
                $real_products[$row['products_id']]['specials_new_products_price'] = $row['specials_new_products_price'];
            }
        }
    }
    
    // Финальная цена товара
    $final_products_price = array();
    foreach($products_ids as $unique_product_id => $product_id)
    {
        if(!empty($real_products[$product_id]['specials_new_products_price']))
        {
            $final_products_price[$unique_product_id] = $real_products[$product_id]['specials_new_products_price'];
        }
        else
        {
            $final_products_price[$unique_product_id] = $real_products[$product_id]['products_price'];
        }
        
        // Учесть наценку атрибутов
        if(!empty($products_attributes[$unique_product_id]))
        {
            foreach($products_attributes[$unique_product_id] as $option_id => $option_value_id)
            {
                foreach($real_products_attributes as $attributes)
                {
                    if($attributes['products_id'] == $product_id && $attributes['options_id'] == $option_id && $attributes['options_values_id'] == $option_value_id)
                    {
                        $attributes['options_values_price'] -= $attributes['options_values_price'] * $real_products[$product_id]['discount'];
                        switch($attributes['price_prefix'])
                        {
                            case '+' :
                                $final_products_price[$unique_product_id] += $attributes['options_values_price'];
                                break;
                            case '-' :
                                $final_products_price[$unique_product_id] -= $attributes['options_values_price'];
                                break;
                            case '=' :
                                $final_products_price[$unique_product_id] = $attributes['options_values_price'];
                                break;
                        }
                    }
                }
            }
        }
    }
    
    // Добавить товары и атрибуты к заказу
    $products_attributes_to_order = array();
    $email_products = '';
    foreach($products_ids as $unique_product_id => $product_id)
    {
        $query_string = sprintf(
                "INSERT INTO orders_products SET orders_id = %d, products_id = %d, products_model = '%s', products_name = '%s', products_price = %.4f, final_price = %.4f, products_tax = %.4f, products_quantity = %d",
                $order_id,
                $product_id,
                tep_db_input($real_products[$product_id]['products_model']),
                tep_db_input($real_products[$product_id]['products_name']),
                $real_products[$product_id]['products_price'],
                $final_products_price[$unique_product_id],
                $real_products[$product_id]['products_tax_class_id'],
                $products_quantity[$unique_product_id]
                );
        $final_products_price[$unique_product_id] += $final_products_price[$unique_product_id] * tep_get_tax_rate($real_products[$product_id]);
        $email_products .= '<br><br>' . $products_quantity[$unique_product_id]
                        .  ' &times; '
                . tep_escape($real_products[$product_id]['products_name'])
                . ' ('
                . tep_escape($real_products[$product_id]['products_model'])
                . ') = '
                . $currencies->display_price_nodiscount($final_products_price[$unique_product_id], 0, $products_quantity[$unique_product_id]);
        if(!tep_db_query($query_string))
        {
            continue;
        }
        $orders_products_id = tep_db_insert_id();
        if(!empty($products_attributes[$unique_product_id]))
        {
            foreach($products_attributes[$unique_product_id] as $option_id => $option_value_id)
            {
                foreach($real_products_attributes as $attributes)
                {
                    if($attributes['products_id'] == $product_id && $attributes['options_id'] == $option_id && $attributes['options_values_id'] == $option_value_id)
                    {
                        $attributes['options_values_price'] -= $attributes['options_values_price'] * $real_products[$product_id]['discount'];
                        $products_attributes_to_order[] = sprintf(
                                "(%d, %d, '%s', '%s', %.4f, '%s')",
                                $order_id,
                                $orders_products_id,
                                tep_db_input($attributes['products_options_name']),
                                tep_db_input($attributes['products_options_values_name']),
                                $attributes['options_values_price'],
                                tep_db_input($attributes['price_prefix'])
                                );
                        $email_products .= '<br>' . tep_escape($attributes['products_options_name']) . ': ' . tep_escape($attributes['products_options_values_name']);
                    }
                }
            }
        }
    }
    if($products_attributes_to_order)
    {
        $products_attributes_to_order = implode(', ', $products_attributes_to_order);
        tep_db_query("INSERT INTO orders_products_attributes (orders_id, orders_products_id, products_options, products_options_values, options_values_price, price_prefix) VALUES {$products_attributes_to_order}");
    }
    
    $total_cost = array_sum($final_products_price);
    $_total_cost = $currencies->display_price_nodiscount($total_cost, 0);
    
    // Вставляем данные в таблицу orders_total
    $query_string = <<<SQL
INSERT INTO orders_total (orders_id,  title, text, value, class, sort_order) VALUES
({$order_id}, '<b>Всего</b>', '<b>{$_total_cost}</b></span>', {$total_cost}, 'ot_total', 1),
({$order_id}, 'Стоимость товара:', '{$_total_cost}', {$total_cost}, 'ot_subtotal', 2),
({$order_id}, 'Самовывоз:', '0', 0, 'ot_shipping', 800)
SQL;
    tep_db_query($query_string);
    
    // Вставляем данные в orders_status_history
    tep_db_query("INSERT INTO orders_status_history SET orders_id = {$order_id}, orders_status_id = {$default_orders_status_id}, date_added = NOW(), customer_notified = 0, comments = ''");
    
    // Выгрузить название статуса заказа
    $default_language = tep_db_input(DEFAULT_LANGUAGE);
    $query = tep_db_query("SELECT os.orders_status_name FROM languages AS l INNER JOIN orders_status AS os ON os.orders_status_id = {$default_orders_status_id} AND os.language_id = l.languages_id WHERE l.code = '{$default_language}'");
    if(tep_db_num_rows($query))
    {
        $orders_status = tep_db_fetch_array($query);
        $orders_status = $orders_status['orders_status_name'];
    }
    
    // Загружаем локализацию
    include_once DIR_WS_LANGUAGES . $_SESSION['language'] . DIRECTORY_SEPARATOR . 'fast_order.php';
    
    // Отсылаем письмо админу
    $email_order = 
            FAST_ORDER_ADMIN_EMAIL_ORDER_NUMBER . '<a href="' . tep_href_link('admin/orders.php', 'oID=' . $order_id . '&action=edit') . '" target="_blank">' . $order_id . '</a><br>' . 
            FAST_ORDER_ADMIN_EMAIL_ORDER_STATUS . ' ' . tep_escape($orders_status) . '<br>' .
            FAST_ORDER_ADMIN_EMAIL_ORDER_PHONE . ' ' . tep_escape($customer_phone) . '<br>' .
            FAST_ORDER_ADMIN_EMAIL_ORDER_PRODUCTS . $email_products . '<br><br>' .
            FAST_ORDER_ADMIN_EMAIL_ORDER_TOTAL . '<br><b>' . $_total_cost . '</b>';

    tep_mail('', SEND_EXTRA_ORDER_EMAILS_TO, strftime(DATE_FORMAT_LONG) . FAST_ORDER_ADMIN_EMAIL_SUBJECT . $order_id, $email_order, STORE_NAME, EMAIL_FROM, STORE_NAME, EMAIL_REPLY_TO);

    // Очистить корзину, если нужно
    if(filter_input(INPUT_POST, 'clear_cart', FILTER_VALIDATE_BOOLEAN))
    {
        unset($_SESSION['cart']);
    }
    
    // Отправить ответ клиенту
    \EShopmakers\Http\Response::sendJSON(array(
        'success'  => true
    ));
} else {
    tep_redirect(tep_href_link(FILENAME_DEFAULT));
}