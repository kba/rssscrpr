<?php

require_once 'src/Parser.php';

class XMLParser extends Parser
{

    var $xpathOutgoing;

    public function parseDOM(Session $session)
    {
        $session->dom->loadXML(mb_convert_encoding($session->bytes, 'HTML-ENTITIES', 'UTF-8'));
    }

    public function parse(Session $session)
    {
        $session->dom = new DOMDocument();
        $this->parseDOM($session);
        $session->xpath = new DOMXPath($session->dom);
    }
}

?>
