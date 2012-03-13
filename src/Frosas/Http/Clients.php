<?php

namespace Frosas\Http;

use Zend\Http\Client;

class Clients {

    private $connectionsQueued = array();
    private $connectionsActive = array();
    private $connectionsDone = array();
    private $maxActiveConnections = 10;

    function __construct($clients = null) {
        if ($clients) {
            if (! is_array($clients)) $clients = array($clients);
            foreach ($clients as $client) $this->add($client);
        }
    }

    function add($client) {
        if (! $client instanceof Client) $client = new Client($client);
        $this->connectionsQueued[] = array(
            'client' => $client,
            'redirects' => 0);
        $this->fillActiveConnectionsQueue();
        return $client;
    }

    function next() {
        while ($this->connectionsActive && ! $this->connectionsDone) {
            $sockets = $this->sockets();
            $null = null;
            // TODO Handle timeouts
            stream_select($sockets, $null, $null, 9999);
            foreach ($sockets as $socket) {
                $connection = $this->connectionsActive[(int) $socket];
                $client = $connection['client'];
                $data = stream_get_contents($socket);
                if ($data === false) throw new \Exception;
                $client->getAdapter()->appendToResponse($data);
                if ($data === '') { // End of response
                    unset($this->connectionsActive[(int) $socket]);
                    $this->connectionsDone[] = $connection;

                    $response = $connection['client']->send();

                    if ($response->isRedirect()) {
                        if ($connection['redirects'] < $connection['config']['maxredirects']) {
                            $connection['redirects']++;
                            $this->connectionsQueued[] = $connection;
                        }
                    }

                    $this->fillActiveConnectionsQueue(); // Add another one
                }
            }
        }
        
        if ($connection = array_shift($this->connectionsDone)) {
            return $connection['client'];
        }
    }

    function setMaxActiveConnections($max) {
        $this->maxActiveConnections = $max;
    }
    
    private function sockets() {
        return array_map(function($client) {
            return $client['socket'];
        }, $this->connectionsActive);        
    }

    private function fillActiveConnectionsQueue() {
        while ($this->connectionsQueued && count($this->connectionsActive) < $this->maxActiveConnections) {
            $connection = array_shift($this->connectionsQueued);

            // Connect + send request
            $client = $connection['client'];
            $client->setAdapter(new Adapter\Writer);

            if (! array_key_exists('config', $connection)) {
                // This should be in add() but until now we can't be sure the client has an 
                // adapter (needed as we can't get the config straight from the client)
                $connection['config'] = $client->getAdapter()->getConfig();
            }

            $client->send();

            // Prepare client for receiving response
            $socket = $client->getAdapter()->socket();
            $connection['socket'] = $socket;
            stream_set_blocking($socket, 0);
            $rawRequest = $client->getAdapter()->rawRequest();
            $client->setAdapter(new Adapter\Reader($rawRequest));

            $this->connectionsActive[(int) $socket] = $connection;
        }
    }
}