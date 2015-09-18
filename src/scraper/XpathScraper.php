<?php

require_once 'src/Scraper.php';

class XpathScraper extends Scraper 
{

    var $xpathItem = "";
    var $xpathTitle = "";
    var $xpathLink = "";
    var $xpathAuthor = "";
    var $xpathDate = "";

    protected function getByXpath($session, $itemEl, $field)
    {
        // error_log("Applying $field: {$this->$field}");
        $nodeList = $session->xpath->query($this->$field, $itemEl);
        if ($nodeList->length > 0)
        {
			return $nodeList->item(0)->textContent;
        }
        else
        {
            return 'ERROR<font color=RED>ERROR</font>';
        }
    }

    protected function scrapeAuthor($session, $e)
    {
        return Utils::trimHard($this->getByXpath($session, $e, 'xpathAuthor'));
    }

    protected function scrapeDate($session, $e)
    {
        return Utils::trimHard($this->getByXpath($session, $e, 'xpathDate'));
    }

    protected function scrapeTitle($session, $e)
    {
        return $this->getByXpath($session, $e, 'xpathTitle');
    }

    protected function scrapeLink($session, $e)
    {
        return $this->getByXpath($session, $e, 'xpathLink');
    }

    public function scrape(Session $session)
    {
        $elements = $session->xpath->query($this->xpathItem);

        foreach ($elements as $e)
        {
            $item = $session->feed->addItem();
            $item->title = $this->scrapeTitle($session, $e);
            $item->author = $this->scrapeAuthor($session, $e);
            $item->url = Utils::ensureAbsoluteUrl($this->scrapeLink($session, $e), $session->url);
            $item->date = date(DATE_RFC822, $this->scrapeDate($session, $e));
        }
    }
}

?>
