<?php
  $compare = '';
  if(COMPARE_MODULE_ENABLED == 'true'){
    $compare .= '<div class="left">';
    if (isset($_SESSION['compares'][$id])) {
     $compare .= '<div class="left">
                    <a id="chk'.$id.'" href="javascript:go_compare('.$id.',\'delete\');">
                      <img src="'.HTTP_SERVER.'/'.DIR_WS_TEMPLATES . TEMPLATE_NAME.'/images/check_on.png">
                    </a>
                  </div>
                  <div class="left">
                    <a id="cmph'.$id.'" class="pereiti" href="compare.php" >'.GO_COMPARE.'</a>
                  </div><div class="clear"></div>';
          } else {
     $compare .= '<div class="left">
                    <a id="chk'.$id.'" href="javascript:go_compare('.$id.');" >
                      <img src="'.HTTP_SERVER.'/'.DIR_WS_TEMPLATES . TEMPLATE_NAME.'/images/check_off.png">
                    </a>
                  </div>
                  <div class="left">
                    <a id="cmph'.$id.'" href="javascript:;" onClick="go_compare('.$id.')">'.COMPARE.'</a>
                  </div><div class="clear"></div>';
    }

    $compare .= '</div>';
}
    $wishlist = '';

if(WISHLIST_MODULE_ENABLED == 'true'){
  $wishlist = '<div class="wishlist_block left">';
    $data_mode = 'listing';
    if (isset($_SESSION['wishList']->wishID[$id])) {
       $wishlist .= '<a data-id="'.$id.'" data-mode="'.$data_mode.'" data-action="delete" class="wishlisht_button">
                          <img src="'.HTTP_SERVER.'/'.DIR_WS_TEMPLATES . TEMPLATE_NAME.'/images/check_on.png">
                          <span class="wishlisht_text">'.IN_WHISHLIST.'</span>
                      </a>';
    } else {
      $wishlist .= '<a data-id="'.$id.'" data-mode="'.$data_mode.'" data-action="add" class="wishlisht_button">
                          <img src="'.HTTP_SERVER.'/'.DIR_WS_TEMPLATES . TEMPLATE_NAME.'/images/check_off.png">
                          <span class="wishlisht_text">'.WHISH.'</span>
                      </a>';
    }
  $wishlist .= '</div>';
}
?>