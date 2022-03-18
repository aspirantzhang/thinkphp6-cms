#!/bin/sh
cd /var/www || exit
./vendor/bin/phpunit --configuration ./phpunit.xml.dist --coverage-html build/logs/phpunit-coverage/
