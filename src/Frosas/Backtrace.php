<?php

namespace Frosas;
 
class Backtrace
{
    private $backtrace;

    static function createFromException(\Exception $exception)
    {
        $backtrace = $exception->getTrace();

        // Wondering why PHP removes this information
        $backtrace[0]['file'] = $exception->getFile();
        $backtrace[0]['line'] = $exception->getLine();

        return new static($backtrace);
    }

    function __construct($backtrace = null)
    {
        $this->backtrace = $backtrace ?: debug_backtrace();
    }

    function __toString()
    {
        $stepsCount = count($this->backtrace);
        $stepsCountLength = strlen($stepsCount);
        return Collection::wrap($this->backtrace)
            ->map(function($step, $index) use ($stepsCount, $stepsCountLength) {
                $string = sprintf("%{$stepsCountLength}s. ", $stepsCount - $index);
                if (isset($step['class'])) $string .= $step['class'] . '::';
                $string .= $step['function'] . '()';
                if (isset($step['file'])) $string .= ' @ ' . $step['file'] . ':' . $step['line'];
                return $string;
            })
            ->reduce(function($backtrace, $step) { return "$backtrace$step\n"; });
    }
}