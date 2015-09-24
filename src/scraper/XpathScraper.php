<?php

require_once 'src/Scraper.php';
require_once 'src/Utils.php';

class XpathScraper extends Scraper 
{

    var $xpathItem;
    var $xpathTitle;
    var $xpathLink;
    var $xpathAuthor;
    var $xpathDescription;
    var $xpathDate;

    protected function getByXpath($session, $itemEl, $field)
    {
        // error_log("Applying $field: '{$this->$field}'");
        $xpath = $this->$field;
        if (! $xpath)
            return "NONE";
        $nodeList = $session->xpath->query($this->$field, $itemEl);
        if ($nodeList->length > 0)
        {
            // error_log("TEXT of {$field}: " . $nodeList->item(0)->textContent);
            return $nodeList->item(0)->textContent;
        }
        // return 'ER<font color=RED>RO</font>RR';
    }

    function scrapeAuthor($session, $e)
    {
        $s = $this->getByXpath($session, $e, 'xpathAuthor');
        if (!$s)
        {
            $s = 'Anonymous';
        }
        return $s;
    }

    function scrapeDate($session, $e)
    {
        $s = $this->getByXpath($session, $e, 'xpathDate');
        // error_log("Scraped date: '$s'");
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

    function scrapeDescription($session, $e)
    {
        $xpathToUse = $this->xpathDescription;
        if (!$this->xpathDescription)
        {
            $xpathToUse = $this->xpathTitle;
        }
        $node = $session->xpath->query($xpathToUse, $e)->item(0);
        if ($node)
        {
            $ret = "";
            if (! $node->childNodes)
            {
                $ret = $node->textContent;
            }
            else
            {
                foreach ($node->childNodes as $childNode)
                {
                    $ret .= $node->ownerDocument->saveXML($childNode);
                }
            }
            return $ret;
        }
        Utils::throw400("Could not scrape description, check your xpath");
    }

    function scrapeItems($session)
    {
        $items =  $session->xpath->query($this->xpathItem);
        return $items;
    }
}

?>
