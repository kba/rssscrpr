<?php

require_once 'src/model/Session.php';

abstract class Parser 
{
    abstract function parse(Session $session);
}

?>
