<?php 

require_once 'src/model/Session.php';

abstract class Scraper 
{
    abstract function scrape(Session $session);

    function ensureAbsoluteUrl($url, $compareUrl)
    {
        if (substr($url, 0, 4) == 'http') {
            return $url;
        } else {
            $urlParsed = parse_url($compareUrl);
            $result = $urlParsed['scheme'] .  '://' . $urlParsed['host'];
            // TODO chomp trailing slashes and then implode for clarity
            if ( strlen(dirname($urlParsed['path'])) > 1 && // i.e. not just /
                substr($url, 0, 1) !== "/" ) {// 
                if (substr($urlParsed['path'],-1) == "/") {
                    $result .= $urlParsed['path'];
                } else {
                    $result .= dirname($urlParsed['path']) . '/';
                }
            }
            return $result . $url;
        }
    }
}

?>
