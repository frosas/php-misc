<?php

namespace Frosas;

class Json
{
    /**
     * @see json_encode()
     *
     * Some errors (like encoding recursive arrays or resources) are not caught.
     * Having an error handler that converts them to exceptions is highly
     * recommended.
     */
    static function encode()
    {
        $json = call_user_func_array('json_encode', func_get_args());
        self::throwLastError();
        return $json;
    }

    /**
     * @see json_decode()
     */
    static function decode()
    {
        $data = call_user_func_array('json_decode', func_get_args());
        self::throwLastError();
        return $data;
    }

    private static function throwLastError()
    {
        $lastError = json_last_error();
        if ($lastError === JSON_ERROR_NONE) return;
        throw new \InvalidArgumentException("Error #$lastError");
    }
}
