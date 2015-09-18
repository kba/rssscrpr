<?php

require_once 'src/Scraper.php';

class XpathScraper extends Scraper 
{

    var $xpathItem;
    var $xpathTitle;
    var $xpathLink;
    var $xpathAuthor;
    var $xpathDate;

    protected function getByXpath($session, $itemEl, $field)
    {
        // error_log("Applying $field: {$this->$field}");
        $nodeList = $session->xpath->query($this->$field, $itemEl);
        if ($nodeList->length > 0)
        {
            return $nodeList->item(0)->textContent;
        }
        // return 'ER<font color=RED>RO</font>RR';
    }

    function scrapeAuthor($session, $e)
    {
        $s = Utils::trimHard($this->getByXpath($session, $e, 'xpathAuthor'));
        if (!$s)
        {
            $s = 'Anonymous';
        }
        return $s;
    }

    function scrapeDate($session, $e)
    {
        $s = Utils::trimHard($this->getByXpath($session, $e, 'xpathDate'));
        if (! is_numeric($s))
        {
            $s = strtotime($s);
        }
        return $s;
    }

    function scrapeTitle($session, $e)
    {
        return $this->getByXpath($session, $e, 'xpathTitle');
    }

    function scrapeLink($session, $e)
    {
        return $this->getByXpath($session, $e, 'xpathLink');
    }

    function scrapeItems($session)
    {
        return $session->xpath->query($this->xpathItem);
    }
}

?>
