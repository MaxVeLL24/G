<?php
/*
 * This file is a part of Mibew Messenger.
 *
 * Copyright 2005-2014 the original author or authors.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/*
 *  Application path on server
 */
$mibewroot = "/online";

/*
 *  Internal encoding
 */
$mibew_encoding = "utf-8";
require($_SERVER['DOCUMENT_ROOT'].'/includes/sm_config.php');

/*
 *  MySQL Database parameters
 */
$mysqlhost = $server;
$mysqldb = $db_name;
$mysqllogin = $db_user;
$mysqlpass = $db_pass;
$mysqlprefix = "onl_";


$dbencoding = "utf8";
$force_charset_in_connection = true;

/*
 *  Mailbox
 */
$mibew_mailbox = "admin@eshopmakers.com";
$mail_encoding = "utf-8";

/*
 *  Locales
 */
$home_locale = "ru"; /* native name will be used in this locale */
$default_locale = "ru"; /* if user does not provide known lang */

/*
 * Additional protection using open_basedir
 */
$use_open_basedir_protection = false;

?>