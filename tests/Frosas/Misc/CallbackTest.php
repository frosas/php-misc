<?php 

namespace Frosas;

class CallbackTest extends \PHPUnit_Framework_TestCase {
    
    function testCall() {
        $this->assertEquals('ABC', Callback::call('strtoupper', array('abc')));
    }
    
    function testCallInvalidCallable() {
        $this->setExpectedException('Frosas\Callback\BadCallException');
        Callback::call('unknown');
    }
}
