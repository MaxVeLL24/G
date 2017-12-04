<?php

/**
 * Бокс со списком категорий
 */

use EShopmakers\Data\CategoriesTree;

$c_l1 = CategoriesTree::filterByStatus(CategoriesTree::getAllChildren(0), 1);

if(!$c_l1)
{
    return;
}

?>
<div class="box-categories common-styled-block">
    <div class="title"><?php echo HEADER_TITLE_CATALOG; ?></div>
    <ul class="level-1">
        <?php foreach($c_l1 as $cid_l1) : ?>
        <li class="level-1<?php if(($active = !empty($cPath_array) && in_array($cid_l1, $cPath_array))) : ?> active<?php endif; ?>">
            <a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cid_l1); ?>" class="level-1<?php if(($active = !empty($cPath_array) && in_array($cid_l1, $cPath_array))) : ?> active<?php endif; ?>"><?php echo tep_escape(CategoriesTree::$categories_names[$cid_l1]); ?></a>
            <?php if($active && ($c_l2 = CategoriesTree::filterByStatus(CategoriesTree::getAllChildren($cid_l1), 1))) : ?>
            <ul class="level-2">
                <?php foreach($c_l2 as $cid_l2) : ?>
                <li class="level-2<?php if(($active = !empty($cPath_array) && in_array($cid_l2, $cPath_array))) : ?> active<?php endif; ?>">
                    <a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cid_l1 . '_' . $cid_l2); ?>" class="level-2<?php if(($active = !empty($cPath_array) && in_array($cid_l2, $cPath_array))) : ?> active<?php endif; ?>"><?php echo tep_escape(CategoriesTree::$categories_names[$cid_l2]); ?></a>
                    <?php if($active && ($c_l3 = CategoriesTree::filterByStatus(CategoriesTree::getAllChildren($cid_l2), 1))) : ?>
                    <ul class="level-3">
                        <?php foreach($c_l3 as $cid_l3) : ?>
                        <li class="level-3<?php if(!empty($cPath_array) && in_array($cid_l3, $cPath_array)) : ?> active<?php endif; ?>">
                            <a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=' . $cid_l1 . '_' . $cid_l2 . '_' . $cid_l3); ?>" class="level-3<?php if(!empty($cPath_array) && in_array($cid_l3, $cPath_array)) : ?> active<?php endif; ?>"><?php echo tep_escape(CategoriesTree::$categories_names[$cid_l3]); ?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
</div>