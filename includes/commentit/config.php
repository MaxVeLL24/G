<?php

if(!defined('DIR_WS_CATALOG'))
{
    include_once __DIR__ . '/../application_top.php';
}

######## ���������� ����������������� ������� ################
#### ��������: ������ ����������� ������ ������ � �����.

$wwp = "includes/commentit"; //�������� ����� �� �������� �� �����
######## ��������: $wwp="comment"; / � �������� ����� comment
######## ��������: $wwp="module/comment"; / � ����� module/comment
##############################################################

$table = "road"; //�������� ������� ��� ������������, ������������� ��� ������ ������ � 1 ��
$table2 = "road_config"; //�������� ������� �������� ������������, ������������� ��� ������ ������ � 1 ��
######## ��������� ����������� � MySQL (MSSQL)  ################

$coder = 0; // ��������� ����� �� 1-cp1251(Win)/0-UTF
//chdir('../../');
require($_SERVER['DOCUMENT_ROOT'] . '/includes/configure.php');
//chdir($wwp);

$sql_host = $server;   /// ����
$sql_id = $db_user;         /// �����
$sql_pass = $db_pass;    /// ������
$sql_db = $db_name;      /// ����
######### ����  ###########
#������ ������ ��������� � ����� /lang/
#".php" ������ �� �����. �������� �������������� ��������
#
#�������� ��� ������������� ����������� ����� ����� ��������� ���:
#$mylang='english';
#!!!�������� ��� ������������� �������� ����� � UTF-8 ����� ��������� ���:
#$mylang='russianutf';
$mylang = $_SESSION['language'];

######## ������ � ������� ################
$typeadm = 1; //��� ����� � ������ ����������������� (1 - ����������� / 0 - ���-�����. ������������, ���� PHP ���������� ��� CGI)

$login = 'demo1'; //����� ���������

$pass = 'demo1'; //������
########## ������ ��������� ������ ######
$alphabet = "0123456789abcdefghijklmnopqrstuvwxyz";

# ������� ��� ���������
//$allowed_symbols = "0123456789"; #digits
$allowed_symbols = "23456789abcdeghkmnpqsuvxyz";

# ����� � ��������
$fontsdir = 'fonts';

# ����� ������
$length = mt_rand(5, 6); # �������� ���������� �� 5 �� 6 ��������
//$length = 6; // ׸���� ���������� � 6-�� ��������
# ������ ������� ������. (�� ��������� ����������� �������)
$width = 120;
$height = 60;
$fluctuation_amplitude = 5;

# ������� ����� ��������� (true - ��������� / false - ��������� )
$no_spaces = true;

# ���������� ����� �����
$show_credits = false;
$credits = '';

# ����� (RGB, 0-255)
//$foreground_color = array(0, 0, 0);
//$background_color = array(220, 230, 255);
$foreground_color = array(mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
$background_color = array(mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));

# JPEG �������� ������
$jpeg_quality = 90;

######### END ################

/**
 * ���������� ������ MySQLi ��� ������ � ��
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