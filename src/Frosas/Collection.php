<?php

namespace Frosas;

/**
 * Handy methods for collections (arrays, iterators and other traversable structures)
 */
use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;

final class Collection
{
    const DEFAULT_NULL = 'null';
    const DEFAULT_EXCEPTION = 'exception';

    /**
     * @param \Traversable $traversable
     * @param callable $callable
     * @return array
     */
    static function map($traversable, $callable) 
    {
        $mapped = array();
        foreach ($traversable as $key => $value) {
            $mapped[$key] = call_user_func($callable, $value, $key);
        }
        return $mapped;
    }

    /**
     * @param \Traversable $traversable
     * @param callable $callable
     * @return array
     */
    static function mapKeys($traversable, $callable) 
    {
        $mapped = array();
        foreach ($traversable as $key => $value) {
            $mapped[call_user_func($callable, $value, $key)] = $value;
        }
        return $mapped;
    }
    
    static function resetKeys($traversable) 
    {
        $array = array();
        $i = 0;
        foreach ($traversable as $value) $array[$i++] = $value;
        return $array;
    }

    /**
     * @param \Traversable $traversable
     * @param callable $condition
     * @return array
     */
    static function filter($traversable, $condition = null) 
    {
        $condition = $condition ?: 'static::get';
        $filtered = array();
        foreach ($traversable as $key => $value) {
            if (call_user_func($condition, $value, $key)) {
                $filtered[$key] = $value;
            }
        }
        return $filtered;
    }
    
    /**
     * An array_diff() with strict comparison
     * 
     * @param \Traversable $traversable
     * @param \Traversable $traversable2
     * @return array The elements in $traversable not in $traversable2
     */
    static function diff($traversable, $traversable2) 
    {
        return static::filter($traversable, function($value) use ($traversable2) {
            return ! Collection::contains($traversable2, $value);
        });
    }

    /**
     * @param \Traversable $traversable
     * @param mixed $value
     * @return boolean Whether $traversable contains $value (strict comparison)
     */
    static function contains($traversable, $value) 
    {
        foreach ($traversable as $value2) {
            if ($value2 === $value) return true;
        }
    }
    
    static function unique($traversable) 
    {
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
     * @param \Traversable $traversable
     * @param array $options
     *     - getValue: callable to get the value of each element used to do the 
     *       sorting (the element itself by default)
     *     - sorting: any flag accepted by sort() (SORT_STRING by default)
     * @return array The $traversable as a sorted array
     */
    static function sort($traversable, $options = null)
    {
        if (! is_array($options)) $options = array('getValue' => $options);
        $options += array('sorting' => SORT_STRING);
        if (! isset($options['getValue'])) $options['getValue'] = 'static::get';

        $elementsByString = array();
        foreach ($traversable as $key => $value) {
            $elementString = call_user_func($options['getValue'], $value, $key);
            $elementsByString[$elementString][] = array($key, $value);
        }

        if (! ksort($elementsByString, $options['sorting'])) throw new \InvalidArgumentException;

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
     * @deprecated See firstOption(), getFirst() and findFirst()
     *
     * @param \Traversable $traversable
     * @param mixed $options Array of options or a condition closure
     *     - condition: A callable called for every element returning whether it should be taken in account
     *     - default: What to do when no element is found. 'null' (default) to return null or
     *       'exception' to throw an exception.
     * @return mixed The first element or null if $options['default'] == 'null'
     * @throws NotFoundException If no elements are found and $options['default'] == 'exception'
     */
    static function first($traversable, $options = array()) 
    {
        if ($options instanceof \Closure) $options = array('condition' => $options);
        $options += array('default' => static::DEFAULT_NULL, 'condition' => null);

        $first = static::firstOption($traversable, $options['condition']);

        switch ($options['default']) {
            case static::DEFAULT_NULL: return $first->getOrElse(null);
            case static::DEFAULT_EXCEPTION: return $first->getOrThrow(new NotFoundException);
            default: throw new \InvalidArgumentException;
        }
    }

    /**
     * @return Option
     */
    static function firstOption($traversable, $condition = null)
    {
        foreach ($traversable as $value) {
            if ($condition && ! call_user_func($condition, $value)) continue;
            return new Some($value);
        }
        return None::create();
    }

    static function getFirst($traversable, $condition = null)
    {
        return static::firstOption($traversable, $condition)->getOrCall(function() {
            throw new NotFoundException;
        });
    }

    static function findFirst($traversable, $condition = null)
    {
        return static::firstOption($traversable, $condition)->getOrElse(null);
    }

    /**
     * @param \Traversable $traversable
     * @return mixed The last element
     * @throws NotFoundException If $traversable has no elements
     */
    static function last($traversable) 
    {
        foreach ($traversable as $value);
        if (! isset($value) && $value !== null) throw new NotFoundException;
        return $value;
    }

    /**
     * @param mixed $collection
     * @return Collection\Wrapper A wrapper on $collection that allows method chaining
     */
    static function wrap($collection) 
    {
        return new Collection\Wrapper($collection);
    }
    
    /**
     * @param \Traversable $traversable
     * @param callable $by
     * @return array A bi-dimensional array of the elements of $traversable grouped by $by
     */
    static function group($traversable, $by) 
    {
        $grouped = array();
        foreach ($traversable as $key => $value) {
            $grouped[$by($value)][$key] = $value;
        }
        return $grouped;
    }
    
    /**
     * @see array_reduce
     */
    static function reduce($traversable, $reduce, $initial = null) 
    {
        return array_reduce(self::toArray($traversable), $reduce, $initial);
    }
    
    static function toArray($traversable) 
    {
        $array = array();
        foreach ($traversable as $key => & $value) $array[$key] = $value;
        return $array;
    }

    /**
     * @return booleany Whether any item matches $condition
     */
    static function any($traversable, $condition) 
    {
        foreach ($traversable as $key => $value) {
            if (call_user_func($condition, $value, $key)) return true;
        }
    }

    /**
     * @return booleany Whether all items match $condition
     */
    static function all($traversable, $condition) 
    {
        foreach ($traversable as $key => $value) {
            if (! call_user_func($condition, $value, $key)) return;
        }

        return true;
    }

    /**
     * @return booleany Whether no item matches $condition
     */
    static function none($traversable, $condition) 
    {
        return ! self::any($traversable, $condition);
    }

    static function count($traversable) 
    {
        return count(static::toArray($traversable));
    }

    /**
     * @return array The collection reversed, the keys are preserved
     */
    static function reverse($traversable)
    {
        return array_reverse(static::toArray($traversable), true);
    }

    /**
     * Dummy function that simply returns the value itself
     * 
     * Used when a callable is optional
     */
    private static function get($value) 
    {
        return $value;  
    }
}
