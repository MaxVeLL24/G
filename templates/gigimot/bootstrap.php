<?php
  // ЗАПРОСЫ ДЛЯ КОЛОНОК
  require_once (DIR_WS_INCLUDES . 'columns_queries.php');
  $wrapper_width = 1000;
  $dimension = 'px';
  $body_classes = array();
  $center_margin = '';
  if (DISPLAY_COLUMN_LEFT == 'yes') $sidebar_left = false;
  if (DISPLAY_COLUMN_RIGHT == 'yes') $sidebar_right = true;
  $show_breadcrumbs = true;


  if ($_GET['cPath']=='') {
    $show_breadcrumbs = true;
    $sidebar_right = false;
  }



  if(count($cPath_array) or isset($_GET['manufacturers_id']) or isset($_GET['keywords'])){
    $sidebar_left = true;
  }

  // ЕСЛИ НА ГЛАВНОЙ СТРАНИЦЕ
  if($_SERVER['REQUEST_URI']=='/' or $_SERVER['REQUEST_URI']=='/index.php' or $_SERVER['REQUEST_URI']=='/index.php?language=ru' or $_SERVER['REQUEST_URI']=='/index.php?language=ua' or $_SERVER['REQUEST_URI']=='/index.php?language=en'){
    $show_breadcrumbs = false;
    array_push($body_classes,'frontpage');
  }else{
    array_push($body_classes,'not-front');
  }

  if (isset($_GET['products_id'])) {
    $sidebar_left = true;
    $show_breadcrumbs = true;
  }


  if ($sidebar_left and $sidebar_right) {
    array_push($body_classes, 'two-sidebars','left-sidebar','right-sidebar');
    $squeeze_margin .= "margin:0 ".BOX_WIDTH_RIGHT.$dimension.' 0 '.BOX_WIDTH_LEFT.$dimension.";";
    $center_margin .= "margin:0 -".BOX_WIDTH_RIGHT.$dimension.' 0 -'.BOX_WIDTH_LEFT.$dimension.";";
  }
  elseif ($sidebar_left) {
    array_push($body_classes, 'one-sidebar','left-sidebar');
    $squeeze_margin .= "margin:0 0 0 ".BOX_WIDTH_LEFT.$dimension.";";
    $center_margin .= "margin:0 0 0 -".BOX_WIDTH_LEFT.$dimension.";";
  }elseif ($sidebar_right) {
    array_push($body_classes, 'one-sidebar','right-sidebar');
    $squeeze_margin .= "margin:0 ".BOX_WIDTH_RIGHT.$dimension." 0 0;'";
    $center_margin .= "margin:0 -".BOX_WIDTH_RIGHT.$dimension." 0 0;'";
  }else{$center_margin = '';}

  if(isset($_GET['products_id'])){
    $squeeze_margin = "margin:0 0 0 0;";
  }
  if($body_classes != '' and is_array($body_classes)){
    foreach ($body_classes as $class_name) {
      $body_class .= $class_name.' ';
    }
  }
  if(in_array('frontpage', $body_classes) == false){
    $container_class = 'wrapper';
  }
?>