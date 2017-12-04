<?php

if(!defined('DIR_WS_CATALOG'))
{
    include_once __DIR__ . '/../application_top.php';
}

######## Переменные месторасположения скрипта ################
#### ВНИМАНИЕ: Скрипт обязательно должен лежать в папке.

$wwp = "includes/commentit"; //Название папки со скриптом от корня
######## Например: $wwp="comment"; / в корневой папке comment
######## Например: $wwp="module/comment"; / в папке module/comment
##############################################################

$table = "road"; //Название таблицы для комментариев, используеться для разных сайтов с 1 БД
$table2 = "road_config"; //Название таблицы настроек комментариев, используеться для разных сайтов с 1 БД
######## Настройка подключения к MySQL (MSSQL)  ################

$coder = 0; // Кодировка Вашей БД 1-cp1251(Win)/0-UTF
//chdir('../../');
require($_SERVER['DOCUMENT_ROOT'] . '/includes/configure.php');
//chdir($wwp);

$sql_host = $server;   /// Хост
$sql_id = $db_user;         /// Логин
$sql_pass = $db_pass;    /// Пароль
$sql_db = $db_name;      /// База
######### Язык  ###########
#Список языков находится в папке /lang/
#".php" писать не нужно. Регистро чувствительный параметр
#
#Например для использования английского языка нужно прописать так:
#$mylang='english';
#!!!Например для использования русского языка в UTF-8 нужно прописать так:
#$mylang='russianutf';
$mylang = $_SESSION['language'];

######## Доступ к админке ################
$typeadm = 1; //Вид входа в панель администрирования (1 - Стандартный / 0 - Веб-форма. Используется, если PHP установлен как CGI)

$login = 'demo1'; //Логин латиницей

$pass = 'demo1'; //Пароль
########## Тонкие настройки каптчи ######
$alphabet = "0123456789abcdefghijklmnopqrstuvwxyz";

# Символы для отрисовки
//$allowed_symbols = "0123456789"; #digits
$allowed_symbols = "23456789abcdeghkmnpqsuvxyz";

# Папка с шрифтами
$fontsdir = 'fonts';

# Длина каптчи
$length = mt_rand(5, 6); # Случайно количество от 5 до 6 символов
//$length = 6; // Чёткое количество в 6-ть символов
# Размер рисунка каптчи. (По умолчанию оптимальный вариант)
$width = 120;
$height = 60;
$fluctuation_amplitude = 5;

# Пробелы между символами (true - Убираются / false - Оставлять )
$no_spaces = true;

# Отображать адрес сайта
$show_credits = false;
$credits = '';

# Цвета (RGB, 0-255)
//$foreground_color = array(0, 0, 0);
//$background_color = array(220, 230, 255);
$foreground_color = array(mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
$background_color = array(mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));

# JPEG качество каптчи
$jpeg_quality = 90;

######### END ################

/**
 * Возвращает объект MySQLi для работы с БД
 * 
 * @global string $sql_host
 * @global string $sql_id
 * @global string $sql_pass
 * @global string $sql_db
 * @staticvar \mysqli $instance
 * @return \mysqli
 */
function getDBO()
{
    static $instance;
    global $sql_host, $sql_id, $sql_pass, $sql_db;
    if(!$instance)
    {
        $instance = mysqli_init();
        $instance->options(MYSQLI_INIT_COMMAND, "SET NAMES = 'utf8'");
        $instance->options(MYSQLI_INIT_COMMAND, "SET CHARSET = 'utf8'");
        $instance->options(MYSQLI_INIT_COMMAND, "SET sql_mode = ''");
        $instance->options(MYSQLI_INIT_COMMAND, "SET autocommit = 1");
        $instance->real_connect($sql_host, $sql_id, $sql_pass, $sql_db);
        if($instance->connect_errno)
        {
            exit('<pre>' . htmlspecialchars($instance->connect_error, ENT_COMPAT, 'UTF-8') . '</pre>');
        }
    }
    return $instance;
}

$link = mysql_connect($sql_host, $sql_id, $sql_pass);
mysql_select_db($sql_db, $link);
if($coder == 1)
{
    mysql_set_charset('cp1251', $link);
    $codername = "windows-1251";
}
else
{
    mysql_set_charset('utf8', $link);
    $codername = "UTF-8";
}
mysql_query("SET sql_mode = ''");

$massparam = array();
$query = mysql_query("SELECT * FROM `{$table2}`", $link);
while(($rowclubs = mysql_fetch_array($query)) !== false)
{
    $massparam[$rowclubs['par']] = $rowclubs['val'];
}