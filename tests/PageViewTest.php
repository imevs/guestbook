<?php
include_once  dirname(__FILE__). '/../bootstrap.php';

class PageViewTest extends PHPUnit_Framework_TestCase
{
    public function testGetInstance()
    {
        $this->assertTrue(PageView::getInstance() instanceof PageView);
    }
}