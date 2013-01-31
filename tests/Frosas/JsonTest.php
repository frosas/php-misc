<?php

namespace Frosas;

class JsonTest extends \PHPUnit_Framework_TestCase
{
    function testEncodeValidData()
    {
        $original = array(1, 2, 3);
        $json = Json::encode($original);
        $this->assertEquals($original, json_decode($json));
    }

    function testFailEncodingRecursiveArray()
    {
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        $array = array();
        $array[] =& $array;
        Json::encode($array);
    }

    function testFailEncodingResource()
    {
        $this->setExpectedException('PHPUnit_Framework_Error_Warning');
        Json::encode(tmpfile());
    }

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
