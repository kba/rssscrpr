<?php

require_once 'src/Fetcher.php'; 
require_once 'src/Utils.php'; 

class HttpFetcher extends Fetcher
{
    protected function doFetch($url)
    {
        $headers  = array(
            "Accept-language: en",
            "User-Agent: Yeti");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $ret = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpcode != 200)
        {
            Utils::throw400("Failed to retrieve '$url': " . json_encode(curl_getinfo($ch)));
        }
        return $ret;
    }


    public function fetch(Session $session)
    {
        $session->bytes = $this->doFetch($session->url);
    }
}

?>
