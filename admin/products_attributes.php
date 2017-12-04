<?php
/*
  $Id: products_attributes.php,v 1.52 2003/07/10 20:46:01 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include_once __DIR__ . '/includes/application_top.php';
  $languages = tep_get_languages();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (tep_not_null($action)) {
    $page_info = '';
    if (isset($_GET['option_page'])) $page_info .= 'option_page=' . $_GET['option_page'] . '&';
    if (isset($_GET['value_page'])) $page_info .= 'value_page=' . $_GET['value_page'] . '&';
    if (isset($_GET['attribute_page'])) $page_info .= 'attribute_page=' . $_GET['attribute_page'] . '&';
    if (isset($_GET['current_option'])) $page_info .= 'current_option=' . $_GET['current_option'] . '&';
    if (tep_not_null($page_info)) {
      $page_info = substr($page_info, 0, -1);
    }

    switch ($action) {
      case 'add_product_options':
        $products_options_id = tep_db_prepare_input($_POST['products_options_id']);
        $option_name_array = $_POST['option_name'];
        $option_type = $_POST['option_type'];	//clr 030714 update to add option type to products_option
		  $option_length = $_POST['option_length'];	//clr 030714 update to add option length to products_option
          $option_or_condition = filter_input(INPUT_POST, 'option_or_condition', FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
        $option_name = tep_db_prepare_input($option_name_array[$languages[$i]['id']]);
		  $option_comment = $_POST['option_comment'];
   	  $option_sort_order = $_POST['option_sort_order'];	//clr 030714 update to add option comment to products_option

          tep_db_query("insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, products_options_name, language_id, products_options_type, products_options_length, products_options_comment, products_options_sort_order, products_options_or_condition) values ('" . (int)$products_options_id . "', '" . tep_db_input($option_name) . "', '" . (int)$languages[$i]['id'] . "', '" . $option_type . "', '" . $option_length . "', '" . $option_comment[$languages[$i]['id']]  . "', '" . $option_sort_order[$languages[$i]['id']] . "', {$option_or_condition})");
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_product_attributes':
        $products_id = tep_db_prepare_input($_POST['products_id']);
        $options_id = tep_db_prepare_input($_POST['options_id']);
        $values_id = tep_db_prepare_input($_POST['values_id']);
        $value_price = tep_db_prepare_input($_POST['value_price']);
        $price_prefix = tep_db_prepare_input($_POST['price_prefix']);

// BOF: WebMakers.com Added: Attribute Sorter
// OLD        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $_POST['products_id'] . "', '" . $_POST['options_id'] . "', '" . $_POST['values_id'] . "', '" . $_POST['value_price'] . "', '" . $_POST['price_prefix'] . "', '" . $_POST['sort_order'] . "', '" . $_POST['product_attributes_one_time']  . "')");
        tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES . " values ('', '" . $_POST['products_id'] . "', '" . $_POST['options_id'] . "', '" . $_POST['values_id'] . "', '" . $_POST['value_price'] . "', '" . $_POST['price_prefix'] . "', '" . $_POST['sort_order'] . "', '" . $_POST['product_attributes_one_time'] . "', '" . $_POST['products_attributes_weight'] . "', '" . $_POST['products_attributes_weight_prefix'] . "', '" . $_POST['products_attributes_units'] . "', '" . $_POST['products_attributes_units_price'] . "' )");
// EOF: WebMakers.com Added: Attribute Sorter
        if (DOWNLOAD_ENABLED == 'true') {
          $products_attributes_id = tep_db_insert_id();

          $products_attributes_filename = tep_db_prepare_input($_POST['products_attributes_filename']);
          $products_attributes_maxdays = tep_db_prepare_input($_POST['products_attributes_maxdays']);
          $products_attributes_maxcount = tep_db_prepare_input($_POST['products_attributes_maxcount']);

          if (tep_not_null($products_attributes_filename)) {
            tep_db_query("insert into " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " values (" . (int)$products_attributes_id . ", '" . tep_db_input($products_attributes_filename) . "', '" . tep_db_input($products_attributes_maxdays) . "', '" . tep_db_input($products_attributes_maxcount) . "')");
          }
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_option_name':
        $option_name_array = $_POST['option_name'];
	    $option_type = $_POST['option_type'];	//clr 030714 update to add option type to products_option
	    $option_length = $_POST['option_length'];	//clr 030714 update to add option length to products_option
        $option_id = tep_db_prepare_input($_POST['option_id']);
        $option_or_condition = filter_input(INPUT_POST, 'option_or_condition', FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

        for ($i=0, $n=sizeof($languages); $i<$n; $i ++) {
          $option_name = tep_db_prepare_input($option_name_array[$languages[$i]['id']]);
   		 $option_comment = $_POST['option_comment'];
   		 $option_sort_order = $_POST['option_sort_order'];

          tep_db_query("update " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . tep_db_input($option_name) . "', products_options_sort_order = '" . $option_sort_order[$languages[$i]['id']] . "', products_options_type = '" . $option_type . "', products_options_length = '" . $option_length . "', products_options_comment = '" . $option_comment[$languages[$i]['id']] . "', products_options_or_condition = {$option_or_condition} where products_options_id = '" . (int)$option_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
        }

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'add_product_option_values':
      case 'update_value':
            $value_id = filter_input(INPUT_POST, 'value_id', FILTER_VALIDATE_INT, array('min_range' => 1));
            $value_sort = empty($_POST['value_sort']) ? 0 : intval($_POST['value_sort']);
            $option_id = filter_input(INPUT_POST, 'option_id', FILTER_VALIDATE_INT, array('min_range' => 1));
            if($value_id && $option_id) {
                // Обновить значение опции
                $query_string = "INSERT INTO products_options_values (products_options_values_id, "
                        . "language_id, products_options_values_name, products_options_values_sort_order, "
                        . "products_options_values_extra_data) VALUES ";

                for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
                    $query_string .= ($i ? ', ' : '') . "({$value_id}, {$languages[$i]['id']}, "
                    . "'" . (empty($_POST['value_name'][$languages[$i]['id']]) ? '' : tep_db_input($_POST['value_name'][$languages[$i]['id']])) . "', "
                    . (empty($value_sort) ? '0' : $value_sort) . ", "
                    . "'" . (empty($_POST['extra_data'][$languages[$i]['id']]) ? '' : tep_db_input($_POST['extra_data'][$languages[$i]['id']])) . "') ";
                }

                $query_string .= "ON DUPLICATE KEY UPDATE products_options_values_name = VALUES(products_options_values_name), "
                        . "products_options_values_sort_order = VALUES(products_options_values_sort_order), "
                        . "products_options_values_extra_data = VALUES(products_options_values_extra_data)";
                tep_db_query($query_string);
                
                // Обновить взаимосвязь значения опции и связанной с ней опции
                tep_db_query("DELETE FROM products_options_values_to_products_options WHERE products_options_values_id = {$value_id}");
                tep_db_query("INSERT INTO products_options_values_to_products_options SET products_options_id = {$option_id}, products_options_values_id = {$value_id}");
            }
            tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'update_product_attribute':
        $products_id = tep_db_prepare_input($_POST['products_id']);
        $options_id = tep_db_prepare_input($_POST['options_id']);
        $values_id = tep_db_prepare_input($_POST['values_id']);
        $value_price = tep_db_prepare_input($_POST['value_price']);
        $price_prefix = tep_db_prepare_input($_POST['price_prefix']);
        $attribute_id = tep_db_prepare_input($_POST['attribute_id']);

// BOF: WebMakers.com Added: Attribute Sorter
// OLD  tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . $_POST['products_id'] . "', options_id = '" . $_POST['options_id'] . "', options_values_id = '" . $_POST['values_id'] . "', options_values_price = '" . $_POST['value_price'] . "', price_prefix = '" . $_POST['price_prefix'] . "',  products_options_sort_order = '" . $_POST['sort_order'] . "',  product_attributes_one_time = '" . $_POST['product_attributes_one_time'] . "' where products_attributes_id = '" . $_POST['attribute_id'] . "'");
        tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES . " set products_id = '" . $_POST['products_id'] . "', options_id = '" . $_POST['options_id'] . "', options_values_id = '" . $_POST['values_id'] . "', options_values_price = '" . $_POST['value_price'] . "', price_prefix = '" . $_POST['price_prefix'] . "', products_options_sort_order = '" . $_POST['sort_order'] . "', product_attributes_one_time = '" . $_POST['product_attributes_one_time'] . "', products_attributes_weight = '" . $_POST['products_attributes_weight'] . "', products_attributes_weight_prefix = '" . $_POST['products_attributes_weight_prefix'] . "', products_attributes_units = '" . $_POST['products_attributes_units'] . "', products_attributes_units_price = '" . $_POST['products_attributes_units_price'] . "' where products_attributes_id = '" . $_POST['attribute_id'] . "'");
// EOF: WebMakers.com Added: Attribute Sorter
        if ((DOWNLOAD_ENABLED == 'true') && $_POST['products_attributes_filename'] != '') {
          tep_db_query("update " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . "
                        set products_attributes_filename='" . $_POST['products_attributes_filename'] . "',
                            products_attributes_maxdays='" . $_POST['products_attributes_maxdays'] . "',
                            products_attributes_maxcount='" . $_POST['products_attributes_maxcount'] . "'
                        where products_attributes_id = '" . $_POST['attribute_id'] . "'");
        }
        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_option':
        $option_id = tep_db_prepare_input($_GET['option_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$option_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_value':
        $value_id = tep_db_prepare_input($_GET['value_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$value_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$value_id . "'");
        tep_db_query("delete from " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " where products_options_values_id = '" . (int)$value_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
      case 'delete_attribute':
        $attribute_id = tep_db_prepare_input($_GET['attribute_id']);

        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_attributes_id = '" . (int)$attribute_id . "'");

// added for DOWNLOAD_ENABLED. Always try to remove attributes, even if downloads are no longer enabled
        tep_db_query("delete from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id = '" . (int)$attribute_id . "'");

        tep_redirect(tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, $page_info));
        break;
    }
  }

//CLR 030312 add function to draw pulldown list of option types
// Draw a pulldown for Option Types
function draw_optiontype_pulldown($name, $default = '') {
  $values = array();
  $values[] = array('id' => 0, 'text' => 'Text');
  $values[] = array('id' => 1, 'text' => 'Select');
  $values[] = array('id' => 2, 'text' => 'Radio');
  $values[] = array('id' => 3, 'text' => 'Checkbox');
  $values[] = array('id' => 4, 'text' => 'Textarea');
  $values[] = array('id' => 5, 'text' => 'Filter');
  $values[] = array('id' => 6, 'text' => 'Size');
  $values[] = array('id' => 7, 'text' => 'Color');
  return tep_draw_pull_down_menu($name, $values, $default);
}

//CLR 030312 add function to translate type_id to name
// Translate option_type_values to english string
function translate_type_to_name($opt_type) {
  if ($opt_type == 0) return 'Text';
  if ($opt_type == 1) return 'Select';
  if ($opt_type == 2) return 'Radio';
  if ($opt_type == 3) return 'Checkbox';
  if ($opt_type == 4) return 'Textarea';
  if ($opt_type == 5) return 'Filter';
  if ($opt_type == 6) return 'Size';
  if ($opt_type == 7) return 'Color';
  return 'Error ' . $opt_type;
}

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
    <script type="text/javascript" src="../includes/javascript/lib/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="ajaxupload.js"></script>
    <script type="text/javascript" src="script.js"></script>
		<script type="text/javascript">
 // функция для загрузки доп. картинок для цветов
 function go_upload3(getattrid){
     $.get('towork_attrib_colors.php', {attr:getattrid,act:'read'}, function(obj) {
       if(obj!='') $("#files3").html('<div class=attr_img><img id='+obj+'self src=../images/thumb'+obj+' /><img class=attr_del alt=удалить name='+obj+' src=attributeManager/images/icon_delete.png onclick={jsondel3(this,this.name,'+getattrid+');} /></div>');
     });
 }
		</script>
		<style>
.attr_img {
  position:relative;
  float:left;
  border:1px solid #eee;
  margin:0 5px 0 10px;
}
.attr_img img {
  height:20px;
}

.attr_del {
  position:absolute;
  right:-20px;
  top:0;
  cursor:pointer;
}
.attr_crop {
/*
  position:absolute;
  right:0;
  top:17px; */
  cursor:pointer;
}
		</style>
