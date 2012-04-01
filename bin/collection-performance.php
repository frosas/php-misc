#!/usr/bin/env php
<?php

namespace Frosas\Misc\Collection;

require __DIR__ . '/../vendor/.composer/autoload.php';

use Frosas\Misc\Collection;

class Performance {

    static function testMap() {
        $array = self::dummyArray();
        $callable = self::dummyClosure();
        self::compareTimes(array(
            'Collection::map()' => function() use ($array, $callable) {
                Collection::map($array, $callable);
            },
            'array_map()' => function() use ($array, $callable) {
                array_map($callable, $array);
            }
        ));
    }
   
    static function testFilter() {
        $array = self::dummyArray();
        self::compareTimes(array(
            'Collection::filter()' => function() use ($array) {
                Collection::filter($array);
            },
            'array_filter()' => function() use ($array) {
                array_filter($array);
            }
        ));
    }
   
    static function testFilterWithClosure() {
        $array = self::dummyArray();
        $callable = self::dummyClosure();
        self::compareTimes(array(
            'Collection::filter() w/ closure' => function() use ($array, $callable) {
                Collection::filter($array, $callable);
            },
            'array_filter() w/ closure' => function() use ($array, $callable) {
                array_filter($array, $callable);
            }
        ));
    }
   
    static function testDiff() {
        $array = self::dummyArray();
        self::compareTimes(array(
            'Collection::diff()' => function() use ($array) {
                Collection::diff($array, $array);
            },
            'array_diff()' => function() use ($array) {
                array_diff($array, $array);
            }
        ));
    }
    
    static function testFirst() {
        $array = self::dummyArray();
        self::compareTimes(array(
            'Collection::first()' => function() use ($array) {
                Collection::first($array);
            },
            'reset()' => function() use ($array) {
                reset($array);
            }
        ));
    }
    
    static function testLast() {
        $array = self::dummyArray();
        self::compareTimes(array(
            'Collection::last()' => function() use ($array) {
                Collection::last($array);
            },
            'end()' => function() use ($array) {
                end($array);
            }
        ));
    }
    
    private static function compareTimes(array $closures) {
        
        echo "- " . implode(" vs ", array_keys($closures)) . "\n";
        
        $times = Collection::map($closures, function() { return array(); });
        for ($i = 100; $i; $i--) {
            foreach ($closures as $name => $closure) {
                $start = microtime(true);
                $closure();
                $times[$name][] = microtime(true) - $start;
            }
        }
            
        foreach ($times as $name => & $closureTimes) {
            $closureTimes = self::average($closureTimes);
        }
        
        asort($times);
        
        $red = "\x1b[31m";
        $green = "\x1b[32m";
        $reset = "\x1b[0m";
            
        reset($times); // Make sure each() gets the first element
        list($name) = each($times);
        echo "  - $green{$name} was the fastest$reset\n";
                
        $firstTime = array_shift($times);
        foreach ($times as $name => $time) {
            $timesSlower = round($time / $firstTime, 1);
            echo "  - $red{$name} was $timesSlower times slower$reset\n";
        }
    }
    
    private static function average(array $values) {
        return array_sum($values) / count($values);
    }
    
    private static function dummyArray() {
        return range(0, 100);
    }
    
    private static function dummyClosure() {
        return function($value) { return $value; };
    }
}

foreach (get_class_methods('Frosas\Misc\Collection\Performance') as $method) {
    call_user_func("Frosas\Misc\Collection\Performance::$method");
}
