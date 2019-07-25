install:
	@composer install --no-interaction
	$(MAKE) init

init:
	@composer install --no-interaction
	$(MAKE) clear
	$(MAKE) db
	$(MAKE) migrations
	bin/console doctrine:fixtures:load --append

db:
	bin/console doctrine:database:drop --force --if-exists
	bin/console doctrine:database:create

migrations:
	bin/console make:migration --no-interaction
	bin/console doctrine:migrations:migrate --no-interaction

clear:
	bin/console doctrine:cache:clear-query --quiet --no-debug
	bin/console doctrine:cache:clear-metadata --quiet --no-debug
	bin/console doctrine:cache:clear-result --quiet --no-debug
	bin/console cache:clear --no-debug

run-tests:
	bin/phpunit tests/unit
	bin/phpunit tests/functional

run-unit-tests:
	bin/phpunit tests/unit

run-functional-tests:
	bin/phpunit tests/functional