<script language="javascript"><!--
function go_option() {
  if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
    location = "<?php echo tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_page=' . ($_GET['option_page'] ? $_GET['option_page'] : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
  }
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_smend //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_smend //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
<!-- options and values//-->
      <tr>
        <td width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- options //-->
<?php
  if ($action == 'delete_product_option') { // delete product option
    $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$_GET['option_id'] . "' and language_id = '" . (int)$languages_id . "'");
    $options_values = tep_db_fetch_array($options);
?>
              <tr>
                <td class="pageHeading">&nbsp;<?php echo $options_values['products_options_name']; ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
    $products = tep_db_query("select p.products_id, pd.products_name, pov.products_options_values_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pov.language_id = '" . (int)$languages_id . "' and pd.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_id='" . (int)$_GET['option_id'] . "' and pov.products_options_values_id = pa.options_values_id order by pd.products_name");
    if (tep_db_num_rows($products)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
      $rows = 0;
      while ($products_values = tep_db_fetch_array($products)) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_options_values_name']; ?>&nbsp;</td>
                  </tr>
<?php
      }
?>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td colspan="3" class="main"><br><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="3" class="main"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($_GET['value_page']) ? 'value_page=' . $_GET['value_page'] . '&' : '') . (isset($_GET['attribute_page']) ? 'attribute_page=' . $_GET['attribute_page'] : ''), 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option&option_id=' . $_GET['option_id'], 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($_GET['order_by']) ? 'order_by=' . $_GET['order_by'] . '&' : '') . (isset($_GET['page']) ? 'page=' . $_GET['page'] : ''), 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    }
?>
                </table></td>
              </tr>
