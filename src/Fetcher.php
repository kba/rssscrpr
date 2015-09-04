<?php 

require_once 'src/model/Session.php';

abstract class Fetcher 
{
    abstract function fetch(Session $session);
}

?>
