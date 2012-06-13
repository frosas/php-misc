<?php

namespace Frosas\Misc;

class Util
{
    /**
     * Useful for chaining method calls in ways PHP (5.3) doesn't allow:
     * - Util::get(new Object)->doSomething()
     * - Util::get(clone \DateTime)->add(...)
     *  
     * @param object $object
     * @return object The $object
     */
    static function get($object)
    {
        return $object;
    }
}