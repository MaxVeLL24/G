<?php if($_GET['method']=='ajax') {

  chdir('../../');
  include_once __DIR__ . '/includes/application_top.php';
  $zap_query=tep_db_query('SELECT pa_imgs FROM products_attributes WHERE products_id='.$_GET['pid'].' and options_id='.$_GET['colid'].' and options_values_id='.$_GET['col'].';');
  $row = tep_db_fetch_array($zap_query);
  if(tep_db_num_rows($zap_query)>0 and $row['pa_imgs']!='') {
    $additional_images = explode('|',$row['pa_imgs']);
    $addim_main_img = $perem_sma = $additional_images[0];
    $attr_images=true;
  } else {
    $products_images_query = tep_db_query("select p.products_images, p.products_image, p.products_image_med from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$_GET['pid'] . "' ");
    $products_images = tep_db_fetch_array($products_images_query);
    $additional_images = explode('|',$products_images['products_images']);
    $addim_main_img = $additional_images[0];
    $perem_sma = $products_images['products_image'];
  }


} else {

  $zap_query=tep_db_query("SELECT pa_imgs FROM products_attributes WHERE products_id='".$_GET['products_id']."' order by products_options_sort_order");
  $row = tep_db_fetch_array($zap_query);
  if(tep_db_num_rows($zap_query)>0 and $row['pa_imgs']!='') {
    $additional_images = explode('|',$row['pa_imgs']);
    $addim_main_img = $additional_images[0];
    $attr_images=true;
  } else {

      $products_images_query = tep_db_query("select p.products_images, p.products_image, p.products_image_med from " . TABLE_PRODUCTS . " p where p.products_id = '" . (int)$_GET['products_id'] . "' ");
      $products_images = tep_db_fetch_array($products_images_query);
      $additional_images = explode(';',$products_images['products_images']);
      $addim_main_img = $additional_images[0];
      $perem_sma = $products_images['products_images'];
  }
}
  $count_addImgs = count($additional_images);
  $big_width = 300;
  $big_height = 300;
  $thumb_size_w = 80;
  $thumb_size_h = 80;

  if ($count_addImgs<1){
    $slider_product = 'no_slider_product';
  }else{
    $slider_product = 'slider_product';
  }

?>
  <div class="product_dop_images small_slider">

    <div class="img_overflow">
      <!-- Main image -->
      <a id="ch_link" product_id="<?php echo ($_GET['products_id']?:$_GET['pid']);?>" href="javascript:zoomProduct(<?php echo ($_GET['products_id'])?:$_GET['pid'];?>);" >
        <img id="ch_im" onerror="this.src='images/nofoto.png'" src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/r_imgs.php?thumb='.$addim_main_img.'&amp;w='.$big_width.'&amp;h='.$big_height; ?>" data-image="<?=$addim_main_img; ?>" alt="<? echo strip_tags($products_name); ?>" title="<? echo strip_tags($products_name); ?>">
      </a>
    </div>


    <?php //debug($additional_images); ?>
   <?php if ($count_addImgs>1) { ?>
        <ul id="<?php echo $slider_product; ?>" class="additional_images_list" >
            <?php for ($x=0; $x < $count_addImgs; $x++) { ?>
              <?php if ($additional_images[$x] != '') {
                $current_src = $additional_images[$x];
                $thumb_filepath = 'http://'.$_SERVER['HTTP_HOST'].'/r_imgs.php?thumb='.$current_src.'&amp;w='.$thumb_size_w.'&amp;h='.$thumb_size_h;
              ?>
                <li>
                  <a data-image="<?php echo $current_src; ?>" href="javascript:changeIm('<?php echo $current_src; ?>')">

                    <img src="<?php echo $thumb_filepath; ?>" alt="">
                  </a>
                </li>
              <?php } ?>
            <?php } ?>
          </ul>
    <?php  } ?>
    </div> <!-- /product_dop_images -->


<?php if($slider_product == 'slider_product'){ ?>
  <!-- Init slider -->
  <script type="text/javascript">
      // $('#slider_product').bxSlider({
      //     nextText: '<span class="arrow"></span>',
      //     prevText: '<span class="arrow"></span>',
      //     displaySlideQty: 3,
      //     minSlides: 3,
      //     maxSlides: 3,
      //     moveSlides: 1,
      //     slideWidth: 74,
      //     slideMargin: 3,
      //     pager: false
      // });
  </script>
  <script>
    jQuery('#slider_product a').each(function(e){
      if(jQuery(this).data('image') == jQuery('#ch_im').data('image')){
        jQuery(this).addClass('active');
      }
    });
  </script>
<?php } ?>