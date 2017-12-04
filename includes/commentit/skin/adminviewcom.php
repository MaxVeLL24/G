<?php

/**
 * Шаблон блока вывода комментариев администратора
 * $names - Имя пользователя
 * $comment_msg - Текст комментария
 * $date - Дата размещения комментария
 * $divonter - Система многоуровневых комментариев
 * $scravatar - Аватар
 */

?>
<div class="admin-commit" style="background:linear-gradient(175deg,rgba(70, 157, 14, 0.78) 0,rgba(71, 159, 14, 0.58) 86%);border:1px solid transparent;border-radius:5px;padding:10px 5px;margin-top:5px;margin-left:<?php echo $commentpx; ?>px;">
 <div style="padding:0;margin:0;border:0;">
    <div class="left" style="width:65%;vertical-align: text-top;">
      <span style="font-family:Arial; color:#fff6eb;"><b><?php echo $names; ?></b></span><br /><span style="font-size:8px;color:#f3f3f2"><?php echo $date; ?></span>
    </div>
    <div class="right" style="margin-right:2px;">
      <?php echo $ratingcomment; ?>
    </div>
    <div class="clear" style="color: white"></div>
    <div class="left" style="padding-right:5px;color: white;width: 100%;">
      <?php echo $comment_msg; ?> <br />
      <?php echo $divonter; ?>
    </div>
    <div class="clear"></div>
 </div>
</div>