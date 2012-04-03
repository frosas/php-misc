<?php

namespace Frosas\Misc;

/**
 * Exception representing a PHP error
 */
class ErrorException extends \Exception {
    
    static function createFromLastError() {
        $error = error_get_last();
        if (! $error) throw new \RuntimeException("Hasn't been any error yet");
        return new static($error);
    }
    
    function __construct(array $error) {
        parent::__construct($error['message']);
        $this->file = $error['file'];
        $this->line = $error['line'];
    }
}