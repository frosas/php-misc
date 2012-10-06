<?php

namespace Frosas\Misc;

class Json
{
    static function decode()
    {
        $data = call_user_func_array('json_decode', func_get_args());
        if (json_last_error() !== JSON_ERROR_NONE) throw new \InvalidArgumentException;
        return $data;
    }
}
