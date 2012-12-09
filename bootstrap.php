<?php
define('APP_WEB_PATH', '/guestbook');
define('APP_PATH', $_SERVER['DOCUMENT_ROOT'] . APP_WEB_PATH);
include 'data/data.php';

function my_autoloader($class) {
    include 'app/models/' . $class . '.class.php';
}
spl_autoload_register('my_autoloader');
