<?php

require_once "src/model/Item.php";

class Feed {

    var $title;
    var $url;
    var $description;

    private $items;

    public function __construct($args = array()) {
        $this->reset();
        foreach ($args as $k => $v) {
            $this->$k = $v;
        }
    }

    public function reset()
    {
        $this->items = array();
    }

    public function addItem(Item $item = null)
    {
        $item = $item ?: new Item();
        $this->items[] = $item;
        return $item;
    }

    public function getItems() 
    {
        return array_values($this->items);
    }

    public function size()
    {
        return sizeof($this->items);
    }

    public function asRSS()
    {
        $xml = new DOMDocument("1.0", "UTF-8");
        $xml->formatOutput = true;
        $this->toRSS($xml);
        return $xml;
    }

    public function toRSS(DOMDocument $xml)
    {
        $elem_rss = $xml->createElement("rss");
        $elem_rss->setAttribute("version", "2.0");

        $elem_channel = $xml->createElement("channel");
        $elem_channel->appendChild($xml->createElement("title", htmlspecialchars($this->title)));
        $elem_channel->appendChild($xml->createElement("description", htmlspecialchars($this->description)));
        $elem_channel->appendChild($xml->createElement("link", $this->url)); 

        foreach ($this->items as $item)
        {
            $elem_item = $xml->createElement("item");
            $item->toRSS($elem_item);
            $elem_channel->appendChild($elem_item);
        }

        $xml->appendChild($elem_rss);
        $elem_rss->appendChild($elem_channel);
    }

    public function sort()
    {
        error_log("SORTING");
        uasort($this->items, function($a, $b) {
            return ($a->date > $b->date) ? -1 : +1;
        });
    }
}

?>
