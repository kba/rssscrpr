<?php

require_once 'src/Fetcher.php'; 

class HttpFetcher extends Fetcher
{
    protected function doFetch($url)
    {
        return file_get_contents($url);
    }


    public function fetch(Session $session)
    {
        $session->bytes = $this->doFetch($session->url);
    }
}

?>
