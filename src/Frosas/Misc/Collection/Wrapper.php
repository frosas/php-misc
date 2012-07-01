<?php

namespace Frosas\Misc\Collection;

use Frosas\Misc\Collection;
use Frosas\Misc\Callback;

class Wrapper implements \IteratorAggregate, \Countable {

    private $collection;

    function __construct($collection) {
        $this->collection = $collection;
    }

    /**
     * @return Wrapper
     */
    function __call($method, $args) {
        array_unshift($args, $this->collection);
        $this->collection = Callback::call(array('Frosas\Misc\Collection', $method), $args);
        return $this;
    }

    function unwrap() {
        return $this->collection;
    }
    
    /**
     * Applies $callable to the whole collection
     * 
     * Example: $wrapped->apply('array_reverse')
     * 
     * @return Wrapper
     */
    function apply($callable) {
        $this->collection = Callback::call($callable, array($this->collection));
        return $this;
    }

    function reduce($reduce, $initial = null) {
        return Collection::reduce($this->collection, $reduce, $initial);
    }

    function count() {
        return Collection::count($this->collection);
    }

    function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }
}
