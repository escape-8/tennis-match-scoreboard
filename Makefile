server:
	php -S localhost:8080 -t public
dump:
	composer dump-autoload
install:
	composer install
migration:
	bin/doctrine.php orm:schema-tool:create
test:
	composer test
test-coverage:
	composer test-coverage