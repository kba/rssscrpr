<?php

require_once 'src/model/Session.php';
require_once 'src/Retriever.php';
require_once 'src/scraper/MHonArcScraper.php';
require_once 'src/scraper/TwitterScraper.php';

class RetrieverFactory {

    var $scraperHints = array();

    private function __construct() 
    {
    }

    public function createFromQueryParams($queryParams)
    {
        if (!array_key_exists('url', $queryParams))
        {
            throw new Exception("Must pass url.");
        }

        $session = new Session($queryParams['url']);
        if (array_key_exists('noanswers', $queryParams)) {
            $session->exclude['title'][] = 'Re: ';
        }
        if (array_key_exists('nojobs', $queryParams)) {
            $session->exclude['title'][] = 'Stellenanzeige';
            $session->exclude['title'][] = 'Stellenangebot';
            $session->exclude['title'][] = 'Stellenausschreibung';
        }
        if (array_key_exists('nofb', $queryParams)) {// e.g. exclude automatic posting in twitter from facebook
            $session->exclude['title'][] = 'http://fb.me';
        }
        if (array_key_exists('noretweet', $queryParams)) {
            $session->exclude['test'][] = 'retweet';
        }

        //
        foreach ($queryParams as $k => $v) {
            if (property_exists('Session', $k)) {
                if (is_array($session->$k)) {
                    array_push($session->$k, $v);
                } else {
                    $session->$k = $queryParams[$k];
                }
            }
        }

        // Decide on the scraper to use
        $scraper = null;
        if (array_key_exists('scraper', $queryParams))
        {
            $scraper = new $queryParams['scraper']();
        } else {
            if (strpos($session->url, 'twitter') !== false)
            {
                $scraper = new TwitterScraper();
            }
            else
            {
                throw new Exception("Must set 'scraper' explicitly");
            }
        }

        // create a Retriever
        return new Retriever($session, null, null, $scraper, null);
    }

}

?>
