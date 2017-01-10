RSS-scraper
===========

[![Join the chat at https://gitter.im/kba/rssscrpr](https://badges.gitter.im/kba/rssscrpr.svg)](https://gitter.im/kba/rssscrpr?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## End-user documentation

See the [Github wiki](https://github.com/kba/rssscrpr/wiki)

## Dependencies

* PHP 5.4
* Jade (for compiling `*.jade` -> `*.html`
* php-mbstring
* php5-xsl

## Installation

### Apache

Drop below Apache DocumentRoot.

git initialize submodules

### Heroku

* Register with heroku
* Download the CLI (as described)

```
git clone https://github.com/kba/rssscrpr
cd rssscrpr
heroku login
heroku create
git push heroku master
```

## Building

For every change, make sure you run `make` without arguments, it will rebuild
the necessary files for you.

To do it manually:

### Rebuild API JSON

```
make api.json
```

will do

```
php rebuild-api-json.php > api.json
```

if any PHP scripts changed.

### Rebuild HTML

```
make index.html
```

will do

```
jade -P -O api.json index.jade
```

if the `index.jade` or the `api.json` needs rebuilding.
