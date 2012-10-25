<?php

namespace Frosas;

class Html
{
    const URLS_REGEXP = 
        '~
            (               # leading text
                <\w+.*?>|   # leading HTML tag, or
                [^=!:\'"/]| # leading punctuation, or
                ^           # beginning of line
            )
            (
                (?:https?://)| # protocol spec, or
                (?:www\.)      # www.*
            )
            (
                [-\w]+                                      # subdomain or domain
                (?:\.[-\w]+)*                               # remaining subdomains or domain
                (?::\d+)?                                   # port
                (?:/(?:(?:[\~\w\+%-]|(?:[,.;:][^\s$]))+)?)* # path
                (?:\?[\w\+%&=.;-]+)?                        # query string
                (?:\#[\w\-]*)?                              # trailing anchor
            )
            ([[:punct:]]|\s|<|$) # trailing text
        ~x';

    /**
     * Based on http://trac.symfony-project.org/browser/branches/1.0/lib/helper/TextHelper.php
     */
    static function linkUrls($html)
    {
        return preg_replace_callback(self::URLS_REGEXP, function($matches) {
            // Is URL already linked?
            if (preg_match('/<a\s/i', $matches[1])) return $matches[0];
            
            $url = ($matches[2] == "www." ? "http://www." : $matches[2]) . $matches[3];
            return 
                $matches[1] . 
                '<a href="' . Html::escape($url) . '">' . Html::escape(Html::humanUrl($url)) . '</a>' . 
                $matches[4];
        }, $html);
    }
    
    static function escape($string)
    {
        return htmlspecialchars($string);
    }
    
    static function humanUrl($url)
    {
        // Remove http:// and https:// schemas
        if (preg_match('#^https?://(.*)#', $url, $matches)) $url = $matches[1];
        
        // Remove trailing /
        if (preg_match('#(.*)/+$#', $url, $matches)) $url = $matches[1];
        
        return $url;
    }
}
