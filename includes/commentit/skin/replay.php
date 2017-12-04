<?php

/**
 * {$idcom} - Номер комментария
 */

?>
<div class="align-right">
    <span class="reply button button-small" style="cursor: pointer;" id="span-<?php echo $idcom; ?>" onclick="var oldid=document.getElementById('oldid').value;otvet('<?php echo $idcom; ?>',oldid,'0');"><?php echo $langcommentit['skin_reply']; ?></span>
    <span style="cursor: pointer;" id="spanq-<?php echo $idcom; ?>" onclick="var oldid=document.getElementById('oldid').value;otvet('<?php echo $idcom; ?>',oldid,'1');"><?php echo $langcommentit['skin_reply_que']; ?></span>
    <span class="reply button button-small button-blue" style="cursor: pointer;display:none;" id="repl-<?php echo $idcom; ?>" onclick="var oldid=document.getElementById('oldid').value;resetrepl('<?php echo $idcom; ?>',oldid);"><?php echo $langcommentit['skin_reply_cancel']; ?></span>
</div>
<div id='comment-<?php echo $idcom; ?>'></div>