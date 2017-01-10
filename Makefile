PHP_SCRIPTS = ${wildcard src/*.php src/**/*.php}
JADE = jade -P -O api.json

.PHONY: all\
	dev-deps dev-server

all: index.html

api.json: $(PHP_SCRIPTS)
	php doc/rebuild-api-json.php > api.json

index.html: index.jade api.json
	$(JADE) index.jade

dev-deps:
	sudo apt-get install php-curl php-xml -t testing

dev-server:
	php -S localhost:7070 2>&1| tee 'log/rssscrpr.log'

watch:
	$(JADE) -w index.jade
