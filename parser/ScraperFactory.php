<?php

class ScraperFactory {

    public static createScraper(Feed $feed) {
        if (array_key_exists('noanswers', $feed->scraperHints)) {
            $feed->exclude['title'][] = 'Re: ';
        }
        if (array_key_exists('nojobs', $feed->scraperHints)) {
            $feed->exclude['title'][] = 'Stellenanzeige';
            $feed->exclude['title'][] = 'Stellenangebot';
            $feed->exclude['title'][] = 'Stellenausschreibung';
        }
        if (array_key_exists('nofb', $feed->scraperHints)) {// e.g. exclude automatic posting in twitter from facebook
            $feed->exclude['title'][] = 'http://fb.me';
        }
        if (array_key_exists('noretweet', $feed->scraperHints)) {
            $feed->exclude['test'][] = 'retweet';
        }

        // TODO make configurable
        if (! array_key_exists('parseStrategy', $_GET) && strpos($feed->url, 'twitter') !== false) {
            $feed->parseStrategy = 'twitter';
        }

        $scraper = NULL;
        if ($feed->parseStrategy === 'twitter') {
            $scraper = new TwitterHtmlScraper($feed);
        } else if ($feed->parseStrategy === 'mhonarc_list_date') {
            $scraper = new MHonArcScraper($feed);
        } else {
            throw new Exception("Unknown parseStrategy " . $feed->parseStrategy);
        }
        return $scraper;
    }

}

?>
