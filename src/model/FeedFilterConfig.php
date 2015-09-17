<?php

class FeedFilterConfig
{
    // maximum number of items per feed
    var $maxResult = -1;

    // maximum age of items in days
    var $maxAge = -1;

	// Whether to run the include filters first and then exclude or vice versa
	var $filter_first =  'include';

    // Strings an item's field *MUST NOT* contain
	var $exclude = array(
		"title" => array(),
		"test" => array(),
		"description" => array(),
	);

    // Strings an item's title *MUST* contain
	var $include = array(
		"title" => array(),
		"test" => array(),
		"description" => array(),
	);

}

?>
