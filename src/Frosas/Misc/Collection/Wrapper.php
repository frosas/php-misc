<?php

namespace Frosas\Misc\Collection;

use Frosas\Misc\Callable;

class Wrapper {

    private $collection;

    function __construct($collection) {
        $this->collection = $collection;
    }

    function __call($method, $args) {
        array_unshift($args, $this->collection);
        $this->collection = Callable::call(array('Frosas\Misc\Collection', $method), $args);
        return $this;
    }

    function unwrap() {
        return $this->collection;
    }
}
