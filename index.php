<?php
include 'data/data.php';

function my_autoloader($class) {
    include 'app/models/' . $class . '.class.php';
}
spl_autoload_register('my_autoloader');

$pageView = PageView::getInstance();
echo $pageView->renderPage();