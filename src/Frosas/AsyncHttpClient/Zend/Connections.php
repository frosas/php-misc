<?php

namespace Frosas\AsyncHttpClient\Zend;

class Connections implements \Countable
{
    private $connections = array();
    
    /**
     * Buffer of already responded connections
     */
    private $respondedConnections = array();
    
    function add($socket, $request)
    {
        stream_set_blocking($socket, 0);
        $this->connections[(int) $socket] = array(
            'socket' => $socket,
            'request' => $request,
            'response' => '');
    }
    
    function getNextResponded()
    {
        while ($this->connections && ! $this->respondedConnections) {
            $sockets = $this->getSockets();
            $null = null;
            // TODO Handle timeouts
            stream_select($sockets, $null, $null, 9999);
            foreach ($sockets as $socket) {
                $connection =& $this->connections[(int) $socket];
                $data = stream_get_contents($socket);
                // TODO $data === false is an error
                $connection['response'] .= $data;
                if ($data === '') { // End of response
                    unset($this->connections[(int) $socket]);
                    $this->respondedConnections[] = $connection;
                }
            }
        }
        
        return array_shift($this->respondedConnections);
    }

    function count()
    {
        return count($this->connections);
    }
    
    private function getSockets()
    {
        return array_map(function($connection) {
            return $connection['socket'];
        }, $this->connections);        
    }
}