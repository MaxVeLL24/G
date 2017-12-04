<?php
/*
  $Id: categories.php,v 1.7 21.05.2013 by Shopmakers
*/

  include_once __DIR__ . '/includes/application_top.php';
//Added for Categories Description 1.5
  require('includes/functions/categories_description.php');
//End Categories Description 1.5

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');
// Ultimate SEO URLs v2.1
// If the action will affect the cache entries
  if ($action === 'insert_category' || $action === 'update_category' || $action === 'delete_category_confirm' || $action === 'delete_product_confirm' || $action === 'copy_to_confirm') {
      tep_db_query("DELETE FROM cache WHERE `cache_name` LIKE 'sef.categories.%'");
  }


  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          if (isset($_GET['pID'])) {
            tep_set_product_status($_GET['pID'], $_GET['flag']);
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID']));
        break;

      case 'setflag_cat':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          if (isset($_GET['cID'])) {
            tep_set_categories_status($_GET['cID'], $_GET['flag']);
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&cID=' . $_GET['cID']));
        break;

      case 'setxml':
        if ( ($_GET['flagxml'] == '0') || ($_GET['flagxml'] == '1') ) {
          if (isset($_GET['pID'])) {
            tep_set_product_xml($_GET['pID'], $_GET['flagxml']);
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID']));
        break;

      case 'setxml_cat':
        if ( ($_GET['flagxml'] == '0') || ($_GET['flagxml'] == '1') ) {
          if (isset($_GET['cID'])) {
            tep_set_categories_xml($_GET['cID'], $_GET['flagxml']);
          }
        }
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&cID=' . $_GET['cID']));
        break;

      case 'new_category':
      case 'edit_category':
        $_GET['action']=$_GET['action'] . '_ACD';
        break;

      case 'insert_category':
      case 'update_category':

        if (isset($_POST['categories_id'])) $categories_id = tep_db_prepare_input($_POST['categories_id']);
        else $categories_id = tep_db_prepare_input($_GET['cID']);

        $sort_order = tep_db_prepare_input($_POST['sort_order']);
        $categories_status = tep_db_prepare_input($_POST['categories_status']);

        $sql_data_array = array('sort_order' => $sort_order, 'categories_status' => $categories_status);

        if ($action == 'insert_category') {
          $insert_sql_data = array('parent_id' => $current_category_id,
                                   'date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_CATEGORIES, $sql_data_array);

          $categories_id = tep_db_insert_id();
        } elseif ($action == 'update_category') {
          //    echo '<pre>',var_dump($sql_data_array),'</pre>';
          // die();
          $update_sql_data = array('last_modified' => 'now()');


          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_CATEGORIES, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "'");
        }

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {

          $language_id = $languages[$i]['id'];

           $sql_data_array = array('categories_name' => tep_db_prepare_input($_POST['categories_name'][$language_id]),
                                   'categories_url' => tep_db_prepare_input($_POST['categories_url'][$language_id]),
                                   'categories_heading_title' => tep_db_prepare_input($_POST['categories_heading_title'][$language_id]),
                                   'categories_description' => tep_db_prepare_input($_POST['categories_description'][$language_id]),
                                   'categories_meta_title' => tep_db_prepare_input($_POST['categories_meta_title'][$language_id]),
                                   'categories_meta_description' => tep_db_prepare_input($_POST['categories_meta_description'][$language_id]),
                                   'categories_meta_keywords' => tep_db_prepare_input($_POST['categories_meta_keywords'][$language_id]));


          if ($action == 'insert_category') {
            $insert_sql_data = array('categories_id' => $categories_id,
                                     'language_id' => $languages[$i]['id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
          } elseif ($action == 'update_category') {
            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array, 'update', "categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          }
        }

        $categories_image = new upload('categories_image');
        $categories_image->set_destination(DIR_FS_CATALOG_IMAGES);
        if ($categories_image->parse() && $categories_image->save($_POST['categories_image'],'300','300',false)) {
          $categories_image_name = $categories_image->filename;
        } else {
          $categories_image_name = $_POST['categories_previous_image'];
        }

        if($_POST['delete_categories_image'] == 'on'){
          // $update_sql_data = array_merge($update_sql_data, array('categories_image'=>''));
          $categories_image_name = '';
          if(file_exists(DIR_FS_CATALOG_IMAGES.$_POST['categories_previous_image'])){
            unlink(DIR_FS_CATALOG_IMAGES.$_POST['categories_previous_image']);
          }

        }
        tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = '" . $categories_image_name . "' where categories_id = '" .  tep_db_input($categories_id) . "'");
        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));


        break;
      case 'delete_category_confirm':
        if (isset($_POST['categories_id'])) {
          $categories_id = tep_db_prepare_input($_POST['categories_id']);

          $categories = tep_get_category_tree($categories_id, '', '0', '', true);
          $products = array();
          $products_delete = array();

          for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
            $product_ids_query = tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int)$categories[$i]['id'] . "'");

            while ($product_ids = tep_db_fetch_array($product_ids_query)) {
              $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];
            }
          }

          reset($products);
          while (list($key, $value) = each($products)) {
            $category_ids = '';

            for ($i=0, $n=sizeof($value['categories']); $i<$n; $i++) {
              $category_ids .= "'" . (int)$value['categories'][$i] . "', ";
            }
            $category_ids = substr($category_ids, 0, -2);

            $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$key . "' and categories_id not in (" . $category_ids . ")");
            $check = tep_db_fetch_array($check_query);
            if ($check['total'] < '1') {
              $products_delete[$key] = $key;
            }
          }

          tep_set_time_limit(0);
          for ($i=0, $n=sizeof($categories); $i<$n; $i++) {
            tep_remove_category($categories[$i]['id']);
          }

          reset($products_delete);
          while (list($key) = each($products_delete)) {
            tep_remove_product($key);
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'delete_product_confirm':
        if (isset($_POST['products_id']) && isset($_POST['product_categories']) && is_array($_POST['product_categories'])) {
          $product_id = tep_db_prepare_input($_POST['products_id']);
          $product_categories = $_POST['product_categories'];

          for ($i=0, $n=sizeof($product_categories); $i<$n; $i++) {
            tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
          }

          $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$product_id . "'");
          $product_categories = tep_db_fetch_array($product_categories_query);

          if ($product_categories['total'] == '0') {
            tep_remove_product($product_id);
          }
        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath));
        break;
      case 'move_category_confirm':
        if (isset($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['move_to_category_id'])) {
          $categories_id = tep_db_prepare_input($_POST['categories_id']);
          $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);

          $path = explode('_', tep_get_generated_category_path_ids($new_parent_id));

          if (in_array($categories_id, $path)) {
            $messageStack->add_session(ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT, 'error');

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories_id));
          } else {
            tep_db_query("update " . TABLE_CATEGORIES . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where categories_id = '" . (int)$categories_id . "'");

            tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
          }
        }

        break;
      case 'move_product_confirm':
        $products_id = tep_db_prepare_input($_POST['products_id']);
        $new_parent_id = tep_db_prepare_input($_POST['move_to_category_id']);

        $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$new_parent_id . "'");
        $duplicate_check = tep_db_fetch_array($duplicate_check_query);
        if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_PRODUCTS_TO_CATEGORIES . " set categories_id = '" . (int)$new_parent_id . "' where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$current_category_id . "'");

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $new_parent_id . '&pID=' . $products_id));
        break;

      case 'create_copy_product_attributes':
  // $products_id_to= $copy_to_products_id;
  // $products_id_from = $pID;
        tep_copy_products_attributes($pID,$copy_to_products_id);
        break;

      case 'create_copy_product_attributes_categories':
  // $products_id_to= $categories_products_copying['products_id'];
  // $products_id_from = $make_copy_from_products_id;
        $categories_products_copying_query= tep_db_query("select products_id from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id='" . $cID . "'");
        while ( $categories_products_copying=tep_db_fetch_array($categories_products_copying_query) ) {
          // process all products in category
          tep_copy_products_attributes($make_copy_from_products_id,$categories_products_copying['products_id']);
        }
        break;
      case 'copy_to_confirm':
        if (isset($_POST['products_id']) && isset($_POST['categories_id'])) {
          $products_id = tep_db_prepare_input($_POST['products_id']);
          $categories_id = tep_db_prepare_input($_POST['categories_id']);

          if ($_POST['copy_as'] == 'link') {
            if ($categories_id != $current_category_id) {
              $check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
              $check = tep_db_fetch_array($check_query);
              if ($check['total'] < '1') {
                tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$categories_id . "')");
              }
            } else {
              $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
            }
          } elseif ($_POST['copy_as'] == 'duplicate') {

            $products_price_list = tep_xppp_getpricelist("");

            $product_query = tep_db_query("select products_quantity, products_images, products_model, ". $products_price_list . ", products_date_available, products_weight, lable_1, lable_2, lable_3, products_tax_class_id, products_quantity_order_min, products_quantity_order_units, products_sort_order, manufacturers_id from " . TABLE_PRODUCTS . " where products_id = '" . (int)$products_id . "'");
            $product = tep_db_fetch_array($product_query);

            $prices_num = tep_xppp_getpricesnum();
            for($i=2; $i<=$prices_num; $i++) {
              if ($product['products_price_' . $i] == NULL) $products_instval .= "NULL, ";
              else $products_instval .= "'" . tep_db_input($product['products_price_' . $i]) . "', ";
            }

            $products_instval .= "'" . tep_db_input($product['products_price']) . "' ";

            // переіменовуємо всі картинки в нові:
            $new_images_array = explode(';',$product['products_images']);
            $new_images_str = '';
            foreach($new_images_array as $k => $v) {
              $image_file = $v;

              if($image_file!='') {              
                  $curr_img_name = explode('.',$v);
                  $extension = $curr_img_name[count($curr_img_name)-1];
                  unset($curr_img_name[count($curr_img_name)-1]);
                  $newimage_file = implode('.',$curr_img_name).'_.'.$extension;

                  // копіюємо файл з новою назвою                  
                      if (!copy($_SERVER['DOCUMENT_ROOT'].'/images/'.$image_file, $_SERVER['DOCUMENT_ROOT'].'/images/'.$newimage_file)) {
                          echo "не удалось скопировать $image_file...\n";
                      }
                      if (!copy($_SERVER['DOCUMENT_ROOT'].'/images/thumb'.$image_file, $_SERVER['DOCUMENT_ROOT'].'/images/thumb'.$newimage_file)) {
                          echo "не удалось скопировать thumb$image_file...\n";
                      }
                  
                  // END копіюємо файл з новою назвою
                  

                  $new_images_str .= $newimage_file.';';
                  }
            }
            $new_images_str=substr($new_images_str,0,-1);
            // END переіменовуємо всі картинки в нові

            tep_db_query("insert into " . TABLE_PRODUCTS . "
            (products_quantity,
             products_images,
             products_model,
             ". $products_price_list . ",
             products_date_added,
             products_date_available,
             products_weight,
             lable_1,
             lable_2,
             lable_3,
             products_status,
             products_tax_class_id,
             products_quantity_order_min,
             products_quantity_order_units,
             products_sort_order,
             manufacturers_id)
           values
             ('" . tep_db_input($product['products_quantity']) . "',
             '" . $new_images_str . "',
             '" . tep_db_input($product['products_model']) . "',
             " . $products_instval . ",
             now(),
             " . (empty($product['products_date_available']) ? "null" : "'" . tep_db_input($product['products_date_available']) . "'") . ",
             '" . tep_db_input($product['products_weight']) . "',
             '" . tep_db_input($product['lable_1']) . "',
             '" . tep_db_input($product['lable_2']) . "',
             '" . tep_db_input($product['lable_3']) . "',
             '0',
             '" . (int)$product['products_tax_class_id'] . "',
             '" . (int)$product['products_quantity_order_min'] . "',
             '" . (int)$product['products_quantity_order_units'] . "',
             '" . (int)$product['products_sort_order'] . "',
             '" . (int)$product['manufacturers_id'] . "')");

            $dup_products_id = tep_db_insert_id();

            $description_query = tep_db_query("select language_id, products_name, products_info, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "'");
            while ($description = tep_db_fetch_array($description_query)) {
              tep_db_query("insert into " . TABLE_PRODUCTS_DESCRIPTION . " (products_id, language_id, products_name, products_info, products_description, products_head_title_tag, products_head_desc_tag, products_head_keywords_tag, products_url, products_viewed) values ('" . (int)$dup_products_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['products_name']) . "', '". tep_db_input($description['products_info']) . "', '" . tep_db_input($description['products_description']) . "', '" . tep_db_input($description['products_head_title_tag']) . "', '" . tep_db_input($description['products_head_desc_tag']) . "', '" . tep_db_input($description['products_head_keywords_tag']) . "', '" . tep_db_input($description['products_url']) . "', '0')");
            }

            tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$dup_products_id . "', '" . (int)$categories_id . "')");

            $products_id_from=tep_db_input($products_id);
            $products_id_to= $dup_products_id;
            $products_id = $dup_products_id;

            if ( $_POST['copy_attributes']=='copy_attributes_yes' and $_POST['copy_as'] == 'duplicate' ) {

              $copy_attributes_delete_first='1';
              $copy_attributes_duplicates_skipped='1';
              $copy_attributes_duplicates_overwrite='0';

              if (DOWNLOAD_ENABLED == 'true') {
                $copy_attributes_include_downloads='1';
                $copy_attributes_include_filename='1';
              } else {
                $copy_attributes_include_downloads='0';
                $copy_attributes_include_filename='0';
              }
              tep_copy_products_attributes($products_id_from,$products_id_to);
            }
          }

        }

        tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $categories_id . '&pID=' . $products_id));
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
    <script language="javascript" src="includes/general.js"></script>
