<?php

/**
 * Боковой блок с фильтрами в категории
 */

/* @var $currencies \currencies */

// Не отображаем бокс, если это не страница категории
if($content !== CONTENT_INDEX_PRODUCTS || (empty($_GET['keywords']) && empty($_GET['cPath']) && empty($_GET['manufacturers_id'])))
{
    return;
}

// Активные опции
$options_ids_in_query = array_filter(array_keys($_GET), 'is_numeric');
$options_values_ids_in_query = array();
foreach($options_ids_in_query as $option_id)
{
    $options_values_ids_in_query = array_merge($options_values_ids_in_query, array_filter(explode('-', $_GET[$option_id]), 'is_numeric'));
}
$options_values_ids_in_query = array_unique($options_values_ids_in_query);

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

// Максимальная и минимальная цена
$query = tep_db_query("SELECT MIN(`price`) AS `min`, MAX(`price`) AS `max` FROM `products_final_price`");
if(tep_db_num_rows($query))
{
    $result = tep_db_fetch_array($query);
    $price_min = $currencies->get_price_nodiscount($result['min'], 0);
    $price_max = $currencies->get_price_nodiscount($result['max'], 0);
}
else
{
    $price_min = 0;
    $price_max = 0;
}

// Атрибуты
$products_ids                  = array();
$options_ids                   = array();
$options_values_ids            = array();
$options_to_options_values     = array();
$options_values_to_options     = array();
$options_values_availability   = array();
$options_values_products_count = array();
$options_values_or_condition   = array();
$options_or_condition          = array();
// $query = tep_db_query("SELECT pa.options_id, pa.options_values_id FROM products_final_price AS pfp INNER JOIN products_attributes AS pa ON pa.products_id = pfp.products_id");
// Категория
if(isset($_GET['cPath']))
{
    $current_category_id = end($cPath_array);
    if($current_category_id == 0)
    {
        $query_string = <<<SQL
SELECT
    p.products_id,
    pa.options_id,
    pa.options_values_id,
    po.products_options_or_condition
FROM products AS p
INNER JOIN products_attributes AS pa
ON
    p.products_id = pa.products_id
INNER JOIN products_options AS po
ON
    po.products_options_id = pa.options_id AND
    po.language_id = {$languages_id} AND
    po.products_options_type != 0
WHERE
    p.products_status = 1 AND
    (
        p.products_date_available IS NULL OR
        p.products_date_available = '0000-00-00 00:00:00' OR
        p.products_date_available >= NOW()
    )
SQL;
    }
    else
    {
        // $categories_to_search_in = implode(', ', \EShopmakers\Data\CategoriesTree::getAllChildren($current_category_id));
        // Найти все дочерние категории
        $query = tep_db_query("SELECT categories_id, parent_id FROM categories WHERE categories_status = 1");
        $categories_children = array();
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            if(empty($categories_children[$row['parent_id']]))
            {
                $categories_children[$row['parent_id']] = array();
            }
            $categories_children[$row['parent_id']][] = $row['categories_id'];
        }
        $stack = array($current_category_id);
        $categories_to_search_in = array();
        while($stack)
        {
            $category_id = array_pop($stack);
            if(!empty($categories_children[$category_id]))
            {
                $stack = array_merge($stack, $categories_children[$category_id]);
                unset($categories_children[$category_id]);
            }
            $categories_to_search_in[] = $category_id;
        }
        $categories_to_search_in = implode(', ', $categories_to_search_in);
        
        $query_string = <<<SQL
SELECT
    p.products_id,
    pa.options_id,
    pa.options_values_id,
    po.products_options_or_condition
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
INNER JOIN products_attributes AS pa
ON
    p.products_id = pa.products_id
INNER JOIN products_options AS po
ON
    po.products_options_id = pa.options_id AND
    po.language_id = {$languages_id} AND
    po.products_options_type != 0
WHERE
    ptc.categories_id IN ({$categories_to_search_in})
SQL;
    }
}
elseif(isset($_GET['manufacturers_id']))
{
    $manufacturers_id = intval($_GET['manufacturers_id']);
    $query_string = <<<SQL
SELECT
    p.products_id,
    pa.options_id,
    pa.options_values_id,
    po.products_options_or_condition
FROM products AS p
INNER JOIN products_attributes AS pa
ON
    p.products_id = pa.products_id
INNER JOIN products_options AS po
ON
    po.products_options_id = pa.options_id AND
    po.language_id = {$languages_id} AND
    po.products_options_type != 0
WHERE
    p.manufacturers_id = {$manufacturers_id} AND
    p.products_status = 1 AND
    (
        p.products_date_available IS NULL OR
        p.products_date_available = '0000-00-00 00:00:00' OR
        p.products_date_available >= NOW()
    )
SQL;
}
elseif(isset($_GET['keywords']))
{
    $keywords = tep_db_input(trim($_GET['keywords']));
    $query_string = <<<SQL
SELECT
    p.products_id,
    pa.options_id,
    pa.options_values_id,
    po.products_options_or_condition
FROM products AS p
INNER JOIN products_description AS pd
ON
    p.products_id = pd.products_id AND
    pd.language_id = {$_SESSION['languages_id']}
INNER JOIN products_attributes AS pa
ON
    p.products_id = pa.products_id
INNER JOIN products_options AS po
ON
    po.products_options_id = pa.options_id AND
    po.language_id = {$languages_id} AND
    po.products_options_type != 0
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
SQL;
}
$query = tep_db_query($query_string);
if(tep_db_num_rows($query))
{
    while(($row = tep_db_fetch_array($query)) !== false)
    {
        $products_ids[]       = $row['products_id'];
        $options_ids[]        = $row['options_id'];
        $options_values_ids[] = $row['options_values_id'];
        if(empty($options_to_options_values[$row['options_id']]))
        {
            $options_to_options_values[$row['options_id']] = array();
        }
        $options_to_options_values[$row['options_id']][] = $row['options_values_id'];
        if(!isset($options_values_availability[$row['options_values_id']]))
        {
            $options_values_availability[$row['options_values_id']] = in_array($row['options_id'], $options_ids_in_query) && in_array($row['options_values_id'], $options_values_ids_in_query); // Все опции из запроса по умолчанию являются доступными
        }
        if(!isset($options_values_or_condition[$row['options_values_id']]))
        {
            $options_values_or_condition[$row['options_values_id']] = $row['products_options_or_condition'];
        }
        if(!isset($options_or_condition[$row['options_id']]))
        {
            $options_or_condition[$row['options_id']] = $row['products_options_or_condition'];
        }
        if(!isset($options_values_to_options[$row['options_values_id']]))
        {
            $options_values_to_options[$row['options_values_id']] = $row['options_id'];
        }
    }
}
$or_condition_options_values_ids = array_keys(array_filter($options_values_or_condition));

