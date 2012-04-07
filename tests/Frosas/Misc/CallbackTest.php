<?php 

namespace Frosas\Misc;

class CallbackTest extends \PHPUnit_Framework_TestCase {
    
    function testCall() {
        $this->assertEquals('ABC', Callback::call('strtoupper', array('abc')));
    }
    
    function testCallInvalidCallable() {
        $this->setExpectedException('Frosas\Misc\Callback\BadCallException');
        Callback::call('unknown');
    }
}