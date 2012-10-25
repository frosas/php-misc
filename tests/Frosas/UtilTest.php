<?php

namespace Frosas;

class UtilTest extends \PHPUnit_Framework_TestCase
{    
    function testGetObject() 
    {
        $value = new \stdClass;
        $this->assertEquals($value, Util::get($value));
    }
}
