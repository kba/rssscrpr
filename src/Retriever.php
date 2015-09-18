<?php

require_once 'src/fetcher/HttpFetcher.php';
require_once 'src/parser/DOMParser.php';
require_once 'src/FeedFilter.php';

class Retriever {

    // Stores the information that is shared between fetcher, parser and scraper
    var $session;

    // Retrieves the data
    var $fetcher;

    // Turns the data into scrapeable data structure in the $session
    var $parser;

    // Scrapes the parsed data structure in the $session
    var $scraper;

    // Filter the feeds as needed
    var $filter;

    // Inject the session + the tools
    public function __construct(Session $session) {
        $this->session = $session;
        $this->fetcher = new HttpFetcher();
        $this->parser =  new DOMParser();
        $this->scraper = new XpathScraper();
        $this->filter = new FeedFilter();
    }

    public function go()
    {
        $this->fetcher->fetch($this->session);
        $this->parser->parse($this->session);
        $this->scraper->scrape($this->session);
        $this->session->feed->sort();
        $this->filter->filter($this->session);
        return $this->session->feed;
    }


}

?>
