<?php

namespace Frosas;

/**
 * Utilities around PHP errors
 */
class Error 
{
    static function createExceptionFromLast() 
    {
        $error = error_get_last();
        if (! $error) throw new \RuntimeException("Hasn't been any error yet");
        return new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
    }
}
