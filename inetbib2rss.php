<?php

//Feed-Validator
//http://validator.w3.org/feed/

/* TODO: BITOnline Pipe
   http://www.b-i-t-online.de/bitrss.xml
   mit "b.i.t.online - Ausgabe" anfangen herausfiltern
   auf Webseite verlinken
*/

//default values
$maxResult = 15;
$noAnswers = false;
$noJobs = false;

//overwrite these when parameters are specified
if (array_key_exists('max', $_GET) && is_numeric($_GET['max'])) {
  $maxResult = $_GET['max'];
}
if (array_key_exists('noanswers', $_GET)) {
  $noAnswers = true;
}
if (array_key_exists('nojobs', $_GET)) {
  $noJobs = true;
}

//create message from these filters
$filterArray = [];
foreach($_GET as $key => $value) {
  if ($value !== '') {
    $filterArray[] = $key . '=' . $value;
  } else {
    $filterArray[] = $key;
  }
}
$filterMessage = implode(', ', $filterArray);

header ("content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<rss version="2.0">' . "\n";
echo '<channel>' . "\n";
echo '<title>Inetbib - neueste Beiträge (' . $filterMessage . ')</title>' . "\n";
echo '<description></description>' . "\n";
echo '<link>http://www.ub.uni-dortmund.de/listen/inetbib/date1.html</link>' . "\n";

$url = 'http://www.ub.uni-dortmund.de/listen/inetbib/date1.html';
$html= file_get_contents($url);
$dom = new DOMDocument();
@$dom->loadHTML( $html );

$xpath = new DOMXPath($dom);
$elements = $xpath->query("//ul/ul/li");// war //ul//ul//li

$count = 0;
foreach ($elements as $e) {
  $title = $e->childNodes->item(0)->getElementsByTagName('a')->item(0)->nodeValue;
  // filters away answer threads, beginning with "Re: "
  if ($noAnswers && substr($title, 0, 4) === "Re: ") {
    continue;
  }
  if ($noJobs && (
    strpos($title, "Stellenausschreibung")>0 ||
    strpos($title, "Stellenangebot")>0 ||
    strpos($title, "Stellenanzeige")>0 )
  ) {
    continue;
  }
  $id = $e->childNodes->item(0)->getElementsByTagName('a')->item(0)->getAttribute("href");
  $url = "http://www.ub.uni-dortmund.de/listen/inetbib/".$id;
  $author = $e->childNodes->item(1)->nodeValue;
  $author = ltrim($author, ", ");
  $date = $e->parentNode->previousSibling->previousSibling->nodeValue;
  $date = date(DATE_RFC822, strtotime($date));

  echo "<item>\n";
  echo "<title>$title</title>\n";
  echo "<link>$url</link>\n";
  echo "<guid isPermaLink='false'>$id</guid>\n";
  echo "<author>nobody@nodomain.de ($author)</author>\n";
  echo "<pubDate>$date</pubDate>\n";
  echo "<description></description>\n";
  echo "</item>\n";
  //echo $title . " / " . $author . "(" . $date . ") --> " . $url . "<br/>\n";

  $count = $count+1;
  if ($count>=$maxResult) {
    break;
  }
}

echo "</channel>\n";
echo "</rss>\n";

/*
nested xpath

$xpath = new DOMXPath($doc);
$res = $xpath->query('//div');
$sub = $xpath->query('.//p', $res->item(1));
*/

?>
