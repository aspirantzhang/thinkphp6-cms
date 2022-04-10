#!/bin/sh
cd /var/www || exit
./vendor/bin/phpstan analyze