<?php if ($_GET['action'] == 'new_category_ACD' || $_GET['action'] == 'edit_category_ACD') { ?>
    <script type="text/javascript" src="../includes/ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src="../includes/ckfinder/ckfinder.js"></script>
<?php } ?>
<!--    <script type="text/javascript" src="../includes/javascript/lib/jquery-1.7.1.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js"></script> -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <style>
      .ui-tabs .ui-tabs-panel {
      box-shadow: 0 1px 5px -2px #000;
      }
      .ui-tabs .ui-tabs-nav .ui-tabs-anchor{
        text-align: center;
      }

      .ui-widget-content { border: 0px solid #e7e7e7; background: #fff url(images/ui-bg_flat_75_ffffff_40x100.png) 50% 50% repeat-x; color: #222222; }
    </style>
    <script type="text/javascript">
      $(function(){
        $('.tabs').tabs({
          collapsible: true
        });
        // открываем селектбокс с категориями:
        $('#left_cats').attr('size',$('#left_cats option').size());
      });
    </script>

    <!-- AJAX Attribute Manager  -->
    <?php require_once( 'attributeManager/includes/attributeManagerHeader.inc.php' )?>
    <!-- AJAX Attribute Manager  end -->
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="goOnLoad();">

<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_smend //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
       <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
  <?php
  if ($_GET['action'] == 'new_category_ACD' || $_GET['action'] == 'edit_category_ACD') {
    if ( ($_GET['cID']) && (!$_POST) ) {
      $categories_query = tep_db_query("select c.categories_id, c.categories_status, cd.categories_name, cd.categories_url, cd.categories_heading_title, cd.categories_description,  cd.categories_meta_title, cd.categories_meta_description, cd.categories_meta_keywords, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . $_GET['cID'] . "' and c.categories_id = cd.categories_id and cd.language_id = '" . $languages_id . "' order by c.sort_order, cd.categories_name");
      $category = tep_db_fetch_array($categories_query);


      $cInfo = new objectInfo($category);
    } elseif ($_POST) {
      $cInfo = new objectInfo($_POST);
      $categories_name = $_POST['categories_name'];
      $categories_url = $_POST['categories_url'];
      $categories_heading_title = $_POST['categories_heading_title'];
      $categories_description = $_POST['categories_description'];
      $categories_meta_title = $_POST['categories_meta_title'];
      $categories_meta_description = $_POST['categories_meta_description'];
      $categories_meta_keywords = $_POST['categories_meta_keywords'];

    } else {
      $cInfo = new objectInfo(array());
    }

    $languages = tep_get_languages();

    $text_new_or_edit = ($_GET['action']=='new_category_ACD') ? TEXT_INFO_HEADING_NEW_CATEGORY : TEXT_INFO_HEADING_EDIT_CATEGORY;
?>
      <tr>
        <td class="pageHeading"><?php echo sprintf($text_new_or_edit, tep_output_generated_category_path($current_category_id)); ?></td>
      </tr>
      <tr><?php

      $form_action = ($_GET['cID']) ? 'update_category' : 'insert_category';
      echo tep_draw_form($form_action, FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $_GET['cID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"'); ?>

        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_EDIT_CATEGORIES_NAME; ?></td>
            <td class="main">
              <div class="tabs">
                <ul>
                <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                  <li><a href="#categories-tabs-<?php echo $i ?>"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'16') ?><br><small><?php echo $languages[$i]['name'] ?></small></a></li>
                  <?php } ?>
                </ul>
                <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                  <div id="categories-tabs-<?php echo $i ?>">
                      <?php echo tep_product_link_input('categories_name[' . $languages[$i]['id'] . ']', (($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : tep_get_category_name($cInfo->categories_id, $languages[$i]['id']))) ?>
                  </div>
                <?php } ?>
              </div>
            </td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_CATEGORIES_URL; ?></td>
            <td class="main">
              <div class="tabs">
                <ul>
                <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                  <li><a href="#categories-url-tabs-<?php echo $i ?>"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'16') ?><br><small><?php echo $languages[$i]['name'] ?></small></a></li>
                  <?php } ?>
                </ul>
                <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                  <div id="categories-url-tabs-<?php echo $i ?>">    
                      <?php echo tep_product_link_input('categories_url[' . $languages[$i]['id'] . ']', (($categories_url[$languages[$i]['id']]) ? stripslashes($categories_url[$languages[$i]['id']]) : tep_get_category_url($cInfo->categories_id, $languages[$i]['id']))) ?>
                  </div>
                <?php } ?>
              </div>
            </td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?></td>
            <td class="main">
              <div class="tabs">
                <ul>
                <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                  <li><a href="#categories_heading_title-tabs-<?php echo $i ?>"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'16') ?><br><small><?php echo $languages[$i]['name'] ?></small></a></li>
                  <?php } ?>
                </ul>
                <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                  <div id="categories_heading_title-tabs-<?php echo $i ?>">
                      <?php echo tep_product_link_input('categories_heading_title[' . $languages[$i]['id'] . ']', (($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : tep_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']))) ?>
                  </div>
                <?php } ?>
              </div>
            </td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?></td>
            <td class="main">
              <div class="tabs">
                <ul>
                <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                  <li><a href="#categories_description-tabs-<?php echo $i ?>"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'16') ?><br><small><?php echo $languages[$i]['name'] ?></small></a></li>
                  <?php } ?>
                </ul>
                <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                  <div id="categories_description-tabs-<?php echo $i ?>">
                      <?php echo tep_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '80', '20', (($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : tep_get_category_description($cInfo->categories_id, $languages[$i]['id'])), 'class="ckeditor"'); ?>
                      <script type="text/javascript">
                         var editor = CKEDITOR.replace( 'categories_description[<?php echo $languages[$i][id]; ?>]');
                         CKFinder.setupCKEditor( editor, '../includes/ckfinder/' ) ;
                      </script>
                  </div>
                <?php } ?>
              </div>

            </td>
          </tr>




          <tr>
            <td class="main"><?php echo TEXT_META_TITLE; ?></td>
            <td class="main">
              <div class="tabs">
                <ul>
                <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                  <li><a href="#categories_meta_title-tabs-<?php echo $i ?>"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'16') ?><br><small><?php echo $languages[$i]['name'] ?></small></a></li>
                  <?php } ?>
                </ul>
                <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                  <div id="categories_meta_title-tabs-<?php echo $i ?>">
                      <?php echo tep_product_link_input('categories_meta_title[' . $languages[$i]['id'] . ']', (($categories_meta_title[$languages[$i]['id']]) ? stripslashes($categories_meta_title[$languages[$i]['id']]) : tep_get_category_meta_title($cInfo->categories_id, $languages[$i]['id']))); ?>
                  </div>
                <?php } ?>
              </div>

            </td>

          </tr>

          <tr>
            <td class="main"><?php echo TEXT_META_DESCRIPTION; ?></td>
            <td class="main">
                <div class="tabs">
                  <ul>
                  <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                    <li><a href="#categories_meta_description-tabs-<?php echo $i ?>"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'16') ?><br><small><?php echo $languages[$i]['name'] ?></small></a></li>
                    <?php } ?>
                  </ul>
                  <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                    <div id="categories_meta_description-tabs-<?php echo $i ?>">
                        <?php echo tep_product_link_input('categories_meta_description[' . $languages[$i]['id'] . ']', (($categories_meta_description[$languages[$i]['id']]) ? stripslashes($categories_meta_description[$languages[$i]['id']]) : tep_get_category_meta_description($cInfo->categories_id, $languages[$i]['id']))) ?>
                    </div>
                  <?php } ?>
                </div>
              </td>
          </tr>

          <tr>
            <td class="main"><?php echo TEXT_META_KEYWORDS; ?></td>
            <td class="main">
              <div class="tabs">
                <ul>
                <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                  <li><a href="#categories_meta_keywords-tabs-<?php echo $i ?>"><?php echo tep_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'16') ?><br><small><?php echo $languages[$i]['name'] ?></small></a></li>
                  <?php } ?>
                </ul>
                <?php for ($i=0; $i<sizeof($languages); $i++) { ?>
                  <div id="categories_meta_keywords-tabs-<?php echo $i ?>">
                      <?php echo  tep_product_link_input('categories_meta_keywords[' . $languages[$i]['id'] . ']', (($categories_meta_keywords[$languages[$i]['id']]) ? stripslashes($categories_meta_keywords[$languages[$i]['id']]) : tep_get_category_meta_keywords($cInfo->categories_id, $languages[$i]['id'])))?>
                  </div>
                <?php } ?>
              </div>
            </td>
          </tr>


          <tr>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_CATEGORIES_IMAGE; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('categories_image') . '<br>' . tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . $cInfo->categories_image . tep_draw_hidden_field('categories_previous_image', $cInfo->categories_image); ?></td>
          </tr>
          <?php  if($cInfo->categories_image): ?>
          <tr>
            <td class="main"></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?>
            <img src="/r_imgs.php?thumb=<?php echo $cInfo->categories_image;?>" alt="">
            <br><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?>Удалить фото<input type="checkbox" name="delete_categories_image"></td>
          </tr>
          <?php  endif; ?>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_SORT_ORDER; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_STATUS; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('categories_status', ($cInfo->categories_status!='')?$cInfo->categories_status:1, 'size="2"'); ?>&nbsp;<?php echo TEXT_DEFINE_CATEGORY_STATUS; ?></td>
          </tr>


        </table></td>
      </tr>
      <tr>
        <td class="main" align="right">
          <?php
            if ($_GET['cID']) echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
            else echo tep_image_submit('button_insert.gif', IMAGE_INSERT);

            echo tep_draw_hidden_field('categories_date_added', (($cInfo->date_added) ? $cInfo->date_added : date('Y-m-d'))) .
                 tep_draw_hidden_field('parent_id', $cInfo->parent_id) .
                 '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $_GET['cID']) . '">' .
                 tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
          ?>
        </td>
      </form></tr>
