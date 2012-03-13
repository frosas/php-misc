<?php

namespace Frosas\Http;

use Zend\Http\Client;

class ClientsTest extends \PHPUnit_Framework_TestCase {

    const HTTP_200_URL = 'http://www.iana.org/domains/example/';
    const HTTP_302_URL = 'http://example.com';
    const HTTP_404_URL = 'http://www.iana.org/domains/example/404';
    const UNKNOWN_DOMAIN_URL = 'http://doesntexist.example.com';

    function testResponse() {
        $clients = new Clients(self::HTTP_200_URL);
        $response = $clients->next()->getResponse();
        $this->assertInstanceOf('Zend\Http\Response', $response);
        $this->assertIsHtml($response->getBody());
    }
    
    function testRequestsAndResponsesCountsMatches() {
        $urls = array(self::HTTP_200_URL, self::HTTP_200_URL);
        $clients = new Clients($urls);
        for ($responses = 0; $clients->next(); $responses++);
        $this->assertEquals(count($urls), $responses);
    }
    
    function testUnknownDomainConnection() {
        $this->setExpectedException('RuntimeException');
        $clients = new Clients(self::UNKNOWN_DOMAIN_URL);
    }
    
    function testHttp404Connection() {
        $clients = new Clients(self::HTTP_404_URL);
        $response = $clients->next()->getResponse();
        $this->assertTrue($response->isNotFound());
    }

    function testRedirect() {
        $clients = new Clients(self::HTTP_302_URL);
        $response = $clients->next()->getResponse();
        $this->assertTrue($response->isSuccess());
    }
    
    function testMaxRedirects() {
        $client = new Client(self::HTTP_302_URL, array('maxredirects' => 0));
        $clients = new Clients($client);
        $response = $clients->next()->getResponse();
        $this->assertTrue($response->isRedirect());
    }

    // TODO Test timeouts
    
    private function assertIsHtml($string) {
        $this->assertTrue(
            (boolean) preg_match('/<html( |>)/i', $string), 
            "String \"$string\" is not HTML");
    }
}