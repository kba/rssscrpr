<?php

class Item {

    var $url;
    var $date;
    var $description;
    var $title;
    var $author;
    var $email = 'nobody@nowhere.no';
    // this field isn't output but only for filtering, scraper may add data to it.
    var $test;

    public function __construct($args = array()) {
        foreach ($args as $k => $v) {
            $this->$k = $v;
        }
    }

    public static function template() 
    {
        return array(
            'url' => 'http://-----',
            'title' => 'Untitled Feed Item',
            'author' => 'Unknown Author',
            'date' => new DateTime(''),
            'description' => 'No description',
            'email' => 'nobody@nowhere.no'
        );
    }

    private final function getAuthorWithEmail()
    {
        return sprintf("%s (%s)", $this->author, $this->email);
    }

    public function toRSS(DOMElement $elem_item)
    {
        $xml = $elem_item->ownerDocument;
        $elem_item->appendChild($xml->createElement("title", $this->title));
        $elem_item->appendChild($xml->createElement("guid", $this->url));
        $elem_item->appendChild($xml->createElement("link", $this->url));
        $elem_item->appendChild($xml->createElement("description", $this->description));
        $elem_item->appendChild($xml->createElement("author", $this->getAuthorWithEmail()));
        $elem_item->appendChild($xml->createElement("date", $this->date));
    }
}

?>
