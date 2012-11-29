<?php 

namespace Frosas;

class ReflectionTest extends \PHPUnit_Framework_TestCase 
{
    function testGetFunctionCode()
    {
        $closure = function() {
            return "Hi";
        };

        $expected = 
            "\$closure = function() {\n" .
            "    return \"Hi\";\n" .
            "};";

        $this->assertEquals($expected, Reflection::getFunctionCode($closure));
    }
}

