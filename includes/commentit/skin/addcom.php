<?php

/**
 * Шаблон блока ввода комментариев
 * 
 * $smilebar - Смайлики
 * $htmlz - Сообщение об отключенных HTML тэгах
 * $wwp - Папка со скриптом
 * $rssico - RSS
 * $capt - Каптча
 * $novis - Режим каптчи
 * $novismail - Режим почты
 * $autoarea - Авторазмер
 * $panelbar - Панель редактора
 */

?>
<form
    class="comment_form clearfix"
    name="addcomm"
    enctype="multipart/form-data"
    action=""
    onKeyPress="if(event.keyCode==10||(event.ctrlKey&amp;&amp;event.keyCode==13)){send_message();}"
    method="POST"
    >
    <style scoped>
        .user-mark ul {
            display: block;
            font-size: 0;
            line-height: 0;
            list-style: none;
            margin: 5px 0 0;
            padding: 0;
        }
        .user-mark li {
            display: inline-block;
        }
        .user-mark li ~ li {
            margin-left: 3px;
        }
        .user-mark button {
            appearance: none;
            -moz-appearance: none;
            -webkit-appearance: none;
            background: transparent url('/templates/gigimot/images/rating-stars.svg') 0 0 no-repeat;
            border: none;
            cursor: pointer;
            display: block;
            height: 17px;
            width: 18px;
        }
        .user-mark input[value='1'] ~ ul li:nth-child(1) button {
            background-position: 0 -17px;
        } 
        .user-mark input[value='2'] ~ ul li:nth-child(1) button,
        .user-mark input[value='2'] ~ ul li:nth-child(2) button {
            background-position: 0 -17px;
        } 
        .user-mark input[value='3'] ~ ul li:nth-child(1) button,
        .user-mark input[value='3'] ~ ul li:nth-child(2) button,
        .user-mark input[value='3'] ~ ul li:nth-child(3) button {
            background-position: 0 -17px;
        }
        .user-mark input[value='4'] ~ ul li:nth-child(1) button,
        .user-mark input[value='4'] ~ ul li:nth-child(2) button,
        .user-mark input[value='4'] ~ ul li:nth-child(3) button,
        .user-mark input[value='4'] ~ ul li:nth-child(4) button {
            background-position: 0 -17px;
        }
        .user-mark input[value='5'] ~ ul li:nth-child(1) button,
        .user-mark input[value='5'] ~ ul li:nth-child(2) button,
        .user-mark input[value='5'] ~ ul li:nth-child(3) button,
        .user-mark input[value='5'] ~ ul li:nth-child(4) button,
        .user-mark input[value='5'] ~ ul li:nth-child(5) button {
            background-position: 0 -17px;
        }
    </style>
    <?php /* Рейтинг */ ?>
    <div class="user-mark">
        <label class="control-label"><?php echo $langcommentit['skin_mark']; ?></label>
        <input type="hidden" name="mark" value="0">
        <ul>
            <li>
                <button type="button" onclick="this.form.elements.mark.value = 1;" title="<?php echo $langcommentit['skin_mark_1']; ?>"></button>
            </li>
            <li>
                <button type="button" onclick="this.form.elements.mark.value = 2;" title="<?php echo $langcommentit['skin_mark_2']; ?>"></button>
            </li>
            <li>
                <button type="button" onclick="this.form.elements.mark.value = 3;" title="<?php echo $langcommentit['skin_mark_3']; ?>"></button>
            </li>
            <li>
                <button type="button" onclick="this.form.elements.mark.value = 4;" title="<?php echo $langcommentit['skin_mark_4']; ?>"></button>
            </li>
            <li>
                <button type="button" onclick="this.form.elements.mark.value = 5;" title="<?php echo $langcommentit['skin_mark_5']; ?>"></button>
            </li>
        </ul>
    </div>
    <?php if(!empty($_GET['products_id']) && intval($_GET['products_id']) > 0) : ?>
    <input type="hidden" name="products_id" value="<?php echo intval($_GET['products_id']); ?>">
    <?php endif; ?>
    <?php if($massparam['loginzaglob'] == 1 && empty($_SESSION['djos']['error_message'])) : ?>
    <input
        type="hidden"
        id="nick"
        name="namenew"
        value="<?php echo $oldname; ?>"
        />
    <?php else : ?>
    <div class="form-group">
        <label for="nick" class="control-label"><?php echo $langcommentit['skin_name']; ?></label>
        <input
            type="text"
            id="nick"
            name="namenew"
            maxlength="<?php echo $sumvlname; ?>"
            placeholder="<?php echo $langcommentit['skin_name']; ?>"
            value="<?php echo $oldname; ?>"
            class="form-control"
            />
        <?php echo $icoservice, $exitbut; ?>
    </div>
    <?php endif; ?>
    <?php /* Домашняя страница */ ?>
    <?php if($massparam['http']) : ?>
    <div class="form-group">
        <label for="usurl" class="control-label"><?php echo $langcommentit['skin_homepage']; ?></label>
        <input
            type="text"
            id="usurl"
            name="useurl"
            maxlength="<?php echo $sumvlname; ?>"
            placeholder="<?php echo $langcommentit['skin_homepage']; ?>"
            value="<?php echo $oldurl; ?>"
            class="form-control"
            />
    </div>
    <?php else : ?>
    <input
        type="hidden"
        id="usurl"
        name="useurl"
        value="<?php echo $oldurl; ?>"
        />
    <?php endif; ?>
    <?php /* Email */ ?>
    <?php if($massparam['viewentermail']) : ?>
    <div class="form-group">
        <label for="usmail" class="control-label"><?php echo $langcommentit['skin_mail']; ?></label>
        <input
            type="text"
            id="usmail"
            name="usemail"
            maxlength="<?php echo $sumvlname; ?>"
            placeholder="<?php echo $langcommentit['skin_mail']; ?>"
            value="<?php echo $oldmail; ?>"
            class="form-control"
            />
    </div>
    <?php else : ?>
    <input
        type="hidden"
        id="usmail"
        name="usemail"
        value="<?php echo $oldmail; ?>"
        />
    <?php endif; ?>
    <?php /* Комментарий */ ?>
    <div class="form-group">
        <label for="textz" class="control-label"><?php echo $langcommentit['skin_comment']; ?></label>
        <textarea
            id="textz"
            name="comment"
            placeholder="<?php echo $langcommentit['skin_comment']; ?>"
            class="form-control"
            ><?php echo $oldmess; ?></textarea>
    </div>
    <?php /* Капча */ ?>
    <?php if($massparam['workcapt']) : ?>
    <?php echo $capt; ?>
    <?php endif; ?>
    <div class="align-right">
        <button
            type="submit"
            name="addcomment"
            id="enter"
            onclick="send_message();return false;"
            class="button"
            value="true"
            ><?php echo $langcommentit['skin_addcomment']; ?></button>
    </div>
</form>