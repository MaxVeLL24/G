<?php include_once 'includes/languages/' . $language . '/account.php'; ?>
<h1><?php echo sprintf(HEADING_ORDER_NUMBER, $_GET['order_id']); ?></h1>
<div class="account-grid clearfix">
    <div class="block-menu">
        <?php require __DIR__ . '/account.tpl.php'; ?>
    </div>
    <div class="block-content">
        <div class="tab-content common-styled-block">
            <table id="account_order_info" class="typical_table" width="100%">
                <thead>
                <th align="left"><?php echo HEADING_ORDER_DATE . ' <strong>' . tep_date_long($order->info['date_purchased']) . '</strong>'; ?></th>
                <th align="right"><?php echo HEADING_ORDER_TOTAL . ' <strong>' . $order->info['total'] . '</strong>'; ?></th>
            </thead>
            <tbody>
                <tr>
                    <td valign="top" width="40%">
                        <?php if($order->delivery != false) : ?>
                            <h2><?php echo HEADING_DELIVERY_ADDRESS; ?></h2>
                            <?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br>'); ?>

                            <?php if(tep_not_null($order->info['shipping_method'])): ?>
                                <div>
                                    <h2><?php echo HEADING_SHIPPING_METHOD; ?></h2>
                                    <?php //echo $order->info['shipping_method']; ?>

                                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                        <?php
                                        for($i = 0, $n = sizeof($order->totals); $i < $n; $i++)
                                        {
                                            echo '<tr>' . "\n" .
                                            '<td class="main" align="right" width="100%">' . $order->totals[$i]['title'] . '</td>' . "\n" .
                                            '<td class="main" align="right">' . $order->totals[$i]['text'] . '</td>' . "\n" .
                                            '</tr>' . "\n";
                                        }
                                        ?>
                                    </table>
                                </div>
                                <div class="clear"></div>
                            <?php endif; ?>

                        <?php endif; ?>
                        <h2><?php echo HEADING_PAYMENT_METHOD; ?></h2>
                        <?php echo $order->info['payment_method']; ?>

                    </td>
                    <td valign="top" width="60%">
                        <?php if(sizeof($order->info['tax_groups']) > 1): ?>
                            <h2><?php echo HEADING_PRODUCTS; ?></h2>
                            <?php echo HEADING_TAX; ?>
                            <?php echo HEADING_TOTAL; ?>
                        <?php else: ?>
                            <h2><?php echo HEADING_PRODUCTS; ?></h2>
                        <?php endif; ?>

                        <table width="100%">
                            <tbody>
                                <?php foreach($order->products as $product) : ?>
                                    <tr>
                                        <td>
                                            <span class="qty"><?php echo $product['qty']; ?> x </span>
                                            <?php echo $product['name']; ?>
                                            <?php if(isset($product['attributes']) && $product['attributes'] > 0): ?>
                                                <!-- GET ATTRIBUTES -->
                                                <ul class="attributes">
                                                    <?php
                                                    foreach($product['attributes'] as $key => $attribute)
                                                    {
                                                        echo '<li>' . $attribute['option'] . ':' . $attribute['value'] . '</li>';
                                                    }
                                                    ?>
                                                </ul>
                                                <!-- /GET ATTRIBUTES -->
                                            <?php endif; ?>
                                        </td>
                                        <?php if(sizeof($order->info['tax_groups']) > 1): ?>
                                            <td valign="top" align="right">
                                                <?php echo tep_display_tax_value($product['tax']) . '%'; ?>
                                            </td>
                                        <?php endif; ?>
                                        <td class="main" align="right" valign="top">
                                            <strong><?php echo $currencies->format(tep_add_tax($product['final_price'], $product['tax']) * $product['qty'], true, $order->info['currency'], $order->info['currency_value']); ?></strong>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
            </table>
            <br /><h2 align="center"><?php echo HEADING_ORDER_HISTORY; ?></h2>
            <table id="account_history_table" class="typical_table" width="100%" border="0">
                <thead>
                <th align="left" style="width: 33%"><?php echo TABLE_HEADING_DATE_ADDED; ?></th>
                <th align="center" style="width: 33%"><?php echo TABLE_HEADING_STATUS; ?></th>
                <th><?php echo TABLE_HEADING_COMMENTS; ?></th>
            </thead>
            <tbody>
                <?php
                $orders_history_query = tep_db_query("SELECT os.orders_status_name, osh.orders_status_id, osh.date_added, osh.customer_notified, osh.comments FROM " . TABLE_ORDERS_STATUS_HISTORY . " osh left join " . TABLE_ORDERS_STATUS . " os on
                    osh.orders_status_id=os.orders_status_id
                    where osh.orders_id = '" . $_GET['order_id'] . "'
                    and os.language_id = '" . (int) $languages_id . "'
                    and osh.customer_notified = 1
                    order by osh.date_added");

                if(tep_db_num_rows($orders_history_query))
                {
                    while($orders_history = tep_db_fetch_array($orders_history_query))
                    {
                        echo '<tr>' .
                        '<td>' . $orders_history['date_added'] . '</td>' .
                        '<td align="center">' . $orders_history['orders_status_name'] . '</td>' .
                        '<td align="center">' . nl2br(tep_db_output($orders_history['comments'])) . '</td>' .
                        '</tr>';
                    }
                }
                else
                {
                    echo '<tr>' .
                    '<td colspan="3">' . TEXT_NO_ORDER_HISTORY . '</td>' .
                    '</tr>';
                }
                ?>
            </tbody>
            </table>
        </div>
    </div>
</div>
