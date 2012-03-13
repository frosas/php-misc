<?php

namespace Frosas\Http;

/**
 * TODO Don't be a Zend Client but composite it instead?
 */
class Client extends \Zend\Http\Client {

    private $connections;
    private $readerClient;
    
    function __construct($uri = null, $config = null) {
        parent::__construct($uri, $config);
        
        $this->connections = new Connections;
        
        // This client acts as the writer client
        $this->setAdapter(new Adapter\Writer($this->connections));
        
        $this->readerClient = new \Zend\Http\Client;
        $this->readerClient->setAdapter(new Adapter\Reader($this->connections));
    }
    
    /**
     * @return \Zend\Http\Response|Exception|null The response, an exception or null if there are 
     * no more pending responses
     */
    function updateToNextResponse() {
        if (count($this->connections)) {
            // TODO Handle connection exceptions (e.g. "Zend\Http\Exception\RuntimeException: 
            // Unable to read response, or response is empty")
            $this->readerClient->send();
            $this->setRequest($this->readerClient->getRequest());
            $this->setResponse($this->readerClient->getResponse());
            // TODO Set raw request and response
            return $this->getResponse();
        }
    }
}