// Уникализировать полученные значения
$products_ids = array_unique($products_ids);
$options_ids = array_unique($options_ids);
$options_values_ids = array_unique($options_values_ids);
foreach($options_to_options_values as $option_id => $_options_values_ids)
{
    $options_to_options_values[$option_id] = array_unique($_options_values_ids);
}

// Отфильтровать несуществующие опции и их значения из запроса
$options_ids_in_query = array_intersect($options_ids_in_query, $options_ids);
$options_values_ids_in_query = array_intersect($options_values_ids_in_query, $options_values_ids);

// Найти все опции, которых не было в запросе
$options_ids_not_in_query = array_diff($options_ids, $options_ids_in_query);
$options_values_ids_not_in_query = array_diff($options_values_ids, $options_values_ids_in_query);

// Доступность опций для выбора
$products_ids = implode(', ', $products_ids);
foreach($options_values_ids_not_in_query as $options_value_id)
{
    $tmp = $options_values_ids_in_query;
    $tmp[] = $options_value_id;
    $having_count = 0;
    $having_count_or_options_included = array();
    foreach($tmp as $_options_value_id)
    {
        if(in_array($_options_value_id, $or_condition_options_values_ids))
        {
            if(!in_array($options_values_to_options[$_options_value_id], $having_count_or_options_included))
            {
                $having_count_or_options_included[] = $options_values_to_options[$_options_value_id];
                $having_count++;
            }
        }
        else
        {
            $having_count++;
        }
    }
    $tmp = implode(', ', $tmp);
    
    // Доступность опций
    /* $query_string = <<<SQL
SELECT
    TRUE
FROM products_attributes
WHERE
    products_id IN ({$products_ids}) AND
    options_values_id IN ({$tmp})
GROUP BY products_id
HAVING COUNT(options_values_id) >= {$having_count}
LIMIT 1
SQL;
    $query = tep_db_query($query_string);
    $options_values_availability[$options_value_id] = tep_db_num_rows($query) > 0;
    
    // Количество товаров
    if($options_values_availability[$options_value_id])
    {
        $query = tep_db_query("SELECT COUNT(sq.products_id) AS `count` FROM (SELECT products_id FROM products_attributes WHERE products_id IN ({$products_ids}) AND options_values_id IN ({$tmp}) GROUP BY products_id HAVING COUNT(*) >= {$having_count}) AS `sq`");
        $result = tep_db_fetch_array($query);
        $options_values_products_count[$options_value_id] = $result['count'];
    } */
    $query_string = <<<SQL
SELECT COUNT(sq.products_id) AS `count`
FROM (SELECT products_id
FROM products_attributes
WHERE
    products_id IN ({$products_ids}) AND
    options_values_id IN ({$tmp})
GROUP BY products_id
HAVING COUNT(options_values_id) >= {$having_count}) AS sq
SQL;
    $query = tep_db_query($query_string);
    $result = tep_db_fetch_array($query);
    $options_values_availability[$options_value_id] = $result['count'] > 0;
    // Количество товаров
    if($result['count'] > 0)
    {
        $options_values_products_count[$options_value_id] = $result['count'];
    }
}

