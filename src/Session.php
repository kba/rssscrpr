<?php

require_once 'src/model/Feed.php';

class Session
{

    var $config;

    // Maximum number of results to retrieve
    var $maxResult = 15;

    // filter items by string of fields
    var $exclude = array(
        'title' => array(),
        'test' => array()
    );

    // The url of the feed
    var $url;

    // The raw data to parse in bytes
    var $bytes;

    // The parsed url
    var $url_parts;

    // DOMDocument
    var $dom;

    // DOMXPath
    var $xpath;

    function __construct($url)
    {
        if (!isset($url)) 
        {
            throw new Error("BAD");
        }

        $this->config = json_decode(file_get_contents('config.json'));
        $this->url = $url;
        $this->url_parts = parse_url($url);
        $this->feed = new Feed();
    }

    function ensureAbsoluteUrl($url)
    {
        if (substr($url, 0, 4) == 'http') {
            return $url;
        } else {
            $result = $this->url_parts['scheme'] .  '://' . $this->url_parts['host'];
            // TODO chomp trailing slashes and then implode for clarity
            if ( strlen(dirname($this->url_parts['path'])) > 1 && // i.e. not just /
                substr($url, 0, 1) !== "/" ) {// 
                if (substr($this->url_parts['path'],-1) == "/") {
                    $result .= $this->url_parts['path'];
                } else {
                    $result .= dirname($this->url_parts['path']) . '/';
                }
            }
            return $result . $url;
        }
    }

}

?>
