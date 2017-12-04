<?php

/**
 * Пример файла конфигурации магазина.
 * Вам необходимо указать собственные значения параметров.
 */

$path    = $_SERVER['DOCUMENT_ROOT'] . '/'; // Путь к корневому каталогу, в который установлен магазин
$admin   = 'admin';                         // Папка админки
$domain  = $_SERVER['SERVER_NAME'];         // Домен сайта
$server  = 'localhost';                     // Имя сервера MySQL
$db_name = 'easy3a';                        // Название БД магазина
$db_user = 'root';                          // Имя пользователя для подключения к серверу БД
$db_pass = '';                              // Пароль пользователя для подключения к серверу БД