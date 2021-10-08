.\vendor\bin\phpunit --configuration ./phpunit.xml.dist --coverage-html build/logs/phpunit-coverage/ && php think db:deleteReservedTable && php think migrate:run
