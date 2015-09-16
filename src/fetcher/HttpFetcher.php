<?php

require_once 'src/Fetcher.php'; 

class HttpFetcher extends Fetcher
{
    public function fetch(Session $session)
    {
        $session->bytes = file_get_contents($session->url);
    }
}

?>
