<?php

//Feed-Validator
//http://validator.w3.org/feed/

/* TODO: BITOnline Pipe (low prio)
 * TODO Twitter
 * TODO Facebook
 * TODO Rechenzentrum
 http://www.b-i-t-online.de/bitrss.xml
 mit "b.i.t.online - Ausgabe" anfangen herausfiltern
 auf Webseite verlinken
 */

//default values
$maxResult = 15;
$titleExcludeWords = array();
$feedUrl = 'http://www.ub.uni-dortmund.de/listen/inetbib/date1.html';
$feedTitle = 'Inetbib Neueste (relevante) Einträge';
$feedDescription = '';
$parseStrategy = 'mhonarc_list_date';

//overwrite these when parameters are specified
if (array_key_exists('max', $_GET) && is_numeric($_GET['max'])) {
    $maxResult = $_GET['max'];
}
if (array_key_exists('url', $_GET)) {
    $feedUrl = $_GET['url'];
}
if (array_key_exists('noanswers', $_GET)) {
    $titleExcludeWords[] = 'Re: ';
}
if (array_key_exists('nojobs', $_GET)) {
    $titleExcludeWords[] = 'Stellenanzeige';
    $titleExcludeWords[] = 'Stellenangebot';
    $titleExcludeWords[] = 'Stellenausschreibung';
}

//create message from these filters
$debugRequestParams = [];
foreach($_GET as $key => $value) {
    $debugRequestParams[] = ($value !== '') 
        ? $key . '=' . $value
        : $debugRequestParams[] = $key;
}
$debugRequestParamsString = implode(', ', $debugRequestParams);

$html= file_get_contents($feedUrl);
$dom = new DOMDocument();
@$dom->loadHTML( $html );

$xpath = new DOMXPath($dom);

function ensureAbsoluteUrl($url, $feedUrl) {
    if (substr($url, 0, 4) == 'http') {
        return $url;
    } else {
        $feedUrlInfo = parse_url($feedUrl);
        return implode('', array(
            $feedUrlInfo['scheme'], '://',
            $feedUrlInfo['host'], '/',
            dirname($feedUrlInfo['path']), '/',
            $url));
    }
}

$itemList = array();
if ($parseStrategy === 'mhonarc_list_date') {
    $elements = $xpath->query("//ul/ul/li");// war //ul//ul//li

    foreach ($elements as $e) {
        $item = array();
        $linkToMsg = $xpath->query(".//a", $e)->item(0);
        $item['title'] = $linkToMsg->nodeValue;
        // author is right of title
        $item['author'] = ltrim(explode($item['title'], $e->textContent)[1], ", ");
        $item['url'] = ensureAbsoluteUrl($linkToMsg->getAttribute('href'), $feedUrl);
        $item['date'] = date(DATE_RFC822, strtotime($e->parentNode->previousSibling->previousSibling->nodeValue));
        $item['description'] = '';
        $itemList[] = $item;
    }
}

header ("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<rss version=\"2.0\">\n";
echo "<channel>\n";
echo "<title>$feedTitle ($debugRequestParamsString)</title>\n";
echo "<description>$feedDescription</description>\n";
echo "<link>$feedUrl</link>\n";

$count = 0;
foreach ($itemList as $item) {
    // filter all items with any of the $titleExcludeWords in the title
    foreach ($titleExcludeWords as $titleFilter) {
        if (strpos($item['title'], $titleFilter) !== false) {
            continue 2;
        }
    }
    if ($count++ >= $maxResult) {
        break;
    }
    echo "<item>\n";
    echo "  <title>{$item['title']}</title>\n";
    echo "  <link>{$item['url']}</link>\n";
    echo "  <guid isPermaLink='false'>{$item['url']}</guid>\n";
    echo "  <author>nobody@nodomain.de ({$item['author']})</author>\n";
    echo "  <pubDate>{$item['date']}</pubDate>\n";
    echo "  <description>{$item['description']}</description>\n";
    echo "</item>\n";
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
