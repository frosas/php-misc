<?php

namespace Frosas\Http\Adapter;

/**
 * The adapter that gets the response
 */
class Reader implements \Zend\Http\Client\Adapter {

    private $connections;
    private $lastRespondedConnection;
    
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
        // Get the next responded connection, whichever it is
        $this->lastRespondedConnection = $this->connections->getNextResponded();
        return $this->lastRespondedConnection['request'];
    }
    
    function read() {
        return $this->lastRespondedConnection['response'];
    }
    
    function close() {
        // Not needed
    }
}