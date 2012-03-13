<?php

namespace Frosas\Http;

class Connections implements \Countable {

    // TODO Make it private
    public $current;

    private $connections = array();
    private $lastResponded;

    /**
     * Buffer of already responded connections
     */
    private $respondedConnections = array();

    function addCurrent() {
        $id = (int) $this->current['socket'];
        $this->connections[$id] = $this->current + array('rawResponse' => '');
        $this->current = null;
    }
    
    function waitForNextResponse() {
        while ($this->connections && ! $this->respondedConnections) {
            $sockets = $this->getSockets();
            $null = null;
            // TODO Handle timeouts
            stream_select($sockets, $null, $null, 9999);
            foreach ($sockets as $socket) {
                $connection =& $this->connections[(int) $socket];
                $data = stream_get_contents($socket);
                // TODO $data === false is an error
                $connection['rawResponse'] .= $data;
                if ($data === '') { // End of response
                    unset($this->connections[(int) $socket]);
                    $this->respondedConnections[] = $connection;
                }
            }
        }
        
        $this->lastResponded = array_shift($this->respondedConnections);
    }

    function count() {
        return count($this->connections);
    }

    function getLastResponded() {
        return $this->lastResponded;
    }
    
    private function getSockets() {
        return array_map(function($connection) {
            return $connection['socket'];
        }, $this->connections);        
    }
}