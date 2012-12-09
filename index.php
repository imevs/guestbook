<?php
include_once 'bootstrap.php';
$pageView = PageView::getInstance();
echo $pageView->renderPage();