<?php
  } else {
    if (isset($_GET['option_order_by'])) {
      $option_order_by = $_GET['option_order_by'];
    } else {
      $option_order_by = 'products_options_id';
    }
?>
              <tr>
                <td colspan="2" class="pageHeading">&nbsp;<?php echo HEADING_TITLE_OPT; ?>&nbsp;</td>
                <td align="right"><br><form name="option_order_by" action="<?php echo FILENAME_PRODUCTS_ATTRIBUTES; ?>"><select name="selected" onChange="go_option()"><option value="products_options_id"<?php if ($option_order_by == 'products_options_id') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_ID; ?></option><option value="products_options_name"<?php if ($option_order_by == 'products_options_name') { echo ' SELECTED'; } ?>><?php echo TEXT_OPTION_NAME; ?></option></select></form></td>
              </tr>
              <tr>
                <td colspan="3" class="smallText">
<?php
    $per_page = 50;
 //   $per_page = MAX_ROW_LISTS_OPTIONS;
    $options = "select * from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by " . $option_order_by;
    if (!isset($option_page)) {
      $option_page = 1;
    }
    $prev_option_page = $option_page - 1;
    $next_option_page = $option_page + 1;

    $option_query = tep_db_query($options);

    $option_page_start = ($per_page * $option_page) - $per_page;
    $num_rows = tep_db_num_rows($option_query);

    if ($num_rows <= $per_page) {
      $num_pages = 1;
    } else if (($num_rows % $per_page) == 0) {
      $num_pages = ($num_rows / $per_page);
    } else {
      $num_pages = ($num_rows / $per_page) + 1;
    }
    $num_pages = (int) $num_pages;

    $options = $options . " LIMIT $option_page_start, $per_page";

    // Previous
    if ($prev_value_page)  {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_order_by=' . $option_order_by . '&value_page=' . $prev_value_page.'&current_option='.$_GET['current_option']) . '"> &lt;&lt; </a> | ';
    }

    for ($i = 1; $i <= $num_pages; $i++) {
      if ($i != $value_page) {
         echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($option_order_by) ? 'option_order_by=' . $option_order_by . '&' : '') . 'value_page=' . $i.'&current_option='.$_GET['current_option']) . '">' . $i . '</a> | ';
      } else {
         echo '<b><font color=red>' . $i . '</font></b> | ';
      }
    }

    // Next
    if ($value_page != $num_pages) {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($option_order_by) ? 'option_order_by=' . $option_order_by . '&' : '') . 'value_page=' . $next_value_page.'&current_option='.$_GET['current_option']) . '"> &gt;&gt;</a> ';
    }
