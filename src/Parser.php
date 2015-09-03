<?php

require_once 'src/Session.php';

abstract class Parser 
{
    abstract function parse(Session $session);
}

?>
