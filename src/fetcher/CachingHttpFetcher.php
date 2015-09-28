<?php

require_once 'src/fetcher/HttpFetcher.php';

class CachingHttpFetcher extends HttpFetcher
{

    // Maximum time to cache
    var $maxCacheTime = 2 * 60 * 60;

    private function ensureCacheDir($session)
    {
        if (! file_exists($session->config->cacheDir))
        {
            mkdir($session->config->cacheDir);
        }
    }

    public function fetch(Session $session)
    {
        $this->ensureCacheDir($session);
        $urlMd5 = md5($session->url);
        $cachedFile = $session->config->cacheDir . '/' . $urlMd5;
        if (file_exists($cachedFile) && (time() - filemtime($cachedFile)) < $this->maxCacheTime)
        {
            // error_log("FROM_CACHE: $cachedFile");
            $session->bytes = file_get_contents($cachedFile);
        }
        else
        {
            // error_log("RELOAD: $cachedFile");
            $bytes = parent::doFetch($session->url);
            $session->bytes = $bytes;
            file_put_contents($cachedFile, $bytes);
        }
    }


}

?>