//CLR 030212 - Add column for option type
?>
                </td>
              </tr>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
				<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_TYPE; ?>&nbsp;</td>	<!-- CLR 030212 - Add column for option type //-->
    			<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_LENGTH; ?>&nbsp;</td>	<!-- CLR 030212 - Add column for option length //-->
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_COMMENT; ?>&nbsp;</td>	<!-- CLR 030212 - Add column for option comment //-->
                <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo TABLE_HEADING_OPTION_SORT_ORDER; ?>&nbsp;</td>
                <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    $next_id = 1;
    $rows = 0;
    $options = tep_db_query($options);
    while ($options_values = tep_db_fetch_array($options)) {
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if (($action == 'update_option') && ($_GET['option_id'] == $options_values['products_options_id'])) {
        echo '<form name="option" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_name', 'NONSSL') . '" method="post">';
        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
// WebMakers.com Added: Product Options Sort Order
          $option_name = tep_db_query("select products_options_name, products_options_sort_order, products_options_length, products_options_comment, products_options_or_condition from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . $options_values['products_options_id'] . "' and language_id = '" . $languages[$i]['id'] . "'");
          $option_name = tep_db_fetch_array($option_name);
 		  $inputs .= TABLE_HEADING_OPT_NAME . ' (' . $languages[$i]['code'] . '):&nbsp;<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="32" value="' . $option_name['products_options_name'] . '"><br>' . TABLE_HEADING_OPT_COMMENT . ':<br> <input type="text" name="option_comment[' . $languages[$i]['id'] . ']" size="32" value="' . $option_name['products_options_comment'] . '"><br>' . TEXT_OPTION_SORT_ORDER . ' <input type="text" name="option_sort_order[' . $languages[$i]['id'] . ']" size="3" value="' . $option_name['products_options_sort_order'] . '"><br>';
        }
//CLR 030212 - Add column for option type
?>
                <td align="center" class="smallText">&nbsp;<?php echo $options_values['products_options_id']; ?><input type="hidden" name="option_id" value="<?php echo $options_values['products_options_id']; ?>">&nbsp;</td>
				<td class="smallText" colspan="2"><?php echo $inputs; ?></td>
				<td class="smallText"><?php echo TABLE_HEADING_OPT_LENGTH . ' <input type="text" name="option_length" size="4" value="' . $option_name['products_options_length'] . '">'; ?></td>	<!-- CLR 030212 - Add column for option length //-->
				<td class="smallText"><?php echo TABLE_HEADING_OPT_TYPE . '<br>' . draw_optiontype_pulldown('option_type', $options_values['products_options_type']); ?></td>	<!-- CLR 030212 - Add column for option type //-->
                <td class="smallText">
                    <?php echo TABLE_HEADING_OPT_CONDITION; ?><br>
                    <input
                        type="radio"
                        name="option_or_condition"
                        value="0"
                        <?php if(!$option_name['products_options_or_condition']) : ?>checked<?php endif; ?>
                        id="option_or_condition_no"
                        title="<?php echo TEXT_OPT_CONDITION_AND; ?>"
                        >
                    <label for="option_or_condition_no"><?php echo TEXT_OPT_CONDITION_AND; ?></label><br>
                    <input
                        type="radio"
                        name="option_or_condition"
                        value="1"
                        <?php if($option_name['products_options_or_condition']) : ?>checked<?php endif; ?>
                        id="option_or_condition_yes"
                        title="<?php echo TEXT_OPT_CONDITION_OR; ?>"
                        >
                    <label for="option_or_condition_yes"><?php echo TEXT_OPT_CONDITION_OR; ?></label>
                </td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
        echo '</form>' . "\n";
      } else {
//CLR 030212 - Add column for option type
// WebMakers.com Added: Product Options Sort Order
?>
                <td align="center" class="smallText">&nbsp;<?php echo $options_values["products_options_id"]; ?>&nbsp;</td>
                <td class="smallText">&nbsp;<a href="?current_option=<?php echo $options_values["products_options_id"];?>#yakor"><u><?php echo $options_values["products_options_name"]; ?></u></a>&nbsp;</td>
                <td class="smallText">&nbsp;<?php echo translate_type_to_name($options_values["products_options_type"]); ?>&nbsp;</td> <!-- CLR 030212 - Add column for option type //-->
				<td class="smallText">&nbsp;<?php echo $options_values["products_options_length"]; ?>&nbsp;</td>	<!-- CLR 030212 - Add column for option length //-->
				<td class="smallText">&nbsp;<?php echo $options_values["products_options_comment"]; ?>&nbsp;</td>	<!-- CLR 030212 - Add column for option comment //-->
                <td class="smallText" align="right">&nbsp;<?php echo $options_values["products_options_sort_order"]; ?>&nbsp;</td>
                <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option&option_id=' . $options_values['products_options_id'] . '&option_order_by=' . $option_order_by . '&option_page=' . $option_page, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_edit.gif', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_product_option&option_id=' . $options_values['products_options_id'], 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
      }
