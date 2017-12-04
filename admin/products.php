<?php
/*
  $Id: categories.php,v 1.7 21.05.2013 by Shopmakers
*/

include_once __DIR__ . '/includes/application_top.php';
require_once 'includes/languages/' . $language . '/categories.php';
require(DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();

$action = (isset($_GET['action']) ? $_GET['action'] : '');

// If the action will affect the cache entries
if ($action === 'insert_product' || $action === 'update_product') {
    tep_db_query("DELETE FROM cache WHERE `cache_name` LIKE 'sef.products.%'");
}
if (tep_not_null($action)) {
    switch ($action) {
        case 'insert_product':
        case 'update_product':
            if (isset($_POST['edit_x']) || isset($_POST['edit_y'])) {
                $action = 'new_product';
            } else {

                if (isset($_GET['pID'])) $products_id = trim($_GET['pID']);

                $products_date_available = trim($_POST['products_date_available']);
                $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';
                if (empty($_POST['products_internal_sku'])){
                    $sku='null';
                }
                $sql_data_array = array('products_quantity' => trim($_POST['products_quantity']),
                    'products_model' => trim($_POST['products_model']),
                    'products_price' => trim($_POST['products_price']),
                    'products_date_available' => $products_date_available,
                    'products_weight' => trim($_POST['products_weight']),
                    'lable_1' => trim($_POST['lable_1']),
                    'lable_2' => trim($_POST['lable_2']),
                    'lable_3' => trim($_POST['lable_3']),
                    'products_status' => trim($_POST['products_status']),
                    'products_to_xml' => trim($_POST['products_to_xml']),
                    'products_tax_class_id' => trim($_POST['products_tax_class_id']),
                    'products_quantity_order_min' => trim($_POST['products_quantity_order_min']),
                    'products_url_rozetka' => trim($_POST['products_url_rozetka']),
                    'products_barcode' => trim($_POST['products_barcode']),
                    'products_quantity_order_units' => trim($_POST['products_quantity_order_units']),
                    'products_sort_order' => trim($_POST['products_sort_order']),
                    'manufacturers_id' => trim($_POST['manufacturers_id']),
                    'license_id' => trim($_POST['license_id']),
                    'products_sort_order' => trim($_POST['products_sort_order']));
                if (!empty($_POST['products_internal_sku'])) {
                    $sku_array = array (
                        'products_internal_sku' => trim($_POST['products_internal_sku'])
                    );
                    $sql_data_array=array_merge($sql_data_array,$sku_array);
                }

                $prices_num = tep_xppp_getpricesnum();
                for ($i = 2; $i <= $prices_num; $i++) {
                    if (filter_input(INPUT_POST, 'checkbox_products_price_' . $i) != "true")
                        $sql_data_array['products_price_' . $i] = 'null';
                    else
                        $sql_data_array['products_price_' . $i] = trim($_POST['products_price_' . $i]);
                }

                if ($action == 'insert_product') {

                    $insert_sql_data = array('products_date_added' => 'now()');
                    $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                    tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
                    $products_id = tep_db_insert_id();

                    tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$current_category_id . "')");

                } elseif ($action == 'update_product') {

                    $update_sql_data = array('products_last_modified' => 'now()');
                    $sql_data_array = array_merge($sql_data_array, $update_sql_data);

                    tep_db_perform(TABLE_PRODUCTS, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "'");
                }

                $languages = tep_get_languages();
                for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                    $language_id = $languages[$i]['id'];

                    $sql_data_array = array('products_name' => trim($_POST['products_name'][$language_id]),
                        'products_info' => trim($_POST['products_info'][$language_id]),
                        'products_description' => trim($_POST['products_description'][$language_id]),
                        'products_url' => ($_POST['products_url'][$language_id] != '') ? trim($_POST['products_url'][$language_id]) : trim($_POST['products_name'][$language_id]),
                        'products_head_title_tag' => trim($_POST['products_head_title_tag'][$language_id]),
                        'products_head_desc_tag' => trim($_POST['products_head_desc_tag'][$language_id]),
                        'products_head_keywords_tag' => trim($_POST['products_head_keywords_tag'][$language_id]));

                    if ($action == 'insert_product') {
                        $insert_sql_data = array('products_id' => $products_id,
                            'language_id' => $language_id);

                        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                        tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
                    } elseif ($action == 'update_product') {
                        tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and language_id = '" . (int)$language_id . "'");
                    }
                }

                /** AJAX Attribute Manager  **/
                require_once('attributeManager/includes/attributeManagerUpdateAtomic.inc.php');
                /** AJAX Attribute Manager  end **/

                tep_redirect(tep_href_link(FILENAME_PRODUCTS, 'cPath=' . $cPath . '&pID=' . $products_id . '&action=new_product'));
            }
            break;
    }
}

