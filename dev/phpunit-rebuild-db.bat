.\vendor\bin\phpunit --configuration ./phpunit.xml.dist --coverage-html build/logs/phpunit-coverage/ && php think misc:deleteTable && php think migrate:run
