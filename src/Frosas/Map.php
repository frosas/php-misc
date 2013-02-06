<?php

namespace Frosas;
 
use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;

/**
 * Utils to work with arrays and ArrayAccess classes acting as maps
 *
 */
class Map
{
    /**
     * @param $map array|\ArrayAccess
     * @param $path mixed A valid array key or an array of them
     * @return Option
     */
    static function go($map, $path)
    {
        foreach (static::toArray($path) as $key) {
            if (! static::keyExists($map, $key)) return None::create();
            $map = $map[$key];
        }
        return new Some($map);
    }

    /**
     * @param $map array|\ArrayAccess
     * @param $path mixed A valid array key or an array of them
     * @return mixed The value at $path or $default (if set)
     * @throw Exception If $path doesn't exist and $default isn't set
     */
    static function get($map, $path, $default = null)
    {
        $option = static::go($map, $path);
        return $default ? $option->getOrElse($default) : $option->get();
    }

    /**
     * @param $map array|\ArrayAccess
     * @param $path mixed A valid array key or an array of them
     * @return mixed The value at $path or null
     */
    static function find($map, $path)
    {
        return static::go($map, $path)->getOrElse(null);
    }

    /**
     * @param $map array|\ArrayAccess
     * @param $path mixed A valid array key or an array of them
     * @return boolean Does $path exist?
     */
    static function exists($map, $path)
    {
        return static::go($map, $path)->isDefined();
    }

    /**
     * @return boolean Can $value be accessed like a map (i.e. $value['key'])
     */
    static function isMap($value)
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }

    private static function keyExists($map, $key)
    {
        if (is_array($map)) return array_key_exists($key, $map);
        if ($map instanceof \ArrayAccess) return $map->offsetExists($key);
        throw new \InvalidArgumentException("Unknown map type");
    }

    private static function toArray($value)
    {
        return is_array($value) ? $value : array($value);
    }
}
