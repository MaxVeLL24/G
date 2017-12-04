<h1><?php echo MANUFACTURERS_PAGE_TITLE; ?></h1>
<?php if($manufacturers) : ?>
<?php foreach($latin_alphabet as $letter) : ?>
<?php if(!empty($manufacturers_to_letters[$letter])) : ?>
<h2><?php echo $letter === 'other' ? MANUFACTURERS_OTHER : mb_strtoupper($letter, CHARSET); ?></h2>
<div class="manufacturers-tile">
    <div class="outer-margin">
        <?php foreach($manufacturers_to_letters[$letter] as $manufacturers_id) : ?>
        <a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers_id); ?>">
            <div class="padding">
                <div class="common-styled-block">
                    <div class="image">
                        <?php if($manufacturers[$manufacturers_id]['manufacturers_image'] && is_file(DIR_WS_IMAGES . $manufacturers[$manufacturers_id]['manufacturers_image'])) : ?>
                        <img
                            src="<?php echo tep_href_link(FILENAME_IMAGE_RESIZER, 'w=150&h=150&thumb=' . rawurlencode($manufacturers[$manufacturers_id]['manufacturers_image'])); ?>"
                            alt="<?php echo tep_escape($manufacturers[$manufacturers_id]['manufacturers_name']); ?>"
                            />
                        <?php endif; ?>
                    </div>
                    <div class="name">
                        <?php echo tep_escape($manufacturers[$manufacturers_id]['manufacturers_name']); ?>
                    </div>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
<?php endforeach; ?>
<?php else : ?>
<div class="alert alert-info" role="alert"><?php echo MANUFACTURERS_NO_MANUFACTURERS; ?></div>
<?php endif; ?>