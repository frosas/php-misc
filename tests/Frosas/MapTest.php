<?php

namespace Frosas;

class MapTest extends \PHPUnit_Framework_TestCase
{
    function testGoToOneLevelPath()
    {
        $this->assertEquals('b', Map::go(array('a' => 'b'), 'a')->get());
    }

    function testGoToOneLevelInvalidPath()
    {
        $this->setExpectedException('RuntimeException');
        Map::go(array('a' => 'b'), 'c')->get();
    }

    function testGoToDeepPath()
    {
        $this->assertEquals('c', Map::go(array('a' => array('b' => 'c')), array('a', 'b'))->get());
    }

    function testGoToDeepInvalidPath()
    {
        $this->setExpectedException('RuntimeException');
        Map::go(array('a' => array('b' => 'c')), array('a', 'd'))->get();
    }

    function testGet()
    {
        $this->assertEquals('b', Map::get(array('a' => 'b'), 'a'));
    }

    function testGetInvalidPath()
    {
        $this->setExpectedException('RuntimeException');
        Map::get(array('a' => 'b'), 'c');
    }

    function testGetInvalidPathWithDefault()
    {
        $this->assertEquals('d', Map::get(array('a' => 'b'), 'c', 'd'));
    }

    function testFind()
    {
        $this->assertEquals('b', Map::find(array('a' => 'b'), 'a'));
    }

    function testFindInvalidPath()
    {
        $this->assertNull(Map::find(array('a' => 'b'), 'c'));
    }
}
