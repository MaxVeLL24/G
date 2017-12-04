<?php

/**
 * Главная страница и страница со списком товаров в категории, производителях, результатах поиска
 */

include_once __DIR__ . '/includes/application_top.php';

/* @var $currencies \currencies */

if(isset($_GET['keywords']) || isset($_GET['cPath']) || isset($_GET['manufacturers_id']))
{
    // Активные опции
    $options_ids_in_query = array_filter(array_keys($_GET), 'is_numeric');
    $options_values_ids_in_query = array();
    $options_to_options_values = array();
    $more_then_one_option_value_selected = false;
    foreach($options_ids_in_query as $option_id)
    {
        $options_to_options_values[$option_id] = array_filter(explode('-', $_GET[$option_id]), 'is_numeric');
        $options_values_ids_in_query = array_merge($options_values_ids_in_query, $options_to_options_values[$option_id]);
        if(count($options_to_options_values[$option_id]) > 1)
        {
            $more_then_one_option_value_selected = true;
        }
    }
    $options_ids_in_query = array_unique($options_ids_in_query);
    $options_values_ids_in_query = array_unique($options_values_ids_in_query);
    
    // Ошибка, если опции есть, а значений опций нет
    if($options_ids_in_query && !$options_values_ids_in_query)
    {
        require FILENAME_NOT_FOUND;
    }
    // Проверяем существование опций и значений опций
    elseif($options_ids_in_query && $options_values_ids_in_query)
    {
        $query = tep_db_query("SELECT COUNT(sq.products_options_id) AS `count` FROM (SELECT DISTINCT products_options_id FROM products_options WHERE products_options_id IN (" . implode(', ', $options_ids_in_query) . ")) AS sq");
        $result = tep_db_fetch_array($query);
        if($result['count'] != count($options_ids_in_query))
        {
            require FILENAME_NOT_FOUND;
        }
        $query = tep_db_query("SELECT COUNT(sq.products_options_values_id) AS `count` FROM (SELECT DISTINCT products_options_values_id FROM products_options_values WHERE products_options_values_id IN (" . implode(', ', $options_values_ids_in_query) . ")) AS sq");
        $result = tep_db_fetch_array($query);
        if($result['count'] != count($options_values_ids_in_query))
        {
            require FILENAME_NOT_FOUND;
        }
    }
    
    // Поиск по ключевым словам
    $keywords = isset($_GET['keywords']) ? trim($_GET['keywords']) : null;
    if($keywords !== null)
    {
        // Зыкрыть от индексации страницы резуьтатов поиска
        $page_robots_tag = 'noindex, follow';
    }
    
    // Поиск по производителю
    $manufacturers_id = isset($_GET['manufacturers_id']) ? filter_var($_GET['manufacturers_id'], FILTER_VALIDATE_INT, array('min_range' => 0)) : null;
    if($manufacturers_id === false)
    {
        require FILENAME_NOT_FOUND;
    }
    elseif($manufacturers_id !== null)
    {
        // Проверить, что такой производитель действительно существует
        $query = tep_db_query("SELECT NULL FROM manufacturers WHERE manufacturers_id = {$manufacturers_id} LIMIT 1");
        
        // Производитель не найден
        if(!tep_db_num_rows($query))
        {
            require FILENAME_NOT_FOUND;
        }
        // Неканоничная страница, если в параметрах запроса есть что-то помимо manufacturers_id
        elseif($more_then_one_option_value_selected || array_diff(array_keys($_GET), array_merge(array('manufacturers_id', 'language'), $options_ids_in_query)))
        {
            $page_robots_tag = 'noindex, follow';
        }
    }
    
    // Поиск по категории
    if(isset($_GET['cPath']))
    {
        if($_GET['cPath'] !== '0')
        {
            $categories_ids = array_filter(explode('_', $_GET['cPath']), 'is_numeric');
            if(!$categories_ids || implode('_', $categories_ids) !== $_GET['cPath'])
            {
                require FILENAME_NOT_FOUND;
            }

            // Проверяем, что категории действительно существуют и их родственные связи верны,
            // но только если мы просматриваем не все товары
            if(!(count($categories_ids) === 1 && $categories_ids[0] == 0) && \EShopmakers\Data\CategoriesTree::getParentsChain(end($categories_ids)) != $categories_ids)
            {
                require FILENAME_NOT_FOUND;
            }
        }
        
        // Страница каноническая, если лишь параметр cPath присутствует в запросе
        if($more_then_one_option_value_selected || array_diff(array_keys($_GET), array_merge(array('cPath', 'language'), $options_ids_in_query)))
        {
            $page_robots_tag = 'noindex, follow';
        }
    }
    
    $join = '';
    
    // Если пользователь запрашивает только рекомендуемые товары
    if(!empty($_GET['featured']))
    {
        $join .= <<<SQL
INNER JOIN featured AS f
ON
    p.products_id = f.products_id AND
    f.status = 1 AND
    (
        f.expires_date IS NULL OR
        f.expires_date = '0000-00-00 00:00:00' OR
        f.expires_date >= NOW()
    )

SQL;
    }
    
    // Если пользователь товары со скидкой
    if(!empty($_GET['specials']))
    {
        $join .= <<<SQL
INNER JOIN specials AS sp
ON
    p.products_id = sp.products_id 

SQL;
    }    

    // Если есть опции, то их нужно добавить к запросу
    if($options_ids_in_query && $options_values_ids_in_query)
    {
        // -----------------------------------------------------
        // Для ИЛИ
        // -----------------------------------------------------
        /* $_options_ids_in_query = implode(', ', $options_ids_in_query);
        $_options_values_ids_in_query = implode(', ', $options_values_ids_in_query);
        $join .= <<<SQL
INNER JOIN products_attributes AS pa
ON
    p.products_id = pa.products_id AND
    pa.options_id IN ({$_options_ids_in_query}) AND
    pa.options_values_id IN ({$_options_values_ids_in_query})

SQL; */
        
        // -----------------------------------------------------
        // Для И
        // -----------------------------------------------------
        $i = 0;
        foreach($options_to_options_values as $option_id => $option_values)
        {
            $i++;
            $option_values = implode(', ', $option_values);
            $join .= <<<SQL
INNER JOIN products_attributes AS pa{$i}
ON
    p.products_id = pa{$i}.products_id AND
    pa{$i}.options_id = {$option_id} AND
    pa{$i}.options_values_id IN ({$option_values})

SQL;
        }
    }

    // Минимальная и максимальная цена в запросе
    $price_min_in_query = isset($_GET['rmin']) ? intval($_GET['rmin']) : null;
    $price_max_in_query = isset($_GET['rmax']) ? intval($_GET['rmax']) : null;
    if($price_min_in_query)
    {
        $price_min_in_query = intval(abs($price_min_in_query)) / $currencies->get_value(empty($_SESSION['currency']) ? DEFAULT_CURRENCY : $_SESSION['currency']);
    }
    if($price_max_in_query)
    {
        $price_max_in_query = intval(abs($price_max_in_query)) / $currencies->get_value(empty($_SESSION['currency']) ? DEFAULT_CURRENCY : $_SESSION['currency']);
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
    $query_string = <<<SQL
SET
    @customers_id              = {$customer['customers_id']},
    @customers_discount        = {$customer['customers_discount']},
    @customers_groups_id       = {$customer['customers_groups_id']},
    @customers_groups_discount = {$customer['customers_groups_discount']},
    @customers_groups_price    = {$customer['customers_groups_price']}
SQL;
    tep_db_query($query_string);
    
    // Создать временную таблицу для хранения цен товаров
    $query_string = <<<SQL
CREATE TEMPORARY TABLE `products_final_price` (
    `products_id` INT UNSIGNED,
    `price` FLOAT(15, 4) DEFAULT 0,
    `discount` FLOAT(5,2) DEFAULT 0,
    PRIMARY KEY (`products_id`)
)
SQL;
    tep_db_query($query_string);

    // Мы в категории?
    if(!empty($cPath_array))
    {
        $current_category_id = end($cPath_array);
        
        // Если мы просматриваем все товары
        if($current_category_id == 0)
        {
            // Заполнить временную таблицу базовыми либо скидочными ценами
            $query_string = <<<SQL
INSERT INTO products_final_price (products_id, price, discount)
SELECT
    p.products_id,
    -- Цена
    IF(s.specials_new_products_price, s.specials_new_products_price, p.products_price),
    -- Скидка: скидка производителя, если есть, в противном случае - скидка покупателя + скидка группы покупателя
    COALESCE(IF(mdsc.manudiscount_discount, mdsc.manudiscount_discount, @customers_discount + @customers_groups_discount), 0)
FROM products AS p
                    
{$join}

-- Специальная цена для товара
LEFT OUTER JOIN specials AS s
ON
    s.products_id = p.products_id AND
    s.status = 1 AND
    (
        s.expires_date IS NULL OR
        s.expires_date = '0000-00-00 00:00:00' OR
        s.expires_date > NOW()
    ) AND
    (
        s.customers_groups_id = @customers_groups_id OR
        s.customers_id = @customers_id
    )

-- Скидка на все товары производителя (работает только если покупатель авторизирован)
LEFT OUTER JOIN manudiscount AS mdsc
ON
    @customers_id != 0 AND
    mdsc.manudiscount_manufacturers_id = p.manufacturers_id AND
    (
        mdsc.manudiscount_groups_id = @customers_groups_id OR
        mdsc.manudiscount_customers_id = @customers_id
    )
WHERE
    p.products_status = 1 AND
    (
        p.products_date_available IS NULL OR
        p.products_date_available = '0000-00-00 00:00:00' OR
        p.products_date_available >= NOW()
    )
GROUP BY p.products_id
SQL;
        }
        else
        {
            // Находим все дочерние категории для данной
            $query = tep_db_query("SELECT `categories_id`, `parent_id` FROM `categories` WHERE `categories_status` = 1");
            $categories_children = array();
            if(tep_db_num_rows($query))
            {
                while(($row = tep_db_fetch_array($query)) !== false)
                {
                    if(empty($categories_children[$row['parent_id']]))
                    {
                        $categories_children[$row['parent_id']] = array();
                    }
                    $categories_children[$row['parent_id']][] = $row['categories_id'];
                }
            }
            // Стек для рекурсивного обхода дочерних категорий
            $parent_stack = array($current_category_id);
            // Категории, в которых будем искать товары, для которых будем искать атрибуты
            $categories_to_search_in = array();
            while($parent_stack)
            {
                $current_category_id = end($parent_stack);
                if(!empty($categories_children[$current_category_id]))
                {
                    $parent_stack = array_merge($parent_stack, $categories_children[$current_category_id]);
                    unset($categories_children[$current_category_id]);
                    continue;
                }
                array_push($categories_to_search_in, array_pop($parent_stack));
            }
            $categories_to_search_in = implode(', ', $categories_to_search_in);

            // Заполнить временную таблицу базовыми либо скидочными ценами
            $query_string = <<<SQL
INSERT INTO products_final_price (products_id, price, discount)
SELECT
    p.products_id,
    -- Цена
    IF(s.specials_new_products_price, s.specials_new_products_price, p.products_price),
    -- Скидка: скидка производителя, если есть, в противном случае - скидка покупателя + скидка группы покупателя
    COALESCE(IF(mdsc.manudiscount_discount, mdsc.manudiscount_discount, @customers_discount + @customers_groups_discount), 0)
FROM products_to_categories AS ptc
INNER JOIN products AS p
ON
    p.products_id = ptc.products_id AND
    p.products_status = 1 AND
    (
        p.products_date_available IS NULL OR
        p.products_date_available = '0000-00-00 00:00:00' OR
        p.products_date_available >= NOW()
    )

{$join}

-- Специальная цена для товара
LEFT OUTER JOIN specials AS s
ON
    s.products_id = p.products_id AND
    s.status = 1 AND
    (
        s.expires_date IS NULL OR
        s.expires_date = '0000-00-00 00:00:00' OR
        s.expires_date > NOW()
    ) AND
    (
        s.customers_groups_id = @customers_groups_id OR
        s.customers_id = @customers_id
    )

-- Скидка на все товары производителя (работает только если покупатель авторизирован)
LEFT OUTER JOIN manudiscount AS mdsc
ON
    @customers_id != 0 AND
    mdsc.manudiscount_manufacturers_id = p.manufacturers_id AND
    (
        mdsc.manudiscount_groups_id = @customers_groups_id OR
        mdsc.manudiscount_customers_id = @customers_id
    )
WHERE ptc.categories_id IN ({$categories_to_search_in})
GROUP BY p.products_id
SQL;
        }
        if(isset($_GET['sort']) && $_GET['sort'] === 'new'){
            $query_string .= ' ORDER BY p.products_id DESC';
        }
        if((isset($_GET['featured']) && $_GET['featured'] === 'yes') || (isset($_GET['specials']) && $_GET['specials'] === 'yes') || (isset($_GET['sort']) && $_GET['sort'] === 'new'))
        {
            $query_string .= ' LIMIT 100';
        }
        tep_db_query($query_string);

    }
    // Просматриваем товары конкретного производителя
    elseif($manufacturers_id)
    {
        // Заполнить временную таблицу базовыми либо скидочными ценами
        $query_string = <<<SQL
INSERT INTO products_final_price (products_id, price, discount)
SELECT
    p.products_id,
    -- Цена
    IF(s.specials_new_products_price, s.specials_new_products_price, p.products_price),
    -- Скидка: скидка производителя, если есть, в противном случае - скидка покупателя + скидка группы покупателя
    COALESCE(IF(mdsc.manudiscount_discount, mdsc.manudiscount_discount, @customers_discount + @customers_groups_discount), 0)
FROM products AS p

{$join}

-- Специальная цена для товара
LEFT OUTER JOIN specials AS s
ON
    s.products_id = p.products_id AND
    s.status = 1 AND
    (
        s.expires_date IS NULL OR
        s.expires_date = '0000-00-00 00:00:00' OR
        s.expires_date > NOW()
    ) AND
    (
        s.customers_groups_id = @customers_groups_id OR
        s.customers_id = @customers_id
    )

-- Скидка на все товары производителя (работает только если покупатель авторизирован)
LEFT OUTER JOIN manudiscount AS mdsc
ON
    @customers_id != 0 AND
    mdsc.manudiscount_manufacturers_id = p.manufacturers_id AND
    (
        mdsc.manudiscount_groups_id = @customers_groups_id OR
        mdsc.manudiscount_customers_id = @customers_id
    )
WHERE
    p.manufacturers_id = {$manufacturers_id} AND
    p.products_status = 1 AND
    (
        p.products_date_available IS NULL OR
        p.products_date_available = '0000-00-00 00:00:00' OR
        p.products_date_available >= NOW()
    )
GROUP BY p.products_id
SQL;
        if(isset($_GET['sort']) && $_GET['sort'] === 'new'){
            $query_string .= ' ORDER BY p.products_id DESC';
        }
        if((isset($_GET['featured']) && $_GET['featured'] === 'yes') || (isset($_GET['specials']) && $_GET['specials'] === 'yes') || (isset($_GET['sort']) && $_GET['sort'] === 'new'))
        {
            $query_string .= ' LIMIT 100';
        }
        tep_db_query($query_string);
    }
    // Ищем по названию или модели (коду) товара
    elseif($keywords)
    {
        $keywords = tep_db_input($keywords);
        // Заполнить временную таблицу базовыми либо скидочными ценами
        $query_string = <<<SQL
INSERT INTO products_final_price (products_id, price, discount)
SELECT
    p.products_id,
    -- Цена
    IF(s.specials_new_products_price, s.specials_new_products_price, p.products_price),
    -- Скидка: скидка производителя, если есть, в противном случае - скидка покупателя + скидка группы покупателя
    COALESCE(IF(mdsc.manudiscount_discount, mdsc.manudiscount_discount, @customers_discount + @customers_groups_discount), 0)
FROM products AS p
INNER JOIN products_description AS pd
ON
    pd.products_id = p.products_id AND
    pd.language_id = {$_SESSION['languages_id']}

{$join}

-- Специальная цена для товара
LEFT OUTER JOIN specials AS s
ON
    s.products_id = p.products_id AND
    s.status = 1 AND
    (
        s.expires_date IS NULL OR
        s.expires_date = '0000-00-00 00:00:00' OR
        s.expires_date > NOW()
    ) AND
    (
        s.customers_groups_id = @customers_groups_id OR
        s.customers_id = @customers_id
    )

-- Скидка на все товары производителя (работает только если покупатель авторизирован)
LEFT OUTER JOIN manudiscount AS mdsc
ON
    @customers_id != 0 AND
    mdsc.manudiscount_manufacturers_id = p.manufacturers_id AND
    (
        mdsc.manudiscount_groups_id = @customers_groups_id OR
        mdsc.manudiscount_customers_id = @customers_id
    )
WHERE
    (
        p.products_model LIKE '%{$keywords}%' OR
        pd.products_name LIKE '%{$keywords}%'
    ) AND
    p.products_status = 1 AND
    (
        p.products_date_available IS NULL OR
        p.products_date_available = '0000-00-00 00:00:00' OR
        p.products_date_available >= NOW()
    )
GROUP BY p.products_id
SQL;
        if(isset($_GET['sort']) && $_GET['sort'] === 'new'){
            $query_string .= ' ORDER BY p.products_id DESC';
        }
        if((isset($_GET['featured']) && $_GET['featured'] === 'yes') || (isset($_GET['specials']) && $_GET['specials'] === 'yes') || (isset($_GET['sort']) && $_GET['sort'] === 'new'))
        {
            $query_string .= ' LIMIT 100';
        }
        tep_db_query($query_string);
    }
    
    // Создать временную таблицу для хранения атрибутов
    $query_string = <<<SQL
CREATE TEMPORARY TABLE products_attributes_final_price (
    products_id INT UNSIGNED,
    price FLOAT(15, 4),
    final_price FLOAT(15, 4),
    prefix VARCHAR(1),
    PRIMARY KEY (products_id)
)
SQL;
    tep_db_query($query_string);

    // Заполнить временную таблицу атрибутов
    $query_string = <<<SQL
INSERT INTO products_attributes_final_price
SELECT *
FROM
(
    SELECT
        pa.products_id,
        CASE pa.price_prefix
            WHEN '-' THEN pa.options_values_price * (-1)
            ELSE pa.options_values_price
        END,
        CASE pa.price_prefix
            WHEN '+' THEN pfp.price + pa.options_values_price
            WHEN '-' THEN pfp.price - pa.options_values_price
            ELSE pa.options_values_price
        END AS final_price,
        pa.price_prefix
    FROM products_final_price AS pfp
    INNER JOIN products_attributes AS pa
    ON
        pa.products_id = pfp.products_id AND
        pa.price_prefix IN ('+', '-', '=')
    ORDER BY
        pa.price_prefix = '=' DESC,
        final_price ASC
) AS sq
GROUP BY sq.products_id
SQL;
    tep_db_query($query_string);

    // Заменить цену товара ценой аттрибутов с префиксом =
    // При проблемах з селектом при виборі сортування від дешевих або від нових і тд. Звернути на нижчевказаний блок і префікеси товарів.
    $query_string = <<<SQL
INSERT INTO products_final_price (products_id, price)
SELECT
    products_id,
    price
FROM products_attributes_final_price
WHERE prefix = '='
ON DUPLICATE KEY UPDATE price = VALUES(price)
SQL;
    tep_db_query($query_string);

    // Присумммировать наценку атрибутов с превиксами + и -
    $query_string = <<<SQL
INSERT INTO products_final_price (products_id, price)
SELECT
    products_id,
    SUM(price)
FROM products_attributes_final_price
WHERE prefix != '='
GROUP BY products_id
ON DUPLICATE KEY UPDATE price = price + VALUES(price)
SQL;
    tep_db_query($query_string);
    // Учесть скидку при расчёте финальной цены
    tep_db_query("UPDATE products_final_price SET price = price + price * discount / 100");
    
    // Запрорс для вывода товара
    $listing_sql = "select "
            . "p.products_id, "
            . ($customer['customers_groups_price'] > 1 ? "p.products_price_{$customer['customers_groups_price']} as products_price, " : "p.products_price, ")
            . "p.products_images, "
            . "p.products_model, "
            . "p.products_tax_class_id, "
            . "pd.products_name, "
            . "p.products_rating, "
            . "pd.products_description, "
            . "p.lable_1, "
            . "p.lable_2, "
            . "p.lable_3, "
            . "p.products_quantity, "
            . "p.mankovka_stock, "
            . "p.manufacturers_id "
            . "from products_final_price as pfp "
            . "inner join products as p on p.products_id = pfp.products_id "
            . "inner join products_description as pd on p.products_id = pd.products_id and pd.language_id = {$_SESSION['languages_id']} ";
    
    // Ограничение по цене
    $where = array();
    if(!empty($price_max_in_query) && !empty($price_min_in_query))
    {
        $where[] = "pfp.price between {$price_min_in_query} and {$price_max_in_query}";
    }
    elseif(!empty($price_min_in_query))
    {
        $where[] = "pfp.price >= {$price_min_in_query}";
    }
    elseif(!empty($price_max_in_query))
    {
        $where[] = "pfp.price <= {$price_max_in_query}";
    }
    
    // Добавить условие выборки
    if($where)
    {
        $where = implode(' and ', $where);
        $listing_sql .= " where " . $where;
    }
    
    $listing_sql .= " group by p.products_id";
    
    // Сортировка
    switch(isset($_GET['sort']) ? $_GET['sort'] : null)
    {
        case 'name_asc' :
            $listing_sql .= " order by p.products_quantity > 0 desc, (p.products_quantity <= 0 AND p.mankovka_stock > 0) desc, pd.products_name asc";
            break;
        case 'name_desc' :
            $listing_sql .= " order by p.products_quantity > 0 desc, (p.products_quantity <= 0 AND p.mankovka_stock > 0) desc, pd.products_name desc";
            break;
        case 'price_asc' :
            $listing_sql .= " order by p.products_quantity > 0 desc, (p.products_quantity <= 0 AND p.mankovka_stock > 0) desc, pfp.price asc";
            break;
        case 'price_desc' :
            $listing_sql .= " order by p.products_quantity > 0 desc, (p.products_quantity <= 0 AND p.mankovka_stock > 0) desc, pfp.price desc";
            break;
        case 'new' :
            $listing_sql .= " order by p.products_quantity > 0 desc, (p.products_quantity <= 0 AND p.mankovka_stock > 0) desc, p.products_id desc";
            break;
        case 'viewed' :
            $listing_sql .= " order by p.products_quantity > 0 desc, (p.products_quantity <= 0 AND p.mankovka_stock > 0) desc, pd.products_viewed desc";
            break;
        default :
            $listing_sql .= " order by p.products_quantity > 0 desc, (p.products_quantity <= 0 AND p.mankovka_stock > 0) desc, p.products_sort_order asc, p.products_id desc";
//            $listing_sql .= " order by p.products_quantity > 0 desc, p.mankovka_stock> 0 desc, p.products_sort_order asc, p.products_id desc";
            break;
    }
    $content = CONTENT_INDEX_PRODUCTS;
    $sort_current = isset($_GET['sort']) ? $_GET['sort'] : null;
    $row_by_page_current = isset($_GET['row_by_page']) ? $_GET['row_by_page'] : null;
    $sort_display = isset($_GET['display']) ? $_GET['display'] : null;
    if($sort_display !== 'tile' && $sort_display !== 'list')
    {
        if(!empty($_SESSION['products_list_display_style']) && ($_SESSION['products_list_display_style'] === 'tile' || $_SESSION['products_list_display_style'] === 'list'))
        {
            $sort_display = $_SESSION['products_list_display_style'];
        }
        else
        {
            $sort_display = PRODUCT_LISTING_DISPLAY_STYLE;
        }
    }
    else
    {
        $_SESSION['products_list_display_style'] = $sort_display;
    }

    if($row_by_page_current === 'all')
    {
        $listing_split = new splitPageResults($listing_sql, 100000, 'p.products_id');
    }
    elseif(($_row_by_page_current = intval($row_by_page_current)))
    {
        $row_by_page_current = $_row_by_page_current;
        $listing_split = new splitPageResults($listing_sql, $row_by_page_current, 'p.products_id');
    }
    else
    {
        $row_by_page_current = MAX_DISPLAY_SEARCH_RESULTS;
        $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');
    }

    // Проверяем, что пользователь запросил номер страницы не более, чем есть в списке
    if($listing_split->current_page_number > 0 && $listing_split->requested_page_number !== $listing_split->current_page_number && !\EShopmakers\Http\Request::isAjax())
    {
        tep_redirect(tep_href_link(FILENAME_DEFAULT, manageGetParams(null, array('page' => $listing_split->current_page_number))));
    }
}
else
{
    // default page
    $breadcrumb->add(TITLE);
    $content = CONTENT_INDEX_DEFAULT;
}


if(\EShopmakers\Http\Request::isAjax())
{
    require DIR_WS_CONTENT . 'index_products_listing.tpl.php';
    $products = ob_get_contents();
    ob_clean();
    require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/boxes/filter.php');
    $filter = ob_get_contents();
    ob_clean();
    
    header('Content-Type: application/xml; charset=' . CHARSET);
    header('X-Robots-Tag: noindex, follow');
    echo '<', '?', 'xml version="1.0" encoding="', CHARSET, '" standalone="no"?>';
    echo '<root>';
    echo '<products>', wrapIntoCDATA($products), '</products>';
    echo '<filters>', wrapIntoCDATA($filter), '</filters>';
    if($listing_split->number_of_pages > 1) {
        echo '<pagination>', wrapIntoCDATA($listing_split->display_links(5, tep_get_all_get_params(array('page', 'info', 'x', 'y', 'ajaxloading')))), '</pagination>';
    } else {
        echo '<pagination/>';
    }
    echo '</root>';
}
else
{
    require DIR_WS_TEMPLATES . TEMPLATE_NAME . DIRECTORY_SEPARATOR . TEMPLATENAME_MAIN_PAGE;
}

require(DIR_WS_INCLUDES . 'application_bottom.php');