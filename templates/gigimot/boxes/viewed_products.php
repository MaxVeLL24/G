<?php

$count = !empty($_SESSION['viewed_products']) && is_array($_SESSION['viewed_products']) ? count($_SESSION['viewed_products']) : 0;
?>
<a rel="nofollow"
    href="<?php echo tep_href_link(FILENAME_VIEWED_PRODUCTS); ?>"
    class="icon icon-viewed-products"
    ><?php echo sprintf('%d', $count); ?></a>