<?php

namespace Frosas\Misc;

/**
 * Utilities around callables
 * 
 * It should be called Callable or Function but these are reserved words
 */
class Callback {
    
    /**
     * Like call_user_func_array() but throwing exceptions on bad calls
     * 
     * @throws Callback\BadCallException
     */
    static function call($callable, $args = array()) {
        $result = @call_user_func_array($callable, $args);
        
        // null is also a possible error result (https://bugs.php.net/bug.php?id=47554)
        if (($result === false || $result === null) && ! is_callable($callable)) {
            // Thrown exception has to be quite unique to not be confused with one thrown by the callable 
            throw new Callback\BadCallException(Error::createExceptionFromLast());
        }
        
        return $result;
    }
}