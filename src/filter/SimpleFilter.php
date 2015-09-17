<?php

require_once 'src/FeedFilter.php';

class SimpleFilter extends FeedFilter
{
    private function doFilter(Session $session, $includeOrExclude)
    {
        $toFilter = $session->feed->getItems();
        $filtered = array();
        $session->feed->reset();
        for ($i = 0; $i < count($toFilter); $i++)
        {
            $item = $toFilter[$i];
            foreach ($session->filter->$includeOrExclude as $cat => $list)
            {
                foreach ($list as $f)
                {
                    $matchPos = strpos($item->$cat, $f);
                    if ($includeOrExclude === 'exclude')
                    {
                        // If the filter matched, do not add the item
                        if ($matchPos !== false)
                        {
                            continue 3;
                        }
                    }
                    else
                    {
                        // if the filter did not match, do not add the item
                        if ($matchPos === false)
                        {
                            continue 3;
                        }
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
        if ($session->filter->maxAge <= 0)
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
        if ($session->filter->maxResult <= 0)
        {
            return;
        }
        $toFilter = $session->feed->getItems();
        $filtered = array();
        $session->feed->reset();

        $i = 0;
        foreach ($toFilter as $item)
        {
            if ($i++ >= $session->filter->maxResult)
            {
                break;
            }
            $session->feed->addItem($item);
        }
    }

    public function filter(Session $session)
    {
        if ($session->filter->filter_first === 'include')
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
