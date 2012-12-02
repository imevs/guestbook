<?php
include 'data/data.php';

function my_autoloader($class) {
    include 'code/' . $class . '.class.php';
}
spl_autoload_register('my_autoloader');

$pageView = PageView::getInstance();
echo $pageView->renderPage();