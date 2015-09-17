<?php

//Feed-Validator
//http://validator.w3.org/feed/

/*
 * TODO Facebook
 * TODO Rechenzentrum
 * TODO Rektoratsnachrichten
 * TODO: BITOnline Pipe (low prio)
 http://www.b-i-t-online.de/bitrss.xml
 mit "b.i.t.online - Ausgabe" anfangen herausfiltern
 auf Webseite verlinken

 */

 /*
 Examples calls:

 index.php?url=http://www.ub.uni-dortmund.de/listen/inetbib/date1.html&noanswers=true&nojobs

 index.php?url=http://www.handle.net/mail-archive/handle-info/

 index.php?url=https://twitter.com/UBMannheim&nofb&noretweet
 index.php?url=https://twitter.com/hashtag/zotero
  */

// TODO correct date handling (use DateTime)
date_default_timezone_set('UTC');

require_once 'src/Utils.php';
require_once 'src/RetrieverFactory.php';

function echoRSS($feed)
{
    $dom = $feed->asRSS();
    $xml = $dom->saveXML();
    header('Content-Type: application/rss+xml');
    echo $xml;
}

if (!isset($_GET['action']))
{
    Utils::throw400("Must set 'action'!");
}

if ($_GET['action'] === 'scrape-html')
{
    $retriever = RetrieverFactory::createHtmlScraperFromQueryParams($_GET);
    $feed = $retriever->go();
    echoRSS($feed);
}
else 
{
    Utils::throw400("Undefine action '{$_GET['action']}'!");
}
?>
