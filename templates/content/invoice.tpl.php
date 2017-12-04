<div class="clearfix">
    <h1 class="float-left"><?php echo tep_escape($page_title); ?></h1>
    <a href="<?php echo tep_href_link(FILENAME_INVOICE_HTML, 'orders_id=' . $orders_id . '&download'); ?>" class="button float-right" download><?php echo INVOICE_DOWNLOAD_BUTTON_TEXT; ?></a>
</div>
<iframe src="<?php echo tep_href_link(FILENAME_INVOICE_HTML, 'orders_id=' . $orders_id); ?>" class="html-invoice-iframe"></iframe>