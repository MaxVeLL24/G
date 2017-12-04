<?php

/**
 * Вывод товаров плиткой
 */

if(!empty($_GET['cPath']))
{
    $link_path = 'cPath=' . $_GET['cPath'];
}
if(!empty($_GET['manufacturers_id']))
{
    $link_path = 'manufacturers_id=' . $_GET['manufacturers_id'];
}

$sort_current = isset($_GET['sort']) ? $_GET['sort'] : null;
$row_by_page_current = isset($_GET['row_by_page']) ? $_GET['row_by_page'] : null;

?>
<form
    class="category-listing-params clearfix"
    name="category_listing_params"
    method="GET"
    action="<?php echo tep_href_link(basename($PHP_SELF), $link_path); ?>"
    >
    <div class="view-switch">
        <button
            type="submit"
            name="display"
            value="columns"
            class="display-columns"
            title="<?php echo VIEW_COL; ?>"
            ></button>
        <button
            type="submit"
            name="display"
            value="list"
            class="display-list"
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
            <option value="12"<?php if($row_by_page_current == 12) : ?> selected<?php endif; ?>>12</option>
            <option value="30"<?php if($row_by_page_current == 30) : ?> selected<?php endif; ?>>30</option>
            <option value="60"<?php if($row_by_page_current == 60) : ?> selected<?php endif; ?>>60</option>
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
            <option value="name"<?php if($sort_current === 'name') : ?> selected<?php endif; ?>><?php echo SORT_PROD; ?></option>
            <option value="price_vozr"<?php if($sort_current === 'price_vozr') : ?> selected<?php endif; ?>><?php echo SORT_CHEAP; ?></option>
            <option value="price_ub"<?php if($sort_current === 'price_ub') : ?> selected<?php endif; ?>><?php echo SORT_EXPENSIVE; ?></option>
            <option value="new"<?php if($sort_current === 'new') : ?> selected<?php endif; ?>><?php echo SORT_NEW; ?></option>
            <option value="viewed"<?php if($sort_current === 'viewed') : ?> selected<?php endif; ?>><?php echo SORT_POPULAR; ?></option>
        </select>
    </div>
</form>
<div class="category-products-listing-container common-styled-block"><?php require('r_spisok.php'); ?></div>