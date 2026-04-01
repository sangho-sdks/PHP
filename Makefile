# Détection automatique Windows vs Unix
ifeq ($(OS),Windows_NT)
    PHPUNIT  := vendor\bin\phpunit.bat
    PHPCS    := vendor\bin\phpcs.bat
    PHPCBF   := vendor\bin\phpcbf.bat
    PHPSTAN  := vendor\bin\phpstan.bat
    RM       := rmdir /s /q
    SEP      := \\
else
    PHPUNIT  := vendor/bin/phpunit
    PHPCS    := vendor/bin/phpcs
    PHPCBF   := vendor/bin/phpcbf
    PHPSTAN  := vendor/bin/phpstan
    RM       := rm -rf
    SEP      := /
endif

install:
	composer install

test:
	$(PHPUNIT)

test-filter:
	$(PHPUNIT) --filter=$(FILTER)

lint:
	$(PHPCS) --standard=PSR12 lib/ tests/

format:
	$(PHPCBF) --standard=PSR12 lib/ tests/

analyse:
	$(PHPSTAN) analyse lib/ --level=8

build:
	composer archive --format=zip

publish:
	composer publish

clean:
ifeq ($(OS),Windows_NT)
	if exist vendor $(RM) vendor
	if exist composer.lock del composer.lock
else
	$(RM) vendor/ composer.lock
endif

.PHONY: install test test-filter lint format analyse build publish clean
