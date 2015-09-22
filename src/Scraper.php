<?php 

require_once 'src/Utils.php'; require_once 'src/Session.php';

abstract class Scraper 
{
    abstract function scrapeItems($session);
    abstract function scrapeTitle($session, $itemEl);
    abstract function scrapeAuthor($session, $itemEl);
    abstract function scrapeLink($session, $itemEl);
    abstract function scrapeDate($session, $itemEl);
    abstract function scrapeDescription($session, $itemEl);

    public function scrape(Session $session)
    {
        error_log($session->dom->save('/tmp/fb.html'));
        $items = $this->scrapeItems($session);
        error_log("Scraped " . count($items) . " items");
        if ($items === false)
        {
            throw Utils::throw400("Couldn't determine what the items are...");
        }
        foreach ($items as $e)
        {
            $item = $session->feed->addItem();
            $item->title = $this->scrapeTitle($session, $e);
            $item->author = $this->scrapeAuthor($session, $e);
            $item->url = $session->ensureAbsoluteUrl($this->scrapeLink($session, $e), $session->url);
            $item->date = $this->scrapeDate($session, $e);
            $item->description = $this->scrapeDescription($session, $e);
        }
    }
}

?>
