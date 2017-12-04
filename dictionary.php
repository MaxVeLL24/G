<?php

include_once __DIR__ . '/includes/application_top.php';

$files = array(
    $language . '.php',
    $language . '/fast_order.php'
);
$constants = array(
    'FAST_ORDER_TELEPHONE_INPUT_LABEL_TEXT',
    'FAST_ORDER_SUBMIT_FORM_BUTTON_TEXT',
    'FAST_ORDER_CLOSE_POPUP_BUTTON_TEXT',
    'FAST_ORDER_POPUP_EXPLANATION_TEXT',
    'FAST_ORDER_SUBMIT_FORM_BUTTON_DISABLED_TEXT',
    'FAST_ORDER_ALERT_ERROR_TEXT',
    'FAST_ORDER_ALERT_SUCCESS_TEXT',
    'FAST_ORDER_POPUP_HEADER_TEXT',
    'PRE_ORDER_POPUP_HEADER_TEXT',
    'FAST_ORDER_TELEPHONE_VALIDATION_ERROR_TEXT',
    'PRODUCT_LISTING_ADD_TO_WISHLIST',
    'PRODUCT_LISTING_IN_WISHLIST',
    'PRODUCT_LISTING_ADD_TO_COMPARE',
    'PRODUCT_LISTING_IN_COMPARISON',
    'PRODUCT_LISTING_IN_WISHLIST',
    'PRODUCT_LISTING_ADD_TO_WISHLIST',
    'PRODUCT_LISTING_WAIT',
    'LOAD_MORE_PRODUCTS',
    'SHOW_ALL_SRCH_RES',
    'BOX_HEADING_SELECTED_FILTERS',
    'TEXT_CLEAR_SELECTED_FILTERS'
);

$mtimes = array(
    filemtime(__FILE__)
);
foreach($files as $file)
{
    $mtimes[] = filemtime(DIR_WS_LANGUAGES . $file);
}
$etag = sprintf('%x', crc32(max($mtimes) . $language));

header('Cache-Control: max-age=2592000; must-revalidate');
header('ETag: "' . $etag . '"');
header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));

if(!empty($_SERVER['HTTP_IF_NONE_MATCH']))
{
    $etag_request = preg_replace('/^(?:W\/)?"|(?:-gzip)?"$/i', '', $_SERVER['HTTP_IF_NONE_MATCH']);
    if($etag_request === $etag)
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified', true, 304);
        exit();
    }
}

$r_constants = array();
foreach($files as $file)
{
    $r_value = require_once DIR_WS_LANGUAGES . $file;
    if($r_value && is_array($r_value))
    {
        $r_constants = array_merge($r_constants, $r_value);
    }
}

$_constants = array();
foreach($constants as $constant)
{
    if(array_key_exists($constant, $r_constants))
    {
        $_constants[$constant] = $r_constants[$constant];
    }
    elseif(defined($constant))
    {
        $_constants[$constant] = constant($constant);
    }
    else
    {
        $_constants[$constant] = $constant;
    }
}

header('Content-Type: application/javascript; charset=UTF-8');
$response_body = '"use strict";var dictionary=' . json_encode($_constants) . ';';
header(sprintf('Content-Length: %d', strlen($response_body)));
exit($response_body);