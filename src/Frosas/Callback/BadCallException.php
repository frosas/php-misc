<?php 

namespace Frosas\Callback;

class BadCallException extends \Exception 
{
    function __construct(\ErrorException $exception) 
    {
        parent::__construct($exception->getMessage(), $exception->getCode(), $exception);
    }
}
