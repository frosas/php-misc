<?php

namespace Frosas;

/**
 * Handy methods for collections (arrays, iterators and other traversable structures)
 */
final class Collection {

    /**
     * @param Traversable $traversable
     * @return array
     */
    static function map($traversable, \Closure $closure) {
        $mapped = array();
        foreach ($traversable as $key => & $value) {
            $mapped[$key] = $closure($value, $key);
        }
        return $mapped;
    }

    /**
     * @param Traversable $traversable
     * @return array
     */
    static function mapKeys($traversable, \Closure $closure) {
        $mapped = array();
        foreach ($traversable as $key => $value) {
            $mapped[$closure($value, $key)] = $value;
        }
        return $mapped;
    }

    /**
     * @param Traversable $traversable
     * @return array
     */
    static function filter($traversable, \Closure $closure) {
        $filtered = array();
        foreach ($traversable as $key => $value) {
            if ($closure($value, $key)) {
                $filtered[$key] = $value;
            }
        }
        return $filtered;
    }

    /**
     * @param Traversable $traversable
     * @return mixed The first element
     * @throws NotFoundException If $traversable has no elements
     */
    static function first($traversable) {
        foreach ($traversable as $value) return $value;
        throw new NotFoundException;
    }

    /**
     * @param Traversable $traversable
     * @return mixed The last element
     * @throws NotFoundException If $traversable has no elements
     */
    static function last($traversable) {
        foreach ($traversable as $value);
        if (! isset($value) && $value !== null) throw new NotFoundException;
        return $value;
    }

    /**
     * @param mixed $collection
     * @return Collection\Wrapper A wrapper on $collection that allows method chaining
     */
    static function wrap($collection) {
        return new Collection\Wrapper($collection);
    }
}