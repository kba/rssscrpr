<?php

require_once 'src/Scraper.php';

class XpathScraper extends Scraper 
{

    var $xpathItem = "";
    var $xpathTitle = "";
    var $xpathLink = "";
    var $xpathAuthor = "";
    var $xpathDate = "";

    private function getByXpath($session, $itemEl, $field)
    {
        // error_log("Applying $field: {$this->$field}");
        $nodeList = $session->xpath->query($this->$field, $itemEl);
        if ($nodeList->length > 0)
        {
            return $nodeList->item(0)->nodeValue;
        }
        else
        {
            return 'ERROR<font color=RED>ERROR</font>';
        }
    }

    function scrape(Session $session)
    {
        $elements = $session->xpath->query($this->xpathItem);

        foreach ($elements as $e)
        {
            $item = $session->feed->addItem();

            $item->title = $this->getByXpath($session, $e, 'xpathTitle');
            $item->author = Utils::trimHard($this->getByXpath($session, $e, 'xpathAuthor'));
            $item->url = Utils::ensureAbsoluteUrl($this->getByXpath($session, $e, 'xpathLink'), $session->url);
            $date = Utils::trimHard($this->getByXpath($session, $e, 'xpathDate'));

            // TODO
            if (! preg_match('/^\d+$/', $date))
            {
                $date = strtotime($date);
            }
            $item->date = date(DATE_RFC822, $date);
        }
    }
}

?>
