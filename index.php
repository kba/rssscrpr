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

// TODO correct date handling (use DateTime)
date_default_timezone_set('UTC');

//default values
$feed = array(
    'maxResult' => 15,
    'dummyEmail' => 'nobody@nowhere.none',
    'titleExcludeWords' => array(),
    'url' => 'http://www.ub.uni-dortmund.de/listen/inetbib/date1.html',
    'title' => 'Inetbib Neueste (relevante) Einträge',
    'description' => '',
    'parseStrategy' => 'mhonarc_list_date',
);

// make all feed attributes overrideable via GET params
foreach ($feed as $k => $v) {
    if (array_key_exists($k, $_GET)) {
        $feed[$k] = $_GET[$k];
    }
}

if (array_key_exists('noanswers', $_GET)) {
    $feed['titleExcludeWords'][] = 'Re: ';
}
if (array_key_exists('nojobs', $_GET)) {
    $feed['titleExcludeWords'][] = 'Stellenanzeige';
    $feed['titleExcludeWords'][] = 'Stellenangebot';
    $feed['titleExcludeWords'][] = 'Stellenausschreibung';
}

//create message from these filters
$debugRequestParams = [];
foreach($_GET as $key => $value) {
    $debugRequestParams[] = ($value !== '') 
        ? $key . '=' . $value
        : $debugRequestParams[] = $key;
}
$debugRequestParamsString = implode(', ', $debugRequestParams);

$html= file_get_contents($feed['url']);
$dom = new DOMDocument();
@$dom->loadHTML( $html );
$xpath = new DOMXPath($dom);
$feed['urlParsed'] = parse_url($feed['url']);

function ensureAbsoluteUrl($url) {
    global $feed;
    if (substr($url, 0, 4) == 'http') {
        return $url;
    } else {
        return implode('', array(
            $feed['urlParsed']['scheme'], '://',
            $feed['urlParsed']['host'],
            dirname($feed['urlParsed']['path']), '/',
            $url));
    }
}

$itemList = array();
if ($feed['parseStrategy'] === 'mhonarc_list_date') {
    $elements = $xpath->query("//ul/ul/li");// war //ul//ul//li

    foreach ($elements as $e) {
        $item = array();
        $linkToMsg = $xpath->query(".//a", $e)->item(0);
        $item['title'] = $linkToMsg->nodeValue;
        // author is right of title
        $item['author'] = ltrim(explode($item['title'], $e->textContent)[1], ", ");
        $item['email'] = $feed['dummyEmail'];
        $item['url'] = ensureAbsoluteUrl($linkToMsg->getAttribute('href'));
        $item['date'] = date(DATE_RFC822, strtotime($e->parentNode->previousSibling->previousSibling->nodeValue));
        $item['description'] = '';
        $itemList[] = $item;
    }
}

header ("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<rss version=\"2.0\">\n";
echo "<channel>\n";
echo "<title>{$feed['title']} ($debugRequestParamsString)</title>\n";
echo "<description>{$feed['description']}</description>\n";
echo "<link>{$feed['url']}</link>\n";

$count = 0;
foreach ($itemList as $item) {
    // filter all items with any of the $titleExcludeWords in the title
    foreach ($feed['titleExcludeWords'] as $titleFilter) {
        if (strpos($item['title'], $titleFilter) !== false) {
            continue 2;
        }
    }
    if ($count++ >= $feed['maxResult']) {
        break;
    }
    echo "<item>\n";
    echo "  <title>{$item['title']}</title>\n";
    echo "  <link>{$item['url']}</link>\n";
    echo "  <guid isPermaLink='false'>{$item['url']}</guid>\n";
    echo "  <author>{$item['email']} ({$item['author']})</author>\n";
    echo "  <pubDate>{$item['date']}</pubDate>\n";
    echo "  <description>{$item['description']}</description>\n";
    echo "</item>\n";
}

echo "</channel>\n";
echo "</rss>\n";

?>