?>
              </tr>
<?php
      $max_options_id_query = tep_db_query("select max(products_options_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS);
      $max_options_id_values = tep_db_fetch_array($max_options_id_query);
      $next_id = $max_options_id_values['next_id'];
    }
?>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    if ($action != 'update_option') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
// WebMakers.com Added: Product Options Sort Order
      echo '<form name="options" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_options&option_page=' . $option_page, 'NONSSL') . '" method="post"><input type="hidden" name="products_options_id" value="' . $next_id . '">';
      $inputs = '';
      for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
		$inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="option_name[' . $languages[$i]['id'] . ']" size="32"><br>' . TABLE_HEADING_OPT_COMMENT . ' <input type="text" name="option_comment[' . $languages[$i]['id'] . ']" size="32"><br>' . TEXT_OPTION_SORT_ORDER . '  <input type="text" name="option_sort_order[' . $languages[$i]['id'] . ']" size="3">&nbsp;<br>';
      }
//CLR 030212 - Add column for option type
?>
                <td align="center" class="smallText">&nbsp;<?php echo $next_id; ?>&nbsp;</td>
                <td class="smallText" colspan="2"><?php echo $inputs; ?></td>
		<td class="smallText"><?php echo TABLE_HEADING_OPT_LENGTH . ' <input type="text" name="option_length" size="4" value="' . $option_name['products_options_length'] . '">'; ?></td>	<!-- CLR 030212 - Add column for option length //-->
		<td class="smallText"><?php echo TABLE_HEADING_OPT_TYPE . ' ' . draw_optiontype_pulldown('option_type'); ?></td>	<!-- CLR 030212 - Add column for option type //-->
        <td class="smallText">
            <?php echo TABLE_HEADING_OPT_CONDITION; ?><br>
            <input
                type="radio"
                name="option_or_condition"
                value="0"
                checked
                id="option_or_condition_no"
                title="<?php echo TEXT_OPT_CONDITION_AND; ?>"
                >
            <label for="option_or_condition_no"><?php echo TEXT_OPT_CONDITION_AND; ?></label><br>
            <input
                type="radio"
                name="option_or_condition"
                value="1"
                id="option_or_condition_yes"
                title="<?php echo TEXT_OPT_CONDITION_OR; ?>"
                >
            <label for="option_or_condition_yes"><?php echo TEXT_OPT_CONDITION_OR; ?></label>
        </td>
                <td align="center" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    }
  }
