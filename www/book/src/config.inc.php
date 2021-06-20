<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('log_errors', '0');

define('LANGUAGE', 'en');
define('CHARSET', 'UTF-8');
define('LOCALE', 'hu_HU');
define('KEYWORDS', 'Transfermate,Book Registry');

define('INPUT_DATA', '/home/fattila/_projects/zz_work/transfermate/data'); // index file data root directory

//define('DB_MYSQL', true); // uncomment in case of mysql database backend
define('DB_HOST', 'localhost');
define('DB_USER', 'pesysadmin');
define('DB_PASS', 'foobar');
define('DB_NAME', 'transfermate');
define("DB_CHARSET", "utf8");
define('AUTHORS_TABLE', 'authors');
define('BOOKS_TABLE', 'books');
?>
