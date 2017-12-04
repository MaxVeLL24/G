<?php

/**
 * Список брендов на главной
 */

$manufacturers_to_show_in_list = array(
    'A' => array(),
    'B' => array(),
    'C' => array(),
    'D' => array(),
    'E' => array(),
    'F' => array(),
    'G' => array(),
    'H' => array(),
    'I' => array(),
    'J' => array(),
    'K' => array(),
    'L' => array(),
    'M' => array(),
    'N' => array(),
    'O' => array(),
    'P' => array(),
    'Q' => array(),
    'R' => array(),
    'S' => array(),
    'T' => array(),
    'U' => array(),
    'V' => array(),
    'W' => array(),
    'X' => array(),
    'Y' => array(),
    'Z' => array(),
    'other' => array()
);
$manufacturers_to_show_in_slider = array();

// Находим всех производителей для потрояения списка
$query = tep_db_query("SELECT manufacturers_id, manufacturers_name FROM manufacturers ORDER BY manufacturers_name ASC");
if(tep_db_num_rows($query))
{
    while(($row = tep_db_fetch_array($query)) !== false)
    {
        $letter = strlen($row['manufacturers_name']) && array_key_exists(strtoupper($row['manufacturers_name'][0]), $manufacturers_to_show_in_list) ? strtoupper($row['manufacturers_name'][0]) : 'other';
        $manufacturers_to_show_in_list[$letter][$row['manufacturers_id']] = $row['manufacturers_name'];
    }
}

// Находим случайных 20 производителей для отображения логотипа в слайдере
$query = tep_db_query("SELECT manufacturers_id, manufacturers_name, manufacturers_image FROM manufacturers WHERE manufacturers_image != '' ORDER BY manufacturers_name ASC LIMIT 20");
if(tep_db_num_rows($query))
{
    while(($row = tep_db_fetch_array($query)) !== false)
    {
        $manufacturers_to_show_in_slider[] = $row;
    }
}

?>
<div class="mpm-manufacturers">
    <div class="main-width">
        <div class="header-and-arrows clearfix">
            <div class="list">
                <span class="label"><?php echo MPM_MANUFACTURESR_LIST_LABEL; ?>:</span>
                <?php foreach(array_keys($manufacturers_to_show_in_list) as $letter) : ?>
                <?php if($manufacturers_to_show_in_list[$letter]) : ?>
                <span class="letter-group has-drop-down">
                    <span class="letter"><?php echo $letter === 'other' ? MPM_MANUFACTURESR_OTHER : $letter; ?></span>
                    <?php if($manufacturers_to_show_in_list[$letter]) : ?>
                    <span class="list">
                        <?php foreach($manufacturers_to_show_in_list[$letter] as $manufacturers_id => $manufacturers_name) : ?>
                        <a href="<?php echo tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturers_id); ?>"><?php echo tep_escape($manufacturers_name); ?></a>
                        <?php endforeach; ?>
                    </span>
                    <?php endif; ?>
                </span>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="arrows">
                <a rel="nofollow" href="<?php echo tep_href_link(FILENAME_MANUFACTURERS); ?>"><?php echo MPM_MANUFACTURESR_ALL; ?></a>
                <span></span>
            </div>
        </div>
        <?php if($manufacturers_to_show_in_slider) : ?>
        <div class="slider">
            <?php foreach($manufacturers_to_show_in_slider as $manufacturer) : ?>
            <a
                href="<?php echo tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer['manufacturers_id']); ?>"
                title="<?php echo tep_escape($manufacturer['manufacturers_name']); ?>">
                <img
                    src="<?php echo tep_href_link(FILENAME_IMAGE_RESIZER, 'w=160&h=80&thumb=' . rawurlencode($manufacturer['manufacturers_image'])); ?>"
                    alt="<?php echo tep_escape($manufacturer['manufacturers_name']); ?>"
                    />
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php \EShopmakers\Html\Capture::getInstance('footer')->startCapture(); ?>
<script>
    (function($){
        'use strict';
        $(document).ready(function(){
            // Слайдер брендов на главной
            $('.mpm-manufacturers .slider').slick({
                slidesToShow: 4,
                slidesToScroll: 4,
                infinite: false,
                dots: false,
                appendArrows: '.mpm-manufacturers .arrows span',
                responsive: [
                    {
                        breakpoint: 650,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 520,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    }
                ]
            });
        });
    })(window.jQuery || window.Zepto);
</script>
<?php \EShopmakers\Html\Capture::getInstance('footer')->stopCapture(); ?>