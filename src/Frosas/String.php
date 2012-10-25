<?php

namespace Frosas;

class String
{
    /**
     * @return boolean Is $string as a string exactly ''? 
     */
    static function isEmpty($string)
    {
        return (string) $string === '';
    }
    
    /**
     * @return boolean Does $string contains only non visible characters?
     */
    static function isBlank($string)
    {
        return trim($string) === '';
    }
    
    /**
     * @return string $string shortened to $length characters. An ellipsis is appended to the end 
     * if the string is actually shortened. 
     */
    static function shorten($string, $length)
    {
        if ($length < 1) throw new \InvalidArgumentException("Length can't be less than 1");
        
        if (mb_strlen($string) > $length) {
            $string = mb_substr($string, 0, $length - 1) . '…';
        } 
            
        return $string;
    }
}
