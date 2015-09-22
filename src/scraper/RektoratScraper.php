<?php

require_once 'src/scraper/XpathScraper.php';
require_once 'src/Utils.php';

class RektoratScraper extends XpathScraper
{

    public function scrapeAuthor($session, $e)
    {
        return 'Rektorat';
    }
    public function scrapeDate($session, $e)
    {
        $s = parent::scrapeTitle($session, $e);
        if (preg_match('/\svom\s/', $s))
        {
            $s = preg_replace('/[^0-9]*Teil.*$/', '', $s);
            $s = preg_replace('/.*vom\s*/', '', $s);
            $s = preg_replace('/\./', '', $s);
            $s = Utils::monthNameToNumber($s);
            // error_log($s);
            $day = substr($s, 0, 2);
            $month = substr($s, 3, 2);
            $year = substr($s, 6, 4);
            // error_log("$year-$month-$day 00:00:00.00Z");
            $s = strtotime("$year-$month-$day");
            return $s;
        }
        else
        {
            preg_match('/(\d{2})[-\s]+(\d{4})/', $s, $matches);
            $num = $matches[1];
            $year = $matches[2];
            return strtotime("$year-01-01 00:$num:00.00");
        }
    }

    public function __construct()
    {
        $this->xpathItem = "//table/tr";
        $this->xpathTitle = "./td/text()";
        $this->xpathLink = ".//a/@href";
    }
}


?>
