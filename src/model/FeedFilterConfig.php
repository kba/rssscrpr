<?php

abstract class FeedFilterConfig
{
    // maximum number of items per feed
    var $maxResult = 20;
    // maximum age of items
    var $maxAge = -1;
    // Strings an item's title must not contain
    var $title = array();
    // Strings an item's test field must not contain
    var $test = array();
}

?>
