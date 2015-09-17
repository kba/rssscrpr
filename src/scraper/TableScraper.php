<?php

require_once 'src/scraper/XpathScraper.php';

class TableScraper extends XpathScraper
{
    public function __construct()
    {
        $this->xpathItem = "//table/tr";
        $this->xpathTitle = "./td/text()";
        $this->xpathLink = ".//a/@href";
        $this->xpathAuthor = "";
        $this->xpathDate = "";
    }
}


?>
