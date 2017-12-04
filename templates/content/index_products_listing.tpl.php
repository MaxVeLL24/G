<?php

/* @var $listing_split \splitPageResults */

if($listing_split->number_of_rows > 0)
{
    $tpl_settings = array(
        'request' => tep_db_query($listing_split->sql_query),
        'display' => $sort_display
    );
    include DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/listingTemplate.tpl.php';
    if($listing_split->number_of_pages > 1) { ?>
    <div class="load-more-products-block">
        <a href="<?php echo tep_href_link(FILENAME_DEFAULT, tep_get_all_get_params(array('page', 'info', 'x', 'y', 'ajaxloading', 'row_by_page')) . '&row_by_page=' . ($listing_split->number_of_rows_per_page * 2)); ?>" data-row-by-page="<?php echo sprintf('%d', $listing_split->number_of_rows_per_page * 2); ?>" class="button"><?php echo LOAD_MORE_PRODUCTS; ?></a>
    </div>
    <?php }
}
else
{
    ?><div class="alert alert-info"><?php echo NO_PRODUCTS_FOUND; ?></div><?php
}