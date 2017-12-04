<?php

require('price_settings.php');
// the following cPath references come from application_top.php
$category_depth = 'top';
if(isset($cPath) && tep_not_null($cPath))
{
    $categories_products_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . (int) $current_category_id . "'");
    $cateqories_products = tep_db_fetch_array($categories_products_query);
    if($cateqories_products['total'] > 0)
    {
        $category_depth = 'products'; // display products
    }
    else
    {
        $category_parent_query = tep_db_query("select count(*) as total from " . TABLE_CATEGORIES . " where parent_id = '" . (int) $current_category_id . "'");
        $category_parent = tep_db_fetch_array($category_parent_query);
        if($category_parent['total'] > 0)
        {
            $category_depth = 'nested'; // navigate through the categories
        }
        else
        {
            $category_depth = 'products'; // category has no products, but display the 'no products' message
        }
    }
}

?>
<h1><?php echo HEADING_TITLE; ?></h1>
<div class="tab-content common-styled-block">
<?php

// есть у группы продукты?
// group have products?
function check_products($id_group)
{
    $products_price_query = tep_db_query("select products_to_categories.products_id FROM products_to_categories where products_to_categories.categories_id = " . $id_group . " LIMIT 0,1");
    if(tep_db_fetch_array($products_price_query))
    {
        return true;
    }
    return false;
}

// выводим список продуктов определенной группы $id_group
// list products determined group
function get_products($id_group, $position)
{
    global $currencies;
    global $languages_id;

    $query = "";
    if(!SHOW_MARKED_OUT_STOCK)
    {
        $query = " and products.products_status = 1";
    }
    if(USED_QUANTITY)
    {
        $query = $query . " and products.products_quantity <> 0";
    }
    $products_price_query = tep_db_query("select products_description.products_name, products.products_quantity, products.products_price, products.products_model, products_to_categories.products_id, products_to_categories.categories_id FROM products, products_description, products_to_categories where products.products_id = products_description.products_id" . $query . " and products.products_id = products_to_categories.products_id and products_to_categories.categories_id = " . $id_group . " and products_description.language_id = " . $languages_id);
    $x = 0;
    while($products_price = tep_db_fetch_array($products_price_query))
    {
        if($x == 1)
        {
            $col = "";
            $x = 0;
        }
        else
        {
            $col = "";
            $x++;
        }
        $cell = tep_get_products_special_price($products_price['products_id']);
        if($cell == 0)
        {
            $cell = $products_price['products_price'];

            // BOF FlyOpenair: Extra Product Price
            $cell = extra_product_price($cell);
            // EOF FlyOpenair: Extra Product Price
        }
        else
        {
            $col = "#FFEAEA";
        }
        $quantity = "";
        $str = "";
        for($i = 0; $i < $position; $i++)
        {
            $str = $str . "&nbsp;&nbsp;&nbsp;";
        }
        print "<tr class=\"boxText2\" bgcolor=\"" . $col . "\"><td width=\"80%\" class=\"boxText2\">" . $str . "<a href=\"" . tep_href_link(FILENAME_PRODUCT_INFO, "products_id=" . $products_price['products_id']) . "\">" . $products_price['products_name'] . "</a></td><td width=\"20%\" align=\"right\" class=\"boxText2\">" . $currencies->display_price($cell, TAX_INCREASE) . "&nbsp;</td></tr>";
    }
}

// рекурсивная функция, получает группы по порядку
// get all groups
function get_group($id_parent, $position)
{
    global $languages_id;
    $groups_price_query = tep_db_query("select categories.categories_id,categories.parent_id, categories_description.categories_name from
categories, categories_description where categories_status=1 and categories.categories_id = categories_description.categories_id and
categories.parent_id = " . $id_parent . " and categories_description.language_id = '" . (int) $languages_id . "' order by
categories.sort_order");

    while($groups_price = tep_db_fetch_array($groups_price_query))
    {
        $str = "";
        for($i = 0; $i < $position; $i++)
        {
            $str = $str . "&nbsp;&nbsp;&nbsp;";
        }

        if(check_products($groups_price['categories_id']) || $position == 0)
        {
            if($groups_price['parent_id']=='0'){
                $parent="";
            }
            else{
                $parent=$groups_price['parent_id'].'_';
            }
            $superparent='';
            if ($position==0){
                $_POST['par']=$groups_price['categories_id'];

            }
            if ($position==2){
                $superparent=$_POST['par']."_";
            }
            print "<tr><td colspan=\"2\" class=\"boxText3\">
      " . $str . "&nbsp;<a class=productlisting-headingPrice href='" . tep_href_link(FILENAME_DEFAULT, 'cPath='.$superparent.$parent.$groups_price['categories_id']) . "'>" . $groups_price['categories_name'] . "</a>
      </td></tr>";
            get_products($groups_price['categories_id'], $position + 1);
        }
        get_group($groups_price['categories_id'], $position + 1); // следующая группа
    }
}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
    <?php
    get_group(0, 0);
    ?>
</table>
</div>