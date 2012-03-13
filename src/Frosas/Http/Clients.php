<?php

namespace Frosas\Http;

class Clients {
    
    private $connectionsPending = array();
    private $connectionsDone = array();

    function __construct($clients = null) {
        foreach ((array) $clients as $client) $this->add($client);
    }

    function add($client) {
        if (! $client instanceof \Zend\Http\Client) {
            $client = new \Zend\Http\Client($client);
        }

        $client->setAdapter(new Adapter\Writer);
        $client->send();

        $socket = $client->getAdapter()->socket();
        stream_set_blocking($socket, 0);

        $rawRequest = $client->getAdapter()->rawRequest();
        $client->setAdapter(new Adapter\Reader($rawRequest));

        $this->connectionsPending[(int) $socket] = array(
            'client' => $client,
            'socket' => $socket
        );

        return $client;
    }

    function next() {
        while ($this->connectionsPending && ! $this->connectionsDone) {
            $sockets = $this->sockets();
            $null = null;
            // TODO Handle timeouts
            stream_select($sockets, $null, $null, 9999);
            foreach ($sockets as $socket) {
                $connection = $this->connectionsPending[(int) $socket];
                $client = $connection['client'];
                $data = stream_get_contents($socket);
                if ($data === false) throw new \Exception;
                $client->getAdapter()->appendToResponse($data);
                if ($data === '') { // End of response
                    unset($this->connectionsPending[(int) $socket]);
                    $this->connectionsDone[] = $connection;
                }
            }
        }
        
        if ($connection = array_shift($this->connectionsDone)) {
            $client = $connection['client'];
            $client->send();
            return $client;
        }
    }
    
    private function sockets() {
        return array_map(function($client) {
            return $client['socket'];
        }, $this->connectionsPending);        
    }
}