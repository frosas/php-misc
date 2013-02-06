<?php

namespace Frosas\Collection;

class WrapperTest extends \PHPUnit_Framework_TestCase
{
    function testUnknownMethod()
    {
        $wrapper = new Wrapper(array(1, 2, 3));
        $this->setExpectedException('Frosas\Callback\BadCallException');
        $wrapper->unknown();
    }
    
    function testApply()
    {
        $original = array(1, 2, 3);
        $reversed = array_reverse($original);
        $wrapper = new Wrapper($original);
        $this->assertEquals($reversed, $wrapper->apply('array_reverse')->unwrap());
    }
    
    function testIterate()
    {
        $original = array(1, 2, 3);
        $iterated = array();
        foreach (new Wrapper($original) as $value) $iterated[] = $value;
        $this->assertEquals($original, $iterated);
    }

    function testCount()
    {
        $this->assertEquals(0, count(new Wrapper(array())));
        $this->assertEquals(3, count(new Wrapper(array(1, 2, 3))));
    }

    function testOffsetExists()
    {
        $wrapper = new Wrapper(array('a' => 'b'));
        $this->assertTrue(isset($wrapper['a']));
        $this->assertFalse(isset($wrapper['b']));
    }

    function testOffsetGet()
    {
        $wrapper = new Wrapper(array('a' => 'b'));
        $this->assertEquals('b', $wrapper['a']);
        $this->setExpectedException('PHPUnit_Framework_Error_Notice');
        $this->assertNotEquals('b', $wrapper['b']);
    }

    function testOffsetSet()
    {
        $wrapper = new Wrapper(array());
        $wrapper['a'] = 'b';
        $this->assertEquals('b', $wrapper['a']);
    }

    function testOffsetUnset()
    {
        $wrapper = new Wrapper(array('a' => 'b'));
        unset($wrapper['a']);
        $this->assertCount(0, $wrapper);
    }
}
