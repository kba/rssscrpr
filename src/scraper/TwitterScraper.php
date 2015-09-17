<?php

require_once 'src/scraper/XpathScraper.php';

class TwitterScraper extends XpathScraper
{
    public function __construct()
    {
        $this->xpathItem = "//*[contains(@class, 'original-tweet')]";
        $this->xpathTitle = ".//p[contains(@class, 'TweetTextSize')]/text()";
        $this->xpathLink = ".//a[contains(@class, 'tweet-timestamp')]/@href";
        $this->xpathAuthor = ".//strong[contains(@class, 'fullname')]/text()";
        $this->xpathDate = ".//span[contains(@class, '_timestamp')]/@data-time";
        $this->xpathTest = ".//span[contains(@class, 'js-retweet-text')]/text()";
    }
}


?>
