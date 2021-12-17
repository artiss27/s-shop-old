include .env
export

DB_NAME = app_db
PROJECT_DIR = app
VENDOR = ./vendor

up: docker-up
down: docker-down
restart: docker-down docker-up
init: docker-down-clear app-clear docker-pull docker-build ssl-generate docker-up app-init
init-empty: docker-pull docker-build docker-up
add-migrations: app-make-migrations
test: app-test
test-coverage: app-test-coverage
test-unit: app-test-unit
test-unit-coverage: app-test-unit-coverage


refactoring:
	make eslint php-cs-fixer --keep-going

run-test-sh:
	docker-compose run --rm php-cli sh ./bin/run-tests.sh

psql-connect:
	psql -h 127.0.0.1 -d ranked_choice_2 -U rc_super_admin -W # need to refactor this

watch:
	cd ./app; yarn run watch

cli:
	docker-compose run --rm php-cli bash

php-bash:
	docker exec -it ${APP_PROJECT_NAME}_php_fpm bash

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

ssl-generate:
	docker-compose run --rm php-cli bash -c "cd /app/.docker/development/nginx/ssl/ && \
	  openssl req -x509 -out docker.loc.crt -keyout docker.loc.key \
	  -newkey rsa:2048 -nodes -sha256 \
	  -subj '/CN=docker.loc' -extensions EXT -config <( \
	   printf \"[dn]\nCN=docker.loc\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:docker.loc\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth\")"

app-init: app-composer-install app-assets-install app-oauth-keys app-wait-db app-migrations app-fixtures app-ready

app-clear:
	#docker run --rm -v ${PWD}/$(MY_APP_NAME):/app --workdir=/app alpine rm -f .ready

app-composer-install:
	docker-compose run --rm php-cli composer install

app-assets-install:
	docker-compose run --rm node yarn install
	docker-compose run --rm node npm rebuild node-sass

app-oauth-keys:
	docker-compose run --rm php-cli mkdir -p var/oauth
	docker-compose run --rm php-cli openssl genrsa -out var/oauth/private.key 2048
	docker-compose run --rm php-cli openssl rsa -in var/oauth/private.key -pubout -out var/oauth/public.key
	docker-compose run --rm php-cli chmod 644 var/oauth/private.key var/oauth/public.key

app-wait-db:
	until docker-compose exec -T postgres pg_isready --timeout=0 --dbname=$(DB_NAME) ; do sleep 1 ; done

app-make-migrations:
	docker-compose run --rm php-cli bash -c "bin/console make:migration && bin/console doctrine:migrations:migrate --no-interaction"

app-migrations:
	docker-compose run --rm php-cli php bin/console doctrine:migrations:migrate --no-interaction

app-fixtures:
	#docker-compose run --rm php-cli php bin/console doctrine:fixtures:load --no-interaction

app-ready:
	#docker run --rm -v ${PWD}/$(MY_APP_NAME):/app --workdir=/app alpine touch .ready

app-assets-dev:
	docker-compose run --rm node npm run dev

app-test:
	docker-compose run --rm php-cli php bin/phpunit

app-test-coverage:
	docker-compose run --rm php-cli php bin/phpunit --coverage-clover var/clover.xml --coverage-html var/coverage

app-test-unit:
	docker-compose run --rm php-cli php bin/phpunit --testsuite=unit

app-test-unit-coverage:
	docker-compose run --rm php-cli php bin/phpunit --testsuite=unit --coverage-clover var/clover.xml --coverage-html var/coverage

eslint:
	${PROJECT_DIR}/node_modules/.bin/eslint ${PROJECT_DIR}/assets/js/ --ext .js,.vue --fix

php-cs-fixer:
	docker-compose run --rm php-cli vendor/bin/php-cs-fixer fix src/ --verbose

phpstan:
	docker-compose run --rm php-cli vendor/bin/phpstan analyse src --level 1




build-production:
	docker build --pull --file=$(MY_APP_NAME)/docker/production/nginx.docker --tag ${REGISTRY_ADDRESS}/nginx:${IMAGE_TAG} $(MY_APP_NAME)
	docker build --pull --file=$(MY_APP_NAME)/docker/production/php-fpm.docker --tag ${REGISTRY_ADDRESS}/php-fpm:${IMAGE_TAG} $(MY_APP_NAME)
	docker build --pull --file=$(MY_APP_NAME)/docker/production/php-cli.docker --tag ${REGISTRY_ADDRESS}/php-cli:${IMAGE_TAG} $(MY_APP_NAME)
	docker build --pull --file=$(MY_APP_NAME)/docker/production/postgres.docker --tag ${REGISTRY_ADDRESS}/postgres:${IMAGE_TAG} $(MY_APP_NAME)
	docker build --pull --file=$(MY_APP_NAME)/docker/production/redis.docker --tag ${REGISTRY_ADDRESS}/redis:${IMAGE_TAG} $(MY_APP_NAME)
	docker build --pull --file=centrifugo/docker/production/centrifugo.docker --tag ${REGISTRY_ADDRESS}/centrifugo:${IMAGE_TAG} centrifugo

push-production:
	docker push ${REGISTRY_ADDRESS}/nginx:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/php-cli:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/postgres:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/redis:${IMAGE_TAG}
	docker push ${REGISTRY_ADDRESS}/centrifugo:${IMAGE_TAG}

deploy-production:
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'rm -rf docker-compose.yml .env'
	scp -o StrictHostKeyChecking=no -P ${PRODUCTION_PORT} docker-compose-production.yml ${PRODUCTION_HOST}:docker-compose.yml
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "REGISTRY_ADDRESS=${REGISTRY_ADDRESS}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "IMAGE_TAG=${IMAGE_TAG}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_APP_SECRET=${MANAGER_APP_SECRET}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_DB_PASSWORD=${MANAGER_DB_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_REDIS_PASSWORD=${MANAGER_REDIS_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_MAILER_URL=${MANAGER_MAILER_URL}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "MANAGER_OAUTH_FACEBOOK_SECRET=${MANAGER_OAUTH_FACEBOOK_SECRET}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "STORAGE_BASE_URL=${STORAGE_BASE_URL}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "STORAGE_FTP_HOST=${STORAGE_FTP_HOST}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "STORAGE_FTP_USERNAME=${STORAGE_FTP_USERNAME}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "STORAGE_FTP_PASSWORD=${STORAGE_FTP_PASSWORD}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "CENTRIFUGO_WS_HOST=${CENTRIFUGO_WS_HOST}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "CENTRIFUGO_API_KEY=${CENTRIFUGO_API_KEY}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'echo "CENTRIFUGO_SECRET=${CENTRIFUGO_SECRET}" >> .env'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose pull'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose up --build -d'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'until docker-compose exec -T postgres pg_isready --timeout=0 --dbname=$(DB_NAME) ; do sleep 1 ; done'
	ssh -o StrictHostKeyChecking=no ${PRODUCTION_HOST} -p ${PRODUCTION_PORT} 'docker-compose run --rm php-cli php bin/console doctrine:migrations:migrate --no-interaction'