<?php

require_once 'src/ItemFilter.php';

class NopFilter extends ItemFilter
{
    public function filter(Session $session)
    {
        // Do nothing
    }
}

?>
