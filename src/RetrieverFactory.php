<?php

require_once 'src/Session.php';
require_once 'src/Retriever.php';
require_once 'src/filter/SimpleFilter.php';
require_once 'src/scraper/MHonArcScraper.php';
require_once 'src/scraper/XpathScraper.php';
require_once 'src/scraper/TwitterScraper.php';
require_once 'src/Utils.php';

class RetrieverFactory
{

    var $scraperHints = array();

    private function __construct() 
    {
    }

    public function createHtmlScraperFromQueryParams($queryParams)
    {
        if (! $queryParams['url'])
        {
            Utils::throw400("Must set 'url'.");
        }

        // Create the session
        $session = new Session($queryParams['url']);

        // Setup filter
        if (array_key_exists('noanswers', $queryParams))
        {
            $session->filter->exclude->title[] = 'Re: ';
        }

        if (array_key_exists('nojobs', $queryParams))
        {
            $session->filter->exclude->title[] = 'Stellenanzeige';
            $session->filter->exclude->title[] = 'Stellenangebot';
            $session->filter->exclude->title[] = 'Stellenausschreibung';
        }

        if (array_key_exists('nofb', $queryParams)) 
        {
            // e.g. exclude automatic posting in twitter from facebook
            $session->filter->exclude->title[] = 'http://fb.me';
        }

        if (array_key_exists('noretweet', $queryParams))
        {
            $session->filter->exclude->test[] = 'retweet';
        }

        // Decide on the scraper to use
        if (!isset($queryParams['scraper']))
        {
            throw Utils::throw400("Must set 'scraper' explicitly");
        }
        $scraper = new $queryParams['scraper']();

        foreach ($queryParams as $k => $v)
        {
            if (Utils::startsWith($k, 'exclude_') || Utils::startsWith($k, 'include_'))
            {
                $tokens = explode("_", $k, 2);
                $inex = $tokens[0];
                $field = $tokens[1];
                foreach (preg_split("/,\s*/", $v) as $text)
                {
                    if ($text === '')
                    {
                        continue;
                    }
                    $session->filter->{$inex}[$field][] = $text;
                }
            }
            else if (Utils::startsWith($k, 'scraper_'))
            {
                $field = explode("_", $k, 2)[1];
                $v = $queryParams[$k];
                if ($v)
                {
                    $scraper->$field = $v;
                }
            }
            else if (property_exists('FeedFilterConfig', $k))
            {
                $session->filter->$k = $queryParams[$k];
            }
        }

        // create a Retriever
        return new Retriever($session, null, null, $scraper, null);
    }

}

?>