<?php

  } else {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td align="left"><table border="0" width="100%" height="19" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText" align="left">
                  <div style="float:left;">
<?php
    echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');
    echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
    echo '</form>&nbsp;&nbsp;';
?>
                  </div>
                  <div style="float:left;">
<?php
    echo tep_draw_form('search', FILENAME_CATEGORIES, '', 'get');
    echo '<div style="float:left;">'.HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search').'</div>';
    echo '<div style="float:left;padding:0 3px;">'.tep_image_submit('button_search.gif', IMAGE_SEARCH).'</div>';
//    echo '<div style="float:left;">'.tep_draw_checkbox_field('search_model_key').'</div>';
//    echo '<div style="float:left;line-height:0.9;padding:0 3px;">'.HEADING_TITLE_SEARCH_MODEL.'</div>';
    echo '<div style="clear:both;"></div>';
    echo '</form>';
?>
                  </div>
                  <div style="clear:both;"></div>
                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>

        <td><table border="0" height="100%" width="100%" cellspacing="0" cellpadding="0">
          <tr height="100%">
            <td valign="top" class="td_with_cats">
<?php
    echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');
    echo tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'id="left_cats" onChange="this.form.submit();"');
    echo '</form>';
?>
            </td>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" align="center" width="25">#</td>
                <td class="dataTableHeadingContent" align="center" width="100"><?php  echo ACTION; ?></td>
                <td class="dataTableHeadingContent" align="center" width="40"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="center" width="66"><?php  echo PICTURE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
              </tr>
