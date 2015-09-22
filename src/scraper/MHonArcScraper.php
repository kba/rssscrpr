<?php

require_once 'src/scraper/XpathScraper.php';
require_once 'src/Utils.php';

class MHonArcScraper extends XpathScraper
{

    function scrapeDate($session, $e)
    {
        $s = $this->getByXpath($session, $e, 'xpathDate');
        error_log($s);
        $s = Utils::monthNameToNumber($s);
        $tokens = preg_split("/[^\d]+/", $s);
        // $s = parent::scrapeDate($session, $e);
        $s = "{$tokens[2]}-{$tokens[1]}-{$tokens[0]}";
        error_log($s);
        $s = strtotime($s);
        return $s;
    }

    public function __construct()
    {
        $this->xpathItem = "//ul/ul/li";
        $this->xpathTitle = ".//a";
        $this->xpathLink = ".//a/@href";
        $this->xpathAuthor = 'text()';
        $this->xpathDate = "parent::ul/preceding-sibling::li[1]";
    }
}

?>
