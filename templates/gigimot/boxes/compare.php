<?php

/*
 * Иконка сравнения товаров в шапке
 */

if(COMPARE_MODULE_ENABLED !== 'true')
{
    return;
}

$_compares = array();
if(!empty($_SESSION['compares']))
{
    $_compares = array_keys($_SESSION['compares']);
}

?>
<a
    href="<?php echo tep_href_link(FILENAME_COMPARE); ?>"
    class="icon icon-compare"
    ><?php echo empty($_SESSION['compares']) ? 0 : count($_SESSION['compares']); ?></a>
<script>
    var compares = <?php echo json_encode($_compares); ?>;
</script>