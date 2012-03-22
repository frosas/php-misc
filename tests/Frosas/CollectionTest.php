<?php

namespace Frosas;

class CollectionTest extends \PHPUnit_Framework_TestCase {
    
    function testWrapUnwrap() {
        $collection = array(1, 2, 3);
        $this->assertEquals($collection, Collection::wrap($collection)->unwrap());
    }

    function testMap() {
        $this->assertEquals(
            array(2, 4, 6), 
            Collection::map(array(1, 2, 3), function($value) {
                return $value * 2;
            })
        );
    }

    function testMapKeys() {
        $this->assertEquals(
            array(0 => 1, 2 => 2, 4 => 3),
            Collection::mapKeys(array(1, 2, 3), function($value, $key) {
                return $key * 2;
            })
        );
    }

    function testFilter() {
        $this->assertEquals(
            array(1 => 2), 
            Collection::filter(array(1, 2, 3), function($value, $key) {
                return $key % 2;
            })
        );
    }

    function testFirst() {
        $this->assertEquals(1, Collection::first(array(1, 2, 3)));
    }

    function testFirstOnEmptyCollection() {
        $this->setExpectedException('Frosas\NotFoundException');
        Collection::first(array());
    }

    function testLast() {
        $this->assertEquals(3, Collection::last(array(1, 2, 3)));
    }
}