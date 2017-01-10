<?php

require_once 'src/Fetcher.php'; 
require_once 'src/Utils.php'; 

class HttpFetcher extends Fetcher
{
    protected function doFetch($url)
    {
        $headers  = array(
            "Accept-language: en",
            // "User-Agent: " . $_SERVER['HTTP_USER_AGENT']
            "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:40.0) Gecko/20100101 Firefox/40.0",
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Accept-Language: en-US,en;q=0.5",
            "Connection: keep-alive",
            "Cache-Control: max-age=0"
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);

        $resp = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // var_dump(substr($resp, 0, $header_size-1));
        if ($httpcode != 200)
        {
            Utils::throw400("Failed to retrieve '$url': " . json_encode(curl_getinfo($ch), JSON_PRETTY_PRINT));
        }
        // error_log("Header size (rly, PHP. Rly?): " . $header_size);
        // var_dump(json_encode(curl_getinfo($ch), JSON_PRETTY_PRINT));
        // error_log("RESPONSE: " . json_encode(curl_getinfo($ch)));

        curl_close($ch);
        $ret = substr($resp, $header_size);
        file_put_contents('/tmp/last.xml', $ret);
        return $ret;
    }


    public function fetch(Session $session)
    {
        $session->bytes = $this->doFetch($session->url);
    }
}

?>
