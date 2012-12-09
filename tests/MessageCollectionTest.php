<?php
include_once  dirname(__FILE__). '/../bootstrap.php';

class MessageCollectionTest extends PHPUnit_Framework_TestCase
{
    public function testGetCollection()
    {
        $messages = DB::getInstance()->getMessages();
        $this->assertEquals(1, count($messages->getMessages()));
    }

}
