<?php

namespace Frosas\Misc\Collection;

class WrapperTest extends \PHPUnit_Framework_TestCase {
    
    function testUnknownMethod() {
        $wrapper = new Wrapper(array(1, 2, 3));
        $this->setExpectedException('Frosas\Misc\Callable\BadCallException');
        $wrapper->unknown();
    }
    
    function testApply() {
        $original = array(1, 2, 3);
        $reversed = array_reverse($original);
        
        $wrapper = new Wrapper($original);
        $this->assertEquals($reversed, $wrapper->apply('array_reverse')->unwrap());
    }
}