<?php

require_once 'src/model/Item.php';

class ItemTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $i = new Item(array(
            'title'=>'foo'
        ));
        $this->assertEquals('foo', $i->title);

        // $this->assertEquals('Unknown Author', Item::PROTOTYPE()->author);
    }
}
