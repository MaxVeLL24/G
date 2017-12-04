<?php
/**
 * Шаблон блока вывода комментариев
 * $names - Имя пользователя
 * $comment_msg - Текст комментария
 * $date - Дата размещения комментария
 * $divonter - Система многоуровневых комментариев
 * $scravatar - Аватар
 * $ratingcomment - Рейтинга
 */
?>
<div class="commentit-comment clearfix" style="margin-left:<?php echo $commentpx; ?>px;">
    <div class="comment-top clearfix">
        <div class="comment-head float-left">
            <div><?php echo $scravatar; ?> <span class="comment-author"><b><?php echo $names; ?></b></span> <?php echo $icoservice; ?></div>
            <div class="comment-date"><?php echo $date; ?></div>
        </div>
        <div class="likes float-right">
            <?php echo $ratingcomment; ?>
        </div>
    </div>
    <div class="comment-text left"><?php echo $comment_msg; ?></div>
    <div><?php echo $divonter; ?></div>
</div>