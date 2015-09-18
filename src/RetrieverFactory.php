<?php

require_once 'src/Session.php';
require_once 'src/Retriever.php';
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

        if (! $queryParams['scraper'])
        {
            throw Utils::throw400("Must set 'scraper'.");
        }

        // Create the session
        $session = new Session($queryParams['url']);

        // create a Retriever
        $scraper = new $queryParams['scraper']();
        $retriever = new Retriever($session, null, null, $scraper, null);

        // Setup filter
        if (array_key_exists('noanswers', $queryParams))
        {
            $retriever->filter->exclude['title'][] = 'Re: ';
        }

        if (array_key_exists('nojobs', $queryParams))
        {
            $retriever->filter->exclude['title'][] = 'Stellenanzeige';
            $retriever->filter->exclude['title'][] = 'Stellenangebot';
            $retriever->filter->exclude['title'][] = 'Stellenausschreibung';
        }

        if (array_key_exists('nofb', $queryParams)) 
        {
            // e.g. exclude automatic posting in twitter from facebook
            $retriever->filter->exclude['title'][] = 'fb.me';
        }

        if (array_key_exists('noretweet', $queryParams))
        {
            $retriever->filter->exclude['test'][] = 'retweet';
        }

        foreach ($queryParams as $k => $v)
        {
            if (! $v)
            {
                continue;
            }
            if (Utils::contains($k, '_'))
            {
                $tokens = explode("_", $k, 2);
                $component = $tokens[0];
                // error_log("{$component} -> {$tokens[1]} = $v");
                if (Utils::contains($tokens[1], '_'))
                {
                    $tokens = explode("_", $tokens[1], 2);
                    // error_log("{$component} -> {$tokens[0]} -> {$tokens[1]}} [] = $v");
                    $retriever->$component->{$tokens[0]}[$tokens[1]][] = $v;
                }
                else
                {
                    $retriever->$component->{$tokens[1]} = $v;
                }
            }
        }
        error_log(json_encode($retriever->filter));

        return $retriever;
    }

}

?>
