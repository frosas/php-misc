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
        $error = json_last_error();
        if ($error === JSON_ERROR_NONE) return;
        throw new \InvalidArgumentException(self::getErrorMessage($error));
    }

    private static function getErrorMessage($error)
    {
        switch ($error) {
            case JSON_ERROR_DEPTH: return "Maximum stack depth exceeded";
            case JSON_ERROR_STATE_MISMATCH: return "Underflow or the modes mismatch";
            case JSON_ERROR_CTRL_CHAR: return "Unexpected control character found";
            case JSON_ERROR_SYNTAX: return "Syntax error, malformed JSON";
            case JSON_ERROR_UTF8: return "Malformed UTF-8 characters, possibly incorrectly encoded";
            default: return "Unknown error #$error";
        }
    }
}