// Находим названия опций и их значений
$options        = array();
$options_values = array();
if($options_ids)
{
    $options_ids = implode(', ', $options_ids);
    $query = tep_db_query("SELECT `products_options_id`, `products_options_name` FROM `products_options` WHERE `products_options_id` IN({$options_ids}) AND `language_id` = {$languages_id} ORDER BY `products_options_sort_order` ASC, `products_options_name` ASC");
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $options[$row['products_options_id']] = $row['products_options_name'];
        }
    }
}
if($options_values_ids)
{
    $options_values_ids = implode(', ', $options_values_ids);
    $query = tep_db_query("SELECT `products_options_values_id`, `products_options_values_name` FROM `products_options_values` WHERE `products_options_values_id` IN({$options_values_ids}) AND `language_id` = {$languages_id} ORDER BY `products_options_values_sort_order` ASC, `products_options_values_name` ASC");
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $options_values[$row['products_options_values_id']] = $row['products_options_values_name'];
        }
    }
}
$options_values_ids = array_keys($options_values);
foreach($options_to_options_values as $option_id => $option_values_ids)
{
    $options_to_options_values[$option_id] = array_intersect($options_values_ids, $option_values_ids);
}

// Если запрос был AJAX-ом, то отправляем только данные в формате JSON, отрисовка будет происходить на стороне клиента
if(\EShopmakers\Http\Request::isAjax())
{
    $available_options = array();
    $unavailable_options = array();
    foreach(array_keys($options) as $option_id)
    {
        foreach($options_to_options_values[$option_id] as $option_value_id)
        {
            if(empty($options_values_products_count[$option_value_id]))
            {
                echo '$("#filter-', $option_id, '-', $option_value_id, ' ~ label .count").empty();';
            }
            else
            {
                echo '$("#filter-', $option_id, '-', $option_value_id, ' ~ label .count").text(', $options_values_products_count[$option_value_id], ');';
            }
            if($options_values_availability[$option_value_id])
            {
                $available_options[] = '#filter-' . $option_id . '-' . $option_value_id;
            }
            else
            {
                $unavailable_options[] = '#filter-' . $option_id . '-' . $option_value_id;
            }
        }
    }
    echo '$("', implode(', ', $available_options), '").prop("disabled", false);$("', implode(', ', $unavailable_options), '").prop("disabled", true);';
    return;
}

