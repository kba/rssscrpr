PHP_SCRIPTS = ${wildcard src/*.php src/**/*.php}

.PHONY: all\
	dev-deps dev-server

dev-deps:
	sudo apt-get install php-curl php-xml -t testing

dev-server:
	php -S localhost:7070 2>&1| tee 'log/rssscrpr.log'

all: index.html

api.json: $(PHP_SCRIPTS)
	php rebuild-api-json.php > api.json

index.html: index.jade api.json
	jade -P -O api.json index.jade
