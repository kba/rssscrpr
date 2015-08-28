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
 
//default values
$feed = array(
    'maxResult' => 15,
    'dummyEmail' => 'nobody@nowhere.none',
    // TODO make sure this is $_GET overrideable
    'exclude' => array(
        'title' => array(),
        'test' => array()
    ),
    'url' => 'http://www.ub.uni-dortmund.de/listen/inetbib/date1.html',
    // TODO create site-specific templates (title, description, parseStrategy, exclude etc.
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
if (! array_key_exists('parseStrategy', $_GET) && strpos($feed['url'], 'twitter') !== false) {
    $feed['parseStrategy'] = 'twitter';
}
if (array_key_exists('noanswers', $_GET)) {
    $feed['exclude']['title'][] = 'Re: ';
}
if (array_key_exists('nojobs', $_GET)) {
    $feed['exclude']['title'][] = 'Stellenanzeige';
    $feed['exclude']['title'][] = 'Stellenangebot';
    $feed['exclude']['title'][] = 'Stellenausschreibung';
}
if (array_key_exists('nofb', $_GET)) {// e.g. exclude automatic posting in twitter from facebook
    $feed['exclude']['title'][] = 'http://fb.me';
}
if (array_key_exists('noretweet', $_GET)) {
    $feed['exclude']['test'][] = 'retweet';
}

//create message from these filters
$debugRequestParams = [];
foreach($_GET as $key => $value) {
    $debugRequestParams[] = ($value !== '') 
        ? $key . '=' . $value
        : $key;
}
$debugRequestParamsString = implode(', ', $debugRequestParams);

// TODO cache by URL for at least 10 minutes
$html = file_get_contents($feed['url']);
// TODO allow consuming JSON
$dom = new DOMDocument();
@$dom->loadHTML($html);
$xpath = new DOMXPath($dom);
$feed['urlParsed'] = parse_url($feed['url']);

function ensureAbsoluteUrl($url) {
    global $feed;
    if (substr($url, 0, 4) == 'http') {
        return $url;
    } else {
        $result = $feed['urlParsed']['scheme'] .  '://' . $feed['urlParsed']['host'];
        // TODO chomp trailing slashes and then implode for clarity
        if ( strlen(dirname($feed['urlParsed']['path'])) > 1 && // i.e. not just /
            substr($url, 0, 1) !== "/" ) {// 
            if (substr($feed['urlParsed']['path'],-1) == "/") {
                $result .= $feed['urlParsed']['path'];
            } else {
                $result .= dirname($feed['urlParsed']['path']) . '/';
            }
        }
        return $result . $url;
    }
}

// TODO make feed a class, with ->addItem
// TODO make item a class, with new($e) and ->getDate, ->getTitle etc.
$itemList = array();
if ($feed['parseStrategy'] === 'mhonarc_list_date') {
    $elements = $xpath->query("//ul/ul/li");// war //ul//ul//li

    foreach ($elements as $e) {
        $item = array();
        $linkToMsg = $xpath->query(".//a", $e)->item(0);
        $item['title'] = $linkToMsg->nodeValue;
        // author is right of title
        $item['author'] = ltrim(explode($item['title'], $e->textContent)[1], ", ");
        $item['url'] = ensureAbsoluteUrl($linkToMsg->getAttribute('href'));
        $item['date'] = date(DATE_RFC822, strtotime($e->parentNode->previousSibling->previousSibling->nodeValue));
        $item['description'] = '';
        $itemList[] = $item;
    }
} else if ($feed['parseStrategy'] === 'twitter') {
    $elements = $xpath->query("//*[contains(@class, 'original-tweet')]");
    foreach ($elements as $e) {
        $item = array('title' => '', 'url' => '', 'author' => '', 'date' => '', 'description' => '', 'test' => '');
        $linkToMsg = $xpath->query(".//a[contains(@class, 'tweet-timestamp')]", $e)->item(0);
        $item['url'] = ensureAbsoluteUrl($linkToMsg->getAttribute('href'));
        $item['title'] = $xpath->query(".//p[contains(@class, 'TweetTextSize')]", $e)->item(0)->textContent;
        $item['author'] = $xpath->query(".//strong[contains(@class, 'fullname')]", $e)->item(0)->textContent;
        $item['date'] = $linkToMsg->getAttribute('data-original-title');//TODO why is this not work as expected?
        if ( $xpath->query(".//span[contains(@class, 'js-retweet-text')]", $e)->length > 0) {
            $item['test'] = $xpath->query(".//span[contains(@class, 'js-retweet-text')]", $e)->item(0)->textContent;
        }
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
    foreach ($feed['exclude'] as $cat => $filters) {
        foreach ($filters as $filter) {
            if (strpos($item[$cat], $filter) !== false) {
                continue 3;
            }
        }
    }
    if ($count++ >= $feed['maxResult']) {
        break;
    }
    // TODO research convention of author name
    if (!array_key_exists('email', $item)) {
        $item['email'] = $feed['dummyEmail'];
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
