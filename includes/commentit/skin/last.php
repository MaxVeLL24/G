<?php

/**
 * Шаблон блока вывода комментариев
 * $names - Имя пользователя
 * $comment_msg - Текст комментария
 * $date - Дата размещения комментария
 * $urlz - Ссылка на комментарий
 */

?>
<p>[<?php echo $date; ?>] <?php echo $names; ?> <a href="<?php echo $urlz; ?>"><?php echo $comment_msg; ?></a></p>