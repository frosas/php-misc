<?php

namespace Frosas\Http;

class ClientTest extends \PHPUnit_Framework_TestCase {

    const HTTP_200_URL = 'http://www.iana.org/domains/example/';
    const HTTP_404_URL = 'http://www.iana.org/domains/example/404';
    const UNKNOWN_DOMAIN_URL = 'http://doesntexist.example.com';

    function testSendFakeResponse() {
        $client = new Client(self::HTTP_200_URL);
        $this->assertInstanceOf('Zend\Http\Response', $client->send());
    }
    
    function testRealResponse() {
        $client = new Client(self::HTTP_200_URL);
        $client->send();
        $client->updateToNextResponse();
        $this->assertInstanceOf('Zend\Http\Response', $client->getResponse());
        $this->assertIsHtml($client->getResponse()->getBody());
    }
    
    function testRequestsAndResponsesCountsMatches() {
        $client = new Client;
        $urls = array(self::HTTP_200_URL, self::HTTP_200_URL);
        foreach ($urls as $url) $client->setUri($url)->send();
        $responses = 0;
        while ($client->updateToNextResponse()) $responses++;
        $this->assertEquals(count($urls), $responses);
    }
    
    function testUnknownDomainConnection() {
        $client = new Client(self::UNKNOWN_DOMAIN_URL);
        $this->setExpectedException('RuntimeException');
        $client->send();
    }
    
    function testHttp404Connection() {
        $client = new Client(self::HTTP_404_URL);
        $client->send();
        $response = $client->updateToNextResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }
    
    private function assertIsHtml($string) {
        $this->assertTrue(
            (boolean) preg_match('/<html( |>)/i', $string), 
            "String \"$string\" is not HTML");
    }
}