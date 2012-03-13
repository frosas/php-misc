<?php

namespace Frosas\Http\Adapter;

/**
 * The adapter that gets the response
 */
class Reader implements \Zend\Http\Client\Adapter {

    private $rawRequest;
    private $rawResponse = '';

    function __construct($rawRequest) {
        $this->rawRequest = $rawRequest;
    }

    function setConfig($config = array()) {
        // Not needed
    }
    
    function connect($host, $port = 80, $secure = false) {
        // Not needed
    }
    
    function write($method, $url, $httpVersion = '1.1', $headers = array(), $body = '') {
        return $this->rawRequest;
    }
    
    function read() {
        return $this->rawResponse;
    }
    
    function close() {
        // Not needed
    }

    function appendToResponse($data) {
        $this->rawResponse .= $data;
    }
}