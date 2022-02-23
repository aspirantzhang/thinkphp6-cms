#!/bin/sh
cd /var/www/
php think migrate:run
exec "$@"
