<?php

$active_tab = basename($PHP_SELF);

$query = tep_db_query("select customers_groups_id, customers_discount from customers where customers_id = {$_SESSION['customer_id']}");
$result = tep_db_fetch_array($query);
$customers_discount = $result['customers_discount'];
if($result['customers_groups_id'])
{
    $query = tep_db_query("select customers_groups_discount, customers_groups_name from customers_groups where customers_groups_id = {$result['customers_groups_id']}");
    if(tep_db_num_rows($query))
    {
        $result = tep_db_fetch_array($query);
        $customers_discount += $result['customers_groups_discount'];
        $customers_group_name = $result['customers_groups_name'];
    }
}

?>
<ul class="account-info common-styled-block">
    <li><?php echo MY_ACCOUNT_MY_GROUP, ': <span>', tep_escape($customers_group_name), '</span>'; ?></li>
    <li><?php echo MY_ACCOUNT_MY_DISCOUNT, ': <strong>', tep_escape($customers_discount), '%</strong>'; ?></li>
</ul>
<br>
<ul class="account-menu common-styled-block">
    <li<?php if($active_tab === FILENAME_ACCOUNT_HISTORY) : ?> class="active"<?php endif; ?>>
        <a href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'); ?>"><?php echo MY_ORDERS_VIEW; ?></a>
    </li>
    <li<?php if($active_tab === FILENAME_ACCOUNT_EDIT) : ?> class="active"<?php endif; ?>>
        <a href="<?php echo tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'); ?>"><?php echo MY_ACCOUNT_INFORMATION; ?></a>
    </li>
    <li<?php if($active_tab === FILENAME_ADDRESS_BOOK) : ?> class="active"<?php endif; ?>>
        <a href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'); ?>"><?php echo LOGIN_BOX_ADDRESS_BOOK; ?></a>
    </li>
    <li<?php if($active_tab === FILENAME_ACCOUNT_PASSWORD) : ?> class="active"<?php endif; ?>>
        <a href="<?php echo tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'); ?>"><?php echo MY_ACCOUNT_PASSWORD; ?></a>
    </li>
</ul>
<br>
