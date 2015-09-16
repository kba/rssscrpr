<?php

require_once 'src/Session.php';

abstract class FeedFilter
{

    abstract public function filter(Session $session);

}

?>
