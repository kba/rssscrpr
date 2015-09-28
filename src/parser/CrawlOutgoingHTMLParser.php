<?php

require_once 'src/Utils.php';
require_once 'src/parser/HTMLParser.php';

class CrawlOutgoingHTMLParser extends HTMLParser
{

    var $xpathOutgoing;

    public function parse(Session $session)
    {
        if (!$this->xpathOutgoing)
        {
            Utils::throw400("Must set 'xpathOutgoing'");
        }
        // the list of xpaths to find outgoing links, ordered by level of hierarchy
        $xpathOutgoingList = preg_split("/\s*,\s*/", $this->xpathOutgoing);

        // Let the HTMLParser parse, so we have a DOM
        parent::parse($session);

        // The urls to iterate through in this level of hierarchy
        $crawlUrls = array($session->url);
        // Step through the outgoing link xpaths
        for ($i =0; $i < count($xpathOutgoingList); $i++)
        {
            $nextLevelUrls = array();
            $thisLevelXpath = $xpathOutgoingList[$i];
            foreach ($crawlUrls as $url)
            {
                // create a session
                $subsession = new Session($url);
                // create a fetcher and fetch
                $fetcher = new CachingHttpFetcher();
                $fetcher->fetch($subsession);
                // create a non-crawling HTMLParser and parse
                $parser = new HTMLParser();
                $parser->parse($subsession);
                // Query for URLs of pages to further recurse
                $outLinkNodes = $subsession->xpath->query($thisLevelXpath);
                if ($outLinkNodes === false)
                {
                    throw Utils::throw400("Xpath query '{$thisLevelXpath}' failed for '{$url}' [Level: {$i}]");
                }
                else if ($outLinkNodes->length === 0)
                {
                    throw Utils::throw400("No results for query '{$thisLevelXpath}' failed for '{$url}' [Level: {$i}]");
                }
                foreach ($outLinkNodes as $outLinkNode)
                {
                    $nextLevelUrls[] = $subsession->ensureAbsoluteUrl($outLinkNode->textContent);
                }
            }
            $crawlUrls = $nextLevelUrls;
        }

        // Concatenate all the <body> elements into the original document
        foreach ($crawlUrls as $url)
        {
            // create a session
            $subsession = new Session($url);
            // create a fetcher and fetch
            $fetcher = new CachingHttpFetcher();
            $fetcher->fetch($subsession);
            // create a non-crawling HTMLParser and parse
            $parser = new HTMLParser();
            $parser->parse($subsession);
            $newBody = $session->dom->importNode($subsession->dom->getElementsByTagName('body')->item(0), true);
            $session->dom->documentElement->appendChild($newBody);
        }
        $session->dom->save('/tmp/test3.html');
    }
}


?>
