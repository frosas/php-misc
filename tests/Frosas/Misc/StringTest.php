<?php

namespace Frosas\Misc;

class StringTest extends \PHPUnit_Framework_TestCase
{    
    function testIsEmpty() 
    {
        $this->assertTrue(String::isEmpty(''));
        $this->assertTrue(String::isEmpty(null));
        
        $this->assertFalse(String::isEmpty(' '));
        $this->assertFalse(String::isEmpty(0));
    }
    
    function testIsBlank() 
    {
        $this->assertTrue(String::isBlank(''));
        $this->assertTrue(String::isBlank(' '));
        $this->assertTrue(String::isBlank(null));
        
        $this->assertFalse(String::isBlank('a'));
        $this->assertFalse(String::isBlank(0));
    }

    function testShorten()
    {
        $this->assertEquals('123', String::shorten('123', 4));
        $this->assertEquals('123', String::shorten('123', 3));
        $this->assertEquals('1…', String::shorten('123', 2));
        $this->assertEquals('…', String::shorten('123', 1));
    }
    
    function testShortenInvalidLength()
    {
        $this->setExpectedException('InvalidArgumentException');
        String::shorten('123', 0);
    }
}