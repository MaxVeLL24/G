<?php

/**
 * Страница со списком товаров в категории
 */

/* @var $listing_split \splitPageResults */

// HEAD_TITLE_TAG_ALL
// HEAD_DESC_TAG_ALL
// HEAD_KEY_TAG_ALL

if($manufacturers_id)
{
    $man_desc_query = tep_db_query("select m.manufacturers_image, m.manufacturers_name, mi.name_header, mi.manufacturers_description, mi.manufacturers_title, mi.manufacturers_meta_keywords, mi.manufacturers_meta_description from manufacturers as m inner join manufacturers_info as mi on m.manufacturers_id = mi.manufacturers_id and mi.languages_id = {$_SESSION['languages_id']} where m.manufacturers_id = {$manufacturers_id} limit 1");
    $man_desc = tep_db_fetch_array($man_desc_query);

    $heading_text_box = $man_desc['manufacturers_name'];
    $cat_image = $man_desc['manufacturers_image'];
    $desc_text = $man_desc['manufacturers_description'];

    $page_meta_description = $man_desc['manufacturers_meta_description'];
    
    // Заголовок страницы в браузере
    $page_title = $man_desc['manufacturers_title'] && !$options_values_ids_in_query ? $man_desc['manufacturers_title'] : $man_desc['manufacturers_name'];
    $heading_text_box = $man_desc['name_header'] && !$options_values_ids_in_query ? $man_desc['name_header'] : $man_desc['manufacturers_name'];
    $page_meta_description = $man_desc['manufacturers_meta_description'] && !$options_values_ids_in_query ? $man_desc['manufacturers_meta_description'] : $man_desc['manufacturers_name'];
    $page_meta_keywords = $man_desc['manufacturers_meta_keywords'] ? $man_desc['manufacturers_meta_keywords'] : $man_desc['manufacturers_name'];
}
elseif($current_category_id)
{
    $category_query = tep_db_query("select cd.categories_id, cd.categories_name, cd.categories_meta_title, cd.categories_meta_description, cd.categories_heading_title, cd.categories_description, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $current_category_id . "' and cd.categories_id = '" . $current_category_id . "' and cd.language_id = '" . $languages_id . "'");
    $category = tep_db_fetch_array($category_query);

    $heading_text_box = $category['categories_heading_title'] && !$options_values_ids_in_query ? $category['categories_heading_title'] : $category['categories_name'];
    // $cat_image = $category['categories_image'];
    $page_meta_description = $category['categories_meta_description'] && !$options_values_ids_in_query ? $category['categories_meta_description'] : $category['categories_name'];
    $desc_text = $category['categories_description'];
    
    // Заголовок страницы в браузере
    $page_title = $category['categories_meta_title'] && !$options_values_ids_in_query ? $category['categories_meta_title'] : $category['categories_name'];
}
elseif($keywords !== null && $keywords !== false)
{
    if($keywords && $listing_split->number_of_rows)
    {
        $heading_text_box = sprintf(PRODUCTS_FOUND_HEADER, $keywords);
    }
    else
    {
        $heading_text_box = NO_PRODUCTS_FOUND_HEADER;
    }
    
    // Заголовок страницы в браузере
    $page_title = $heading_text_box;
}

// Добавить выбранные опции в заголовок, тайтл, метаописание
if($options_values_ids_in_query) {
    $query = tep_db_query("select po.products_options_id, po.products_options_name, pov.products_options_values_id, pov.products_options_values_name from products_options_values_to_products_options as povtpo inner join products_options as po on po.products_options_id = povtpo.products_options_id and po.language_id = {$_SESSION['languages_id']} inner join products_options_values as pov on pov.products_options_values_id = povtpo.products_options_values_id and pov.language_id = {$_SESSION['languages_id']} where povtpo.products_options_values_id in (" . implode(', ', $options_values_ids_in_query) . ")");
    $options_names = array();
    $options_values_names = array();
    $options_values_to_options = array();
    while(($row = tep_db_fetch_array($query)) !== false) {
        if(!isset($options_values_to_options[$option_id])) {
            $options_values_to_options[$row['products_options_id']] = array();
        }
        $options_values_to_options[$row['products_options_id']][] = $row['products_options_values_id'];
        $options_names[$row['products_options_id']] = $row['products_options_name'];
        $options_values_names[$row['products_options_values_id']] = $row['products_options_values_name'];
    }
    foreach($options_names as $option_id => $option_name) {
        $page_title .= '. ' . $option_name;
        $heading_text_box .= '. ' . $option_name;
        $page_meta_description .= '. ' . $option_name;
        foreach($options_values_to_options[$option_id] as $i => $option_value_id) {
            if($i) {
                $page_title .= ', ' . $options_values_names[$option_value_id];
                $heading_text_box .= ', ' . $options_values_names[$option_value_id];
                $page_meta_description .= ', ' . $options_values_names[$option_value_id];
            } else {
                $page_title .= ' ' . $options_values_names[$option_value_id];
                $heading_text_box .= ' ' . $options_values_names[$option_value_id];
                $page_meta_description .= ' ' . $options_values_names[$option_value_id];
            }
        }
    }
}

// Добавить номер страницы в тайтл
if(!empty($page_title)) {
    if($listing_split->current_page_number > 1) {
        $page_title .= sprintf(TITLE_ADD_PAGE_NUMBER, $listing_split->current_page_number);
    }
    if(defined('HEAD_TITLE_TAG_ALL') && HEAD_TITLE_TAG_ALL) {
        $page_title .= TITLE_DELIMITER . HEAD_TITLE_TAG_ALL;
    }
}

// Метаописание
if(!empty($seo_text['description'])) {
    $page_meta_description = $seo_text['description'];
}
elseif(strstr($_SERVER['REQUEST_URI'], 'sort=new')){
    $heading_text_box = HEAD_TITLE_TAG_WHATS_NEW;
    $page_title = HEAD_TITLE_TAG_WHATS_NEW.' '.HEAD_TITLE_TAG_DEFAULT;
    $page_meta_description = HEAD_TITLE_TAG_WHATS_NEW.' '.HEAD_DESC_TAG_DEFAULT;
}
elseif(strstr($_SERVER['REQUEST_URI'], 'specials=yes')){
    $heading_text_box = HEAD_TITLE_TAG_SPECIALS;
    $page_title = HEAD_TITLE_TAG_SPECIALS.' '.HEAD_TITLE_TAG_DEFAULT;
    $page_meta_description = HEAD_TITLE_TAG_SPECIALS.' '.HEAD_DESC_TAG_DEFAULT;
}
elseif(strstr($_SERVER['REQUEST_URI'], 'featured=yes')){
    $heading_text_box = HEAD_TITLE_TAG_FEATURED;
    $page_title = HEAD_TITLE_TAG_FEATURED.' '.HEAD_TITLE_TAG_DEFAULT;
    $page_meta_description = HEAD_TITLE_TAG_FEATURED.' '.HEAD_DESC_TAG_DEFAULT;
}
elseif(!empty($page_meta_description) && (!$manufacturers_id || !$man_desc['manufacturers_meta_description'] || $options_values_ids_in_query) && (!$current_category_id || !$category['categories_meta_description'] || $options_values_ids_in_query)) {
    $page_meta_description .= ' ' . HEAD_DESC_TAG_DEFAULT;
}

?>
<h1><?php echo $heading_text_box; ?></h1>
<form
    class="category-listing-params common-styled-block clearfix"
    name="category_listing_params"
    method="GET"
    action="<?php echo tep_href_link(basename($PHP_SELF), $link_path); ?>"
    >
    <div class="view-switch">
        <label><?php echo TEXT_SWITCH_VIEW; ?></label>
        <button
            type="submit"
            name="display"
            value="tile"
            class="display-columns<?php if($sort_display === 'tile') : ?> active<?php endif; ?>"
            title="<?php echo VIEW_COL; ?>"
            ></button>
        <button
            type="submit"
            name="display"
            value="list"
            class="display-list<?php if($sort_display === 'list') : ?> active<?php endif; ?>"
            title="<?php echo VIEW_LIST; ?>"
            ></button>
    </div>
    <div class="display-items">
        <label for="display-items"><?php echo TEXT_RESULT_VIEW; ?></label>
        <select
            id="display-items"
            name="row_by_page"
            class="custom-select"
            >
            <option value="24"<?php if($row_by_page_current == 24) : ?> selected<?php endif; ?>>24</option>
            <option value="48"<?php if($row_by_page_current == 48) : ?> selected<?php endif; ?>>48</option>
            <option value="96"<?php if($row_by_page_current == 96) : ?> selected<?php endif; ?>>96</option>
            <option value="all"<?php if($row_by_page_current == 'all') : ?> selected<?php endif; ?>><?php echo SORT_ALL; ?></option>
        </select>
    </div>
    <div class="sort-order">
        <label for="sort-order"><?php echo TEXT_SORT_PRODUCTS; ?></label>
        <select
            id="sort-order"
            name="sort"
            class="custom-select"
            >
            <option value="name_asc"<?php if($sort_current === 'name_asc') : ?> selected<?php endif; ?>><?php echo SORT_NAME_ASC; ?></option>
            <option value="name_desc"<?php if($sort_current === 'name_desc') : ?> selected<?php endif; ?>><?php echo SORT_NAME_DESC; ?></option>
            <option value="price_asc"<?php if($sort_current === 'price_asc') : ?> selected<?php endif; ?>><?php echo SORT_PRICE_ASC; ?></option>
            <option value="price_desc"<?php if($sort_current === 'price_desc') : ?> selected<?php endif; ?>><?php echo SORT_PRICE_DESC; ?></option>
            <option value="new"<?php if($sort_current === 'new') : ?> selected<?php endif; ?> selected><?php echo SORT_NEW; ?></option>
            <option value="viewed"<?php if($sort_current === 'viewed') : ?> selected<?php endif; ?>><?php echo SORT_POPULAR; ?></option>
        </select>
    </div>
    <?php if($listing_split->number_of_pages > 1) : ?>
    <div class="block-pagination">
        <?php echo $listing_split->display_links(5, tep_get_all_get_params(array('page', 'info', 'x', 'y', 'ajaxloading'))); ?>
    </div>
    <?php endif; ?>
</form>
<div class="category-products-listing-container common-styled-block">
    <?php require __DIR__ . '/index_products_listing.tpl.php'; ?>
</div>
<?php if($listing_split->number_of_pages > 1) : ?>
<div class="block-pagination common-styled-block align-center">
    <?php echo $listing_split->display_links(5, tep_get_all_get_params(array('page', 'info', 'x', 'y', 'ajaxloading'))); ?>
</div>
<?php endif; ?>
<?php if((!empty($cat_image) || !empty($desc_text)) && !array_diff(array_keys($_GET), array('manufacturers_id', 'cPath', 'language'))) : ?>
<div class="category-description common-styled-block clearfix">
    <?php if(!empty($cat_image)): ?>
    <img src="<?php echo tep_href_link(DIR_WS_IMAGES . $cat_image); ?>" class="category-image pull-right"
         alt="<?= (!empty($man_desc['manufacturers_name'])) ? $man_desc['manufacturers_name'] : '' ?>">
    <?php endif; ?>   
    <?php echo $desc_text; ?>
</div>
<?php endif; ?>
<?php require DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/viewed_products.php'; ?>
<?php EShopmakers\Html\Capture::getInstance('footer')->startCapture(); ?>
<script src="templates/gigimot/js/products-listing.js"></script>
<?php EShopmakers\Html\Capture::getInstance('footer')->stopCapture(); ?>