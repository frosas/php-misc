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
     * An array_diff() with strict comparison
     * 
     * @param Traversable $traversable
     * @param Traversable $traversable2
     * @return array The elements in $traversable not in $traversable2
     */
    static function diff($traversable, $traversable2) {
        return static::filter($traversable, function($value) use ($traversable2) {
            return ! Collection::contains($traversable2, $value);
        });
    }

    /**
     * @param Traversable $traversable
     * @param mixed $value
     * @return boolean Whether $traversable contains $value (strict comparison)
     */
    static function contains($traversable, $value) {
        foreach ($traversable as $value2) {
            if ($value2 === $value) return true;
        }
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