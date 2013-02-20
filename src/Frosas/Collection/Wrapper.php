<?php

namespace Frosas\Collection;

use Frosas\Collection;
use Frosas\Map;

class Wrapper implements \IteratorAggregate, \Countable, \ArrayAccess
{
    private $collection;

    function __construct($collection) 
    {
        $this->collection = $collection;
    }

    /**
     * @return Wrapper
     */
    function __call($method, $args) 
    {
        array_unshift($args, $this->collection);
        $this->collection = call_user_func_array(array('Frosas\Collection', $method), $args);
        return $this;
    }

    function unwrap() 
    {
        return $this->collection;
    }
    
    /**
     * Applies $callable to the whole collection
     * 
     * Example: $wrapped->apply('array_reverse')
     * 
     * @return Wrapper
     */
    function apply($callable) 
    {
        $this->collection = call_user_func($callable, $this->collection);
        return $this;
    }

    function reduce($reduce, $initial = null) 
    {
        return Collection::reduce($this->collection, $reduce, $initial);
    }

    function count() 
    {
        return Collection::count($this->collection);
    }

    function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }

    function offsetExists($offset)
    {
        $this->toMap(); // TODO Avoid modifying it?
        return array_key_exists($offset, $this->collection);
    }

    function offsetGet($offset)
    {
        $this->toMap(); // TODO Avoid modifying it?
        return $this->collection[$offset];
    }

    function offsetSet($offset, $value)
    {
        $this->toMap();
        $this->collection[$offset] = $value;
    }

    function offsetUnset($offset)
    {
        $this->toMap();
        unset($this->collection[$offset]);
    }

    private function toMap()
    {
        if (! Map::isMap($this->collection)) $this->toArray();
    }
}
