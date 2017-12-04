<?php

/*
 * Иконка списка жеданий в шапке
 */

if(WISHLIST_MODULE_ENABLED !== 'true')
{
    return;
}

?>
<a rel="nofollow"
    href="<?php echo tep_href_link(FILENAME_WISHLIST); ?>"
    class="icon icon-wishlist"
    ><?php echo empty($_SESSION['wishList']->wishID) ? 0 : count($_SESSION['wishList']->wishID); ?></a>
<script>
    var wishlist = <?php echo json_encode(empty($_SESSION['wishList']->wishID) ? array() : array_keys($_SESSION['wishList']->wishID)); ?>;
</script>