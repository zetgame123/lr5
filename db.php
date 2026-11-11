<?php
define('DB_HOST', 'localhost'); // Адрес сервера БД
define('DB_USER', 'root');      // Имя пользователя
define('DB_PASSWORD', 'root');      // Пароль
define('DB_NAME', 'lab5_data'); // Имя базы данных

$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($mysql->connect_error) {
    die('Ошибка подключения к базе данных: ' . $mysql->connect_error);
}

$mysql->set_charset('utf8mb4');
