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
                $string = sprintf("%{$stepsCountLength}s. ", $index);
                if (isset($step['class'])) $string .= $step['class'] . '::';
                $string .= $step['function'] . '()';
                if (isset($step['file'])) $string .= ' @ ' . $step['file'] . ':' . $step['line'];
                return $string;
            })
            ->reduce(function($backtrace, $step) { return "$backtrace$step\n"; });
    }
}