<?php

class AtomParser
{

    // override
    public function parse(Session $session)
    {
        $chan = new DOMDocument();
        $chan->loadXML(mb_convert_encoding($session->bytes, 'HTML-ENTITIES', 'UTF-8'));
        $sheet = new DOMDocument();
        $sheet->load('vendor/atom2rss/atom2rss.xsl'); /* use stylesheet from this page */
        $processor = new XSLTProcessor();
        $processor->registerPHPFunctions();
        $processor->importStylesheet($sheet);
        $session->dom = $processor->transformToDoc($chan);
        $session->xpath = new DOMXPath($session->dom);
        $session->dom->save('/tmp/atom.xml');
        // error_log("RESULT: " . $session->dom->saveXML());
    }
}

?>
