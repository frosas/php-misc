<?php

namespace Frosas\Misc;

class CollectionTest extends \PHPUnit_Framework_TestCase {
    
    function testWrapper() {
        $collection = array(1, 2, 3);
        $this->assertEquals($collection, Collection::wrap($collection)->filter()->unwrap());
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
    
    function testFilterWithNoClosure() {
        $this->assertEquals(array('a', 1), Collection::filter(array('a', 1, 0, '', null)));
    }

    function testDiff() {
        $this->assertEquals(array(1 => 2, 2 => 3), Collection::diff(array(1, 2, 3), array(1)));
    }

    function testContains() {
        $this->assertTrue(Collection::contains(array(1, 2, 3), 1));
        $this->assertTrue(! Collection::contains(array(1, 2, 3), 4));
    }
    
    function testUnique() {
        $this->assertEquals(array(1, 2, 3, true, 5 => '3'), Collection::unique(array(1, 2, 3, true, 2, '3')));
    }
    
    function testSort() {
        $this->assertEquals(
            array(2 => 1, 0 => '2', 1 => 3),
            Collection::sort(array('2', 3, 1)));
    }
    
    function testSortWithClosure() {
        $this->assertEquals(
            array(1 => array(), 0 => array(1), 2 => array(1, 1)),
            Collection::sort(array(array(1), array(), array(1, 1)), function($array) {
                return count($array);
            })
        );
    }

    function testFirst() {
        $this->assertEquals(1, Collection::first(array(1, 2, 3)));
    }
    
    function testFirstWithClosure() {
        $even = function($value) { return ! ($value % 2); };
        $this->assertEquals(2, Collection::first(array(1, 2, 3), $even));
    }
    
    function testFirstOnEmptyCollection() {
        $this->assertEquals(null, Collection::first(array()));
    }
    
    function testFirstWithFallback() {
        $this->setExpectedException('Frosas\Misc\NotFoundException');
        Collection::first(array(), array('default' => 'exception'));
    }
    
    function testLast() {
        $this->assertEquals(3, Collection::last(array(1, 2, 3)));
    }
    
    function testGroup() {
        $this->assertEquals(
            array('odd' => array(1, 2 => 3), 'even' => array(1 => 2)),
            Collection::group(array(1, 2, 3), function($value) {
                return $value % 2 ? 'odd' : 'even';
            })
        );
    }

    function testAny() {
        $this->assertTrue((boolean) Collection::any(array(1, 2, 3), function($value) {
            return $value === 2;
        }));

        $this->assertFalse((boolean) Collection::any(array(1, 2, 3), function($value) {
            return $value === 4;
        }));
    }

    function testAll() {
        $this->assertTrue((boolean) Collection::all(array(1, 2, 3), function($value) {
            return $value > 0;
        }));

        $this->assertFalse((boolean) Collection::all(array(1, 2, 3), function($value) {
            return $value > 1;
        }));
    }

    function testNone() {
        $this->assertTrue((boolean) Collection::none(array(1, 2, 3), function($value) {
            return $value > 3;
        }));

        $this->assertFalse((boolean) Collection::none(array(1, 2, 3), function($value) {
            return $value > 2;
        }));
    }
}