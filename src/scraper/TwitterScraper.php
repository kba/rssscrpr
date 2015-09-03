<?php

require_once 'src/Scraper.php';

class TwitterScraper extends Scraper {

    function scrape(Session $session)
    {
        $elements = $session->xpath->query("//*[contains(@class, 'original-tweet')]");

        foreach ($elements as $e)
        {
            $item = $session->feed->addItem();
            $linkToMsg = $session->xpath->query(".//a[contains(@class, 'tweet-timestamp')]", $e)->item(0);
            $item->url = $this->ensureAbsoluteUrl($linkToMsg->getAttribute('href'), $session->url);
            $item->title = $session->xpath->query(".//p[contains(@class, 'TweetTextSize')]", $e)->item(0)->textContent;
            $item->author = $session->xpath->query(".//strong[contains(@class, 'fullname')]", $e)->item(0)->textContent;
            $item->date = $linkToMsg->getAttribute('data-original-title');//TODO why is this not work as expected?
            if ( $session->xpath->query(".//span[contains(@class, 'js-retweet-text')]", $e)->length > 0) {
                $item->test = $session->xpath->query(".//span[contains(@class, 'js-retweet-text')]", $e)->item(0)->textContent;
            }
        }
    }
}


?>
