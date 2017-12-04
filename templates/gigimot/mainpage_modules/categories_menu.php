<?php

$query_string = <<<SQL
SELECT
    c.categories_id,
    c.parent_id,
    c.categories_image,
    cd.categories_name
FROM categories AS c
INNER JOIN categories_description AS cd
ON c.categories_id = cd.categories_id AND cd.language_id = {$languages_id}
WHERE c.categories_status = 1
ORDER BY c.sort_order ASC, cd.categories_name ASC
SQL;

$query = tep_db_query($query_string);
if(!tep_db_num_rows($query))
{
    return;
}
$categories = array();
$categories_children = array();
while(($row = tep_db_fetch_array($query)) !== false)
{
    // Категория активна?
    $row['active'] = !empty($cPath_array) && in_array($row['categories_id'], $cPath_array);
    $categories[$row['categories_id']] = $row;
    if(empty($categories_children[$row['parent_id']]))
    {
        $categories_children[$row['parent_id']] = array();
    }
    $categories_children[$row['parent_id']][] = $row['categories_id'];
}

if(empty($categories_children[0]))
{
    return;
}

?>
<nav>
    <div class="main-width">
        <div class="toggle-menu clearfix">
            <div class="toggle-button toggle-main-menu"></div>
            <?php if($left_column_modules) : ?>
            <div class="toggle-button toggle-side-menu"></div>
            <?php endif; ?>
            <div class="toggle-button show-cart"><span><?php echo $cart->count_contents(); ?></span></div>
        </div>
        <div class="first-level-items">
            <?php foreach($categories_children[0] as $category_id) : ?>
            <div class="first-level-item-wrapper">
                <a class="first-level-item<?php if($categories[$category_id]['active']) : ?> active<?php endif; ?>" href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=' . $category_id); ?>"><?php echo tep_escape($categories[$category_id]['categories_name']); ?></a>
                <?php if(!empty($categories_children[$category_id])) : ?>
                <div class="first-level-children-wrapper">
                    <div class="main-width">
                        <div class="clearfix<?php if(!empty($categories[$category_id]['categories_image'])) : ?> with-top-category-image<?php endif; ?>">
                            <?php if(!empty($categories[$category_id]['categories_image'])) : ?>
                            <div class="top-category-image"><img src="<?php echo tep_href_link(FILENAME_IMAGE_RESIZER, 'w=150&h=150&thumb=' . rawurlencode($categories[$category_id]['categories_image'])); ?>" alt="<?php echo tep_escape($categories[$category_id]['categories_name']); ?>" /></div>
                            <?php endif; ?>
                            <div class="second-level-items">
                                <?php foreach($categories_children[$category_id] as $category_id) : ?>
                                <div class="second-level-item-wrapper">
                                    <?php if(!empty($categories[$category_id]['categories_image'])) : ?>
                                    <a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=' . $categories[$category_id]['parent_id'] . '_' . $category_id); ?>"> <div class="top-category-image"><img src="<?php echo tep_href_link(FILENAME_IMAGE_RESIZER, 'w=150&h=150&thumb=' . rawurlencode($categories[$category_id]['categories_image'])); ?>" alt="<?php echo tep_escape($categories[$category_id]['categories_name']); ?>" /></div></a>
                                    <?php else : ?>
                                    <a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=' . $categories[$category_id]['parent_id'] . '_' . $category_id); ?>"> <div class="top-category-image"><img src="<?php echo tep_href_link(FILENAME_IMAGE_RESIZER, 'w=150&h=150&thumb=no_image.jpg'); ?>" alt="No image" /></div></a>
                                    <?php endif; ?>
                                    <div class="second-level-right-part">
                                        <a class="second-level-item<?php if($categories[$category_id]['active']) : ?> active<?php endif; ?>" href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=' . $categories[$category_id]['parent_id'] . '_' . $category_id); ?>"><?php echo tep_escape($categories[$category_id]['categories_name']); ?></a>
                                        <?php if(!empty($categories_children[$category_id])) : ?>
                                        <div class="third-level-items">
                                            <?php $first_level_category_id = $categories[$category_id]['parent_id']; ?>
                                            <?php foreach($categories_children[$category_id] as $category_id) : ?>
                                            <a class="third-level-item<?php if($categories[$category_id]['active']) : ?> active<?php endif; ?>" href="<?php echo tep_href_link(FILENAME_DEFAULT, 'cPath=' . $first_level_category_id . '_' . $categories[$category_id]['parent_id'] . '_' . $category_id); ?>"><?php echo tep_escape($categories[$category_id]['categories_name']); ?></a>
                                            <?php endforeach; ?>
                                            </div>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</nav>