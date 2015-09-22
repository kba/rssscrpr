<?php

class FacebookScraper extends XpathScraper
{

    function __construct()
    {
        $this->xpathItem = "//div[contains(@class, 'userContentWrapper ')]";

        $this->xpathTitle = './/div[contains(@class, "userContent")]';
        $this->xpathLink = './/a[contains(@class, "5pcq")]/@href';
        $this->xpathAuthor = './/span[@data-ft=\'{"tn":"k"}\']/a';
        $this->xpathDate = './/abbr[@data-utime]/@data-utime';
        $this->xpathDescription = './/div[contains(@class, "userContent")]/following-sibling::div';
    }
}

?>
