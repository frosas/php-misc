<?php

namespace Frosas;

class Set implements \Countable
{
    private $values = array();

    static function create($values = array())
    {
        return new static($values);
    }

    function __construct($values = array())
    {
        foreach ($values as $value) $this->add($value);
    }

    function has($value)
    {
        return in_array($value, $this->values, true);
    }

    function add($value)
    {
        if (! $this->has($value)) $this->values[] = $value;
    }

    function toArray()
    {
        return $this->values;
    }

    function count()
    {
        return count($this->values);
    }
}