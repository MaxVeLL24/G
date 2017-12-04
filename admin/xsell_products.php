<?php
/* $Id$
osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com
Copyright (c) 2002 osCommerce

Released under the GNU General Public License
xsell.php
Original Idea From Isaac Mualem im@imwebdesigning.com <mailto:im@imwebdesigning.com>
Complete Recoding From Stephen Walker admin@snjcomputers.com
*/
  include_once __DIR__ . '/includes/application_top.php';
  require(DIR_WS_CLASSES . 'currencies.php');
  
  $currencies = new currencies();
//  print_r($_POST);
  switch($_GET['action']){
    case 'update_cross' :
	if ($_POST['product']){
        foreach ($_POST['product'] as $temp_prod){
          $save_discount_query = tep_db_query('select products_id, discount from ' . TABLE_PRODUCTS_XSELL . ' where xsell_id = "'.$_GET['add_related_product_ID'].'" and products_id = "'.$temp_prod.'"');
          while ($save_discount = tep_db_fetch_array($save_discount_query)) {
            $tmp_discounts[$save_discount['products_id']] = $save_discount['discount'];
          }
          tep_db_query('delete from ' . TABLE_PRODUCTS_XSELL . ' where xsell_id = "'.$temp_prod.'" and products_id = "'.$_GET['add_related_product_ID'].'"');
          tep_db_query('delete from ' . TABLE_PRODUCTS_XSELL . ' where xsell_id = "'.$_GET['add_related_product_ID'].'" and products_id = "'.$temp_prod.'"');		  
        }
      }

      if ($_POST['cross']){
        foreach ($_POST['cross'] as $temp){
          $insert_array = array();
          $insert_array = array('products_id' => $_GET['add_related_product_ID'], 
                                'xsell_id' => $temp);
          tep_db_perform(TABLE_PRODUCTS_XSELL, $insert_array);
        } // foreach $temp
      } // if cross
// insert reciprocable x-sell products BOF
      if ($_POST['reciprocal_link_cross']){
        foreach ($_POST['reciprocal_link_cross'] as $temp2) {
          $insert_array = array();
          $insert_array = array('products_id' => $temp2,
                                'discount' => $tmp_discounts[$temp2],
                                'xsell_id' => $_GET['add_related_product_ID']);
          tep_db_perform(TABLE_PRODUCTS_XSELL, $insert_array);
        } // foreach $temp2
      } // if reciprocal_link_cross
// insert reciprocable x-sell products EOF
		if ($_POST['option']){
		$products_options = $_POST['option'];
		$xsell_id = $_POST['product'];
		for ($i=0;$i<sizeof($products_options);$i++) {
			tep_db_query('update ' . TABLE_PRODUCTS_XSELL . ' set sort_order = "' . $products_options[$i] . '" where xsell_id = "' . $xsell_id[$i]  . '"');
		  }
		 }
		 
		if ($_POST['discount']){
		$products_discounts = $_POST['discount'];
		$xsell_id = $_POST['product'];
		for ($i=0;$i<sizeof($products_discounts);$i++) {
			tep_db_query('update ' . TABLE_PRODUCTS_XSELL . ' set discount = "' . $products_discounts[$i] . '" where xsell_id = "' . $xsell_id[$i]  . '" and products_id = "' . $_GET['add_related_product_ID']  . '"');
	  //----------------------ОДИНАКОВЫЕ СКИДКИ--------------------------------------------------
  //	  tep_db_query('update ' . TABLE_PRODUCTS_XSELL . ' set discount = "' . $products_discounts[$i] . '" where products_id = "' . $xsell_id[$i]  . '" and xsell_id = "' . $_GET['add_related_product_ID']  . '"');
      }
		 }
//Cache
      $cachedir = DIR_FS_CACHE_XSELL . $_GET['add_related_product_ID'];
      if(is_dir($cachedir)) {
        rdel($cachedir);
      }
//Fin Cache

  	// tep_redirect(tep_href_link(FILENAME_XSELL_PRODUCTS));
	  $messageStack->add(SORT_CROSS_SELL_SUCCESS, 'success');
  //  print_r($tmp_discounts);

      break;
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
 <!-- <link rel="stylesheet" type="text/css" href="../templates/easy2/stylesheet.css"> -->
<style>
ul#linkaction li#new, li#edit { width:400px; list-style:none; padding:5px}
ul#linkaction li#new {background:#66CCFF;}
ul#linkaction li#edit {background:#6699FF;}
ul#linkaction li#edit a, li#new a{color:#ffffff; font-size:80%;}
.productmenutitle{
cursor:pointer;
margin-bottom: 0px;
background-color:orange;
color:#FFFFFF;
font-weight:bold;
font-family:ms sans serif;
width:100%;
padding:3px;
font-size:12px;
text-align:center;
/*/*/border:1px solid #000000;/* */
}
.productmenutitle1{
cursor:pointer;
margin-bottom: 0px;
background-color: red;
color:#FFFFFF;
font-weight:bold;
font-family:ms sans serif;
width:100%;
padding:3px;
font-size:12px;
text-align:center;
/*/*/border:1px solid #000000;/* */
}
</style>
<script language="JavaScript1.2">

function cOn(td)
{
if(document.getElementById||(document.all && !(document.getElementById)))
{
td.style.backgroundColor="#CCCCCC";
}
}

function cOnA(td)
{
if(document.getElementById||(document.all && !(document.getElementById)))
{
td.style.backgroundColor="#CCFFFF";
}
}

function cOut(td)
{
if(document.getElementById||(document.all && !(document.getElementById)))
{
td.style.backgroundColor="DFE4F4";
}
}
</script>
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2" style=" width: 95%; margin: 0 auto; ">
 <tr>
  <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
  </table></td>
  <td width="100%" valign="top">
<!-- body_text //-->
  <table border="0" width="100%" cellspacing="0" cellpadding="10">
   <tr>
     <td>&nbsp;</td>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10');?></td>
   </tr>
   <tr>
     <td colspan="2" class="pageHeading"><?php echo HEADING_TITLE; ?></td>
    </tr>
   <tr>
     <td>
     	<?='<a class="btn" href="'.tep_href_link(FILENAME_XSELL_PRODUCTS, 'action=new_xsell').'">'.IMAGE_WITHOUT_XSELLS.'</a>' ?>
     	&nbsp;&nbsp;&nbsp;&nbsp;
     	<?= '<a class="btn" href="'.tep_href_link(FILENAME_XSELL_PRODUCTS, 'action=edit_xsell').'">' . IMAGE_WITH_XSELLS . '</a>' ?>
		<br>    <br> 
     </td>
   </tr>
	<tr>
	  <td align="left">
 		
<?php
 if(isset($_GET['add_related_product_ID'])){ // si esta definido el producto
		if (isset($_GET['sort'])){ // si esta definido la definicion del orden enseсamos las relaciones que tiene para editarlas o quitarlas
		    $products_name_query = tep_db_query('select pd.products_name, p.products_model, p.products_images, p.products_price from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = "'.$_GET['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id ="'.(int)$languages_id.'"');
				$products_name = tep_db_fetch_array($products_name_query);
		?>
        
		  <table border="0" cellspacing="0" cellpadding="0" width="100%">
		   <tr>
		     <td><?php echo'<h2>'.HEADING_TITLE_EDIT_XSELL.'</h2><hr>';?></td>
	        </tr>
		   <tr>
			<td><?php echo tep_draw_form('update_cross', FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=update_cross', 'post');?>
				<table cellpadding="1" cellspacing="1" border="0" width="100%">
				 <tr>
				  <td colspan="8"><table cellpadding="3" cellspacing="0" border="0" width="100%">
				   <tr class="dataTableHeadingRow">
				   <?php $img_var = explode(';', $products_name['products_images']);
		  	 $products['products_image'] = $img_var[0]; ?>
				   <td valign="middle" align="left"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products['products_image'], "", SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);?></td>
			  <?php
			  if ($products_name['products_model'] == NULL) {
				$products_model = TEXT_NONE;
			  } else {
				$products_model = $products_name['products_model'];
			  }
			  ?>
					<td valign="middle" align="left"><span class="main"><?php echo TEXT_SETTING_SELLS.' '.$products_name['products_name'].' ('.TEXT_MODEL.': '.$products_model.') ('.TEXT_PRODUCT_ID.': '.$_GET['add_related_product_ID'].')';?></span></td>
					<td valign="middle" align="center"><?php echo tep_image_submit('button_update.gif')?></td>
					<td valign="middle" align="center"><?php echo '<a href="'.tep_href_link(FILENAME_XSELL_PRODUCTS, 'men_id=catalog').'">' . tep_image_button('button_cancel.gif') . '</a>'; ?></td>
				   </tr>
				  </table></td>
				 </tr>
			 <tr class="dataTableHeadingRow">
				  <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_ID;?>&nbsp;</td>
				  <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_MODEL;?>&nbsp;</td>
				  <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_IMAGE;?>&nbsp;</td>
				  <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_PRODUCT_NAME;?>&nbsp;</td>
				  <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_PRICE;?>&nbsp;</td>
				  <td class="dataTableHeadingContent">&nbsp;Скидка&nbsp;</td>
				  <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_SORT;?>&nbsp;</td>
				  <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_CROSS_SELL_THIS;?>&nbsp;</td>
				 </tr>
		<?php
			$products_query_raw = 'select p.products_id as products_id, p.products_price, p.products_images, p.products_model, pd.products_name, x.discount, x.products_id as xproducts_id, x.xsell_id, x.sort_order, x.ID from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd, '.TABLE_PRODUCTS_XSELL.' x where x.xsell_id = p.products_id and x.products_id = "'.$_GET['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by x.sort_order asc';
			$products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
				$sort_order_drop_array = array();
				for($i=1;$i<=$products_query_numrows;$i++){
				$sort_order_drop_array[] = array('id' => $i, 'text' => $i);
				}
			$products_query = tep_db_query($products_query_raw);
		 while ($products = tep_db_fetch_array($products_query)){
		?>
				 <tr bgcolor='#DFE4F4'>
				  <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
			  <?php
			  if ($products['products_model'] == NULL) {
				$products_model = TEXT_NONE;
			  } else {
				$products_model = $products['products_model'];
			  }
			  $img_var = explode(';', $products['products_images']);
		  	 $products['products_image'] = $img_var[0];
			  ?>
				  <td class="dataTableContent" align="center">&nbsp;<?php echo $products_model;?>&nbsp;</td>
				  <td class="dataTableContent" align="center">&nbsp;<?php echo ((is_file(DIR_FS_CATALOG_IMAGES . '/'.$products['products_image'])) ?  tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products['products_image'], "", SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) : TEXT_NONE);?>&nbsp;</td>
				  <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
				  <td class="dataTableContent" align="center">&nbsp;<?php echo $currencies->format($products['products_price']);?>&nbsp;</td>
				  <td class="dataTableContent" align="center">
            &nbsp;
            <input type="text" name="discount[]" value="<?php echo $products['discount'];?>" style="width:40px;" />
            &nbsp;
          </td>
          <td class="dataTableContent" align="center">&nbsp;<?php echo tep_draw_pull_down_menu('option[]', $sort_order_drop_array, $products['sort_order']);?>&nbsp;				  </td>
				  <?php
				$xsold_query = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.$_GET['add_related_product_ID'].'" and xsell_id = "'.$products['products_id'].'"');
				$xsold_query_reciprocal = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.$products['products_id'].'" and xsell_id = "'.$_GET['add_related_product_ID'].'"');
		?>    
				  <td class="dataTableContent">&nbsp;<?php echo tep_draw_checkbox_field('cross[]', $products['products_id'], ((tep_db_num_rows($xsold_query) > 0) ? true : false), '', ' onMouseOver="this.style.cursor=\'hand\'"');?>&nbsp;<label onMouseOver="this.style.cursor='hand'"><?php echo TEXT_CROSS_SELL;?><br>&nbsp;
				  <?php echo tep_draw_hidden_field('product[]', $products['products_id']) . tep_draw_checkbox_field('reciprocal_link_cross[]', $products['products_id'], ((tep_db_num_rows($xsold_query_reciprocal) > 0) ? true : false), '', ' onMouseOver="this.style.cursor=\'hand\'"');?>&nbsp;<label onMouseOver="this.style.cursor='hand'"><?php echo TEXT_RECIPROCAL_LINK;?></label>&nbsp;</td>
		      
    <?php
			}
		?>
			 </tr>
			</table></form></td>
		   </tr>
		   <tr>
			<td colspan="7">
			 <table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">
			  <tr>
			   <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
			   <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>
			  </tr>
			 </table>			</td>
		   </tr>
		  </table>
	<?php
}else{ //si no esta definido el orden solo mostramos para cruzar nuevos
		//Definimos el producto a cruzar
		$products_name_query = tep_db_query('select pd.products_name, p.products_model, p.products_images, p.products_price from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = "'.$_GET['add_related_product_ID'].'" and p.products_id = pd.products_id and pd.language_id ="'.(int)$languages_id.'"');
			$products_name = tep_db_fetch_array($products_name_query);
	?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
        <td align="left"><?php echo'<h2>'.HEADING_TITLE_NEW_XSELL.'</h2><hr>';?>
        </td>
        </tr>
	   <tr>
             <tr>
             <td class="main" colspan="6" align="right">
                 <?php
					echo tep_draw_form('search', FILENAME_XSELL_PRODUCTS, '', 'get'). tep_draw_hidden_field('add_related_product_ID', $add_related_product_ID);
					echo TEXT_SEARCH_MODEL . ' ' . tep_draw_input_field('search');
					echo '</form>';
				?>				</td>
              </tr>
		<td><?php echo tep_draw_form('update_cross', FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=update_cross', 'post');
			
			$img_var = explode(';', $products_name['products_images']);
		?>
        <table cellpadding="1" cellspacing="1" border="0" width="100%">
			 <tr>
			  <td colspan="7">
              <table cellpadding="3" cellspacing="0" border="0" width="100%">
				<?php
					if($_GET['search'] != '') {}
					else
					{
				?>
			   <tr class="dataTableHeadingRow">
			   <td valign="middle" align="left"><?php echo tep_image(DIR_WS_CATALOG_IMAGES .$img_var[0], "", SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);?></td>
			  <?php
			  if ($products_name['products_model'] == NULL) {
				$products_model = TEXT_NONE;
			  } else {
				$products_model = $products_name['products_model'];
			  }
			  ?>
				<td valign="middle" align="left"><span class="main"><?php echo TEXT_SETTING_SELLS.' '.$products_name['products_name'].' ('.TEXT_MODEL.': '.$products_model.') ('.TEXT_PRODUCT_ID.$_GET['add_related_product_ID'].')';?></span></td>
				<td valign="middle" align="center"><?php echo tep_image_submit('button_update.gif')?></td>
				<td valign="middle" align="center"><?php echo '<a href="'.tep_href_link(FILENAME_XSELL_PRODUCTS, 'men_id=catalog').'">' . tep_image_button('button_cancel.gif') . '</a>'; ?></td>
			   </tr>
			   <?php } ?>
			  </table>              </td>
			 </tr>
			<?php
				if($_GET['search'] != '')
				{
			?>
                    <?php echo tep_draw_form('update_cross', FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=update_cross', 'post');?>
                    <tr class="dataTableHeadingRow products-connection">
                        <?php $img_var = explode(';', $products_name['products_images']);
                        $products['products_image'] = $img_var[0]; ?>
                        <td colspan="4" valign="middle" align="center"><?php echo tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products['products_image'], "", SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);?></td>
                        <?php
                        if ($products_name['products_model'] == NULL) {
                            $products_model = TEXT_NONE;
                        } else {
                            $products_model = $products_name['products_model'];
                        }
                        ?>
                        <td valign="middle" align="left"><span class="main"><?php echo TEXT_SETTING_SELLS.' '.$products_name['products_name'].' ('.TEXT_MODEL.': '.$products_model.') ('.TEXT_PRODUCT_ID.': '.$_GET['add_related_product_ID'].')';?></span></td>
                        <td valign="middle" align="center"><?php echo tep_image_submit('button_update.gif')?></td>
                        <td valign="middle" align="center"><?php echo '<a href="'.tep_href_link(FILENAME_XSELL_PRODUCTS, 'men_id=catalog').'">' . tep_image_button('button_cancel.gif') . '</a>'; ?></td>
                    </tr>
				<tr class="dataTableHeadingRow">
					<td class="dataTableHeadingContent" width="75">#</td>
					<td class="dataTableHeadingContent">Картинка</td>
					<td class="dataTableHeadingContent">Код товара</td>
                    <td class="dataTableHeadingContent">Обновить сопутствующие</td>
					<td class="dataTableHeadingContent">Имя товара</td>
					<td class="dataTableHeadingContent">Цена</td>
					<td class="dataTableHeadingContent">Скидка</td>
				</tr>
			<?php
				}
				else
				{
			?>
				 <tr class="dataTableHeadingRow">
					<td class="dataTableHeadingContent" width="75">&nbsp;<?php echo TABLE_HEADING_PRODUCT_ID;?>&nbsp;</td>
					<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_MODEL;?>&nbsp;</td>
					<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_IMAGE;?>&nbsp;</td>
					<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_CROSS_SELL_THIS;?>&nbsp;</td>
					<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_NAME;?>&nbsp;</td>
					<td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_PRODUCT_PRICE;?>&nbsp;</td>
					<td class="dataTableContent" align="center">Скидка</td>
				 </tr>
			<?php
				}
			?>
	<?php //Listamos los productos a cruzar
	  // Hacemos Array de los productos que tienen alguna relaciуn con el producto elegido para excluirlos del listado
			$xsell_array = array(); 
            $xsell_query = tep_db_query('select x.xsell_id from ' . TABLE_PRODUCTS . ' p, ' . TABLE_PRODUCTS_XSELL . ' x where x.products_id = "'.$_GET['add_related_product_ID'].'"');
			while ($xsell = tep_db_fetch_array($xsell_query)) {
			$xsell_array[] = $xsell['xsell_id'];
		  }
            $num_xsell = tep_db_num_rows($xsell_query);
		if (isset($_GET['search'])) {
		  $search = tep_db_prepare_input($_GET['search']);			
		  $products_query_raw = "select p.products_id, p.products_model, p.products_images, pd.products_name, p.products_price from products as p inner join products_description as pd on p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and pd.products_name like '%" . tep_db_input($search) . "%' left outer join products_xsell as px on px.products_id = p.products_id where px.products_id is null order by p.products_model";
		  }else{
		  $products_query_raw = 'select p.products_id, p.products_images, p.products_model, pd.products_name, p.products_price from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd where p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by p.products_model asc';
		  }
         // var_dump($products_query_raw);
		  $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
		  //var_dump($products_split);
		  $products_query = tep_db_query($products_query_raw);
         
				while ($products = tep_db_fetch_array($products_query)) {    
				  if (!in_array($products['products_id'], $xsell_array)) {

			$xsold_query = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.$_GET['add_related_product_ID'].'" and xsell_id = "'.$products['products_id'].'"');
			$xsold_query_reciprocal = tep_db_query('select * from '.TABLE_PRODUCTS_XSELL.' where products_id = "'.$products['products_id'].'" and xsell_id = "'.$_GET['add_related_product_ID'].'"');
			if($_GET['search'] != '')
			{

// 06.09.2017
	?>
                <tr bgcolor='#DFE4F4'>
                    <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
                    <?php
                    if ($products['products_model'] == NULL) {
                        $products_model = TEXT_NONE;
                    } else {
                        $products_model = $products['products_model'];
                    }
                    $img_var = explode(';', $products['products_images']);
                    $products['products_image'] = $img_var[0];

                    ?>
                    <td class="dataTableContent" align="center">&nbsp;<?php echo ((is_file(DIR_FS_CATALOG_IMAGES . '/'.$products['products_image'])) ?  tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products['products_image'], "", 75, 75) : TEXT_NONE);?>&nbsp;</td>
                    <td class="dataTableContent" align="center">&nbsp;<?php echo $products_model;?>&nbsp;</td>
                    <td class="dataTableContent"><?php echo tep_draw_hidden_field('product[]', $products['products_id']) . tep_draw_checkbox_field('cross[]', $products['products_id'], ((tep_db_num_rows($xsold_query) > 0) ? true : false), '', ' onMouseOver="this.style.cursor=\'hand\'"');?>&nbsp;<label onMouseOver="this.style.cursor='hand'"><?php echo TEXT_CROSS_SELL;?><br>
                            <?php echo tep_draw_checkbox_field('reciprocal_link_cross[]', $products['products_id'], ((tep_db_num_rows($xsold_query_reciprocal) > 0) ? true : false), '', ' onMouseOver="this.style.cursor=\'hand\'"');?>&nbsp;<label onMouseOver="this.style.cursor='hand'"><?php echo TEXT_RECIPROCAL_LINK;?></label>&nbsp;</td>
                    <input type="hidden" name="lol" value="33" />
                    <td class="dataTableContent">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
                    <td class="dataTableContent">&nbsp;<?php echo $currencies->format($products['products_price']);?>&nbsp;</td>
                    <td class="dataTableContent" align="center">
                        &nbsp;
                        <input type="text" name="discount[]" value="" style="width:40px;" />
                        &nbsp;
                    </td>
                </tr>

<!--	<tr onMouseOver="cOn(this); this.style.cursor='pointer'; this.style.cursor='hand';" onMouseOut="cOut(this);" bgcolor='#DFE4F4' onClick=document.location.href="--><?php //echo tep_href_link(FILENAME_XSELL_PRODUCTS, 'add_related_product_ID=' . $products['products_id'], 'NONSSL');?>
<!--                ">-->
<!--									<td class="dataTableContent" valign="top">&nbsp;-->
<!--                                        --><?php //echo $products['products_id'];?><!--&nbsp;</td>-->
<!--									  --><?php
//									  if ($products['products_model'] == NULL) {
//										$products_model = TEXT_NONE;
//									  } else {
//										$products_model = $products['products_model'];
//									  }
//									  $img_var = explode(';', $products['products_images']);
//		  	 $products['products_image'] = $img_var[0];
//									  ?>
<!--									<td class="dataTableContent" align="center">&nbsp;--><?php //echo tep_image(DIR_WS_CATALOG_IMAGES .$products['products_image'], $products['products_name'], 50, 50);?><!--&nbsp;</td>-->
<!--									<td class="dataTableContent" valign="top">&nbsp;--><?php //echo $products_model;?><!--&nbsp;</td>-->
<!--									<td class="dataTableContent" valign="top">&nbsp;--><?php //echo $products['products_name'];?><!--&nbsp;</td>-->
<!--								<td class="dataTableContent" valign="top" align="center">&nbsp;<a href="/admin/xsell_products.php?sort=1&add_related_product_ID=--><?php //echo $products['products_id'];?><!--">Редактировать Соп.</a>&nbsp;</td>-->
<!--		  </tr>-->
	
	<?php } else {?>
			 <tr bgcolor='#DFE4F4'> 
			  <td class="dataTableContent" align="center">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
		  <?php
		  if ($products['products_model'] == NULL) {
			$products_model = TEXT_NONE;
		  } else {
			$products_model = $products['products_model'];
		  }
		  	$img_var = explode(';', $products['products_images']);
		  	 $products['products_image'] = $img_var[0];

		  ?>
			  <td class="dataTableContent" align="center">&nbsp;<?php echo $products_model;?>&nbsp;</td>
			  <td class="dataTableContent" align="center">&nbsp;<?php echo ((is_file(DIR_FS_CATALOG_IMAGES . '/'.$products['products_image'])) ?  tep_image(DIR_WS_CATALOG_IMAGES . '/'.$products['products_image'], "", 75, 75) : TEXT_NONE);?>&nbsp;</td>
			  <td class="dataTableContent"><?php echo tep_draw_hidden_field('product[]', $products['products_id']) . tep_draw_checkbox_field('cross[]', $products['products_id'], ((tep_db_num_rows($xsold_query) > 0) ? true : false), '', ' onMouseOver="this.style.cursor=\'hand\'"');?>&nbsp;<label onMouseOver="this.style.cursor='hand'"><?php echo TEXT_CROSS_SELL;?><br>
			  <?php echo tep_draw_checkbox_field('reciprocal_link_cross[]', $products['products_id'], ((tep_db_num_rows($xsold_query_reciprocal) > 0) ? true : false), '', ' onMouseOver="this.style.cursor=\'hand\'"');?>&nbsp;<label onMouseOver="this.style.cursor='hand'"><?php echo TEXT_RECIPROCAL_LINK;?></label>&nbsp;</td>
			  <input type="hidden" name="lol" value="33" />
        <td class="dataTableContent">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
			  <td class="dataTableContent">&nbsp;<?php echo $currencies->format($products['products_price']);?>&nbsp;</td>
				<td class="dataTableContent" align="center">
            &nbsp;
            <input type="text" name="discount[]" value="" style="width:40px;" />
            &nbsp;
        </td>
       </tr>
	<?php
		}
		}
	}
	?>
			</table></form></td>
	   </tr>
	   <tr>
		<td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="infoBoxContent">
		 <tr>
		  <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
		  <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>
		 </tr>
		</table></td>
	   </tr>
	  </table> 
<?php
		}
	}else{
	// Pagina por defecto
                if ($_GET['action'] == 'new_xsell'){ //Si hay productos relacionados
						?>
                        
        <table border="0" cellspacing="1" cellpadding="2" width="100%">
           <tr >
             <td colspan="4"><?php echo'<h2>'.HEADING_TITLE_NEW_XSELL.'</h2><hr>';?></td>
           </tr>
           <tr>
             <td class="main" colspan="4" nowrap align="right">
			  <?php 
					// Busqueda
                                        $search = trim($_GET['search_new_xsell']);
					echo tep_draw_form('search_new_xsell', FILENAME_XSELL_PRODUCTS, '', 'get'). tep_draw_hidden_field('action', 'new_xsell');
					echo TEXT_SEARCH_MODEL . ' ' . tep_draw_input_field('search_new_xsell', $search ? $search : '', 'onkeypress="if(event.keyCode === 13 && !this.value.trim()) this.disabled = true;"');
					echo '</form>';
                    ?>            </td>
           </tr>
						<?php
				        if (isset($_GET['search_new_xsell']))
						{
                                                        $search = tep_db_input($search);
							$products_query_raw = "select p.products_id, p.products_model, p.products_images, pd.products_name, p.products_price from products as p inner join products_description as pd on p.products_id = pd.products_id and pd.language_id = {$_SESSION['languages_id']} and pd.products_name like '%{$search}%' left outer join products_xsell as px on px.products_id = p.products_id where px.products_id is null group by p.products_id order by p.products_model";
						}
						else{
				// enseсamos todos los artнculos que NO tienen relacion para aсadir nuevos
						$products_query_raw = "select p.products_id, p.products_model, p.products_images, pd.products_name, p.products_price from products as p inner join products_description as pd on p.products_id = pd.products_id and pd.language_id = {$_SESSION['languages_id']} left outer join products_xsell as px on px.products_id = p.products_id where px.products_id is null group by p.products_id order by p.products_model";
				}
                                
				$products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
				?>
            
                 <tr>
                  <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                  <td class="smallText" valign="top">&nbsp;</td>
                  <td class="smallText" valign="top">&nbsp;</td>
                  <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                 </tr>
                   <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent" width="75"><?php echo TABLE_HEADING_PRODUCT_ID;?></td>
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_IMAGE;?></td>
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_MODEL;?></td>
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_NAME;?></td>
                   </tr>

        <?php 
				$products_query = tep_db_query($products_query_raw);
				while ($products = tep_db_fetch_array($products_query)) {
				  // if (!in_array($products['products_id'], $xsell_array)) {
								?>
								   <tr onMouseOver="cOn(this); this.style.cursor='pointer'; this.style.cursor='hand';" onMouseOut="cOut(this);" bgcolor='#DFE4F4' onClick=document.location.href="<?php echo tep_href_link(FILENAME_XSELL_PRODUCTS, 'add_related_product_ID=' . $products['products_id'], 'NONSSL');?>">
									<td class="dataTableContent" valign="top">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
									  <?php
									  if ($products['products_model'] == NULL) {
										$products_model = TEXT_NONE;
									  } else {
										$products_model = $products['products_model'];
									  }
									  $img_var = explode(';', $products['products_images']);
		  	 $products['products_image'] = $img_var[0];
									  ?>
									<td class="dataTableContent" align="center">&nbsp;<?php echo tep_image(DIR_WS_CATALOG_IMAGES .$products['products_image'], $products['products_name'], 50, 50);?>&nbsp;</td>
									<td class="dataTableContent" valign="top">&nbsp;<?php echo $products_model;?>&nbsp;</td>
									<td class="dataTableContent" valign="top">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
		  </tr>
						<?php 
			//			}
				  }
				
				?>
                               
		 <tr>
		  <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
		  <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
		 </tr>
		</table>
		
				<?php 
				
				
				 }elseif (!isset ($_GET['action']) || $_GET['action'] == 'edit_xsell'){
				 // Enseсamos solo los que tienen alguna relacion
		?>    
                    <table border="0" cellpadding="2" cellspacing="1" width="100%">
           <tr>
             <td colspan="7"><?php echo'<h2>'.HEADING_TITLE_EDIT_XSELL.'</h2><hr>';?></td>
             </tr>
           <tr>
             <td class="main" colspan="7" nowrap align="right">
             		  <?php
					echo tep_draw_form('search', FILENAME_XSELL_PRODUCTS, '', 'get'). tep_draw_hidden_field('add_related_product_ID', $add_related_product_ID);
					echo TEXT_SEARCH_MODEL . ' ' . tep_draw_input_field('search');
					echo '</form>';
						?>			</td>
           </tr>
           <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent" width="75"><?php echo TABLE_HEADING_PRODUCT_ID;?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_MODEL;?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_IMAGE;?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCT_NAME;?></td>
            <td class="dataTableHeadingContent" nowrap><?php echo TABLE_HEADING_CURRENT_SELLS;?></td>
            <td class="dataTableHeadingContent" colspan="2" nowrap align="center"><?php echo TABLE_HEADING_UPDATE_SELLS;?></td>
           </tr>
        <?php
						
				$products_query_raw = 'select distinct p.products_id, p.products_model, pd.products_name, p.products_images, p.products_price, p.products_tax_class_id, p.products_id from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd, '.TABLE_PRODUCTS_XSELL.' x where p.products_id = x.products_id and p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by p.products_model asc';
		$products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
		$products_query_numrows = tep_db_query("select products_id from " . TABLE_PRODUCTS_XSELL . " group by products_id");
		$products_query_numrows = tep_db_num_rows($products_query_numrows);
		$products_query = tep_db_query($products_query_raw);
		
		while ($products = tep_db_fetch_array($products_query)) {
	?>
	   <tr onMouseOver="cOn(this); this.style.cursor='pointer'; this.style.cursor='hand';" onMouseOut="cOut(this);" bgcolor='#DFE4F4' onClick=document.location.href="<?php echo tep_href_link(FILENAME_XSELL_PRODUCTS, 'add_related_product_ID=' . $products['products_id'], 'NONSSL');?>">
		<td class="dataTableContent" valign="top">&nbsp;<?php echo $products['products_id'];?>&nbsp;</td>
		  <?php
						  if ($products['products_model'] == NULL) {
							$products_model = TEXT_NONE;
						  } else {
							$products_model = $products['products_model'];
						  }
						  $img_var = explode(';', $products['products_images']);
		  	 $products['products_image'] = $img_var[0];
		  ?>
		<td class="dataTableContent" valign="top">&nbsp;<?php echo $products_model;?>&nbsp;</td>
		<td class="dataTableContent" align="center">&nbsp;<?php echo tep_image(DIR_WS_CATALOG_IMAGES .$products['products_image'], $products['products_name'], 50, 50);?>&nbsp;</td>
		<td class="dataTableContent" valign="top">&nbsp;<?php echo $products['products_name'];?>&nbsp;</td>
		<td class="dataTableContent" valign="top">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
								 <tr>
								  <td class="dataTableContent">&nbsp;Название&nbsp;</td>
								  <td class="dataTableContent">&nbsp;Скидка&nbsp;</td>
                 </tr>
	<?php
		$products_cross_query = tep_db_query('select p.products_id, p.products_model, pd.products_name, p.products_id, x.discount, x.products_id, x.xsell_id, x.sort_order, x.ID from '.TABLE_PRODUCTS.' p, '.TABLE_PRODUCTS_DESCRIPTION.' pd, '.TABLE_PRODUCTS_XSELL.' x where x.xsell_id = p.products_id and x.products_id = "'.$products['products_id'].'" and p.products_id = pd.products_id and pd.language_id = "'.(int)$languages_id.'" order by x.sort_order asc');
			$i=0;
							while ($products_cross = tep_db_fetch_array($products_cross_query)){
										$i++;
						?>
								 <tr>
								  <td class="dataTableContent">&nbsp;<?php echo $i . '.&nbsp;' . $products_cross['products_name'].'';?>&nbsp;</td>
								  <td class="dataTableContent">&nbsp;<?php echo ' <b>'.$products_cross['discount'].'</b>';?>&nbsp;</td>
                 </tr>
						<?php
								}
								if ($i <= 0){
							?>
									 <tr>
									  <td class="dataTableContent">&nbsp;--&nbsp;</td>
									  <td class="dataTableContent">&nbsp;</td>
									 </tr>
							<?php
									}
							?>

		</table></td>
		<td class="dataTableContent" valign="top" align="center">&nbsp;<a href="<?php echo tep_href_link(FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'add_related_product_ID=' . $products['products_id'], 'NONSSL');?>"><?php echo TEXT_ADD_XSELLS;?></a>&nbsp;</td>
		<td class="dataTableContent" valign="top" align="center" align="center">&nbsp;<?php echo (($i > 0) ? '<a href="' . tep_href_link(FILENAME_XSELL_PRODUCTS, tep_get_all_get_params(array('action')) . 'sort=1&add_related_product_ID=' . $products['products_id'], 'NONSSL') .'">'.TEXT_EDIT_XSELLS.'</a>&nbsp;' : '--')?></td>
	   </tr>
	<?php
			}
	?>
	   <tr>
		<td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
		 <tr>
		  <td class="smallText" valign="top"><?php echo $products_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
		  <td class="smallText" align="right"><?php echo $products_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID', 'action'))); ?></td>
		 </tr>
		</table></td>
	   </tr>
	  </table>
<?php
				
			}
		}
?>
<!-- body_text_eof //-->
  <tr>
    <td>    </td>
    <tr>
      <td>      
      <td>    
<tr>
  <td>  </td><tr>
    <td>    </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>