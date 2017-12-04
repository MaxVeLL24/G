<?php
/*
  $Id: create_order.php,v 1.1 2003/09/24 14:33:18 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

 */

include_once('includes/application_top.php');
include_once(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CREATE_ORDER);

$customer_id = isset($_GET['customer_id']) ? filter_var($_GET['customer_id'], FILTER_VALIDATE_INT, array('min_range' => 1)) : null;
if($customer_id)
{
    $account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = {$customer_id}");
    $account = tep_db_fetch_array($account_query);
    $customer = $account['customers_id'];
    $address_query = tep_db_query("select * from " . TABLE_ADDRESS_BOOK . " where customers_id = {$customer_id}");
    $address = tep_db_fetch_array($address_query);
}
else
{
    // Выгружаем группы пользователей
    $customers_groups = array();
    $query = tep_db_query("SELECT customers_groups_id, customers_groups_name FROM customers_groups");
    while(($row = tep_db_fetch_array($query)) !== false)
    {
        $customers_groups[$row['customers_groups_id']] = $row['customers_groups_name'];
    }
    
    // Критерии поиска пользователей
    $order_by = array();
    if(isset($_GET['order_by']) && is_array($_GET['order_by']))
    {
        foreach(array('customers_id', 'customers_firstname', 'customers_lastname', 'customers_email_address', 'customers_telephone', 'customers_groups_id', 'customers_status') as $filed_name)
        {
            if(isset($_GET['order_by'][$filed_name]) && ($_GET['order_by'][$filed_name] === 'ASC' || $_GET['order_by'][$filed_name] === 'DESC'))
            {
                $order_by[$filed_name] = $_GET['order_by'][$filed_name];
            }
        }
    }
    $where = array();
    if(isset($_GET['where']) && is_array($_GET['where']))
    {
        foreach(array('customers_id', 'customers_firstname', 'customers_lastname', 'customers_email_address', 'customers_telephone', 'customers_groups_id', 'customers_status') as $filed_name)
        {
            if(isset($_GET['where'][$filed_name]))
            {
                switch($filed_name)
                {
                    case 'customers_status' :
                        $tmp = intval($_GET['where'][$filed_name]);
                        if($tmp === 1 || $tmp === 0)
                        {
                            $where[$filed_name] = $tmp;
                        }
                        break;
                    case 'customers_groups_id' :
                        $tmp = intval($_GET['where'][$filed_name]);
                        if(array_key_exists($tmp, $customers_groups))
                        {
                            $where[$filed_name] = $tmp;
                        }
                        break;
                    case 'customers_id' :
                        $tmp = intval($_GET['where'][$filed_name]);
                        if($tmp)
                        {
                            $where[$filed_name] = $tmp;
                        }
                        break;
                    default :
                        $tmp = trim($_GET['where'][$filed_name]);
                        if($tmp)
                        {
                            $where[$filed_name] = $tmp;
                        }
                        break;
                }
            }
        }
    }
    $_where = array();
    foreach($where as $field_name => $value)
    {
        switch($field_name)
        {
            case 'customers_status' :
            case 'customers_groups_id' :
            case 'customers_id' :
                $_where[] = "`{$field_name}` = {$value}";
                break;
            default :
                $value = tep_db_input($value);
                $_where[] = "`{$field_name}` LIKE '%{$value}%'";
                break;
        }
    }
    if($_where)
    {
        $_where = ' WHERE ' . implode(' AND ', $_where);
    }
    else
    {
        $_where = '';
    }
    $_order_by = array();
    foreach($order_by as $field_name => $value)
    {
        $_order_by[] = "`{$field_name}` {$value}";
    }
    if($_order_by)
    {
        $_order_by = ' ORDER BY ' . implode(', ', $_order_by);
    }
    else
    {
        $_order_by = '';
    }
    
    // Находим общее количество пользователей, соответствующих запросу
    $customers = array();
    $query = tep_db_query("SELECT COUNT(*) AS `count` FROM customers" . $_where . $_order_by);
    $result = tep_db_fetch_array($query);
    $customers_count = $result['count'];
    if($customers_count)
    {
        // Общее количество страниц
        $pages_count = ceil($customers_count / 20);
        if(!$pages_count)
        {
            $pages_count = 1;
        }
        
        // Текущая страница
        $page = isset($_GET['page']) ? filter_var($_GET['page'], FILTER_VALIDATE_INT, array('min_range' => 1, 'max_range' => $pages_count)) : null;
        if($page === null)
        {
            $page = 1;
        }
        elseif($page === false)
        {
            tep_redirect(tep_href_link(FILENAME_CREATE_ORDER, tep_get_all_get_params(array('page'))));
        }
        
        // Смещение выборки
        $offset = ($page - 1) * 20;
        
        // Выгружаем пользователей
        $query = tep_db_query("SELECT customers_id, customers_firstname, customers_lastname, customers_email_address, customers_telephone, customers_groups_id, customers_status FROM customers" . $_where . $_order_by . " LIMIT {$offset}, 20");
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $customers[] = $row;
        }
    }
}

