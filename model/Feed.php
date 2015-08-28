<?php

require_once "model/Item.php";

class Feed {
    var $maxResult = 15;
    var $defaultEmail = 'nobody@nowhere.no';
    var $parseStrategy = 'mhonarc_list_date';
    var $scraperHints = array();
    // TODO make sure this is $_GET overrideable
    var $exclude = array(
        'title' => array(),
        'test' => array()
    );
    private $items = array();

    public function __construct($args) {
        foreach ($args as $k => $v) {
            $this->$k = $v;
        }
    }

    public function addItem(Item $item) {
        $items[] = $item;
    }
    public function getItems() {
        // TODO apply filters, count etc.
    }
}
 
?>
