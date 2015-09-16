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

require_once 'src/RetrieverFactory.php';

$_GET['url'] = 'http://www.ub.uni-dortmund.de/listen/inetbib/date1.html#56142';
// $_GET['url'] = 'https://twitter.com/UBMannheim';
// $_GET['scraper'] = 'MHonArcScraper';

$retriever = RetrieverFactory::createFromQueryParams($_GET);
$feed = $retriever->go();

header('Content-Type: application/rss+xml');
echo $feed->asRSS()->saveXML();
?>
