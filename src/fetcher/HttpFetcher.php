<?php

require_once 'src/Fetcher.php'; 

class HttpFetcher extends Fetcher
{
    protected function doFetch($url)
    {
        $options  = array('http' => array(
            'header' => "Accept-language: en\r\n" .
                        "User-Agent: Yeti"));
        $context  = stream_context_create($options);
        return file_get_contents($url, false, $context);
    }


    public function fetch(Session $session)
    {
        $session->bytes = $this->doFetch($session->url);
    }
}

?>
