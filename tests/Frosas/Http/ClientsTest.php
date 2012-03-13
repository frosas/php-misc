<?php

namespace Frosas\Http;

class ClientsTest extends \PHPUnit_Framework_TestCase {

    const HTTP_200_URL = 'http://www.iana.org/domains/example/';
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
        $responses = 0;
        while ($clients->next()) $responses++;
        $this->assertEquals(count($urls), $responses);
    }
    
    function testUnknownDomainConnection() {
        $this->setExpectedException('RuntimeException');
        $clients = new Clients(self::UNKNOWN_DOMAIN_URL);
    }
    
    function testHttp404Connection() {
        $clients = new Clients(self::HTTP_404_URL);
        $response = $clients->next()->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    private function assertIsHtml($string) {
        $this->assertTrue(
            (boolean) preg_match('/<html( |>)/i', $string), 
            "String \"$string\" is not HTML");
    }
}