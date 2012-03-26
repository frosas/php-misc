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
    static function filter($traversable, \Closure $closure = null) {
        if (! $closure) $closure = function($value) { return $value; };
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
    
    static function unique($traversable) {
        $uniques = array();
        foreach ($traversable as $key => $value) {
            if (! static::contains($uniques, $value)) {
                $uniques[$key] = $value;
            }
        }
        return $uniques;
    }
    
    /**
     * An asort() that works with any value 
     * 
     * Sorting type is SORT_STRING. Keys are maintained.
     * 
     * @param Traversable $traversable
     * @param \Closure $elementToString Returns the value that is actually used to do the sorting.
     *                                  The value itself is used by default.
     * @return array The $traversable as a sorted array
     */
    static function sort($traversable, \Closure $elementToString = null) {
        if (! $elementToString) $elementToString = function ($value) { return $value; };
        
        $elementsByString = array();
        foreach ($traversable as $key => $value) {
            $elementsByString[$elementToString($value, $key)][] = array($key, $value);
        }
        
        if (! ksort($elementsByString, SORT_STRING)) throw new \InvalidArgumentException;
        
        $sorted = array();
        foreach ($elementsByString as $elementsWithSameString) {
            foreach ($elementsWithSameString as $keyAndValue) {
                list($key, $value) = $keyAndValue;
                $sorted[$key] = $value;
            }
        }
        return $sorted;
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