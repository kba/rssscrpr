<?php
require_once 'src/model/Feed.php';

class FeedTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $f1 = new Feed();
        $this->assertEquals(0, $f1->size());
        $f1->createItem();
        $this->assertEquals(1, $f1->size());
    }
}
