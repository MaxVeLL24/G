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
    $addim_main_img = $products_images['products_image_med'];
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

?>
<div class="section_template_title zoomProductView__prod-title"><?php echo $products_name; ?></div>

<div class="product_dop_popup zoomProductView">
  <ul class="bxslider">
      <li><img style="max-height:500px" src="images/<?php echo $_GET['img']; ?>" /></li>
    <?php foreach ($additional_images as $image): ?>
        <?if($image!=$_GET['img']){?>
      <li><img style="max-height:500px" src="images/<?php echo $image; ?>" /></li>
      <?}?>
    <?php endforeach ?>
  </ul>
 <?php if ($count_addImgs>1) { ?>
    <div class="additional_images_list" >
        <a data-slide-index="0" href="#"><img src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/r_imgs.php?thumb='.$_GET['img'].'&w=80&h=80' ?>" alt=""></a>
      <?php
      $i=1;
      foreach ($additional_images as $num => $image): ?>
          <?if($image!=$_GET['img']){?>
          <a data-slide-index="<?php echo $i ?>" href="#"><img src="<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/r_imgs.php?thumb='.$image.'&w=80&h=80' ?>" alt=""></a>
          <?$i++;?>
          <?}?>
      <?php endforeach ?>
    </div>
  <?php  } ?>
</div> 



<!-- /product_dop_images -->
