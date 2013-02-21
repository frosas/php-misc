<?php

namespace Frosas;

class PropertyTest extends \PHPUnit_Framework_TestCase
{
    function testGetterArrayKey()
    {
        $getter = Property::getter('[key]');
        $array = array('key' => 'value');
        $this->assertEquals('value', $getter($array));
    }

    function testGetterArrayInvalidKey()
    {
        $getter = Property::getter('[invalidKey]');
        $array = array('key' => 'value');
        $this->setExpectedException('Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException');
        $getter($array);
    }

    function testFinderArrayKey()
    {
        $finder = Property::finder('[key]');
        $array = array('key' => 'value');
        $this->assertEquals('value', $finder($array));
    }

    function testFinderArrayInvalidKey()
    {
        $finder = Property::finder('[invalidKey]');
        $array = array('key' => 'value');
        $this->assertNull($finder($array));
    }
}
