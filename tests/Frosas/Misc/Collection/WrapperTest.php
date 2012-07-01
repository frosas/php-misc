<?php

namespace Frosas\Misc\Collection;

class WrapperTest extends \PHPUnit_Framework_TestCase {
    
    function testUnknownMethod() {
        $wrapper = new Wrapper(array(1, 2, 3));
        $this->setExpectedException('Frosas\Misc\Callback\BadCallException');
        $wrapper->unknown();
    }
    
    function testApply() {
        $original = array(1, 2, 3);
        $reversed = array_reverse($original);
        $wrapper = new Wrapper($original);
        $this->assertEquals($reversed, $wrapper->apply('array_reverse')->unwrap());
    }
    
    function testIterate() {
        $original = array(1, 2, 3);
        $iterated = array();
        foreach (new Wrapper($original) as $value) $iterated[] = $value;
        $this->assertEquals($original, $iterated);
    }

    function testCount() {
        $this->assertEquals(0, count(new Wrapper(array())));

        $this->assertEquals(3, count(new Wrapper(array(1, 2, 3))));
    }
}