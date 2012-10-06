<?php

namespace Frosas\Misc;

class JsonTest extends \PHPUnit_Framework_TestCase
{
    function testDecodeValidJson()
    {
        $original = array(1, 2, 3);
        $json = json_encode($original);
        $decoded = Json::decode($json);
        $this->assertEquals($original, $decoded);
    }

    function testFailDecodingInvalidJson()
    {
        $this->setExpectedException('InvalidArgumentException');
        Json::decode("{1: 2}");
    }
}
