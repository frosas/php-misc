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
        
        // The writer client is this class itself
        $this->setAdapter(new Adapter\Writer($this->connections));
        
        // TODO Handle redirections
        $this->readerClient = new \Zend\Http\Client;
        $this->readerClient->setAdapter(new Adapter\Reader($this->connections));
    }

    function send(\Zend\Http\Request $request = null) {
        $request = $request ?: clone $this->getRequest();
        $this->connections->current = array('request' => $request);
        $response = parent::send($request);
        $this->connections->current['response'] = $response;
        return $response;
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
            $connection = $this->connections->getLastResponded();
            $this->setRequest($connection['request']);
            $this->setResponse($this->readerClient->getResponse());
            // TODO Set raw request and response
            return $this->getResponse();
        }
    }
}