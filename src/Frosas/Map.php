<?php

namespace Frosas;
 
use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;

class Map
{
    /**
     * @return Option
     */
    static function go($map, $path)
    {
        $path = self::toArray($path);
        while ($path) {
            $index = array_shift($path);
            if (! array_key_exists($index, $map)) return None::create();
            $map = $map[$index];
        }
        return new Some($map);
    }

    static function get($map, $path, $default = null)
    {
        $option = static::go($map, $path);
        return $default ? $option->getOrElse($default) : $option->get();
    }

    static function find($map, $path)
    {
        return static::go($map, $path)->getOrElse(null);
    }

    private static function toArray($value)
    {
        return is_array($value) ? $value : array($value);
    }
}