<?php
    $categories_count = 0;
    $rows = 0;
    if (isset($_GET['search'])) {
      $search = tep_db_prepare_input($_GET['search']);
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_meta_title, cd.categories_meta_description, cd.categories_meta_keywords, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status, c.categories_to_xml from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and cd.categories_name like '%" . tep_db_input($search) . "%' order by c.sort_order, cd.categories_name");
        }  else {
      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, cd.categories_meta_title, cd.categories_meta_description, cd.categories_meta_keywords, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status, c.categories_to_xml from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
}

    while ($categories = tep_db_fetch_array($categories_query)) {
       $categories_count++;
      $rows++;

      if (isset($_GET['search'])) $cPath= $categories['parent_id'];

      if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => tep_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => tep_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge($categories, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        $r_selected='1';
      } else {
        $r_selected='0';
      }

      echo '  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '\'">' . "\n";
?>
                <td class="dataTableContent nobg bg_left" align="center"><?php echo $categories['sort_order']; ?></td>
                <td class="dataTableContent" align="right">
                <?php
                  echo '&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=move_category') . '">'.tep_image(DIR_WS_ICONS . 'move.gif', IMAGE_MOVE) . '</a>';
                  echo '&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=copy_product_attributes_categories') . '">'.tep_image(DIR_WS_ICONS . 'copy_atr.gif', ATTRIBUTES_COPY_MANAGER_COPY) . '</a>';
                  echo '&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=delete_category') . '">'.tep_image(DIR_WS_ICONS . 'del.gif', IMAGE_DELETE) . '</a>';
                  echo '&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=edit_category') . '">'.tep_image(DIR_WS_ICONS . 'icon_properties_add.gif', PHOTOGALLERY_EDIT) . '</a>';
                ?>
                </td>
                <td class="dataTableContent" align="center">