?>
            </table></td>
<!-- options eof //-->
          </tr>
          </table>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
<!-- value //-->
<?php
  if ($action == 'delete_option_value') { // delete product option value
    $values = tep_db_query("select distinct products_options_values_id, products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$_GET['value_id'] . "' and language_id = '" . (int)$languages_id . "'");
    $values_values = tep_db_fetch_array($values);
?>
              <tr>
                <td colspan="3" class="pageHeading">&nbsp;<?php echo $values_values['products_options_values_name']; ?>&nbsp;</td>
                <td>&nbsp;<?php echo tep_image(DIR_WS_IMAGES . 'pixel_trans.gif', '', '1', '53'); ?>&nbsp;</td>
              </tr>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
    $products = tep_db_query("select p.products_id, pd.products_name, po.products_options_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and po.language_id = '" . (int)$languages_id . "' and pa.products_id = p.products_id and pa.options_values_id='" . (int)$_GET['value_id'] . "' and po.products_options_id = pa.options_id order by pd.products_name");
    if (tep_db_num_rows($products)) {
?>
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent" align="right">&nbsp;<?php echo TABLE_HEADING_OPTION_SORT_ORDER; ?>&nbsp;</td>
                    <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</td>
                  </tr>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
<?php
      while ($products_values = tep_db_fetch_array($products)) {
        $rows++;
?>
                  <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                    <td align="center" class="smallText">&nbsp;<?php echo $products_values['products_id']; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_name']; ?>&nbsp;</td>
                    <td class="smallText" align="right">&nbsp;<?php echo $options_values["products_options_sort_order"]; ?>&nbsp;</td>
                    <td class="smallText">&nbsp;<?php echo $products_values['products_options_name']; ?>&nbsp;</td>
                  </tr>
<?php
      }
?>
                  <tr>
                    <td colspan="3"><?php echo tep_black_line(); ?></td>
                  </tr>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_WARNING_OF_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, (isset($_GET['value_page']) ? 'value_page=' . $_GET['value_page'] . '&' : '') . (isset($_GET['attribute_page']) ? 'attribute_page=' . $attribute_page : ''), 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    } else {
?>
                  <tr>
                    <td class="main" colspan="3"><br><?php echo TEXT_OK_TO_DELETE; ?></td>
                  </tr>
                  <tr>
                    <td class="main" align="right" colspan="3"><br><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_value&value_id=' . $_GET['value_id'], 'NONSSL') . '">'; ?><?php echo tep_image_button('button_delete.gif', ' delete '); ?></a>&nbsp;&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '&option_page=' . $option_page . (isset($_GET['value_page']) ? '&value_page=' . $value_page : '') . (isset($_GET['attribute_page']) ? '&attribute_page=' . $attribute_page : ''), 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', ' cancel '); ?></a>&nbsp;</td>
                  </tr>
<?php
    }
?>
              	</table></td>
              </tr>
<?php
  } else {
?>
              <tr>
                <td colspan="7" class="pageHeading"><?php echo HEADING_TITLE_VAL; ?></td>
              </tr>
              <tr>
                <td colspan="7" class="smallText">
<?php
    $per_page = 50;
 //   $per_page = MAX_ROW_LISTS_OPTIONS;
    if($_GET['current_option']!='') $add_curr_option = "and pov2po.products_options_id = '".$_GET['current_option']."'";

	  $values = "select pov.products_options_values_sort_order, pov.products_options_values_id, pov.products_options_values_image, pov.products_options_values_name, pov.products_options_values_extra_data, pov2po.products_options_id from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov left join " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " pov2po on pov.products_options_values_id = pov2po.products_options_values_id where pov.language_id = '" . (int)$languages_id . "' ".$add_curr_option." order by pov.products_options_values_id DESC";

	  if ($value_page =='') {
      $value_page = 1;
    }
    $prev_value_page = $value_page - 1;
    $next_value_page = $value_page + 1;

    $value_query = tep_db_query($values);

    $value_page_start = ($per_page * $value_page) - $per_page;
    $num_rows = tep_db_num_rows($value_query);

    if ($num_rows <= $per_page) {
      $num_pages = 1;
    } else if (($num_rows % $per_page) == 0) {
      $num_pages = ($num_rows / $per_page);
    } else {
      $num_pages = ($num_rows / $per_page) + 1;
    }
    $num_pages = (int) $num_pages;

    $values = $values . " LIMIT $value_page_start, $per_page";

    // Previous
    if ($prev_value_page)  {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'option_order_by=' . $option_order_by . '&value_page=' . $prev_value_page) . '#yakor"> &lt;&lt; </a> | ';
    }

    for ($i = 1; $i <= $num_pages; $i++) {
      if ($i != $value_page) {
         echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, ($_GET['current_option'] ? 'current_option=' . $_GET['current_option'] . '&' : '') . (isset($option_order_by) ? 'option_order_by=' . $option_order_by . '&' : '') . 'value_page=' . $i) . '#yakor">' . $i . '</a> | ';
      } else {
         echo '<b><font color=red>' . $i . '</font></b> | ';
      }
    }

    // Next
    if ($value_page != $num_pages) {
      echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, ($_GET['current_option'] ? 'current_option=' . $_GET['current_option'] . '&' : '') . (isset($option_order_by) ? 'option_order_by=' . $option_order_by . '&' : '') . 'value_page=' . $next_value_page) . '#yakor"> &gt;&gt;</a> ';
    }
