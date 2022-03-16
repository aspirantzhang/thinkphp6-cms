#!/bin/sh
cd /var/www || exit
command=''
if [ "$*" = "-D" ]; then
    command='--dry-run'
fi
./vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php --verbose ${command} -- app tests
