#!/bin/sh
cd /var/www
./vendor/bin/php-cs-fixer fix --verbose --diff
