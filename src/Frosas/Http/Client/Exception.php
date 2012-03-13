<?php

namespace Frosas\Http\Client\Exception;

class Exception extends \RuntimeException {

    private $client;

    function __construct(\Zend\Http\Client $client, $previous) {
        parent::__construct($previous->getMessage, 0, $previous);
        $this->client = $client;
    }

    function client() {
        return $this->client;
    }
}