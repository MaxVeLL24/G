<?php

/**
 * Шаблон страницы списка желаний
 */

?>
<h1><?php echo WISHLIST_PAGE_HEADER; ?></h1>
<?php if(!empty($listing_query) && tep_db_num_rows($listing_query)) { ?>
<?php $tpl_settings = array('request' => $listing_query); ?>
<div class="common-styled-block">
    <?php require DIR_WS_TEMPLATES . TEMPLATE_NAME . '/mainpage_modules/listingTemplate.tpl.php'; ?>
</div>
<?php } else { ?>
<div class="alert alert-info" role="alert"><?php echo WISHLIST_EMPTY_LIST_ALERT_TEXT; ?></div>
<?php } ?>
