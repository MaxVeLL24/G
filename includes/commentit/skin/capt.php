<?php

/**
 * $width - ширина каптчи (автомат)
 * $height - высота каптчи (автомат)
 * $captsourse - картинка каптчи
 * refcapt() - функция на обновление каптчи
 */

?>
<div class="block-captcha clearfix">
    <div class="block-image">
        <div class="captcha-image"><?php echo $captsourse; ?></div>
        <div class="refresh-link">
            <span onclick="refcapt()"><?php echo $langcommentit['skin_reload_capt']; ?></span>
        </div>
    </div>
    <div class="block-input">
        <div class="block-control">
            <div class="block-keystringz">
                <label for="keystringz"><?php echo $langcommentit['skin_enter_capt']; ?></label>
            </div>
            <div class="block-input">
                <input
                    type="text"
                    id="keystringz"
                    name="keystringz"
                    />
            </div>
        </div>
    </div>
</div>