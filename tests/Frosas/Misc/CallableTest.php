<?php 

namespace Frosas\Misc;

class CallableTest extends \PHPUnit_Framework_TestCase {
    
    function testCall() {
        $this->assertEquals('ABC', Callable::call('strtoupper', array('abc')));
    }
    
    function testCallInvalidCallable() {
        $this->setExpectedException('Frosas\Misc\Callable\BadCallException');
        Callable::call('unknown');
    }
}