// check if the catalog image directory exists
if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
} else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
}
?>

    <!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
        <script language="javascript" src="includes/menu.js"></script>
        <script language="javascript" src="includes/general.js"></script>
        <script type="text/javascript" src="../includes/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="../includes/ckfinder/ckfinder.js"></script>
        <script type="text/javascript" src="../includes/javascript/lib/jquery-1.7.1.min.js"></script>
        <link type="text/css" href="../includes/javascript/ui/css/smoothness/jquery-ui-1.7.2.custom.css"
              rel="stylesheet"/>

        <script type="text/javascript" src="includes/javascript/jquery-ui-1.9.2.custom.min.js"></script>
        <script type="text/javascript">
            $(function () {
                $('#tabs,.tabs').tabs({fx: {opacity: 'toggle', duration: 'fast'}});
            });
        </script>

        <script src="includes/javascript/imagecrop/jquery.Jcrop.min.js"></script>
        <link rel="stylesheet" href="includes/javascript/imagecrop/jquery.Jcrop.min.css" type="text/css"/>
        <style>
            .ui-tabs .ui-tabs-panel {
                box-shadow: 0 1px 5px -2px #000;
            }

            .ui-tabs .ui-tabs-nav .ui-tabs-anchor {
                text-align: center;
            }

            .attr_img {
                position: relative;
                float: left;
                border: 1px solid #eee;
                margin: 10px 5px 0 0;
            }

            .attr_del {
                position: absolute;
                right: 0;
                top: 0;
                cursor: pointer;
            }

            .attr_crop {
                cursor: pointer;
            }
        </style>

        <style>
            small {
                color: #999;
            }

            .ui-widget-content {
                border: 0px solid #e7e7e7;
                background: #fff url(images/ui-bg_flat_75_ffffff_40x100.png) 50% 50% repeat-x;
                color: #222222;
            }
        </style>
        <!-- AJAX Attribute Manager  -->
        <?php require_once('attributeManager/includes/attributeManagerHeader.inc.php') ?>
        <!-- AJAX Attribute Manager  end -->
    </head>
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0"
          bgcolor="#FFFFFF" onload="goOnLoad();">

    <div id="spiffycalendar" class="text"></div>
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_smend //-->

    <!-- body //-->
    <table border="0" width="100%" cellspacing="2" cellpadding="2">
        <tr>
            <td width="100%" valign="top">
                <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <?php
                    if ($action == 'new_product') {
                        $parameters = array('products_name' => '',
                            'products_info' => '',
                            'products_description' => '',
                            'products_url' => '',
                            'products_id' => '',
                            'products_quantity' => '',
                            'products_model' => '',
                            'products_price' => '',
                            'products_weight' => '',
                            'lable_1' => '',
                            'lable_2' => '',
                            'lable_3' => '',
                            'products_date_added' => '',
                            'products_last_modified' => '',
                            'products_date_available' => '',
                            'products_status' => '',
                            'products_to_xml' => '',
                            'products_sort_order' => '',
                            'products_tax_class_id' => '',
                            'manufacturers_id' => '',
                            'license_id' => '');

                        $prices_num = tep_xppp_getpricesnum();
                        for ($i = 2; $i <= $prices_num; $i++) {
                            $parameters['products_price_' . $i] = '';
                        }

                        $pInfo = new objectInfo($parameters);

                        if (isset($_GET['pID']) && empty($_POST)) {
                            $products_price_list = tep_xppp_getpricelist("p");
                            $product_query = tep_db_query("select pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_desc_tag, pd.products_head_keywords_tag, pd.products_url, p.products_id, p.products_quantity, p.kiev_stock, p.our_stock, p.mankovka_stock, p.products_model, " . $products_price_list . ", p.products_weight, p.lable_1, p.lable_2, p.lable_3, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_to_xml, p.products_tax_class_id, p.products_quantity_order_min, p.products_url_rozetka, p.products_barcode , p.products_internal_sku, p.products_quantity_order_units, p.products_sort_order, p.manufacturers_id, p.license_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int)$_GET['pID'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
                            $product = tep_db_fetch_array($product_query);

                            $pInfo->objectInfo($product);
                        } elseif (tep_not_null($_POST)) {
                            $pInfo->objectInfo($_POST);
                            $products_name = $_POST['products_name'];
                            $products_info = $_POST['products_info'];
                            $products_description = $_POST['products_description'];
                            $products_url = $_POST['products_url'];
                        }

                        $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
                        $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_name from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
                        while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
                            $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                'text' => $manufacturers['manufacturers_name']);
                        }

                        $languages = tep_get_languages();

                        if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
                        switch ($pInfo->products_status) {
                            case '0':
                                $in_status = false;
                                $out_status = true;
                                break;
                            case '1':
                            default:
                                $in_status = true;
                                $out_status = false;
                        }
                        // лейбы:
                        if (!isset($pInfo->lable_1)) $pInfo->lable_1 = '0';
                        switch ($pInfo->lable_1) {
                            case '0':
                                $in_lable_1 = false;
                                $out_lable_1 = true;
                                break;
                            case '1':
                                $in_lable_1 = true;
                                $out_lable_1 = false;
                                break;
                            default:
                                $in_lable_1 = false;
                                $out_lable_1 = true;
                        }
                        if (!isset($pInfo->lable_2)) $pInfo->lable_2 = '0';
                        switch ($pInfo->lable_2) {
                            case '0':
                                $in_lable_2 = false;
                                $out_lable_2 = true;
                                break;
                            case '1':
                                $in_lable_2 = true;
                                $out_lable_2 = false;
                                break;
                            default:
                                $in_lable_2 = false;
                                $out_lable_2 = true;
                        }
                        if (!isset($pInfo->lable_3)) $pInfo->lable_3 = '0';
                        switch ($pInfo->lable_3) {
                            case '0':
                                $in_lable_3 = false;
                                $out_lable_3 = true;
                                break;
                            case '1':
                                $in_lable_3 = true;
                                $out_lable_3 = false;
                                break;
                            default:
                                $in_lable_3 = false;
                                $out_lable_3 = true;
                        }
                        // лейбы END

                        if (!isset($pInfo->products_to_xml)) $pInfo->products_to_xml = '1';
                        switch ($pInfo->products_to_xml) {
                            case '0':
                                $in_xml = false;
                                $out_xml = true;
                                break;
                            case '1':
                            default:
                                $in_xml = true;
                                $out_xml = false;
                        }

                        $form_action = (isset($_GET['pID'])) ? 'update_product' : 'insert_product';
                        echo tep_draw_form($form_action, FILENAME_PRODUCTS, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');
                        ?>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <tr>
                                <td class="main" style="color:#777;">
                                    <?php echo sprintf(TEXT_NEW_PRODUCT, tep_output_generated_category_path($current_category_id)); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                        <tr>
                                            <td class="main"></td>
                                            <td class="main" align="right">
                                                <?php
                                                if (isset($_GET['pID'])) echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
                                                else echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
                                                echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '">' . tep_image_button('button_back.gif', IMAGE_CANCEL) . '</a>';
                                                ?>
                                            </td>
                                        </tr>
                                    </table>

                                    <div id="tabs">
                                        <ul>
                                            <li><a href="#text"><?php echo TEXTS; ?></a></li>
                                            <li><a href="#images"><?php echo IMAGES; ?></a></li>
                                            <li><a href="#attribs"><?php echo TEXT_ATTRIBUTE_HEAD; ?></a></li>
                                        </ul>
                                        <div id="text">
                                            <div style="float:left;width:100%;margin-right:-300px;">
                                                <div style="margin-right:310px;">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                        <?php
                                                        $product_texts_array = array(array());
                                                        $product_texts_query = tep_db_query("select products_name, products_url, products_info, products_description, products_head_title_tag, products_head_keywords_tag, products_head_desc_tag, language_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$pInfo->products_id . "' order by language_id");
                                                        while ($product_texts = tep_db_fetch_array($product_texts_query)) {
                                                            for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                                                                if ($product_texts['language_id'] == $languages[$i]['id']) {
                                                                    $product_texts_array[$languages[$i]['id']] = array('products_name' => $product_texts['products_name'],
                                                                        'products_url' => $product_texts['products_url'],
                                                                        'products_info' => $product_texts['products_info'],
                                                                        'products_description' => $product_texts['products_description'],
                                                                        'products_head_title_tag' => $product_texts['products_head_title_tag'],
                                                                        'products_head_keywords_tag' => $product_texts['products_head_keywords_tag'],
                                                                        'products_head_desc_tag' => $product_texts['products_head_desc_tag']);
                                                                }
                                                            }
                                                        }
                                                        ?>

                                                        <tr bgcolor="#cef0ff">
                                                            <td class="main"
                                                                width="130"><?php echo TEXT_PRODUCTS_NAME; ?></td>
                                                            <td class="main">
                                                                <div class="tabs">
                                                                    <ul>
                                                                        <?php for ($i = 0; $i < sizeof($languages); $i++) { ?>
                                                                            <li>
                                                                                <a href="#products_name-tabs-<?php echo $i ?>"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], '16') ?>
                                                                                    <br>
                                                                                    <small><?php echo $languages[$i]['name'] ?></small>
                                                                                </a></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                    <?php for ($i = 0; $i < sizeof($languages); $i++) { ?>
                                                                        <div id="products_name-tabs-<?php echo $i ?>">
                                                                            <?php
                                                                            $r_products_name = tep_product_link_input('products_name[' . $languages[$i]['id'] . ']', (isset($products_name[$languages[$i]['id']]) ? stripslashes($products_name[$languages[$i]['id']]) : $product_texts_array[$languages[$i]['id']]['products_name']), 'style="width:40%;"');
                                                                            $r_products_url = tep_product_link_input('products_url[' . $languages[$i]['id'] . ']', (isset($products_url[$languages[$i]['id']]) ? stripslashes($products_url[$languages[$i]['id']]) : $product_texts_array[$languages[$i]['id']]['products_url']), 'style="width:30%;"');
                                                                            ?>
                                                                            <?php echo $r_products_name; ?>
                                                                            Ссылка:
                                                                            <?php echo $r_products_url; ?>
                                                                            <div style="display:none;"><?php if ($i == 0) echo tep_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . tep_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?></div>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>

                                                            </td>
                                                        </tr>


                                                        <tr>
                                                            <td class="main"
                                                                valign="top"><?php echo TEXT_PRODUCTS_INFO . '<br />'; ?>
                                                                <small><?php echo $languages[$i]['name']; ?></small>
                                                            </td>
                                                            <td class="main">
                                                                <div class="tabs">
                                                                    <ul>
                                                                        <?php for ($i = 0; $i < sizeof($languages); $i++) { ?>
                                                                            <li>
                                                                                <a href="#products_info-tabs-<?php echo $i ?>"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], '16') ?>
                                                                                    <br>
                                                                                    <small><?php echo $languages[$i]['name'] ?></small>
                                                                                </a></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                    <?php for ($i = 0; $i < sizeof($languages); $i++) { ?>
                                                                        <div id="products_info-tabs-<?php echo $i ?>">
                                                                            <?php echo tep_draw_textarea_field('products_info[' . $languages[$i]['id'] . ']', 'soft', '105', '3', (isset($products_info[$languages[$i]['id']]) ? $products_info[$languages[$i]['id']] : $product_texts_array[$languages[$i]['id']]['products_info']), 'class="notinymce" style="width:100%;height:80px;"'); ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </td>
                                                        </tr>


                                                        <tr>
                                                            <td class="main"
                                                                valign="top"><?php echo TEXT_PRODUCTS_DESCRIPTION . '<br />'; ?></td>
                                                            <td>
                                                                <div class="tabs">
                                                                    <ul>
                                                                        <?php for ($i = 0; $i < sizeof($languages); $i++) { ?>
                                                                            <li>
                                                                                <a href="#products_description-tabs-<?php echo $i ?>"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], '16') ?>
                                                                                    <br>
                                                                                    <small><?php echo $languages[$i]['name'] ?></small>
                                                                                </a></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                    <?php for ($i = 0; $i < sizeof($languages); $i++) { ?>
                                                                        <div id="products_description-tabs-<?php echo $i ?>">
                                                                            <?php echo tep_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '80', '20', (($products_description[$languages[$i]['id']]) ? stripslashes($products_description[$languages[$i]['id']]) : $product_texts_array[$languages[$i]['id']]['products_description']), 'class="ckeditor"'); ?>
                                                                            <script type="text/javascript">
                                                                                var editor = CKEDITOR.replace('products_description[<?php echo $languages[$i][id]; ?>]', {height: '400px'});
                                                                                CKFinder.setupCKEditor(editor, '../includes/ckfinder/');
                                                                            </script>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                    </table>
                                                </div>
                                            </div>
                                            <div style="float:right;width:300px;">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                    <tr bgcolor="#cef0ff">
                                                        <td class="main"><?php echo ENTRY_PRODUCTS_PRICE ?>:</td>
                                                        <td class="main"><?php echo tep_draw_hidden_field('products_to_xml', $pInfo->products_to_xml);
                                                            echo tep_draw_input_field('products_price', $pInfo->products_price, 'style="width:150px;"'); ?></td>
                                                    </tr>

                                                    <?php
                                                    $prices_num = tep_xppp_getpricesnum();
                                                    for ($i = 2; $i <= $prices_num; $i++) { ?>

                                                        <tr>
                                                            <td class="main"><?php echo "Цена " . $i; ?>&nbsp;<input
                                                                        type="checkbox"
                                                                        name="<?php echo "checkbox_products_price_" . $i; ?>" <?php
                                                                $products_price_X = "products_price_" . $i;
                                                                if ($pInfo->$products_price_X != NULL) echo " checked "; ?>
                                                                        value="true"
                                                                        onClick="if (!<?php echo "products_price_" . $i; ?>.disabled) { <?php echo "products_price_" . $i; ?>.disabled = true;  <?php echo "products_price_" . $i . "_gross"; ?>.disabled = true; } else { <?php echo "products_price_" . $i; ?>.disabled = false;  <?php echo "products_price_" . $i . "_gross"; ?>.disabled = false; } ">
                                                            </td>

                                                            <td class="main"><?php
                                                                $products_price_X = "products_price_" . $i;
                                                                if ($pInfo->$products_price_X == NULL) {
                                                                    echo tep_draw_input_field('products_price_' . $i, $pInfo->$products_price_X, ', disabled');
                                                                } else {
                                                                    echo tep_draw_input_field('products_price_' . $i, $pInfo->$products_price_X, '');
                                                                } ?></td>
                                                        </tr>

                                                    <?php } ?>
                                                    <tr>
                                                        <td class="main"
                                                            width="140"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
                                                        <td class="main"><?php echo tep_draw_input_field('products_model', $pInfo->products_model, 'style="width:150px;"'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
                                                        <td class="main"><?php echo tep_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id, 'style="width:150px;"'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main"><?php echo TEXT_PRODUCTS_LICENSE; ?></td>
                                                        <td class="main"><?php echo tep_draw_pull_down_menu('license_id', $manufacturers_array, $pInfo->license_id, 'style="width:150px;"'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main"><?php echo LABEL_TOP; ?>:</td>
                                                        <td class="main"><?php echo PRODUCTS_ITEM1; ?><?php echo tep_draw_radio_field('lable_1', '1', $in_lable_1); ?>
                                                            &nbsp;<?php echo PRODUCTS_ITEM2; ?><?php echo tep_draw_radio_field('lable_1', '0', $out_lable_1); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main"><?php echo LABEL_NEW; ?>:</td>
                                                        <td class="main"><?php echo PRODUCTS_ITEM1; ?><?php echo tep_draw_radio_field('lable_2', '1', $in_lable_2); ?>
                                                            &nbsp;<?php echo PRODUCTS_ITEM2; ?><?php echo tep_draw_radio_field('lable_2', '0', $out_lable_2); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main"><?php echo LABEL_SALE; ?>:</td>
                                                        <td class="main"><?php echo PRODUCTS_ITEM1; ?><?php echo tep_draw_radio_field('lable_3', '1', $in_lable_3); ?>
                                                            &nbsp;<?php echo PRODUCTS_ITEM2; ?><?php echo tep_draw_radio_field('lable_3', '0', $out_lable_3); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main"><?php echo TABLE_HEADING_ATTRIBUTE_9; ?>:</td>
                                                        <td class="main"><?php echo tep_draw_input_field('products_weight', $pInfo->products_weight, 'style="width:150px;"'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main"><?php echo HEADING_SORT; ?>:</td>
                                                        <td class="main"><?php echo tep_draw_input_field('products_sort_order', $pInfo->products_sort_order, 'style="width:150px;"'); ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
                                                        <td class="main"><?php echo tep_draw_input_field('products_quantity', ($pInfo->products_quantity == '' ? 1 : $pInfo->products_quantity), 'style="width:150px;"') . tep_draw_hidden_field('r_oldquantity', $pInfo->products_quantity); ?></td>
                                                    </tr>
                                                    <?php function qtyCheck($stock)
                                                    {
                                                        $tdColor = 'style="background:#ffc3bf"';
                                                        if ($stock > 3) {
                                                            $tdColor = 'style="background:#deffde"';
                                                        } else if ($stock <= 3 && $stock > 0) {
                                                            $tdColor = 'style="background:#ffefbf"';
                                                        }

                                                        return $tdColor;
                                                    }

                                                    ?>
                                                    <tr>
                                                        <td colspan="2">
                                                            <table>
                                                                <tr <?php echo qtyCheck($pInfo->kiev_stock) ?>>
                                                                    <td width="130" class="main">Склад Киев</td>
                                                                    <td class="main"><?php echo tep_draw_input_field('kiev_stock', ($pInfo->kiev_stock == '' ? 1 : $pInfo->kiev_stock), 'style="width:150px;"') . tep_draw_hidden_field('r_oldquantity', $pInfo->kiev_stock); ?></td>
                                                                </tr>
                                                                <tr <?php echo qtyCheck($pInfo->mankovka_stock) ?>>
                                                                    <td class="main">Склад Маньковка</td>
                                                                    <td class="main"><?php echo tep_draw_input_field('mankovka_stock', ($pInfo->mankovka_stock == '' ? 1 : $pInfo->mankovka_stock), 'style="width:150px;"') . tep_draw_hidden_field('r_oldquantity', $pInfo->mankovka_stock); ?></td>
                                                                </tr>
                                                                <tr <?php echo qtyCheck($pInfo->our_stock) ?>>
                                                                    <td class="main">Наш</td>
                                                                    <td class="main"><?php echo tep_draw_input_field('our_stock', ($pInfo->our_stock == '' ? 1 : $pInfo->our_stock), 'style="width:150px;"') . tep_draw_hidden_field('r_oldquantity', $pInfo->our_stock); ?></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY_FOR_ORDER; ?></td>
                                                        <td class="main"><?php echo tep_draw_input_field('products_quantity_order_min', ($pInfo->products_quantity_order_min == 0 ? 1 : $pInfo->products_quantity_order_min), 'style="width:150px;"'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main"><?php echo TEXT_MIN_QUANTITY_UNITS; ?></td>
                                                        <td class="main"><?php echo tep_draw_input_field('products_quantity_order_units', ($pInfo->products_quantity_order_units == 0 ? 1 : $pInfo->products_quantity_order_units), 'style="width:150px;"'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main"><?php echo TEXT_URL_ROZETKA; ?></td>
                                                        <td class="main"><?php echo tep_draw_input_field('products_url_rozetka', $pInfo->products_url_rozetka, 'style="width:150px;" maxlength="800"'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main"><?php echo TEXT_INTERNAL_SKU; ?></td>
                                                        <td class="main"><?php echo tep_draw_input_field('products_internal_sku', $pInfo->products_internal_sku, 'style="width:150px;" maxlength="80"'); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main"><?php echo TEXT_BARCODE; ?></td>
                                                        <td class="main"><?php echo tep_draw_input_field('products_barcode', $pInfo->products_barcode, 'style="width:150px;" maxlength="80"'); ?></td>
                                                    </tr>
                                                    <?php
                                                    for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
                                                        ?>
                                                        <tr>
                                                            <td colspan="2" valign="top" class="main"><br/>
                                                                <small><?php echo TEXT_PRODUCTS_PAGE_TITLE; ?></small>
                                                                <br/>
                                                                <div class="tabs">
                                                                    <ul>
                                                                        <?php for ($i = 0; $i < sizeof($languages); $i++) { ?>
                                                                            <li>
                                                                                <a href="#products_head_title_tag-tabs-<?php echo $i ?>"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], '16') ?>
                                                                                    <br>
                                                                                    <small><?php echo $languages[$i]['name'] ?></small>
                                                                                </a></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                    <?php for ($i = 0; $i < sizeof($languages); $i++) { ?>
                                                                        <div id="products_head_title_tag-tabs-<?php echo $i ?>">
                                                                            <?php echo tep_draw_textarea_field('products_head_title_tag[' . $languages[$i]['id'] . ']', 'soft', '105', '1', (isset($products_head_title_tag[$languages[$i]['id']]) ? $products_head_title_tag[$languages[$i]['id']] : $product_texts_array[$languages[$i]['id']]['products_head_title_tag']), 'style="width:100%;height:60px;"'); ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" valign="top" class="main">
                                                                <small><?php echo TEXT_PRODUCTS_HEADER_DESCRIPTION; ?></small>
                                                                <br/>
                                                                <div class="tabs">
                                                                    <ul>
                                                                        <?php for ($i = 0; $i < sizeof($languages); $i++) { ?>
                                                                            <li>
                                                                                <a href="#products_head_desc_tag-tabs-<?php echo $i ?>"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], '16') ?>
                                                                                    <br>
                                                                                    <small><?php echo $languages[$i]['name'] ?></small>
                                                                                </a></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                    <?php for ($i = 0; $i < sizeof($languages); $i++) { ?>
                                                                        <div id="products_head_desc_tag-tabs-<?php echo $i ?>">
                                                                            <?php echo tep_draw_textarea_field('products_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '105', '1', (isset($products_head_desc_tag[$languages[$i]['id']]) ? $products_head_desc_tag[$languages[$i]['id']] : $product_texts_array[$languages[$i]['id']]['products_head_desc_tag']), 'style="width:100%;height:100px;"'); ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" valign="top" class="main">
                                                                <small><?php echo TEXT_PRODUCTS_KEYWORDS; ?></small>
                                                                <br/>
                                                                <div class="tabs">
                                                                    <ul>
                                                                        <?php for ($i = 0; $i < sizeof($languages); $i++) { ?>
                                                                            <li>
                                                                                <a href="#products_head_keywords_tag-tabs-<?php echo $i ?>"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'], '16') ?>
                                                                                    <br>
                                                                                    <small><?php echo $languages[$i]['name'] ?></small>
                                                                                </a></li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                    <?php for ($i = 0; $i < sizeof($languages); $i++) { ?>
                                                                        <div id="products_head_keywords_tag-tabs-<?php echo $i ?>">
                                                                            <?php echo tep_draw_textarea_field('products_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '105', '1', (isset($products_head_keywords_tag[$languages[$i]['id']]) ? $products_head_keywords_tag[$languages[$i]['id']] : $product_texts_array[$languages[$i]['id']]['products_head_keywords_tag']), 'style="width:100%;height:60px;"'); ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </table>
                                            </div>
                                            <div style="clear:both;"></div>
                                        </div>
                                        <div id="images">
                                            <?php if (isset($_GET['pID'])) { ?>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                    <tr>
                                                        <td>
                                                            <!-- html5uploader -->

                                                            <link rel="stylesheet"
                                                                  href="html5uploader/assets/css/styles.css"/>
                                                            <script src="html5uploader/assets/js/jquery.getimagedata.min.js"></script>
                                                            <script src="html5uploader/assets/js/canvas-toBlob.min.js"></script>
                                                            <script src="html5uploader/assets/js/jquery.filedrop.js"></script>
                                                            <script src="html5uploader/assets/js/script.js"></script>
                                                            <?php echo TEXT_PRODUCTS_IMAGES; ?> <input type="text"
                                                                                                       name="img_width"
                                                                                                       value="<?php echo SMALL_IMAGE_WIDTH; ?>"
                                                                                                       style="width:40px;font-size:12px;">
                                                            x <input type="text" name="img_height"
                                                                     value="<?php echo SMALL_IMAGE_HEIGHT; ?>"
                                                                     style="width:40px;font-size:12px;"> px
                                                            <div id="dropbox_first" class="dropbox">
                                                                <span class="message"><i><?php echo DROP_HERE; ?></i></span>
                                                            </div>
                                                            <?php
                                                            $color_id = 1;

                                                            $attributes_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$_GET['pID'] . "' and pa.options_id = '" . (int)$color_id . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");
                                                            while ($attributes = tep_db_fetch_array($attributes_query)) {

                                                                echo PRODUCTS_ITEM3 . ': <b>' . $attributes['products_options_values_name'] . '</b>:
          <div id="dropbox_' . $attributes['products_options_values_id'] . '" class="dropbox">
            <span class="message"><i>Перетяните сюда картинки</i></span>
          </div>';
                                                            }

                                                            ?>


                                                            <input type="hidden" name="pidd" id="pidd" value="<?php echo $pInfo->products_id; ?>">
                                                            <!-- html5uploader -->

                                                            <input type="hidden" id="crop_x" name="crop_x"/>
                                                            <input type="hidden" id="crop_y" name="crop_y"/>
                                                            <input type="hidden" id="crop_w" name="crop_w"/>
                                                            <input type="hidden" id="crop_h" name="crop_h"/>

                                                            <span id="crop_button"
                                                                  style="display:none;float:left;font-weight:bold;cursor:pointer;color:#fff;font-size:17px;background:#339933;border-radius:5px;padding:5px;margin:15px 0;">Вырезать!</span>
                                                            <div style="clear:both"></div>
                                                            <div id="crop_area"></div>

                                                        </td>
                                                    </tr>
                                                </table>
                                            <?php } else echo '<img border="0" src="images/icon_info.gif">Сохраните товар перед добавлением картинок.'; ?>
                                        </div>
                                        <div id="attribs">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                <!-- AJAX Attribute Manager  -->
                                                <?php require_once('attributeManager/includes/attributeManagerPlaceHolder.inc.php') ?>
                                                <!-- AJAX Attribute Manager end -->
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="main" align="right">
                                    <?php echo tep_draw_hidden_field('products_date_added', (tep_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))); ?>
                                    <?php
                                    if (isset($_GET['pID'])) echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
                                    else echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
                                    echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '')) . '">' . tep_image_button('button_back.gif', IMAGE_CANCEL) . '</a>';
                                    ?>
                                </td>
                            </tr>
                        </table>
                        </form>
                        <div id="custom_form_wrapper">
                            <form style="display:none;font-size: 16px;" id="custom_form"
                                  action="/admin/html5uploader/post_file.php?act=custom_update&img_w=150&img_h=150&pid=<?php echo $_GET['pID']; ?>&opid=first"
                                  method="post" enctype="multipart/form-data">
                                <?php echo IMAGE_UPLOAD; ?>: <input name="pic[]" type="file" multiple/><input
                                        type="submit" value="<?php echo IMAGE_UPLOAD; ?>"/>
                            </form>
                        </div>
                        <?php
                    }
                    ?>
                    </td>
                    <!-- body_text_smend //-->
                    </tr>
                </table>
    </body>
    </html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
