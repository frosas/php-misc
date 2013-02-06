<?php

namespace Frosas;

use PHPUnit_Framework_Assert as Assert;

class MapTest extends \PHPUnit_Framework_TestCase
{
    function testGoToOneLevelPath()
    {
        $this->forEachMapType(array('a' => 'b'), function($map) {
            Assert::assertEquals('b', Map::go($map, 'a')->get());
        });
    }

    function testGoToOneLevelInvalidPath()
    {
        $this->forEachMapType(array('a' => 'b'), function($map) {
            try {
                Map::go($map, 'c')->get();
                Assert::fail("RuntimeException expected");
            } catch (\PHPUnit_Framework_AssertionFailedError $exception) {
                throw $exception;
            } catch (\RuntimeException $exception) {
            }
        });
    }

    function testGoToDeepPath()
    {
        $this->forEachMapType(array('a' => array('b' => 'c')), function($map) {
            Assert::assertEquals('c', Map::go($map, array('a', 'b'))->get());
        });
    }

    function testGoToDeepInvalidPath()
    {
        $this->forEachMapType(array('a' => array('b' => 'c')), function($map) {
            try {
                Map::go($map, array('a', 'd'))->get();
                Assert::fail("RuntimeException expected");
            } catch (\PHPUnit_Framework_AssertionFailedError $exception) {
                throw $exception;
            } catch (\RuntimeException $exception) {
            }
        });
    }

    function testGet()
    {
        $this->forEachMapType(array('a' => 'b'), function($map) {
            Assert::assertEquals('b', Map::get($map, 'a'));
        });
    }

    function testGetInvalidPath()
    {
        $this->forEachMapType(array('a' => 'b'), function($map) {
            try {
                Map::get($map, 'c');
                Assert::fail("RuntimeException expected");
            } catch (\PHPUnit_Framework_AssertionFailedError $exception) {
                throw $exception;
            } catch (\RuntimeException $exception) {
            }
        });
    }

    function testFind()
    {
        $this->forEachMapType(array('a' => 'b'), function($map) {
            Assert::assertEquals('b', Map::find($map, 'a'));
        });
    }

    function testFindInvalidPath()
    {
        $this->forEachMapType(array('a' => 'b'), function($map) {
            Assert::assertNull(Map::find($map, 'c'));
        });
    }

    function testFindInvalidPathWithDefault()
    {
        $this->forEachMapType(array('a' => 'b'), function($map) {
            Assert::assertEquals('d', Map::find($map, 'c', 'd'));
        });
    }

    function testExists()
    {
        $this->forEachMapType(array('a' => 'b'), function($map) {
            Assert::assertTrue(Map::exists($map, 'a'));
        });
    }

    function testInvalidPathExists()
    {
        $this->forEachMapType(array('a' => 'b'), function($map) {
            Assert::assertFalse(Map::exists($map, 'c'));
        });
    }

    function testIsMap()
    {
        $this->assertTrue(Map::isMap(array()));
        $this->assertTrue(Map::isMap(new \SplObjectStorage));
    }

    function testIsNotMap()
    {
        $this->assertFalse(Map::isMap('foo'));
        $this->assertFalse(Map::isMap(new \stdClass));
        $this->assertFalse(Map::isMap(new \MultipleIterator)); // instanceof Iterator
    }

    private function forEachMapType($array, $callback)
    {
        $callback($array);
        $callback(new \ArrayIterator($array)); // ArrayAccess
    }
}
