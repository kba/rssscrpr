RSS-scraper
===========

## Dependencies

* PHP 5.4
* Jade (for compiling `*.jade` -> `*.html`
* php-log
* php5-xsl

## Installation

Drop below Apache DocumentRoot.

git initialize submodules

## Building

Rebuild API JSON:

```
php rebuild-api-json.php > api.json
```

Rebuild HTML

```
jade -O api.json index.jade
```