<?php
      if ($categories['categories_status'] == '1') {
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag_cat&flag=0&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag_cat&flag=1&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>';
      }
?>

<?php
  if(XML_MODULE_ENABLED == 'true'){
      if ($categories['categories_to_xml'] == '1')
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setxml_cat&flagxml=0&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      else
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setxml_cat&flagxml=1&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
   }
?>
                </td>
                <td class="nobg" style="width:70px;text-align:center;">
                  <?php
                    if($categories['categories_image']!='')
                    echo tep_info_image($categories['categories_image'], '', '', '64');
                    else echo tep_info_image('noimage.jpg', '', '64', '64');
                  ?>
                </td>
                <td class="dataTableContent" style="padding-left:10px;font-size:12px;">
                  <div class="left"><?php echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, tep_get_path($categories['categories_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>'; ?></div>
                  <div class="left" style="padding:2px 0 0 5px;">
                    <?php echo '<b>' . $categories['categories_name'] . '</b><br /><span style="color:#aaa;">'.
                    TBL_HEADING_SUBCATEGORIES_COUNT.': ' . tep_childs_in_category_count($categories['categories_id']) . ', ' .
                    TBL_HEADING_PRODUCTS_COUNT.': ' . tep_products_in_category_count($categories['categories_id']).'</span>'; ?>
                  </div>
                  <div class="clear"></div>
                </td>
              </tr>
<?php
    }

    $products_count = 0;
if (!isset($page)){$page=0;};

  $max_prod_admin_side_q=tep_db_query("select configuration_value FROM configuration WHERE configuration_key='MAX_PROD_ADMIN_SIDE'");
  $max_prod_admin_side=(tep_db_fetch_array($max_prod_admin_side_q));
  #
  $max_count=$max_prod_admin_side['configuration_value'];
  #

    if (isset($_GET['search'])) {
      $search_query_string = " and (p.products_model like '%" . tep_db_input($search) . "%' or p.products_internal_sku like '%" . tep_db_input($search) . "%' or pd.products_name like '%" . tep_db_input($search) . "%' or p.products_barcode like '%" . tep_db_input($search) . "%') ";

    } else {
       $search_query_string = " and p2c.categories_id = '" . (int)$current_category_id . "' ";
    }
       $products_query_line = "select p.products_images, p.products_id, pd.products_name, p.products_quantity, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_to_xml, p.products_sort_order, p.products_model, p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id ".$search_query_string." order by pd.products_name, p.products_id";
       $products_query = tep_db_query($products_query_line);
       $numr=tep_db_num_rows($products_query);

  if ( (isset($pID)) and ($numr>0) ){
    $pnum=1;

    while ($row=tep_db_fetch_array($products_query)){
      if ($row["products_id"]==$pID){
                $pnum=($pnum/$max_count);
                  if (strpos($pnum,".")>0){
                  $pnum=substr($pnum,0,strpos($pnum,"."));
                  } else{
                  if ($pnum<>0){
                      $pnum=$pnum-1;
                        }
                  }
                  $page=$pnum*$max_count;
                break;
      }
      $pnum++;
    }
  }

  $products_query_line .= " limit ".$page.",".$max_count;
  $products_query = tep_db_query($products_query_line);

  if ($numr>$max_count){
      $kn=0;
      $stp= TEXT_PAGES;

      $im=1;$nk=0;
      while ($kn<$numr){
      if ($kn<>$page){
        if(isset($_GET['search']) && !empty($_GET['search'])) {
            $stp.='<a href=categories.php?search='.$_GET['search'].'&page='.$kn.'>'.$im.'</a>&nbsp';
        } else {          
            $stp.='<a href=categories.php?cPath='.$cPath.'&page='.$kn.'>'.$im.'</a>&nbsp';
        }
      }else{
      $stp.='<font color="CC0000">['.$im.']</font>&nbsp';
      }
      $kn=$kn+$max_count;
      $nk=$nk+$max_count;
      if ($nk>=$max_count*40){$stp.='<br>';$nk=0;}
      $im++;
      }
  }


    while ($products = tep_db_fetch_array($products_query)) {
        $products_count++;
      $rows++;

// Get categories_id for product if search
      if (isset($_GET['search'])) $cPath = $products['categories_id'];

      if ( (!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['products_id']))) && !isset($pInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $pInfo = new objectInfo($products);
      }

      if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
           $r_selected='1';
      } else {
           $r_selected='0';
      }

      echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_PRODUCTS, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product') . '\'">' . "\n";