?>
                </td>
              </tr>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_SORT_ORDER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_OPT_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_OPT_VALUE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PHOTO; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_EXTRA_DATA; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?></td>
              </tr>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    $next_id = 1;
    $rows = 0;
    $values = tep_db_query($values);

      $max_values_id_query = tep_db_query("select max(products_options_values_id) + 1 as next_id from " . TABLE_PRODUCTS_OPTIONS_VALUES);
      $max_values_id_values = tep_db_fetch_array($max_values_id_query);
      $next_id = $max_values_id_values['next_id'];
    if ($action != 'update_option_value') {
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=add_product_option_values&value_page=' . $value_page, 'NONSSL') . '" method="post">';
?>
                <td align="center" class="smallText"><?php echo $next_id; ?></td>
                <td align="center" class="smallText">
                    <input type="text" name="value_sort" size="3" onchange="this.value = parseInt(this.value.trim()) || 0;">
                </td>
                <td align="center" class="smallText">
                    <select name="option_id">
<?php
      $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . $languages_id . "' order by products_options_name");
      while ($options_values = tep_db_fetch_array($options)) {
        if($options_values['products_options_id']==$_GET['current_option']) $selected = 'selected';
        else $selected = '';

        echo '<option '.$selected.' name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
      }
?>
                    </select>
                </td>
                <td class="smallText">
                    <input type="hidden" name="value_id" value="<?php echo $next_id; ?>">
                    <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) { ?>
                    <label for="value_name_input_<?php echo $languages[$i]['id']; ?>"><?php echo tep_escape($languages[$i]['name']); ?></label>
                    <input
                        type="text"
                        id="value_name_input_<?php echo $languages[$i]['id']; ?>"
                        name="value_name[<?php echo $languages[$i]['id']; ?>]"
                        maxlength="64"
                        style="box-sizing: border-box; display: block; width: 100%;"
                        >
                    <?php } ?>
                </td>
                <td class="smallText"><i><?php echo TEXT_SAVE_BEFORE_UPLOAD; ?></i></td>
                <td class="smallText">
                    <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) { ?>
                    <label for="extra_data_input_<?php echo $languages[$i]['id']; ?>"><?php echo tep_escape($languages[$i]['name']); ?></label>
                    <input
                        type="text"
                        id="extra_data_input_<?php echo $languages[$i]['id']; ?>"
                        name="extra_data[<?php echo $languages[$i]['id']; ?>]"
                        maxlength="1024"
                        style="box-sizing: border-box; display: block; width: 100%;"
                        >
                    <?php } ?>
                </td>
                <td align="right" class="smallText">&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;</td>
<?php
      echo '</form>';
?>
              </tr>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
