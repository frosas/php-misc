<?php

namespace Frosas;

class Collection {

    static function map($collection, \Closure $closure) {
        foreach ($collection as $key => & $value) {
            $value = $closure($value, $key);
        }
        return $collection;
    }

    static function mapKeys($collection, \Closure $closure) {
        $mapped = array();
        foreach ($collection as $key => $value) {
            $mapped[$closure($value, $key)] = $value;
        }
        return $mapped;
    }

    static function filter($collection, \Closure $closure) {
        $filtered = array();
        foreach ($collection as $key => $value) {
            if ($closure($value, $key)) {
                $filtered[$key] = $value;
            }
        }
        return $filtered;
    }

    static function first($collection) {
        foreach ($collection as $value) return $value;
        throw new NotFoundException;
    }

    static function last($collection) {
        foreach ($collection as $value);
        if (! isset($value) && $value !== null) throw new NotFoundException;
        return $value;
    }

    static function wrap($collection) {
        return new Collection\Wrapper($collection);
    }
}