// #### Generate Page
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
        <script language="javascript" src="includes/menu.js"></script>
        <script language="javascript" src="includes/general.js"></script>
        <?php require('includes/form_check.js.php'); ?>
        <style>
            .search-input,
            .search-button {
                -moz-box-sizing: border-box;
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
                -moz-border-radius: 0px;
                -webkit-border-radius: 0px;
                border-radius: 0px;
                border: 1px solid rgb(169, 169, 169);
                height: 19px;
            }
            .search-input {
                display: block;
                width: 100%;
            }
            .search-button {
                background-image: url('images/icons/search.png');
                background-position: center center;
                background-repeat: no-repeat;
                cursor: pointer;
                width: 19px;
            }
            .select_customer {
                cursor: pointer;
            }
            .select_customer:hover {
                background-color: #66bdfe;
            }
        </style>
        <script>
            function onSelectCustomerFormSubmit()
            {
                if(!document.forms.select_customer)
                {
                    return;
                }
                var i = document.forms.select_customer.elements.length - 1;
                while(i--)
                {
                    if(!document.forms.select_customer.elements[i].value.trim())
                    {
                        console.log(document.forms.select_customer.elements[i].value);
                        document.forms.select_customer.elements[i].disabled = true;
                    }
                }
                document.forms.select_customer.submit();
            }
            function findLinkAndFollowIt()
            {
                var a = this.getElementsByTagName('a');
                if(a.length)
                {
                    location = a[0].href;
                }
            }
            function onDocumentLoad()
            {
                var rows = document.getElementsByClassName('select_customer'), i = rows.length;
                while(i--)
                {
                    rows[i].onclick = findLinkAndFollowIt;
                }
            }
            if(document.addEventListener)
            {
                document.addEventListener('DOMContentLoaded', onDocumentLoad);
            }
            else if(document.attachEvent)
            {
                document.attachEvent('onload', onDocumentLoad);
            }
            else
            {
                window.onload = onDocumentLoad;
            }
        </script>
    </head>
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
        <!-- header //-->
        <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
        <!-- header_smend //-->

        <!-- body //-->
        <table border="0" width="100%" cellspacing="2" cellpadding="2">
            <tr>
                <td width="<?php echo BOX_WIDTH; ?>" valign="top">
                    <!-- Порльзователь выбран -->
                    <?php if($customer_id) { ?>
                    <form id="create_order" name="create_order" method="POST" action="<?php echo tep_href_link(FILENAME_CREATE_ORDER_PROCESS) ?>">
                        <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>">
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="pageHeading"><?php echo HEADING_CREATE; ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <?php require(DIR_WS_MODULES . 'create_order_details.php'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                        <tr>
                                            <td class="main"><?php echo '<a href="' . tep_href_link(FILENAME_CREATE_ORDER) . '">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                                            <td class="main" align="right"><?php echo tep_image_submit('button_confirm.gif', IMAGE_BUTTON_CONFIRM); ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <!-- Порльзователь не выбран -->
                    <?php } else { ?>
                    <form id="select_customer" name="select_customer" method="GET" action="<?php echo tep_href_link(FILENAME_CREATE_ORDER) ?>" onsubmit="onSelectCustomerFormSubmit();">
                        <?php if($order_by) { ?>
                        <?php foreach($order_by as $key => $value) { ?>
                        <input type="hidden" name="order_by[<?php echo tep_escape($key); ?>]" value="<?php echo tep_escape($value); ?>">
                        <?php } ?>
                        <?php } ?>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="pageHeading"><?php echo HEADING_SELECT_CUSTOMER; ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <?php if($order_by || $where) { ?>
                            <tr>
                                <td>
                                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td class="smallText">
                                                <?php echo TEXT_FILTERS_APPLIED; ?>
                                                <a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER); ?>"><?php echo BUTTON_RESET_FILTERS; ?></a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" width="100%" cellspacing="0" cellpadding="2">
                                        <thead>
                                            <tr class="dataTableHeadingRow">
                                                <td class="dataTableHeadingContent">
                                                    <?php
                                                    
                                                    $_order_by = $order_by;
                                                    if(empty($order_by['customers_id']))
                                                    {
                                                        $sort_sign = '';
                                                        $_order_by['customers_id'] = 'ASC';
                                                    }
                                                    elseif(isset($order_by['customers_id']) && $order_by['customers_id'] === 'ASC')
                                                    {
                                                        $sort_sign = '&nbsp;&uarr;';
                                                        $_order_by['customers_id'] = 'DESC';
                                                    }
                                                    else
                                                    {
                                                        $sort_sign = '&nbsp;&darr;';
                                                        unset($_order_by['customers_id']);
                                                    }
                                                    $query_array = array();
                                                    if($_order_by)
                                                    {
                                                        $query_array['order_by'] = $_order_by;
                                                    }
                                                    if($where)
                                                    {
                                                        $query_array['where'] = $where;
                                                    }
                                                    if($query_array)
                                                    {
                                                        $query_string = http_build_query($query_array);
                                                    }
                                                    
                                                    ?>
                                                    <a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER, empty($query_string) ? '' : $query_string) ?>"><?php echo COLUMN_ID, $sort_sign; ?></a>
                                                </td>
                                                <td class="dataTableHeadingContent">
                                                    <?php
                                                    
                                                    $_order_by = $order_by;
                                                    if(empty($order_by['customers_firstname']))
                                                    {
                                                        $sort_sign = '';
                                                        $_order_by['customers_firstname'] = 'ASC';
                                                    }
                                                    elseif(isset($order_by['customers_firstname']) && $order_by['customers_firstname'] === 'ASC')
                                                    {
                                                        $sort_sign = '&nbsp;&uarr;';
                                                        $_order_by['customers_firstname'] = 'DESC';
                                                    }
                                                    else
                                                    {
                                                        $sort_sign = '&nbsp;&darr;';
                                                        unset($_order_by['customers_firstname']);
                                                    }
                                                    $query_array = array();
                                                    if($_order_by)
                                                    {
                                                        $query_array['order_by'] = $_order_by;
                                                    }
                                                    if($where)
                                                    {
                                                        $query_array['where'] = $where;
                                                    }
                                                    if($query_array)
                                                    {
                                                        $query_string = http_build_query($query_array);
                                                    }
                                                    
                                                    ?>
                                                    <a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER, empty($query_string) ? '' : $query_string) ?>"><?php echo COLUMN_FIRSTNAME, $sort_sign; ?></a>
                                                </td>
                                                <td class="dataTableHeadingContent">
                                                    <?php
                                                    
                                                    $_order_by = $order_by;
                                                    if(empty($order_by['customers_lastname']))
                                                    {
                                                        $sort_sign = '';
                                                        $_order_by['customers_lastname'] = 'ASC';
                                                    }
                                                    elseif(isset($order_by['customers_lastname']) && $order_by['customers_lastname'] === 'ASC')
                                                    {
                                                        $sort_sign = '&nbsp;&uarr;';
                                                        $_order_by['customers_lastname'] = 'DESC';
                                                    }
                                                    else
                                                    {
                                                        $sort_sign = '&nbsp;&darr;';
                                                        unset($_order_by['customers_lastname']);
                                                    }
                                                    $query_array = array();
                                                    if($_order_by)
                                                    {
                                                        $query_array['order_by'] = $_order_by;
                                                    }
                                                    if($where)
                                                    {
                                                        $query_array['where'] = $where;
                                                    }
                                                    if($query_array)
                                                    {
                                                        $query_string = http_build_query($query_array);
                                                    }
                                                    
                                                    ?>
                                                    <a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER, empty($query_string) ? '' : $query_string) ?>"><?php echo COLUMN_LASTNAME, $sort_sign; ?></a>
                                                </td>
                                                <td class="dataTableHeadingContent">
                                                    <?php
                                                    
                                                    $_order_by = $order_by;
                                                    if(empty($order_by['customers_email_address']))
                                                    {
                                                        $sort_sign = '';
                                                        $_order_by['customers_email_address'] = 'ASC';
                                                    }
                                                    elseif(isset($order_by['customers_email_address']) && $order_by['customers_email_address'] === 'ASC')
                                                    {
                                                        $sort_sign = '&nbsp;&uarr;';
                                                        $_order_by['customers_email_address'] = 'DESC';
                                                    }
                                                    else
                                                    {
                                                        $sort_sign = '&nbsp;&darr;';
                                                        unset($_order_by['customers_email_address']);
                                                    }
                                                    $query_array = array();
                                                    if($_order_by)
                                                    {
                                                        $query_array['order_by'] = $_order_by;
                                                    }
                                                    if($where)
                                                    {
                                                        $query_array['where'] = $where;
                                                    }
                                                    if($query_array)
                                                    {
                                                        $query_string = http_build_query($query_array);
                                                    }
                                                    
                                                    ?>
                                                    <a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER, empty($query_string) ? '' : $query_string) ?>"><?php echo COLUMN_EAMIL, $sort_sign; ?></a>
                                                </td>
                                                <td class="dataTableHeadingContent">
                                                    <?php
                                                    
                                                    $_order_by = $order_by;
                                                    if(empty($order_by['customers_telephone']))
                                                    {
                                                        $sort_sign = '';
                                                        $_order_by['customers_telephone'] = 'ASC';
                                                    }
                                                    elseif(isset($order_by['customers_telephone']) && $order_by['customers_telephone'] === 'ASC')
                                                    {
                                                        $sort_sign = '&nbsp;&uarr;';
                                                        $_order_by['customers_telephone'] = 'DESC';
                                                    }
                                                    else
                                                    {
                                                        $sort_sign = '&nbsp;&darr;';
                                                        unset($_order_by['customers_telephone']);
                                                    }
                                                    $query_array = array();
                                                    if($_order_by)
                                                    {
                                                        $query_array['order_by'] = $_order_by;
                                                    }
                                                    if($where)
                                                    {
                                                        $query_array['where'] = $where;
                                                    }
                                                    if($query_array)
                                                    {
                                                        $query_string = http_build_query($query_array);
                                                    }
                                                    
                                                    ?>
                                                    <a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER, empty($query_string) ? '' : $query_string) ?>"><?php echo COLUMN_TELEPHONE, $sort_sign; ?></a>
                                                </td>
                                                <td class="dataTableHeadingContent">
                                                    <?php
                                                    
                                                    $_order_by = $order_by;
                                                    if(empty($order_by['customers_groups_id']))
                                                    {
                                                        $sort_sign = '';
                                                        $_order_by['customers_groups_id'] = 'ASC';
                                                    }
                                                    elseif(isset($order_by['customers_groups_id']) && $order_by['customers_groups_id'] === 'ASC')
                                                    {
                                                        $sort_sign = '&nbsp;&uarr;';
                                                        $_order_by['customers_groups_id'] = 'DESC';
                                                    }
                                                    else
                                                    {
                                                        $sort_sign = '&nbsp;&darr;';
                                                        unset($_order_by['customers_groups_id']);
                                                    }
                                                    $query_array = array();
                                                    if($_order_by)
                                                    {
                                                        $query_array['order_by'] = $_order_by;
                                                    }
                                                    if($where)
                                                    {
                                                        $query_array['where'] = $where;
                                                    }
                                                    if($query_array)
                                                    {
                                                        $query_string = http_build_query($query_array);
                                                    }
                                                    
                                                    ?>
                                                    <a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER, empty($query_string) ? '' : $query_string) ?>"><?php echo COLUMN_GROUP, $sort_sign; ?></a>
                                                </td>
                                                <td class="dataTableHeadingContent">
                                                    <?php
                                                    
                                                    $_order_by = $order_by;
                                                    if(empty($order_by['customers_status']))
                                                    {
                                                        $sort_sign = '';
                                                        $_order_by['customers_status'] = 'ASC';
                                                    }
                                                    elseif(isset($order_by['customers_status']) && $order_by['customers_status'] === 'ASC')
                                                    {
                                                        $sort_sign = '&nbsp;&uarr;';
                                                        $_order_by['customers_status'] = 'DESC';
                                                    }
                                                    else
                                                    {
                                                        $sort_sign = '&nbsp;&darr;';
                                                        unset($_order_by['customers_status']);
                                                    }
                                                    $query_array = array();
                                                    if($_order_by)
                                                    {
                                                        $query_array['order_by'] = $_order_by;
                                                    }
                                                    if($where)
                                                    {
                                                        $query_array['where'] = $where;
                                                    }
                                                    if($query_array)
                                                    {
                                                        $query_string = http_build_query($query_array);
                                                    }
                                                    
                                                    ?>
                                                    <a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER, empty($query_string) ? '' : $query_string) ?>"><?php echo COLUMN_STATUS, $sort_sign; ?></a>
                                                </td>
                                            </tr>
                                            <tr class="dataTableHeadingRow">
                                                <td class="dataTableHeadingContent">
                                                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input
                                                                        type="text"
                                                                        name="where[customers_id]"
                                                                        <?php if(!empty($where['customers_id'])) { ?>value="<?php echo tep_escape($where['customers_id']); ?>"<?php } ?>
                                                                        class="search-input"
                                                                        >
                                                                </td>
                                                                <td width="21px" align="right">
                                                                    <button type="submit" title="<?php echo TEXT_GO_SEARCH; ?>" class="search-button"></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td class="dataTableHeadingContent">
                                                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input
                                                                        type="text"
                                                                        name="where[customers_firstname]"
                                                                        <?php if(!empty($where['customers_firstname'])) { ?>value="<?php echo tep_escape($where['customers_firstname']); ?>"<?php } ?>
                                                                        class="search-input"
                                                                        >
                                                                </td>
                                                                <td width="21px" align="right">
                                                                    <button type="submit" title="<?php echo TEXT_GO_SEARCH; ?>" class="search-button"></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td class="dataTableHeadingContent">
                                                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input
                                                                        type="text"
                                                                        name="where[customers_lastname]"
                                                                        <?php if(!empty($where['customers_lastname'])) { ?>value="<?php echo tep_escape($where['customers_lastname']); ?>"<?php } ?>
                                                                        class="search-input"
                                                                        >
                                                                </td>
                                                                <td width="21px" align="right">
                                                                    <button type="submit" title="<?php echo TEXT_GO_SEARCH; ?>" class="search-button"></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td class="dataTableHeadingContent">
                                                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input
                                                                        type="text"
                                                                        name="where[customers_email_address]"
                                                                        <?php if(!empty($where['customers_email_address'])) { ?>value="<?php echo tep_escape($where['customers_email_address']); ?>"<?php } ?>
                                                                        class="search-input"
                                                                        >
                                                                </td>
                                                                <td width="21px" align="right">
                                                                    <button type="submit" title="<?php echo TEXT_GO_SEARCH; ?>" class="search-button"></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td class="dataTableHeadingContent">
                                                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <input
                                                                        type="text"
                                                                        name="where[customers_telephone]"
                                                                        <?php if(!empty($where['customers_telephone'])) { ?>value="<?php echo tep_escape($where['customers_telephone']); ?>"<?php } ?>
                                                                        class="search-input"
                                                                        >
                                                                </td>
                                                                <td width="21px" align="right">
                                                                    <button type="submit" title="<?php echo TEXT_GO_SEARCH; ?>" class="search-button"></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td class="dataTableHeadingContent">
                                                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <select
                                                                        name="where[customers_groups_id]"
                                                                        onchange="onSelectCustomerFormSubmit();"
                                                                        class="search-input"
                                                                        >
                                                                        <option
                                                                            value=""
                                                                            <?php if(empty($where['customers_groups_id'])) { ?>selected<?php } ?>
                                                                            >---</option>
                                                                        <?php foreach($customers_groups as $value => $text) { ?>
                                                                        <option
                                                                            value="<?php echo $value; ?>"
                                                                            <?php if(!empty($where['customers_groups_id']) && $where['customers_groups_id'] == $value) { ?>selected<?php } ?>
                                                                            ><?php echo tep_escape($text); ?></option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </td>
                                                                <td width="21px" align="right">
                                                                    <button type="submit" title="<?php echo TEXT_GO_SEARCH; ?>" class="search-button"></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td class="dataTableHeadingContent">
                                                    <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <select
                                                                        name="where[customers_status]"
                                                                        onchange="onSelectCustomerFormSubmit();"
                                                                        class="search-input"
                                                                        >
                                                                        <option
                                                                            value=""
                                                                            <?php if(!array_key_exists('customers_status', $where)) { ?>selected<?php } ?>
                                                                            >---</option>
                                                                        <option
                                                                            value="1"
                                                                            <?php if(array_key_exists('customers_status', $where) && $where['customers_status'] == 1) { ?>selected<?php } ?>
                                                                            ><?php echo TEXT_ACTIVE; ?></option>
                                                                        <option
                                                                            value="0"
                                                                            <?php if(array_key_exists('customers_status', $where) && $where['customers_status'] == 0) { ?>selected<?php } ?>
                                                                            ><?php echo TEXT_INACTIVE; ?></option>
                                                                    </select>
                                                                </td>
                                                                <td width="21px" align="right">
                                                                    <button type="submit" title="<?php echo TEXT_GO_SEARCH; ?>" class="search-button"></button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!--- Пользователи есть -->
                                            <?php if($customers_count) { ?>
                                            <?php foreach($customers as $customer) { ?>
                                            <tr class="dataTableRow select_customer">
                                                <td class="dataTableContent">
                                                    <a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER, 'customer_id=' . $customer['customers_id']); ?>"><img src="images/icons/preview.gif" width="16" height="16" alt="<?php echo TEXT_SELECT_THIS_CUSTOMER; ?>" title="<?php echo TEXT_SELECT_THIS_CUSTOMER; ?>"></a>
                                                    <?php echo $customer['customers_id']; ?>
                                                </td>
                                                <td class="dataTableContent"><?php echo tep_escape($customer['customers_firstname']); ?></td>
                                                <td class="dataTableContent"><?php echo tep_escape($customer['customers_lastname']); ?></td>
                                                <td class="dataTableContent"><?php echo tep_escape($customer['customers_email_address']); ?></td>
                                                <td class="dataTableContent"><?php echo tep_escape($customer['customers_telephone']); ?></td>
                                                <td class="dataTableContent"><?php echo tep_escape($customers_groups[$customer['customers_groups_id']]); ?></td>
                                                <td class="dataTableContent">
                                                    <img src="images/icon_status_<?php echo $customer['customers_status'] ? 'green' : 'red' ?>.gif" width="10" height="10" alt="<?php echo $customer['customers_status'] ? TEXT_ACTIVE : TEXT_INACTIVE ?>" title="<?php echo $customer['customers_status'] ? TEXT_ACTIVE : TEXT_INACTIVE ?>">
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <!--- Пользователей нет -->
                                            <?php } else { ?>
                                            <tr class="dataTableRowSelected">
                                                <td colspan="7" align="center" class="dataTableContent">
                                                    <?php echo TEXT_NOT_FOUND; ?>
                                                    <?php if($order_by || $where) { ?>
                                                    <?php echo TEXT_RESET_FILTERS_1; ?>
                                                    <a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER); ?>"><?php echo TEXT_RESET_FILTERS_2; ?></a>.
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                            </tr>
                            <?php if(isset($page, $pages_count)) { ?>
                            <tr>
                                <td class="main">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                        <tbody>
                                            <tr>
                                                <td class="smallText">Страница <?php echo $page; ?> из <?php echo $pages_count; ?></td>
                                                <?php if($pages_count > 1) { ?>
                                                <td class="smallText" align="right">
                                                    <?php if($page > 1) { ?>
                                                    <a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER, tep_get_all_get_params(array('page')) . '&page=' . ($page - 1)) ?>">Назад</a>
                                                    <?php } ?>
                                                    <?php if($page < $pages_count) { ?>
                                                    <a href="<?php echo tep_href_link(FILENAME_CREATE_ORDER, tep_get_all_get_params(array('page')) . '&page=' . ($page + 1)) ?>">Вперед</a>
                                                    <?php } ?>
                                                </td>
                                                <?php } ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                    </form>
                    <?php } ?>
                </td>
                <!-- body_text_smend //-->

            </tr>
        </table>
        <!-- body_smend //-->

        <!-- footer //-->
        <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
        <!-- footer_smend //-->
        <br>
    </body>
</html>
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>