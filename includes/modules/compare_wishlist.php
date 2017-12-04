<?php

// Текущий ID товара
$products_id = isset($_GET['products_id']) ? $_GET['products_id'] : null;
if($products_id)
{
    $products_id = tep_get_prid($products_id);
}

// Сравнение
$compare = '';
if(COMPARE_MODULE_ENABLED == 'true')
{
    $in_comparison = array();
    if(!empty($_SESSION['compares']))
    {
        foreach($_SESSION['compares'] as $uprid => $product)
        {
            if($product['products_id'] == $products_id)
            {
                $in_comparison[] = $uprid;
            }
        }
    }
    ?>
<script>
    var compares = <?php echo json_encode($in_comparison); ?>;
</script>
<div class="compare-item">
    <span class="custom-checkbox">
        <input
            type="checkbox"
            id="add-to-comparison"
            />
        <label for="add-to-comparison"></label>
    </span>
    <label
        for="add-to-comparison"
        data-text-added="<?php echo GO_COMPARE; ?>"
        data-text-not-added="<?php echo COMPARE; ?>"
        ><?php echo $in_comparison ? GO_COMPARE : COMPARE; ?></label>
</div>
<?php
}

// Список желаний
$wishlist = '';
if(WISHLIST_MODULE_ENABLED == 'true')
{
    $wishlist = '<div class="wishlist_block">';
    $data_mode = 'listing';
    if(isset($_SESSION['wishList']->wishID[$id]))
    {
        $wishlist .= '<a data-id="' . $id . '" data-mode="' . $data_mode . '" data-action="delete" class="wishlist_btn">
                          <img alt="" src="' . HTTP_SERVER . '/' . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/check_on.png">
                          <span class="wishlisht_text">' . IN_WHISHLIST . '</span>
                      </a>';
    }
    else
    {
        $wishlist .= '<a data-id="' . $id . '" data-mode="' . $data_mode . '" data-action="add" class="wishlist_btn">
                          <img alt="" src="' . HTTP_SERVER . '/' . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/check_off.png">
                          <span class="wishlisht_text">' . WHISH . '</span>
                      </a>';
    }
    $wishlist .= '</div>';
}