<?php

require_once 'src/Utils.php';
require_once 'src/parser/HTMLParser.php';

class CrawlOutgoingHTMLParser extends HTMLParser
{

    var $xpathOutgoing;
    var $maxRecursion = 3;

    public function parse(Session $session)
    {
        if (!$this->xpathOutgoing)
        {
            Utils::throw400("Must set 'xpathOutgoing'");
        }
        error_log($this->xpathOutgoing);

        parent::parse($session);


        $outgoingNodes = $session->xpath->query($this->xpathOutgoing);
        if ($outgoingNodes === false)
        {
            throw Utils::throw400("Could not determine outgoing links");
        }
        else if ($outgoingNodes->length === 0)
        {
            throw Utils::throw400("No outgoing links");
        }

        $i = 0;
        foreach ($outgoingNodes as $outgoingNode)
        {
            if ($i++ > $this->maxRecursion)
            {
                break;
            }
            $outgoingLink = $session->ensureAbsoluteUrl($outgoingNode->textContent);

            $subsession = new Session($outgoingLink);
            $subfetcher = new CachingHttpFetcher();
            $subparser = new HTMLParser();
            $subfetcher->fetch($subsession);
            $subparser->parse($subsession);
            $newBody = $session->dom->importNode($subsession->dom->getElementsByTagName('body')->item(0), true);
            $session->dom->documentElement->appendChild($newBody);
        }
        $session->xpath = new DOMXPath($session->dom);
        // error_log($session->dom->save('/tmp/intra.html'));
    }
}


?>
