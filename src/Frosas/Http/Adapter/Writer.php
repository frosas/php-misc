<?php

namespace Frosas\Http\Adapter;

/**
 * The adapter that makes the request 
 * 
 * TODO Connect asynchronously
 */
class Writer extends \Zend\Http\Client\Adapter\Socket {

    function write($method, $uri, $http_ver = '1.1', $headers = array(), $body = '') {
        $this->rawRequest = parent::write($method, $uri, $http_ver, $headers, $body);
        return $this->rawRequest;
    }

    function read() {
        // Zend Client requires an actual response but we can't give it to him yet!
        // TODO How to avoid the user to get this fake response?
        return "HTTP/1.1 200 OK\n\nThis is a fake response!";
    }
    
    function close() {
        // TODO When to close the connection?
    }

    function socket() {
        return $this->socket;
    }

    function rawRequest() {
        return $this->rawRequest;
    }
}