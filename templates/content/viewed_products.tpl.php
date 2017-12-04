<?php

/**
 * Шаблон страницы просмотренных товаров
 */

?>
<h1><?php echo VIEWED_PRODUCTS_PAGE_HEADER; ?></h1>
<?php if(!empty($listing_split) && $listing_split->number_of_rows) { ?>
<?php $tpl_settings = array('request' => tep_db_query($listing_split->sql_query)); ?>
<?php if(!empty($listing_split) && $listing_split->number_of_pages > 1) { ?>
<div class="block-pagination common-styled-block align-center">
    <?php echo $listing_split->display_links(5, tep_get_all_get_params(array('page', 'info', 'x', 'y', 'ajaxloading'))); ?>
</div>
<?php } ?>
<div class="common-styled-block">
    <?php require DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/listingTemplate.tpl.php'; ?>
</div>
<?php if(!empty($listing_split) && $listing_split->number_of_pages > 1) { ?>
<div class="block-pagination common-styled-block align-center">
    <?php echo $listing_split->display_links(5, tep_get_all_get_params(array('page', 'info', 'x', 'y', 'ajaxloading'))); ?>
</div>
<?php } ?>
<?php } else { ?>
<div class="alert alert-info" role="alert"><?php echo VIEWED_PRODUCTS_EMPTY_LIST_ALERT_TEXT; ?></div>
<?php } ?>
<?php \EShopmakers\Html\Capture::getInstance('header')->startCapture(); ?>
<style>
    .block-pagination {
        margin: 9px 0;
        padding: 6px;
    }
</style>
<?php \EShopmakers\Html\Capture::getInstance('header')->stopCapture(); ?>