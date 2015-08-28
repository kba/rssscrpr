<?php

require_once 'parser/HtmlScraper.php';
require_once 'model/Item.php';

class MHonArcScraper extends HtmlScraper {

    public function __construct($feed) {
        parent::__construct($feed);
        $this->parse();
    }

    function scrape() {
        $elements = $this->xpath->query("//ul/ul/li");// war //ul//ul//li

        foreach ($elements as $e) {
            $item = new Item();
            $linkToMsg = $this->xpath->query(".//a", $e)->item(0);
            $item->title = $linkToMsg->nodeValue;
            // author is right of title
            $item->author = ltrim(explode($item['title'], $e->textContent)[1], ", ");
            $item->url = ensureAbsoluteUrl($linkToMsg->getAttribute('href'));
            $item->date = date(DATE_RFC822, strtotime($e->parentNode->previousSibling->previousSibling->nodeValue));
            $item->description = '';
            $this->feed->addItem($item);
        }
    }
}
