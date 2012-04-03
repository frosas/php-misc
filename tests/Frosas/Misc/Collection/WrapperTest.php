<?php

namespace Frosas\Misc\Collection;

class WrapperTest extends \PHPUnit_Framework_TestCase {
    
    function testUnknownMethod() {
        $wrapper = new Wrapper(array(1, 2, 3));
        $this->setExpectedException('Frosas\Misc\Callable\BadCallException');
        $wrapper->unknown();
    }
}