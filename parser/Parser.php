<?php

require_once 'model/Feed.php';

abstract class Parser {
    var $feed;
    var $url_parts;

    abstract function getData();
    abstract function parse();

    public function __construct(Feed $feed) {
        $this->feed = $feed;
        $this->url_parts = parse_url($feed->url);
    }
}

?>