?>
                <td class="dataTableContent nobg bg_left" align="center">
                  <?php  echo $products['products_sort_order']; ?>
                </td>
                <td class="dataTableContent actions_class" align="right" style="width:100px;">
                <?php
                  echo '&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=move_product') . '">'.tep_image(DIR_WS_ICONS . 'move.gif', IMAGE_MOVE) . '</a>';
                  echo '&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=copy_to') . '">'.tep_image(DIR_WS_ICONS . 'copy.gif', IMAGE_COPY_TO) . '</a>';
                  echo '&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=copy_product_attributes') . '">'.tep_image(DIR_WS_ICONS . 'copy_atr.gif', ATTRIBUTES_COPY_MANAGER_COPY) . '</a>';
                  echo '&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCTS, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product') . '">'.tep_image(DIR_WS_ICONS . 'icon_properties_add.gif', ICON_PREVIEW) . '</a>';
                  echo '&nbsp;<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=delete_product') . '">'.tep_image(DIR_WS_ICONS . 'del.gif', IMAGE_DELETE) . '</a>';
                ?>
                </td>
                <td class="dataTableContent" align="center">
<?php
      if ($products['products_status'] == '1') {
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 16, 16) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 16, 16) . '</a>';
      }
?>

<?php
if(XML_MODULE_ENABLED == 'true'){
      if ($products['products_to_xml'] == '1')
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setxml&flagxml=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      else
        echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'action=setxml&flagxml=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
}
?>
                </td>
                <td class="nobg" style="width:70px;text-align:center;">
                  <?php
                    $prod_img = explode(';', $products['products_images']);
                    if($prod_img[0]!='') echo tep_info_image($prod_img[0], '', '', '64',true);
                    else echo tep_info_image('noimage.jpg', '', '64', '64');
                  ?>
                </td>
                <td class="dataTableContent" style="padding-left:10px;"><?php

                    echo '<span style="font-size:12px;font-weight:bold;">'.$products['products_name'].'</span>';
                    echo '<br /><br />#'.$products['products_id'].'';
                    if($products['products_model']) echo '&nbsp;&nbsp;&nbsp;&nbsp;'. CATEG1 .': '.$products['products_model'].'';
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;'. CATEG2 .': <b>'.$products['products_quantity'].'</b>';
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;'. CATEG3 .': <b>'.$currencies->format($products['products_price']).'</b>';

                ?></td>


              </tr>
