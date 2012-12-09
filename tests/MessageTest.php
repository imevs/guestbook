<?php
include_once  dirname(__FILE__). '/../bootstrap.php';

class MessageTest extends PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $message = new Message();
        $this->assertNotNull($message);
    }
}
