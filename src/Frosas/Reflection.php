<?php

namespace Frosas;
 
use ReflectionFunction;
use SplFileObject;

class Reflection
{
    static function getFunctionCode($function)
    {
        $r = new ReflectionFunction($function);
        $code = '';
        foreach (new SplFileObject($r->getFileName()) as $line => $string) {
            if ($line < $r->getStartLine() - 1) continue;
            if ($line == $r->getEndLine()) break;
            $code .= $string;
        }
        return static::unindent($code);
    }

    private static function unindent($code)
    {
        $lines = explode("\n", rtrim($code));
        while (true) {
            $chars = array();
            foreach ($lines as $line) $chars[] = $line[0];
            if (count(array_unique($chars)) > 1) return implode("\n", $lines);
            foreach ($lines as $i => $line) $lines[$i] = substr($line, 1);
        }
    }
}
