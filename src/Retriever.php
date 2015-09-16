<?php

require_once 'src/fetcher/HttpFetcher.php';
require_once 'src/parser/HtmlParser.php';
require_once 'src/filter/NopFilter.php';
require_once 'src/filter/SimpleFilter.php';

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
    public function __construct(Session $session, $fetcher, $parser, $scraper, $filter) {
        $this->session = $session;
        $this->fetcher = $fetcher ?: new HttpFetcher();
        $this->parser = $parser ?: new HtmlParser();
        $this->scraper = $scraper ?: new MHonArcScraper();
        $this->filter = $filter ?: new SimpleFilter();
    }

    public function go()
    {
        $this->fetcher->fetch($this->session);
        $this->parser->parse($this->session);
        $this->scraper->scrape($this->session);
        $this->filter->filter($this->session);
        return $this->session->feed;
    }


}

?>
