<?php

require_once 'src/Parser.php';

class DOMParser extends Parser
{

    var $xpathOutgoing;

    public function parse(Session $session)
    {
        $session->dom = new DOMDocument();
        $session->dom->loadHTML($session->bytes);
        $session->xpath = new DOMXPath($session->dom);
    }
}

?>
