<?php

require_once 'src/Session.php';

abstract class ItemFilter
{
    abstract function filter(Session $session);
}

?>
