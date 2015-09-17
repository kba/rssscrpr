<?php 

require_once 'src/Utils.php'; require_once 'src/Session.php';

abstract class Scraper 
{
    abstract function scrape(Session $session);
}

?>