<?php
    }
    while ($values_values = tep_db_fetch_array($values)) {
      $options_name = tep_options_name($values_values['products_options_id']);
      $values_name = $values_values['products_options_values_name'];
      $rows++;
?>
              <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
      if($_GET['current_option']!='') $plus_action = '&current_option='.$_GET['current_option'].'&value_page='.$_GET['value_page'].'#yakor';
      else $plus_action = '';
      if (($action == 'update_option_value') && ($_GET['value_id'] == $values_values['products_options_values_id'])) {
        echo '<form name="values" action="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_value'.$plus_action, 'NONSSL') . '" method="post">';
        $inputs = '';
        for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) {
          $value_name = tep_db_query("select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$values_values['products_options_values_id'] . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          $value_name = tep_db_fetch_array($value_name);
          $inputs .= $languages[$i]['code'] . ':&nbsp;<input type="text" name="value_name[' . $languages[$i]['id'] . ']" size="15" value="' . $value_name['products_options_values_name'] . '">&nbsp;<br>';
        }
?>
                <td class="smallText"><?php echo $values_values['products_options_values_id']; ?><input type="hidden" name="value_id" value="<?php echo $values_values['products_options_values_id']; ?>">&nbsp;</td>
                <td class="smallText"><input type="text" name="value_sort" value="<?php echo $values_values['products_options_values_sort_order']; ?>">&nbsp;</td>
                <td class="smallText">
                    <select name="option_id">
<?php
        $options = tep_db_query("select products_options_id, products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where language_id = '" . (int)$languages_id . "' order by products_options_name");
        while ($options_values = tep_db_fetch_array($options)) {
          echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '"';
          if ($values_values['products_options_id'] == $options_values['products_options_id']) {
            echo ' selected';
          }
          echo '>' . $options_values['products_options_name'] . '</option>';
        }
?>
                    </select>
                </td>
                <td class="smallText">
                    <?php
                    
                    // Выгрузить значение этой опци и дополнительные данные на всех языках
                    $languge_id_in = array();
                    foreach($languages as $_language) {
                        $languge_id_in[] = $_language['id'];
                    }
                    $languge_id_in = implode(', ', $languge_id_in);
                    $names = array();
                    $extra_data = array();
                    $query = tep_db_query("SELECT language_id, products_options_values_name, products_options_values_extra_data FROM products_options_values WHERE products_options_values_id = {$values_values['products_options_values_id']} AND language_id IN ({$languge_id_in})");
                    while(($row = tep_db_fetch_array($query)) !== false) {
                        $names[$row['language_id']] = $row['products_options_values_name'];
                        $extra_data[$row['language_id']] = $row['products_options_values_extra_data'];
                    }
                    
                    ?>
                    <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) { ?>
                    <label for="value_name_input_<?php echo $languages[$i]['id']; ?>"><?php echo tep_escape($languages[$i]['name']); ?></label>
                    <input
                        type="text"
                        id="value_name_input_<?php echo $languages[$i]['id']; ?>"
                        name="value_name[<?php echo $languages[$i]['id']; ?>]"
                        maxlength="64"
                        <?php if(!empty($names[$languages[$i]['id']])) { ?>value="<?php echo tep_escape($names[$languages[$i]['id']]); ?>"<?php } ?>
                        style="box-sizing: border-box; display: block; width: 100%;"
                        >
                    <?php } ?>
                </td>
                <td class="smallText">
                    <script type="text/javascript">
                        $(document).ready(function(){
                            go_upload3(<?php echo $_GET['value_id'];?>);
                        });
                    </script>
                    <input type="hidden" name="attr_id" id="attr_id" value="<?php echo $_GET['value_id'];?>" />
                    <input type="hidden" name="filenames3" id="filenames3" />
                    <div style="float:left;">
                        <div id="uploadButton3" style="padding-left:0px;margin-bottom:15px;cursor:pointer;">
                            <img id="load3" src="images/butt_ld.png"/>
                        </div>
                    </div>
                    <div style="float:left;">
                        <div id="files3"></div>
                    </div>
                    <div style="clear:both;"></div>
                </td>
                <td class="smallText">
                    <?php for ($i = 0, $n = sizeof($languages); $i < $n; $i ++) { ?>
                    <label for="extra_data_input_<?php echo $languages[$i]['id']; ?>"><?php echo tep_escape($languages[$i]['name']); ?></label>
                    <input
                        type="text"
                        id="extra_data_input_<?php echo $languages[$i]['id']; ?>"
                        name="extra_data[<?php echo $languages[$i]['id']; ?>]"
                        maxlength="1024"
                        <?php if(!empty($extra_data[$languages[$i]['id']])) { ?>value="<?php echo tep_escape($extra_data[$languages[$i]['id']]); ?>"<?php } ?>
                        style="box-sizing: border-box; display: block; width: 100%;"
                        >
                    <?php } ?>
                </td>
                <td align="right" class="smallText">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>
<?php
        echo '</form>';
      } else {
?>
                <td class="smallText"><?php echo $values_values["products_options_values_id"]; ?></td>
                <td class="smallText"><?php echo $values_values["products_options_values_sort_order"]; ?></td>
                <td class="smallText"><?php echo $options_name; ?></td>
                <td class="smallText"><?php echo $values_name; ?></td>
                <td class="smallText">
                  <?php
                    if($values_values["products_options_values_image"]!='')
                      echo '<img src="../images/'.$values_values["products_options_values_image"].'" style="height:20px;" />';
                  ?>
                </td>
                <td class="smallText"><?php echo $values_values["products_options_values_extra_data"]; ?></td>
                <td align="right" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=update_option_value&value_id=' . $values_values['products_options_values_id'] . (isset($_GET['value_page']) ? '&value_page=' . $_GET['value_page'] : '').$plus_action, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_edit.gif', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, 'action=delete_option_value&value_id=' . $values_values['products_options_values_id'], 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
<?php
      }
    }

?>
              </tr>
              <tr>
                <td colspan="7"><?php echo tep_black_line(); ?></td>
              </tr>
<?php } ?>
            </table></td>
          </tr>
        </table></td>
<!-- option value eof //-->
      </tr>
            </table>
</td>
<!-- products_attributes_smend //-->
  </tr>
</table>
<!-- body_text_smend //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_smend //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
