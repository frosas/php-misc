<?php

namespace Frosas\Html;

class SnippetTest extends \PHPUnit_Framework_TestCase
{
    function testParseAndDumpFullHtml()
    {
        $html = '<html><body>full</body></html>';
        $this->assertEquals('full', (string) new Snippet($html));
    }

    function testParseAndDumpPartialHtml()
    {
        $html = '<div><span>partial</span></div>';
        $this->assertEquals($html, (string) new Snippet($html));
    }

    function testGetPlainWithTags()
    {
        $html = "<p>This <span>is a <strong>test</strong></span></p>";
        $plain = 'This is a test';
        $this->assertEquals($plain, Snippet::create($html)->getPlain());
    }

    function testGetPlainWithExtraSpaces()
    {
        $html = " This   is \n a test ";
        $plain = 'This is a test';
        $this->assertEquals($plain, Snippet::create($html)->getPlain());
    }

    function testGetPlainWithAbbr()
    {
        $html = '<abbr title="For your information">FYI</abbr>, this is a test';
        $plain = 'For your information, this is a test';
        $this->assertEquals($plain, Snippet::create($html)->getPlain());
    }
}
