<?php 

require_once 'src/Session.php';

abstract class Fetcher 
{
    abstract function fetch(Session $session);
}

?>
