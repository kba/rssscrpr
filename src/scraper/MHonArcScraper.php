<?php

require_once 'src/Scraper.php';

class MHonArcScraper extends Scraper 
{

    function scrape(Session $session)
    {
        $elements = $session->xpath->query("//ul/ul/li");// war //ul//ul//li

        foreach ($elements as $e)
        {
            $item = $session->feed->addItem();

            $linkToMsg = $session->xpath->query(".//a", $e)->item(0);
            $item->title = $linkToMsg->nodeValue;
            // author is right of title
            $item->author = ltrim(explode($item->title, $e->textContent)[1], ", ");
            $item->url = $this->ensureAbsoluteUrl($linkToMsg->getAttribute('href'), $session->url);
            $item->date = date(DATE_RFC822, strtotime($e->parentNode->previousSibling->previousSibling->nodeValue));
            $item->description = '';
        }
    }
}

?>
