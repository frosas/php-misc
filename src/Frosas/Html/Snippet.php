<?php

namespace Frosas\Html;

use Frosas\Collection;

class Snippet
{
    private $document;
    private $xPath;

    static function create($content)
    {
        return new static($content);
    }

    function __construct($content)
    {
        $this->document = new \DOMDocument;
        // TODO Use libxml_use_internal_errors instead of @
        // TODO How allow snippets with <html> and <body> tags?
        @$this->document->loadHTML('<html><body>' . $content . '</body></html>');
        $this->xPath = new \DOMXPath($this->document);
    }

    function __toString()
    {
        return implode("", Collection::map($this->getRoot()->childNodes, function($child) {
            return $child->ownerDocument->saveHTML($child);
        }));
    }

    function getPlain()
    {
        $snippet = clone $this;

        foreach ($snippet->query('//text()') as $node) {
            $node->nodeValue = preg_replace('/\s+/', ' ', $node->nodeValue);
        }

        foreach ($snippet->query('//abbr') as $node) {
            $new = $this->document->createTextNode($node->getAttribute('title'));
            $node->parentNode->replaceChild($new, $node);
        }

        return trim($snippet->document->textContent);
    }

    private function query($expression)
    {
        return $this->xPath->query($expression, $this->getRoot());
    }

    private function getRoot()
    {
        return $this->xPath->query('/html/body')->item(0);
    }
}
