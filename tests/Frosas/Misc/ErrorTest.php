<?php 

namespace Frosas\Misc;

class ErrorTest extends \PHPUnit_Framework_TestCase {
    
    function testCreateFromLastError() {
        @UNKNOWN;
        $exception = Error::createExceptionFromLast();
        $this->assertEquals("Use of undefined constant UNKNOWN - assumed 'UNKNOWN'", $exception->getMessage());
    }
}