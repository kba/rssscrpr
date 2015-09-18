<?php

require_once 'src/scraper/XpathScraper.php';

class RSSScraper extends XpathScraper
{

    function __construct()
    {
        $this->xpathItem = '//item';
        $this->xpathTitle = 'title';
        $this->xpathLink = 'link';
        $this->xpathDate = 'pubdate';
        $this->xpathAuthor = 'author';
        $this->xpathDescription = 'description';
    }

}
