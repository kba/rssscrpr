<?php

require_once 'src/model/FeedFilterConfig.php';
require_once 'src/model/Feed.php';

class Session
{

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

    // FeedFilterConfig
    var $filter;

    function __construct($url)
    {
        if (!isset($url)) 
        {
            throw new Error("BAD");
        }

        $this->url = $url;
        $this->url_parts = parse_url($url);
        $this->feed = new Feed();
        $this->filter = new FeedFilterConfig();
    }

}

?>
