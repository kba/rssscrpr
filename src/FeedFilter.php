<?php

require_once 'src/model/Session.php';

abstract class FeedFilter
{

    abstract public function filter(Session $session);

}

?>
