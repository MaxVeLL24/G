<?php
/*
  $Id: stats_products_viewed.php,v 1.2 2003/09/24 15:18:15 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include_once __DIR__ . '/includes/application_top.php';
  if ($_SESSION['navigation'] != null) $_SESSION['navigation']->remove_current_page();
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<style type="text/css" title="currentStyle">
@import "tbl/css/demo_page.css";
@import "tbl/css/demo_table.css";
@import "tbl/css/demo_table_jui.css";
</style>
<link rel="stylesheet" href="tbl/css/jquery-ui-1.8.13.custom.css">
<link rel="stylesheet" href="includes/stylesheet.css">
<style type="text/css">
BODY{background-color:#ffffff;}
#tbl TD{
font-family: Arial, Tahoma, Verdana, sans-serif;
font-size: 12px;
padding:5px;
border: #b6b7cb solid 0px;
}

</style>
<script type="text/javascript" language="javascript" src="includes/general.js"></script>
<script type="text/javascript" language="javascript" src="tbl/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" language="javascript" src="tbl/js/jquery-ui-1.8.13.custom.min.js"></script>
<script type="text/javascript" language="javascript" src="tbl/js/jquery.jeditable.js"></script>
<script type="text/javascript" language="javascript" src="tbl/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="tbl/js/ui.selectmenu.js"></script>
<script type="text/javascript" charset="utf-8">
$(function() {

	$('select[name="cPath"]').selectmenu({maxHeight:400,width:400,menuWidth:580,style:'dropdown'});
	$( "a.goback" ).button();

	var oTable = $('#tbl').dataTable( {
		"bJQueryUI": true,
		"sPaginationType":"full_numbers",
		"bStateSave": true,
		"iDisplayLength": 50,
		//"sScrollY": "300px",
		"oLanguage": {"sUrl": "tbl/<?php echo $language; ?>.txt"},
		"fnDrawCallback": function() {

			$('table#tbl tbody tr:not(".folder") td:not(:first-child)').editable( 'tbl/<?php echo FILENAME_TBL_AJAX; ?>', {
			"callback": function( sValue, y ) {
					var aPos = oTable.fnGetPosition( this );
					oTable.fnUpdate( sValue, aPos[0], aPos[1] );
			},
			"submitdata": function ( value, settings ) {
					return {
						"row_id": this.parentNode.getAttribute('id').substr(4),
						"column": oTable.fnGetPosition( this )[2]
					};
			},
				"height": "25px"
			});
		
		},
	});
		
	$("#lfor").focus(function () {
		var k=$(this).val();
		if(k=='<?php echo TBL_HEADING_TITLE_SEARCH; ?>')  $(this).val('');
	});
	 $("#lfor").focusout(function () {
		var j=$(this).val();
		if(j=='')  $(this).val('<?php echo TBL_HEADING_TITLE_SEARCH; ?>');
	});	
		
});
</script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_smend //-->
<table border="0" style="margin:auto;margin-top:20px;" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="100%" valign="top">
<?php

    $cPath_back = '';
    if (sizeof($cPath_array) > 0) {
      for ($i=0, $n=sizeof($cPath_array); $i<$n; $i++) {    
          $cPath_back .= $cPath_array[$i];
      }
    }

//Get the Parent Id, used from Go Back To Parent Button
//Can also be wrapped in a function, ex. tep_get_parent($categories_id) and placed in admin/includes/functions/general.php

$parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$cPath_back . "'");
$parent_categories = tep_db_fetch_array($parent_categories_query);
$parent_id = $parent_categories['parent_id'];
    
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td colspan="2">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			 <tr>
				 <td>
					<div style="float:left;">
						<?php  
						 //   echo tep_draw_form('goto', FILENAME_TABLEDATA, '', 'get');
						 //   echo tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $cPath_back, 'onChange="this.form.submit();"');
						 //   echo tep_hide_session_id() . '</form>';     
						?>
					</div>
					<div style="float:left;">
						<?php if (!empty($cPath_back)) echo '&nbsp;<a class="goback" href="' . tep_href_link(FILENAME_TABLEDATA, 'cPath='.$parent_id) . '">' . TBL_HEADING_TITLE_BACK_TO_PARENT . '</a>';?>
					</div>
				</td>
				<td align="right">
					<div style="float:right;">
						<?php
						    echo tep_draw_form('search', FILENAME_TABLEDATA, '', 'get');
						    echo tep_draw_input_field('search',TBL_HEADING_TITLE_SEARCH,'id="lfor" class="lfor"');
						    echo tep_hide_session_id() . '</form>';   
						?>
					</div>
					

				</td>
			</tr>
	        </table>
</td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
						    <?php
						      include ('includes/categories.php'); 
						    ?>
					  </td>
            <td valign="top">
            
<table id="tbl" border="0" width="100%" cellspacing="0" cellpadding="0">
<thead>
	<tr>
		<td align="center" width="40">#</td>
		<td width="70"></td>
		<td align="center"><?php echo '&nbsp;'.TBL_HEADING_CATEGORIES_PRODUCTS; ?></td>
		<td align="center"><?php echo TBL_HEADING_MODEL; ?></td>
		<td align="center"><?php echo TBL_HEADING_QUANTITY; ?></td>
		<td align="center"><?php echo TBL_HEADING_PRICE; ?></td>
	</tr>
</thead><tbody>
<?php //FIND CATEGORIES
    $categories_count = 0;
    $rows = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
    } else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
    }

    while ($categories = tep_db_fetch_array($categories_query)) { ?>
	<tr class="folder gradeX">
		<td align="center" style="padding:0px;"><?php echo $categories['sort_order'].'<a href="' . tep_href_link(FILENAME_TABLEDATA,'cPath='.$categories['categories_id']) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>'; ?></td>
		<td align="center"><img src="../images/<?php echo $categories['categories_image']; ?>" width="64" /> </td>
    <td>
      <?php echo '<a style="color:#000;" href="' . tep_href_link(FILENAME_TABLEDATA,'cPath='.$categories['categories_id']) . '">
                    <b>' . $categories['categories_name'] . '</b></a> <br /> 
                    '.TBL_HEADING_PRODUCTS_COUNT.': '.tep_products_in_category_count($categories['categories_id']).
                    '<br /> '.TBL_HEADING_SUBCATEGORIES_COUNT.': '.tep_childs_in_category_count($categories['categories_id']); ?>
    </td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
	</tr>
<?php } ?>

<?php //FIND PRODUCTS
    if (isset($HTTP_GET_VARS['search'])) {
      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_model, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and pd.products_name like '%" . tep_db_input($search) . "%' order by pd.products_name");
    } else {
      $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_model, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by pd.products_name");
    }
    while ($products = tep_db_fetch_array($products_query)) { ?>
	<tr id="pid_<?php echo (int)$products['products_id'] ?>">
		<td align="center" style="padding:5px;"><?php echo tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW); ?></td>
		<td align="center"><img src="../images/<?php echo $products['products_image']; ?>" width="64" /> </td>
    <td align="center" style="padding-left:5px;"><?php echo $products['products_name']; ?></td>
		<td align="center"><?php echo $products['products_model']; ?> </td>
		<td align="center"><?php echo $products['products_quantity']; ?> </td>
		<td align="center"><?php echo $products['products_price']; ?> </td>
	</tr>
<?php } ?>
</tbody>
</table>
		</td>
          </tr>
        </table></td>
      </tr>
    </table>

    </td>
  </tr>
</table>

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_smend //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
