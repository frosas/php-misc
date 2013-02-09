<?php

namespace Frosas;
 
class Backtrace
{
    private $backtrace;

    function __construct()
    {
        $this->backtrace = debug_backtrace();
    }

    function __toString()
    {
        $stepsCountLength = strlen(count($this->backtrace));
        return Collection::wrap($this->backtrace)
            ->map(function($step, $index) use ($stepsCountLength) {
                $string = '';
                if (isset($step['class'])) $string .= $step['class'] . '::';
                $string .= $step['function'] . '()';
                $string .= ' @ ' . $step['file'] . ':' . $step['line'];
                $string = sprintf("%{$stepsCountLength}s. %s", $index, $string);
                return $string;
            })
            ->reduce(function($backtrace, $step) { return "$backtrace$step\n"; });
    }
}