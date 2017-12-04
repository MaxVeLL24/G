<?php

if(!defined('DIR_WS_CATALOG'))
{
    include_once __DIR__ . '/../application_top.php';
}

include_once __DIR__ . '/kcaptcha.php';

$captcha = new KCAPTCHA();
$_SESSION['captcha_keystring'] = $captcha->getKeyString();
