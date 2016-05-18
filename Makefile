PHP_SCRIPTS = ${wildcard src/*.php src/**/*.php}

.PHONY: all

all: index.html

api.json: $(PHP_SCRIPTS)
	php rebuild-api-json.php > api.json

index.html: index.jade api.json
	jade -P -O api.json index.jade
