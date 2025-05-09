setup:
	cp -n .env .env.local || true
	docker-compose up -d
	make install

install:
	docker exec zaufany_detailer_php composer install

run-migrations:
	docker exec zaufany_detailer_php bin/console d:m:m -n

start:
	docker compose up -d

stop:
	docker compose stop

restart:
	docker compose stop
	docker compose up -d

shell:
	docker exec -it zaufany_detailer_php sh

setup-test-db:
	docker exec zaufany_detailer_php bin/console --env=test d:d:c

test:
	docker exec zaufany_detailer_php bin/phpunit

analyze: ### Run code analysis (CS-Fixer check & PHPStan)
	docker exec zaufany_detailer_php composer analyze

cs-fix:
	docker exec zaufany_detailer_php composer cs-fix

build:
	docker exec zaufany_detailer_php composer build

cmd: ### Run a command in the PHP container, e.g. make cmd CMD="php -v"
	docker exec -it zaufany_detailer_php ${CMD}

refresh-test-db:
	docker exec zaufany_detailer_php bin/console --env=test d:s:d --force
	docker exec zaufany_detailer_php bin/console --env=test d:s:c
#	docker exec zaufany_detailer_php bin/console --env=test d:f:l -n
	docker exec zaufany_detailer_php bin/console --env=test d:s:v