?>
<?php /* Блок выбранных фильтров */ ?>
<?php /* if($options_values_ids_in_query) : ?>
<div class="box-selected-filters common-styled-block">
    <div class="title"><?php echo BOX_HEADING_SELECTED_FILTERS; ?></div>
    <div class="clear-selected-filters"><?php echo TEXT_CLEAR_SELECTED_FILTERS; ?></div>
    <dl>
        <?php foreach($options_ids_in_query as $option_id) : ?>
        <dt><?php echo tep_escape($options[$option_id]); ?>:</dt>
        <dd>
            <ul>
                <?php foreach($options_to_options_values[$option_id] as $option_value_id) : ?>
                <?php if(in_array($option_value_id, $options_values_ids_in_query)) : ?>
                <li data-options-id="<?php echo $option_id; ?>" data-options-values-id="<?php echo $option_value_id; ?>"><?php echo tep_escape($options_values[$option_value_id]); ?><span></span></li>
                <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </dd>
        <?php endforeach; ?>
    </dl>
</div>
<?php endif; */ ?>
<?php /* Блок фильтров */ ?>
<?php if($price_max || $options) : ?>
<div class="box-filter common-styled-block">
    <div class="options-group">
        <div class="group-title"><?php echo BOX_HEADING_FILTER_BY_PRICE; ?></div>
        <div class="price">
            <div class="selector"></div>
            <div class="inputs">
                <input
                    type="number"
                    min="<?php echo $price_min; ?>"
                    max="<?php echo $price_max; ?>"
                    step="1"
                    class="form-control price-min"
                    value="<?php echo empty($price_min_in_query) ? $price_min : $price_min_in_query; ?>"
                    >
                <span class="delimiter">-</span>
                <input
                    type="number"
                    min="<?php echo $price_min; ?>"
                    max="<?php echo $price_max; ?>"
                    step="1"
                    class="form-control price-max"
                    value="<?php echo empty($price_max_in_query) ? $price_max : $price_max_in_query; ?>"
                    >
                <span class="currency"><?php echo $currencies->currencies[$currency ? $currency : DEFAULT_CURRENCY]['symbol_right'] ? $currencies->currencies[$currency ? $currency : DEFAULT_CURRENCY]['symbol_right'] : $currencies->currencies[$currency ? $currency : DEFAULT_CURRENCY]['symbol_left']; ?></span>
            </div>
        </div>
    </div>
    <?php foreach($options as $option_id => $option_name) : ?>
    <div class="options-group">
        <div class="group-title"><?php echo tep_escape($option_name); ?></div>
        <div class="options">
            <div class="option">
                <input
                    type="checkbox"
                    name="<?php echo $option_id; ?>[]"
                    value="all"
                    <?php if(!in_array($option_id, $options_ids_in_query)) : ?>checked<?php endif; ?>
                    id="filter-<?php echo $option_id; ?>-all"
                    >
                <label for="filter-<?php echo $option_id; ?>-all" class="control-label">
                    <span class="indicator"></span>
                    <span class="label"><?php echo TEXT_FILTER_ALL; ?></span>
                </label>
            </div>
            <?php foreach($options_to_options_values[$option_id] as $option_value_id) : ?>
            <div class="option">
                <input
                    type="checkbox"
                    name="<?php echo $option_id; ?>[]"
                    value="<?php echo $option_value_id; ?>"
                    <?php if(in_array($option_value_id, $options_values_ids_in_query)) : ?>checked<?php endif; ?>
                    id="filter-<?php echo $option_id, '-', $option_value_id; ?>"
                    <?php if(!$options_values_availability[$option_value_id]) : ?>disabled<?php endif; ?>
                    >
                <label for="filter-<?php echo $option_id, '-', $option_value_id; ?>">
                    <span class="indicator"></span>
                    <span class="label"><?php echo tep_escape($options_values[$option_value_id]); ?></span>
                    <span class="count"><?php if(!empty($options_values_products_count[$option_value_id])) echo $options_values_products_count[$option_value_id]; ?></span>
                </label>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>