<?php

namespace Frosas;

class SetTest extends \PHPUnit_Framework_TestCase
{
    function testEmpty()
    {
        $this->assertCount(0, new Set);
    }

    function testAddOne()
    {
        $this->assertCount(1, new Set(array(1)));
    }

    function testAddTwice()
    {
        $this->assertCount(2, new Set(array(1, 2)));
    }

    function testAddSameTwice()
    {
        $this->assertCount(1, new Set(array(1, 1)));
    }

    function testToArray()
    {
        $this->assertEquals(array(1, 2, 3), Set::create(array(1, 2, 3))->toArray());
    }
}
