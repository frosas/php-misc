<?php

namespace Frosas\Misc\Collection;

class Wrapper {

    private $collection;

    function __construct($collection) {
        $this->collection = $collection;
    }

    function __call($method, $args) {
        array_unshift($args, $this->collection);
        $this->collection = call_user_func_array(array('Frosas\Misc\Collection', $method), $args);
        return $this;
    }

    function unwrap() {
        return $this->collection;
    }
}
