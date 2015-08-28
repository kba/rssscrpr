<?php

require_once 'parser/Parser.php';
require_once 'model/Feed.php';

abstract class HtmlScraper extends Parser {
    protected $html;
    protected $dom;
    protected $xpath;

    // produces Feed
    abstract function scrape();
 
    public function __construct(Feed $feed) {
        parent::__construct($feed);
        $this->dom = new DOMDocument();
    }

    public function parse() {
        getData();
        $dom->loadHTML($this->html);
        $this->xpath = new DOMXPath($dom);
    }

    public function getData() {
        if (! isset($this->html)) {
            $this->html = file_get_contents($this->feed->url);
        }
        return $this->html;
    }
}

?>
