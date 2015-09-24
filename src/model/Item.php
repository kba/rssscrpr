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
        return sprintf("%s (%s)", $this->email, $this->author);
    }

    // private final function createCDATA($xml, $elName, $data)
    // {
        // $cd =  $xml->createCDATASection($data);
        // $node = $xml->createElement($elName, $cd);
        // return $node;
    // }

    public function toRSS(DOMElement $elem_item)
    {
        $xml = $elem_item->ownerDocument;
        $elem_item->appendChild($xml->createElement("title", htmlspecialchars($this->title)));
        $elem_item->appendChild($xml->createElement("guid", htmlspecialchars($this->url)));
        $elem_item->appendChild($xml->createElement("link", htmlspecialchars($this->url)));

        $fragment = $xml->createDocumentFragment();
        $fragment->appendXML($this->description);
        $elem_item->appendChild($xml->createElement("description"))->appendChild($fragment);

        $elem_item->appendChild($xml->createElement("author", htmlspecialchars($this->getAuthorWithEmail())));
        $elem_item->appendChild($xml->createElement("pubDate", htmlspecialchars(date(DATE_RFC822, $this->date))));
    }
}

?>
