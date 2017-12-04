<?php

/**
 * Шаблон страницы со списком заказов в кабинете покупателя
 */

include_once 'includes/languages/' . $language . '/account.php';

$history_query_raw = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int) $customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int) $languages_id . "' and o.orders_status != '99999' order by orders_id DESC";
$history_split = new splitPageResults($history_query_raw, MAX_DISPLAY_ORDER_HISTORY);
$history_query = tep_db_query($history_split->sql_query);
$history = array();
if(tep_db_num_rows($history_query))
{
    while(($row = tep_db_fetch_array($history_query)) !== false)
    {
        if(tep_not_null($row['delivery_name']))
        {
            $row['order_type'] = TEXT_ORDER_SHIPPED_TO;
            $row['order_name'] = $row['delivery_name'];
        }
        else
        {
            $row['order_type'] = TEXT_ORDER_BILLED_TO;
            $row['order_name'] = $row['billing_name'];
        }
        $history[$row['orders_id']] = $row;
    }
}
if($history)
{
    $query = tep_db_query("select orders_id, count(*) as count from " . TABLE_ORDERS_PRODUCTS . " where orders_id IN (" . implode(', ', array_unique(array_keys($history))) . ") group by orders_id");
    if(tep_db_num_rows($query))
    {
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $history[$row['orders_id']]['products_count'] = $row['count'];
        }
    }
}

?>
<h1><?php echo IMAGE_BUTTON_HISTORY; ?></h1>
<div class="account-grid clearfix">
    <div class="block-menu">
        <?php require __DIR__ . '/account.tpl.php'; ?>
    </div>
    <div class="block-content">
        <div class="tab-content common-styled-block">
            <?php if(tep_count_customer_orders()) : ?>
                <?php if($history_split->number_of_pages > 1) : ?>
                <div class="clearfix">
                    <div class="float-left"><?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></div>
                    <div class="float-right"><?php echo TEXT_RESULT_PAGE . ' ' . $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></div>
                </div>
                <br />
                <?php endif; ?>
                <div class="account-history-table-wrapper">
                    <table class="account-history-table">
                        <thead>
                            <tr>
                                <th><?php echo ACC_HISTORY_ORDER_NUM; ?></th>
                                <th><?php echo ACC_HISTORY_ORDER_DATE; ?></th>
                                <th><?php echo ACC_HISTORY_ORDER_QUANTITY; ?></th>
                                <th><?php echo ACC_HISTORY_ORDER_TOTAL; ?></th>
                                <th><?php echo ACC_HISTORY_ORDER_STATUS; ?></th>
                                <th><?php echo ACC_HISTORY_ORDER_SHOW; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($history as $history) : ?>
                                <tr>
                                    <td><?php echo $history['orders_id']; ?></td>
                                    <td><?php echo tep_date_long($history['date_purchased']); ?></td>
                                    <td><?php echo $history['products_count']; ?></td>
                                    <td><?php echo strip_tags($history['order_total']); ?></td>
                                    <td><?php echo $history['orders_status_name']; ?></td>
                                    <td><?php echo '<a class="btn" href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'order_id=' . $history['orders_id'], 'SSL') . '">' . SMALL_IMAGE_BUTTON_VIEW . '</a>'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <br />
                <div class="clearfix">
                    <div class="float-left"><?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></div>
                    <?php if($history_split->number_of_pages > 1) : ?>
                    <div class="float-right"><?php echo TEXT_RESULT_PAGE . ' ' . $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></div>
                    <?php endif; ?>
                </div>
            <?php else : ?>
            <div class="alert alert-info" role="alert"><?php echo TEXT_NO_PURCHASES; ?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
