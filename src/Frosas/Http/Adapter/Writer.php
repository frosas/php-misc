<?php

namespace Frosas\Http\Adapter;

/**
 * The adapter that makes the request 
 * 
 * TODO Connect asynchronously
 */
class Writer extends \Zend\Http\Client\Adapter\Socket {

    private $connections;
    
    function __construct(\Frosas\Http\Connections $connections) {
        parent::__construct();
        $this->connections = $connections;
    }
    
    function write($method, $url, $httpVersion = '1.1', $headers = array(), $body = '') {
        $rawRequest = parent::write($method, $url, $httpVersion, $headers, $body);
        stream_set_blocking($this->socket, 0);
        $this->connections->current = $this->connections->current + array(
            'socket' => $this->socket,
            'rawRequest' => $rawRequest);
        $this->connections->addCurrent();
        return $rawRequest;
    }
    
    function read() {
        // Zend Client requires an actual response but we can't give it to him yet!
        // TODO Avoid getting this fake response when using Frosas\Client (throw 
        // an exception or something)
        return "HTTP/1.1 200 OK\n\nThis is a fake response!";
    }
    
    function close() {
        // We don't want to close the connection yet
    }
}