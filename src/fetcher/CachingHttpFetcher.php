<?php

require_once 'src/fetcher/HttpFetcher.php';

class CachingHttpFetcher extends HttpFetcher
{

    $config = json_decode(file_get_contents('config.json'));

    public function fetch(Session $session)
    {
        $urlMd5 = md5($session->url);
        $cachedFile = $config->cacheDir . '/' . $urlMd5;
        if (file_exists($cachedFile))
        {
            $session->bytes = file_get_contents($cachedFile);
        }
        else
        {
            $bytes = parent::doFetch($session->url);
            $session->bytes = $bytes;
            file_put_contents($cachedFile, $bytes);
        }
    }


}

?>
