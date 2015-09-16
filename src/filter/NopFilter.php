<?php

require_once 'src/FeedFilter.php';

class NopFilter extends FeedFilter
{
    public function filter(Session $session)
    {
        // Do nothing
    }
}

?>
