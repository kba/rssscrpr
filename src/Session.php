<?php

require_once 'src/model/Feed.php';
require_once 'src/phpuri.php';

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
        // error_log(json_encode($this->url_parts));
        if (substr($url, 0, 4) == 'http') {
            return $url;
        } else {
            return phpUri::parse($this->url)->join($url);
        }
    }

}

?>
