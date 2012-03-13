<?php

namespace Frosas\Http\Adapter;

/**
 * The adapter that gets the response
 */
class Reader implements \Zend\Http\Client\Adapter {

    private $connections;
    
    function __construct(\Frosas\Http\Connections $connections) {
        $this->connections = $connections;
    }
    
    function setConfig($config = array()) {
        // Not needed
    }
    
    function connect($host, $port = 80, $secure = false) {
        // Not needed
    }
    
    function write($method, $url, $httpVersion = '1.1', $headers = array(), $body = '') {
        $connection = $this->connections->waitForNextResponse();
        return $connection['rawRequest'];
    }
    
    function read() {
        $connection = $this->connections->getLastResponded();
        return $connection['rawResponse'];
    }
    
    function close() {
        // Not needed
    }
}