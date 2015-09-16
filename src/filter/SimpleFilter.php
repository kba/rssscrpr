<?php

require_once 'src/FeedFilter.php';

class SimpleFilter extends FeedFilter
{
    public function filter(Session $session)
    {
        $toFilter = $session->feed->getItems();
        $filtered = array();
        $session->feed->reset();
        for ($i = 0; $i < count($toFilter); $i++)
        {
            $item = $toFilter[$i];
            foreach (array('title', 'test') as $cat)
            {
                foreach ($session->filter->$cat as $f)
                {
                    if (strpos($item->$cat, $f) !== false)
                    {
                        continue 3;
                    }
                }
            }
            $session->feed->addItem($item);
        }
    }
}

?>
