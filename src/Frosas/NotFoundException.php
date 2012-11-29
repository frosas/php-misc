<?php

namespace Frosas;

use Exception;

class NotFoundException extends \Exception
{
    function __construct($message = "", $code = 0, Exception $previous = null)
    {
        if (! $message) $message = "Not found";
        parent::__construct($message, $code, $previous);
    }
}
