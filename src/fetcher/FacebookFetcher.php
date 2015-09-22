<?php

require_once 'src/fetcher/CachingHttpFetcher.php';

class FacebookFetcher extends CachingHttpFetcher
{

    function fetch(Session $session)
    {

        // libxml_use_internal_errors();
        $session->url = $session->url  . '?_fb_noscript=1';

        parent::fetch($session);

        // Uncomment everything
        $session->bytes = preg_replace('/<script.*?<\/script>/', '', $session->bytes);
        // $session->bytes = preg_replace('/<!--/', '', $session->bytes);
        // $session->bytes = preg_replace('/-->/', '', $session->bytes);

        file_put_contents('/tmp/fb-clean.html', $session->bytes);
    }

}
