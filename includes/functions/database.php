<?php

/*
  $Id: database.php,v 1.1.1.1 2003/09/18 19:05:08 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

function tep_db_connect($server = DB_SERVER, $username = DB_SERVER_USERNAME, $password = DB_SERVER_PASSWORD, $database = DB_DATABASE, $link = 'db_link')
{
    global ${$link};

    if(USE_PCONNECT == 'true')
    {
        ${$link} = mysql_pconnect($server, $username, $password);
    }
    else
    {
        ${$link} = mysql_connect($server, $username, $password);
    }
    
    if(${$link})
    {
        mysql_set_charset('utf8');
        mysql_query("SET sql_mode = ''");
        mysql_select_db($database);
    }
    else
    {
        tep_db_error("connect", mysql_errno(), mysql_error());
    }
    
    return ${$link};
}

function tep_db_close($link = 'db_link')
{
    global ${$link};

    return mysql_close(${$link});
}

//  function tep_db_error($query, $errno, $error) { 
//    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
//  }

function tep_db_error($query, $errno, $error)
{
    //Start VaM db-error processing
    include(DIR_WS_LANGUAGES . 'russian_db_error.php');
    $msg = "\n" . 'MYSQL QUERY ERROR REPORT' . "\n" . " - " . date("d/m/Y H:m:s", time()) . "\n" . '---------------------------------------' . "\n";
    $msg .= $errno . ' - ' . $error . "\n\n" . $query . "\n";
    $msg .= '---------------------------------------' . "\n";
    $msg .= 'Server Name   : ' . $_SERVER['SERVER_NAME'] . "\n";
    $msg .= 'Remote Address: ' . $_SERVER['REMOTE_ADDR'] . "\n";
    $msg .= 'Referer       : ' . $_SERVER["HTTP_REFERER"] . "\n";
    $msg .= 'Requested     : ' . $_SERVER["REQUEST_URI"] . "\n";
    // die(DB_ERR_MSG);
    die('<pre>' . htmlspecialchars($msg, ENT_COMPAT, defined('CHARSET') ? CHARSET : 'UTF-8') . '</pre>');
}

//End VaM db-error processing


function tep_db_query($query, $link = 'db_link')
{
    global ${$link};
    global $query_counts;
    global $query_total_time;
    $query_counts++;
    tep_db_log($query);
    if(defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true'))
    {
        error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    $time_start = explode(' ', microtime());
    // limex: mod query performance START
    list($usec, $sec) = explode(" ", microtime());
    $start = (float) $usec + (float) $sec;

    $result = mysql_query($query, ${$link}) or tep_db_error($query, mysql_errno(), mysql_error());
    list($usec, $sec) = explode(" ", microtime());

    $end = (float) $usec + (float) $sec;
    $parsetime = $end - $start;
    $qlocation = $_SERVER["SCRIPT_FILENAME"];
    // limex: some queries come before having the config values. Default to 10 secs
    $mysql_perf_treshold = MYSQL_PERFORMANCE_TRESHOLD > 0 ? MYSQL_PERFORMANCE_TRESHOLD : 10;
    if($parsetime > $mysql_perf_treshold)
    {
        $log_file = DIR_FS_CATALOG . 'includes/slow_queries/slow_query_log.txt';
        $slow_when = date('F j, Y, g:i a', time());
        $slow_query = tep_db_input($query) . "\t" . $qlocation . "\t" . $parsetime . "\t" . $slow_when . "\r\n";
        $slow_log = fopen($log_file, 'a');
        fwrite($slow_log, $slow_query);
        fclose($slow_log);
    }
    // limex: mod query performance END
    $time_end = explode(' ', microtime());
    $query_time = $time_end[1] + $time_end[0] - $time_start[1] - $time_start[0];
    $query_total_time += $query_time;

    if(defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true'))
    {
        $result_error = mysql_error();
        error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }
//Start VaM db-error processing
    if(!$result)
    {
        tep_db_error($query, mysql_errno(), mysql_error());
    }
//End VaM db-error processing
    return $result;
}

function tep_db_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link')
{
    reset($data);
    if($action == 'insert')
    {
        $query = 'insert into ' . $table . ' (';
        while(list($columns, ) = each($data))
        {
            $query .= $columns . ', ';
        }
        $query = substr($query, 0, -2) . ') values (';
        reset($data);
        while(list(, $value) = each($data))
        {
            switch((string) $value)
            {
                case 'now()':
                    $query .= 'now(), ';
                    break;
                case 'null':
                    $query .= 'null, ';
                    break;
                default:
                    $query .= '\'' . tep_db_input($value) . '\', ';
                    break;
            }
        }
        $query = substr($query, 0, -2) . ')';
    }
    elseif($action == 'update')
    {
        $query = 'update ' . $table . ' set ';
        while(list($columns, $value) = each($data))
        {
            switch((string) $value)
            {
                case 'now()':
                    $query .= $columns . ' = now(), ';
                    break;
                case 'null':
                    $query .= $columns .= ' = null, ';
                    break;
                default:
                    $query .= $columns . ' = \'' . tep_db_input($value) . '\', ';
                    break;
            }
        }
        $query = substr($query, 0, -2) . ' where ' . $parameters;
    }

    return tep_db_query($query, $link);
}

function tep_db_fetch_array($db_query)
{
    return @mysql_fetch_array($db_query, MYSQL_ASSOC);
}

function tep_db_num_rows($db_query)
{
    return mysql_num_rows($db_query);
}

function tep_db_data_seek($db_query, $row_number)
{
    return mysql_data_seek($db_query, $row_number);
}

function tep_db_insert_id()
{
    return mysql_insert_id();
}

function tep_db_free_result($db_query)
{
    return mysql_free_result($db_query);
}

function tep_db_fetch_fields($db_query)
{
    return mysql_fetch_field($db_query);
}

function tep_db_output($string)
{
    return htmlspecialchars($string);
}

function tep_db_input($string, $link = 'db_link')
{
    global ${$link};

    if(function_exists('mysql_real_escape_string'))
    {
        return mysql_real_escape_string($string, ${$link});
    }
    elseif(function_exists('mysql_escape_string'))
    {
        return mysql_escape_string($string);
    }
    return addslashes($string);
}

function tep_db_prepare_input($string)
{
    static $is_magic_quotes_enabled;
    if(!isset($is_magic_quotes_enabled))
    {
        $is_magic_quotes_enabled = filter_var(ini_get('magic_quotes_gpc'), FILTER_VALIDATE_BOOLEAN);
    }
    if(is_string($string))
    {
        if($is_magic_quotes_enabled)
        {
            $string = stripslashes($string);
        }
        $string = trim($string);
    }
    elseif(is_array($string))
    {
        foreach($string as $key => $value)
        {
            $string[$key] = tep_db_prepare_input($value);
        }
    }
    return $string;
}

function tep_db_log($string)
{
    static $fp;
    if(defined('WRITE_DB_QUERIES_TO_LOG') && WRITE_DB_QUERIES_TO_LOG)
    {
        if(!isset($fp))
        {
            $fp = fopen(DIR_WS_INCLUDES . 'slow_queries/queries_log.txt', 'a');
        }
        if($fp)
        {
            fwrite($fp, $string . PHP_EOL . PHP_EOL);
            fflush($fp);
        }
    }
}

function tep_db_result($result, $row = 0, $field = 0)
{
    return mysql_result($result, $row, $field);
}