<?php
   if($r_selected=='1') {

    $heading = array();
    $contents = array();
    switch ($action) {
      case 'delete_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=delete_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
        $contents[] = array('text' => '<br><b>' . $pInfo->products_name . '</b>');

        $product_categories_string = '';
        $product_categories = tep_generate_category_path($pInfo->products_id, 'product');
        for ($i = 0, $n = sizeof($product_categories); $i < $n; $i++) {
          $category_path = '';
          for ($j = 0, $k = sizeof($product_categories[$i]); $j < $k; $j++) {
            $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $category_path = substr($category_path, 0, -16);
          $product_categories_string .= tep_draw_checkbox_field('product_categories[]', $product_categories[$i][sizeof($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
        }
        $product_categories_string = substr($product_categories_string, 0, -4);

        $contents[] = array('text' => '<br>' . $product_categories_string);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=move_product_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => tep_draw_form('copy_to', FILENAME_CATEGORIES, 'action=copy_to_confirm&cPath=' . $cPath) . tep_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . tep_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES . '<br>' . tep_draw_pull_down_menu('categories_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
// BOF: WebMakers.com Added: Attributes Copy
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','1'));
        // only ask about attributes if they exist
        if (tep_has_product_attributes($pInfo->products_id)) {
          $contents[] = array('text' => '<br>' . TEXT_COPY_ATTRIBUTES_ONLY);
          $contents[] = array('text' => '<br>' . TEXT_COPY_ATTRIBUTES . '<br>' . tep_draw_radio_field('copy_attributes', 'copy_attributes_yes', true) . ' ' . TEXT_COPY_ATTRIBUTES_YES . '<br>' . tep_draw_radio_field('copy_attributes', 'copy_attributes_no') . ' ' . TEXT_COPY_ATTRIBUTES_NO);
          $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER . '<br>' . tep_draw_separator('pixel_trans.gif', '1', '10'));
          $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','1'));
        }
// EOF: WebMakers.com Added: Attributes Copy

        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

/////////////////////////////////////////////////////////////////////
// WebMakers.com Added: Copy Attributes Existing Product to another Existing Product
      case 'copy_product_attributes':
        $copy_attributes_delete_first='1';
        $copy_attributes_duplicates_skipped='1';
        $copy_attributes_duplicates_overwrite='0';

        if (DOWNLOAD_ENABLED == 'true') {
          $copy_attributes_include_downloads='1';
          $copy_attributes_include_filename='1';
        } else {
          $copy_attributes_include_downloads='0';
          $copy_attributes_include_filename='0';
        }

        $heading[] = array('text' => '<b>' . ATTRIBUTES_COPY_MANAGER_13 . '</b>');
        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=create_copy_product_attributes&cPath=' . $cPath . '&pID=' . $pInfo->products_id) . tep_draw_hidden_field('products_id', $pInfo->products_id) . tep_draw_hidden_field('products_name', $pInfo->products_name));
        $contents[] = array('text' => '<br>' . ATTRIBUTES_COPY_MANAGER_2 . '<b>' . $pInfo->products_name . '</b><br>' . ATTRIBUTES_COPY_MANAGER_15 . '<b>' . $pInfo->products_id . '</b>');
        $contents[] = array('text' => ATTRIBUTES_COPY_MANAGER_16 . tep_draw_input_field('copy_to_products_id', $copy_to_products_id, 'size="3"') . ATTRIBUTES_COPY_MANAGER_3);
        $contents[] = array('text' => '<br>' . ATTRIBUTES_COPY_MANAGER_17 . tep_draw_checkbox_field('copy_attributes_delete_first',$copy_attributes_delete_first, 'size="2"'));
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','1'));
        $contents[] = array('text' => '<br>' . ATTRIBUTES_COPY_MANAGER_7);
        $contents[] = array('text' => ATTRIBUTES_COPY_MANAGER_8 . tep_draw_checkbox_field('copy_attributes_duplicates_skipped',$copy_attributes_duplicates_skipped, 'size="2"'));
        $contents[] = array('text' => ATTRIBUTES_COPY_MANAGER_9 . tep_draw_checkbox_field('copy_attributes_duplicates_overwrite',$copy_attributes_duplicates_overwrite, 'size="2"'));
        if (DOWNLOAD_ENABLED == 'true') {
          $contents[] = array('text' => '<br>' . ATTRIBUTES_COPY_MANAGER_10 . tep_draw_checkbox_field('copy_attributes_include_downloads',$copy_attributes_include_downloads, 'size="2"'));
        }
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','1'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . PRODUCT_NAMES_HELPER);
        if ($pID) {
          $contents[] = array('align' => 'center', 'text' => '<br>' . ATTRIBUTES_NAMES_HELPER);
        } else {
          $contents[] = array('align' => 'center', 'text' => '<br>Select a product for display');
        }
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', ATTRIBUTES_COPY_MANAGER_COPY) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
// WebMakers.com Added: Copy Attributes Existing Product to All Products in Category
      case 'copy_product_attributes_categories':
        $copy_attributes_delete_first='1';
        $copy_attributes_duplicates_skipped='1';
        $copy_attributes_duplicates_overwrite='0';

        if (DOWNLOAD_ENABLED == 'true') {
          $copy_attributes_include_downloads='1';
          $copy_attributes_include_filename='1';
        } else {
          $copy_attributes_include_downloads='0';
          $copy_attributes_include_filename='0';
        }

        $heading[] = array('text' => '<b>' . ATTRIBUTES_COPY_MANAGER_1 . '</b>');
        $contents = array('form' => tep_draw_form('products', FILENAME_CATEGORIES, 'action=create_copy_product_attributes_categories&cPath=' . $cPath . '&cID=' . $cID . '&make_copy_from_products_id=' . $copy_from_products_id));
        $contents[] = array('text' => ATTRIBUTES_COPY_MANAGER_2 . tep_draw_input_field('make_copy_from_products_id', $make_copy_from_products_id, 'size="3"') . ATTRIBUTES_COPY_MANAGER_3);
        $contents[] = array('text' => '<br>' . ATTRIBUTES_COPY_MANAGER_4 . '<b>' . tep_get_category_name($cID, $languages_id) . '</b><br>' .ATTRIBUTES_COPY_MANAGER_5 . $cID);
        $contents[] = array('text' => '<br>' . ATTRIBUTES_COPY_MANAGER_6 . tep_draw_checkbox_field('copy_attributes_delete_first',$copy_attributes_delete_first, 'size="2"'));
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','1'));
        $contents[] = array('text' => '<br>' . ATTRIBUTES_COPY_MANAGER_7);
        $contents[] = array('text' => ATTRIBUTES_COPY_MANAGER_8 . tep_draw_checkbox_field('copy_attributes_duplicates_skipped',$copy_attributes_duplicates_skipped, 'size="2"'));
        $contents[] = array('text' => '<br>' . ATTRIBUTES_COPY_MANAGER_9 . tep_draw_checkbox_field('copy_attributes_duplicates_overwrite',$copy_attributes_duplicates_overwrite, 'size="2"'));
        if (DOWNLOAD_ENABLED == 'true') {
          $contents[] = array('text' => '<br>' . ATTRIBUTES_COPY_MANAGER_10 . tep_draw_checkbox_field('copy_attributes_include_downloads',$copy_attributes_include_downloads, 'size="2"'));
        }
        $contents[] = array('text' => '<br>' . tep_image(DIR_WS_IMAGES . 'pixel_black.gif','','100%','1'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . PRODUCT_NAMES_HELPER);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', ATTRIBUTES_COPY_MANAGER_COPY) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cID) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
    }

    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
      echo '            <tr><td colspan="5" style="border:2px solid red;">' . "\n";
      $box = new box;
      echo $box->infoBox($heading, $contents);
      echo '            </td></tr>' . "\n";
    }
   }
    }

    $cPath_back = '';
    if (sizeof($cPath_array) > 0) {
      for ($i=0, $n=sizeof($cPath_array)-1; $i<$n; $i++) {
        if (empty($cPath_back)) {
          $cPath_back .= $cPath_array[$i];
        } else {
          $cPath_back .= '_' . $cPath_array[$i];
        }
      }
    }

    $cPath_back = (tep_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?>
                    <br>
                    <?php echo TEXT_TOTAL_PRODUCTS . $numr; ?>
                    <br>
                    <?php echo $stp; ?>
                    </td>
</tr>
<tr>
                    <td align="right" class="smallText"><?php if (sizeof($cPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, $cPath_back . 'cID=' . $current_category_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($_GET['search'])) echo '<a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&action=new_category') . '">' . tep_image_button('button_new_category.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . tep_href_link(FILENAME_PRODUCTS, 'cPath=' . $cPath . '&action=new_product') . '">' . tep_image_button('button_new_product.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
         case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=delete_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

        $contents = array('form' => tep_draw_form('categories', FILENAME_CATEGORIES, 'action=move_category_confirm&cPath=' . $cPath) . tep_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . tep_draw_pull_down_menu('move_to_category_id', tep_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
    }

    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
      echo '            <td width="25%" height="100%" valign="top">' . "\n";
       echo '                    <div style="padding-left:10px;">';

      $box = new box;
      echo $box->infoBox($heading, $contents);

      echo '            </div></td>' . "\n";
    }
?>
          </tr>
        </table></td>
      </tr>
    </table>
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
