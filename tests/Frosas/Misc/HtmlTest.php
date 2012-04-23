<?php

namespace Frosas\Misc;

class HtmlTest extends \PHPUnit_Framework_TestCase
{    
    function testLinkUrls() 
    {
        $this->assertEquals(
            'x <a href="http://example.com/">example.com</a> x',
            Html::linkUrls('x http://example.com/ x'));
        
        $this->assertEquals(
            'x <a href="https://example.com/">example.com</a> x',
            Html::linkUrls('x https://example.com/ x'));
        
        $this->assertEquals(
            'x <a href="http://www.example.com">www.example.com</a> x',
            Html::linkUrls('x www.example.com x'));
    }
}