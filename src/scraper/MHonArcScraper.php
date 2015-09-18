<?php

require_once 'src/scraper/XpathScraper.php';

class MHonArcScraper extends XpathScraper
{

    protected function scrapeDate($xpath, $e)
    {
        $scraped = parent::scrapeDate($xpath, $e);
        return strtotime($scraped);
    }

    public function __construct()
    {
        $this->xpathItem = "//ul/ul/li";
        $this->xpathTitle = ".//a";
        $this->xpathLink = ".//a/@href";
        $this->xpathAuthor = 'text()';
        $this->xpathDate = "ancestor::ul/preceding-sibling::li";
    }
}

?>
