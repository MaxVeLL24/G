<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require('includes/simple_html_dom.php');
require("includes/parser/RollingCurl.class.php");
require("includes/parser/AngryCurl.class.php");


# Initializing AngryCurl instance with callback function named 'callback_function'
$AC = new AngryCurl('callback_function');

# Initializing so called 'web-console mode' with direct cosnole-like output
//$AC->init_console();

# Importing proxy and useragent lists, setting regexp, proxy type and target url for proxy check
# You may import proxy from an array as simple as $AC->load_proxy_list($proxy array);
$AC->load_proxy_list('includes/parser/import/proxy_list.txt',
    # optional: number of threads
    200,
    # optional: proxy type
    'http',
    # optional: target url to check
    'https://rozetka.com.ua',
    # optional: target regexp to check
    'title>'
);
$AC->load_useragent_list('includes/parser/import/useragent_list.txt');
# Basic request usage (for extended - see demo folder)
$AC->get($_POST['product_rozetka_update']);

# Starting with number of threads = 200
$AC->execute(200);

# You may pring debug information, if console_mode is NOT on ( $AC->init_console(); )
//AngryCurl::print_debug(); 

# Destroying
//unset($AC);

# Callback function example
function callback_function($response, $info, $request)
{
//    if($info['http_code']!==200)
//    {
//        AngryCurl::add_debug_msg(
//            "->\t" .
//            $request->options[CURLOPT_PROXY] .
//            "\tFAILED\t" .
//            $info['http_code'] .
//            "\t" .
//            $info['total_time'] .
//            "\t" .
//            $info['url']
//        );
//    }else
//    {
//        AngryCurl::add_debug_msg(
//            "->\t" .
//            $request->options[CURLOPT_PROXY] .
//            "\tOK\t" .
//            $info['http_code'] .
//            "\t" .
//            $info['total_time'] .
//            "\t" .
//            $info['url']
//        );


//    }

    $link=mysqli_connect('localhost','u_gigimot','2tA2Q0rl','gigimot');


//     Для локального використання:

 //   $link=mysqli_connect('localhost','root','','gigimot');

    $contRoz = (string)($response);
    //print_r($_POST['product_rozetka_update']);
    //echo '11111'.$contRoz;
    preg_match_all('/\<meta itemprop="price" content="(.+)\">/U', $contRoz, $arr);
    //print_r($arr[1]);
    if (isset($arr[1][0])) {
      $update=$link->query("UPDATE `products` SET `products_rozetka_price`='".$arr[1][0]."'  WHERE `products_url_rozetka`='".$_POST['product_rozetka_update']."'");  
    } else {
      $update=$link->query("UPDATE `products` SET `products_rozetka_price`='999999'  WHERE `products_url_rozetka`='".$_POST['product_rozetka_update']."'");  
      $arr[1][0] = 'ERR';
    }
    
    echo $arr[1][0];
    mysqli_close($link);
}
