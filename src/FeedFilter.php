<?php

require_once 'src/Session.php';

class FeedFilter
{
    // maximum number of items per feed
    var $maxResult = -1;

    // maximum age of items in days
    var $maxAge = -1;

	// Whether to run the include filters first and then exclude or vice versa
	var $filter_first =  'include';

    // Strings an item's field *MUST NOT* contain
	var $exclude = array(
		"title" => array(),
		"test" => array(),
		"description" => array(),
	);

    // Strings an item's title *MUST* contain
	var $include = array(
		"title" => array(),
		"test" => array(),
		"description" => array(),
	);

    private function doFilter(Session $session, $includeOrExclude)
    {
        $toFilter = $session->feed->getItems();
        $filtered = array();
        $session->feed->reset();
        foreach ($toFilter as $item)
        {
            foreach ($this->$includeOrExclude as $cat => $list)
            {
                // Utils::var_error_log($list);
                foreach ($list as $f)
                {
                    if (! $f)
                        continue;
                    $matchPos = strpos($item->$cat, $f);
                    if (
                        ($includeOrExclude === 'exclude'  && $matchPos !== false)
                        ||
                        ($includeOrExclude === 'include'  && $matchPos === false)
                    )
                    {
                        continue 3;
                    }
                }
            }
            // All include filters succeeded for all fields, add the item
            // OR
            // No exclude filter succeeded for any field, add the item
            $session->feed->addItem($item);
        }
    }

    private function doAgeFilter(Session $session)
    {
        if ($this->maxAge <= 0)
        {
            return;
        }
        $toFilter = $session->feed->getItems();
        $filtered = array();
        $session->feed->reset();
        foreach ($toFilter as $item)
        {
            // TODO
            $session->feed->addItem($item);
        }
    }

    private function doMaxFilter(Session $session)
    {
        if ($this->maxResult <= 0)
        {
            return;
        }
        $toFilter = $session->feed->getItems();
        $filtered = array();
        $session->feed->reset();

        $i = 0;
        foreach ($toFilter as $item)
        {
            if ($i++ >= $this->maxResult)
            {
                break;
            }
            $session->feed->addItem($item);
        }
    }

    public function filter(Session $session)
    {
        if ($this->filter_first === 'include')
        {
            $this->doFilter($session, 'include');
            $this->doFilter($session, 'exclude');
        }
        else
        {
            $this->doFilter($session, 'exclude');
            $this->doFilter($session, 'include');
        }
        $this->doAgeFilter($session);
        $this->doMaxFilter($session);
    }

}

?>
