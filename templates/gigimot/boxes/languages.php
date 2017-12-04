<?php

/**
 * Переключатель языка
 */

if(!is_object($lng))
{
    include_once(DIR_WS_CLASSES . 'language.php');
    $lng = new language;
    $lng->set_language_by_id($_SESSION['languages_id']);
}

?>
<span class="lang-switch">
    <?php foreach($lng->catalog_languages as $key => $value) { ?>
    <?php if($value['directory'] === $lng->language['directory']) { ?>
    <span><?php echo mb_substr($value['name'], 0, 3, CHARSET); ?></span>
    <?php } else { ?>
    <a
        href="<?php echo tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, empty($connection) ? 'NONSSL' : $connection); ?>"
        hreflang="<?php echo $key === 'ua' ? 'uk' : $key; ?>"
        ><?php echo mb_substr($value['name'], 0, 3, CHARSET); ?></a>
    <?php } ?>
    <?php } ?>
</span>