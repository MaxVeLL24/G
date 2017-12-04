<?php

/**
 * Шаблон хлебных крошек
 */

?>
<ol class="breadcrumbs" itemprop="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
    <?php foreach($this->_trail as $i => $item) { ?>
    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
        <?php if($item['link']) { ?>
        <a href="<?php echo tep_escape($item['link']); ?>" itemprop="url"><span itemprop="name"><?php echo tep_escape($item['title']); ?></span></a>
        <?php } else { ?>
        <span itemprop="name"><?php echo tep_escape($item['title']); ?></span>
        <?php } ?>
        <meta itemprop="position" content="<?php echo $i + 1; ?>">
    </li>
    <?php } ?>
</ol>