<?php
include ("config.php");if ($coder==1){$dbcoder="cp1251 COLLATE cp1251_general_ci ";}else {$dbcoder="utf8 COLLATE utf8_unicode_ci ";}
$query = "CREATE TABLE `".$table."` (
`num` int(10) NOT NULL auto_increment,
`url` varchar(255) NOT NULL,
`name` TEXT NOT NULL,
`comm` TEXT NOT NULL,
`data` DATETIME NOT NULL,
`moder` int(1) NOT NULL, 
`ip` TEXT NOT NULL, 
`mail` TEXT NOT NULL, 
`rootid` INT NOT NULL,
`level` INT NOT NULL DEFAULT '0',
`raitng` INT NOT NULL DEFAULT '0',
`iprating` TEXT NOT NULL, 
`http` TEXT NOT NULL, 
`service` TEXT NOT NULL, 
`avatar` TEXT NOT NULL, 
`admincom` INT NOT NULL DEFAULT '0',
PRIMARY KEY (`num`) 
) ENGINE = MYISAM CHARACTER SET ".$dbcoder."";

$query2 = "CREATE TABLE `".$table2."` (
`id` INT NOT NULL AUTO_INCREMENT ,
 `par` TEXT NOT NULL ,
 `val` TEXT NOT NULL ,
 `com` TEXT NOT NULL ,
 `tmp` TEXT NOT NULL ,
PRIMARY KEY (`id`) 
) ENGINE = MYISAM CHARACTER SET ".$dbcoder." AUTO_INCREMENT=61" ;

$query3 ="
INSERT INTO `".$table2."` (`id`, `par`, `val`,`com`, `tmp`) VALUES
(1, 'smile', '1','', ''),
(2, 'bbpanel', '1','', ''),
(3, 'linkbb', '1','', ''),
(4, 'linknofol', '1','', ''),
(5, 'linkredirect', '','', ''),
(6, 'linkpars', '0','', ''),
(7, 'picbb', '1','', ''),
(8, 'picpars', '0','', ''),
(9, 'quotebb', '0','', ''),
(10, 'quotestyle', 'border: dotted #666 1px;border-left:solid #ff5a00 5px;margin:10px 10px;color:#333;font-style:italic;background:#fcfcfc;padding:15px;','', ''),
(11, 'colorbb', '1','', ''),
(12, 'sizebb', '1','', ''),
(13, 'justbb', '1','', ''),
(14, 'sumvl', '200','', ''),
(15, 'sumvlname', '20','', ''),
(16, 'wordcut', '50','', ''),
(17, 'moder', '0','', ''),
(18, 'http', '0','', ''),
(19, 'mail', '0','', ''),
(20, 'mailbox', 'mail@mail.ru','', ''),
(21, 'viewentermail', '0','', ''),
(22, 'replaymail', '0','', ''),
(23, 'listz', '6','', ''),
(24, 'sort', '1','', ''),
(25, 'nameadmin', 'DEMOAdmin','', ''),
(26, 'formatdate', 'Y-m-d H:i:s','', ''),
(27, 'correcttime', '+0 hours','', ''),
(28, 'specurl', '','', ''),
(29, 'hideaddform', '1','', ''),
(30, 'autosize', '1','', ''),
(31, 'useravatar', '0','', ''),
(32, 'workcapt', '1','', ''),
(33, 'maxlevel', '5','', ''),
(34, 'pxlevel', '20','', ''),
(35, 'startpx', '5','', ''),
(36, 'lastcomment', '5','', ''),
(37, 'lastsum', '5','', ''),
(38, 'lastend', '...','', ''),
(39, 'rss', '1','', ''),
(40, 'titlecia', 'CommentIt 5 Ajax','', ''),
(41, 'descriptioncia', 'Comment in you site','', ''),
(42, 'yaping', '0','', ''),
(43, 'europing', '0','', ''),
(44, 'antimat', '0','', ''),
(45, 'antilevel', '1','', ''),
(46, 'antislovo', '<censor>','', ''),
(47, 'rating', '1','', ''),
(48, 'ratingadmin', '1','', ''),
(49, 'loginzaglob', '0','', ''),
(50, 'yandex', '0','', ''),
(51, 'google', '0','', ''),
(52, 'vkontakte', '1','', ''),
(53, 'mailru', '1','', ''),
(54, 'twitter', '0','', ''),
(55, 'loginza', '1','', ''),
(56, 'myopenid', '0','', ''),
(57, 'openid', '0','', ''),
(58, 'webmoney', '0','', ''),
(59, 'loginzavatar', '0','', ''),
(60, 'loginzurl', '0','','');
";

$query5 ="
ALTER TABLE `$table` ADD INDEX (`url`) 
";

@mysql_query($query) or die("Error:".mysql_error()."");
@mysql_query($query2) or die("Error:".mysql_error()."");
@mysql_query($query3) or die("Error:".mysql_error()."");
@mysql_query($query5) or die("Error:".mysql_error()."");
echo "CommentIt 5 Ajax: Installation finished";
?>