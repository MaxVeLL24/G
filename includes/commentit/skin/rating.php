<div style="diplay:block;width:60px;" id="ratingcom-<?php echo $idcom; ?>"><img src="/<?php echo $wwp; ?>/im/down.png" title="&darr;" alt="&darr;" style="border:0;cursor:pointer;float:right;" onclick="commentrating('/<?php echo $wwp; ?>/func.php?g=0&amp;n=<?php echo $idcom; ?>','commentit-itogo-<?php echo $idcom; ?>');" /><span id="commentit-itogo-<?php echo $idcom; ?>" style="padding:0 3px 0 3px;color:<?php echo $ratingcolor; ?>;float:right;font: bold 10pt Arial;"><?php echo $ratingznak; ?><?php echo $ratingval; ?></span><img src="/<?php echo $wwp; ?>/im/up.png" title="&uarr;" alt="&uarr;" style="border:0;cursor:pointer;float:right;" onclick="commentrating('/<?php echo $wwp; ?>/func.php?g=1&amp;n=<?php echo $idcom; ?>','commentit-itogo-<?php echo $idcom; ?>');" /></div>