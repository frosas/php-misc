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
            if (! array_key_exists($key, $map)) return None::create();
            $map = $map[$key];
        }
        return new Some($map);
    }

    /**
     * @param $map array|\ArrayAccess
     * @param $path mixed A valid array key or an array of them
     * @return mixed The value at $path
     * @throw RuntimeException If $path doesn't exist
     */
    static function get($map, $path)
    {
        return static::go($map, $path)->get();
    }

    /**
     * @param $map array|\ArrayAccess
     * @param $path mixed A valid array key or an array of them
     * @return mixed The value at $path or $default
     */
    static function find($map, $path, $default = null)
    {
        return static::go($map, $path)->getOrElse($default);
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

    private static function toArray($value)
    {
        return is_array($value) ? $value : array($value);
    }
}
