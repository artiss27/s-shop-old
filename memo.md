# MEMO:

- `docker-compose run --rm manager-php-cli composer require --dev orm-fixtures`
- `docker-compose run --rm manager-php-cli php bin/console`
- `docker-compose run --rm manager-php-cli php bin/console make:migration` - after create entity (make:entity) this command makes migration from it. (need to delete after generate line with 'CREATER SCHEMA public')
- `bin/console doctrine:migrations:migrate prev`
- `doctrine:query:sql "TRUNCATE category CASCADE";` delete all category Cascade (with all relative tables row)
- `bin/console doctrine:query:sql "ALTER SEQUENCE category_id_seq RESTART WITH 1";` udpade index after deleting
- `make manager-fixtures`
> http://localhost:8080 - почтовик

> регистрация и почта - 5 день

# FLOW:

> create docker, firstly enough: php, php-cli & nginx & DB
> if u create from (`curl -sS https://get.symfony.com/cli/installer | bash`) u need to move folder (it has written after install exemple: `mv /root/.symfony/bin/symfony /usr/local/bin/symfony`), and verify that `symfony -v`

> CREATER SKELETON: `make cli` and `composer create-project symfony/website-skeleton my_project_name` && move created files to folder what u want

> DBAL: modify your `DATABASE_URL` config in `.env` && Configure the driver (postgresql) and server_version (13) in config/packages/doctrine.yaml && in manager/config/packages/doctrine.yaml settings for DBAL (verify: `bin/console doctrine:schema:validate`)

> ADD COMPOSER:
> 
> flag -w (show dependencies and u can downgrade them if u have conflict)
* `composer require --dev orm-fixtures`
* `composer require --dev phpunit/phpunit`
* `composer require ramsey/uuid`
* `composer require knplabs/knp-paginator-bundle`
* `composer require symfony/swiftmailer-bundle`
* `composer require finesse/swiftmailer-defaults-plugin`
* 
* `composer require symfonycasts/verify-email-bundle` verify Emails 
* `composer require imagine/imagine` Images
* `composer require twig/intl-extra` Currency
* `composer require stof/doctrine-extensions-bundle` Slug
* `composer require api` Api
* `composer require symfony/webpack-encore-bundle` Webpack
* `composer require twig/cssinliner-extra` Css inliner (for emails)
* `composer require twig/inky-extra` for emails templates
* `composer require symfonycasts/reset-password-bundle` for reset password
* `composer require knpuniversity/oauth2-client-bundle` for auth from google and other - config/packages/knpu_oauth2_client.yaml
* `composer require league/oauth2-facebook` for auth from facebook
* `composer require league/oauth2-google` for auth from google
* `composer require php-translation/symfony-bundle` translation dundle (php_translation.yaml, php_translation.yaml, /admin/_trans - интерфейс, `bin/console translation:extract app` - Сформировать файлы перевода (много пустых зачений), `bin/console translation:update --force en` - генерация переводов для языка)
* `composer require symfony/messenger` for events (`bin/console messenger:consume async -vv` - отправка евентов из бд через консоль)
* `composer require knplabs/knp-paginator-bundle` пагинация (knp_paginator.yaml)
* `composer require lexik/form-filter-bundle` фильтры
* `composer require --dev phpunit/phpunit symfony/test-pack` юнит тесты (.env.test)
* `composer req --dev symfony/panther` (не заработал) для функциональных тестов (`bin/console make:functional-test` \App\Tests\Functional\Controller\Main\DefaultControllerTest) (.env.panther)
* `composer req orm-fixtures --dev` фикстуры для тестов
* `composer req --dev dama/doctrine-test-bundle` обновление бд при каждом тесте (phpunit.xml.dist)
* `composer require friendsofphp/php-cs-fixer --dev` code style (.php-cs-fixer.dist.php vendor/bin/php-cs-fixer) (запуск - vendor/bin/php-cs-fixer fix src/ --diff)
* `composer require phpstan/phpstan --dev` code style (запуск - vendor/bin/phpstan analyse src/) (можно посмотреть --level и потом указать допустим --level=4 для более строгой проверки)
* 
* `npm run dev` run compile Webpack
* `yarn run watch` run watcher
* `npm install --save @fortawesome/fontawesome-free`
* `npm install bootstrap` (удалил)
* `npm install --save-dev sass sass-loader`
* `yarn add file-loader --dev`
* `yarn add jquery jquery.easing chart.js`
* `npm i @popperjs/core`
* `yarn add vue vue-loader vue-template-compiler`
* `yarn add vuex`
* `yarn add axios`
* `yarn add http-status-codes`
* `npm install foundation-emails` (for emails template (inky-extra))
* `npm install --save-dev eslint eslint-config-prettier eslint-plugin-prettier eslint-plugin-vue` code style (.eslintrc.js) (запуск -  node_modules/.bin/eslint assets/js/ --ext .js,.vue --fix)
* `npm i mdb-ui-kit` https://mdbootstrap.com
* `npm install materialize-css@next` https://materializecss.com (конфликты - удалил)
* `npm install --save virtual-select-plugin` https://sa-si-dev.github.io/virtual-select/ (селект с поиском)

> CHANGE TWIG FORM: in manager/config/packages/twig.yaml add `form_themes: ['bootstrap_4_layout.html.twig']`

> CHANGE DIR FOR DOCTRINE(DBAL) AND ENTITIES(ORM): `manager/config/packages/doctrine.yaml`

> ADD OWN TYPES FOR ORM: in annotation (manager/src/Model/User/Entity/User/User.php::Email) & create (manager/src/Model/User/Entity/User/EmailType.php) & register in (manager/config/packages/doctrine.yaml)

> CHENGE LOCALE LANG: manager/config/services.yaml || manager/config/packages/translation.yaml

> ADD NEW SERVICES: manager/config/services.yaml

> SECURITY: manager/config/packages/security.yaml



# SIMPHONY MEMO:

- `bin/console list` list of commands
- `docker-compose run --rm manager-php-cli ( make:entity | make:migration | make:migrations:diff | make:crud | make:user | make:auth )`

> CREATE CONTROLLER: `bin/console make:controller HomeController`

> CREATE ENTITY: `docker-compose run --rm manager-php-cli make:entity`

> CREATE MIGRATION: `bin/console make:migration`

> MAKE MIGRATION: `bin/console doctrine:migrations:migrate`

> MAKE FORM: `bin/console make:form`

> CREATE CRUD: `docker-compose run --rm manager-php-cli make:crud` - add some files and UI

> CHANGE TWIG FORM: in manager/config/packages/twig.yaml add `form_themes: ['bootstrap_4_layout.html.twig']`

> CREATE USER: `docker-compose run --rm manager-php-cli make:user` - add some files and register in manager/config/packages/security.yaml

> ADD FIELDS TO USER: `bin/console make:entity User`

> CREATE AUTH: `docker-compose run --rm manager-php-cli make:auth`

> MAKE REGISTRATION FORM: `docker-compose run --rm manager-php-cli make:registration-form`

> MAKE RESET PASSWORD: `docker-compose run --rm manager-php-cli make:reset-password`

> MAKE TRANSLATIONS: `bin/console translation:update --force ru`

> MAKE COMMAND: `bin/console make:command` (app:add-user)

> MAKE SUBSCRIBER: `bin/console make:subscriber`

> WRITE TEST CASES: in the tests/ folder && Run php bin/phpunit

> GET ENCODED PASSWORD: `bin/console security:encode-password`

> TWIG EXTENSIONS: `bin/console make:twig-extension`

## MAIL:

* If you want to send emails via a supported email provider, install the corresponding bridge. For
  instance, `composer require mailgun-mailer` for Mailgun.
* If you want to send emails asynchronously:
    1) Install the messenger component by running `composer require messenger`;
    2) Add 'Symfony\Component\Mailer\Messenger\SendEmailMessage': amqp to the config/packages/messenger.yaml file under
       framework.messenger.routing and replace amqp with your transport name of choice.

* Read the documentation at https://symfony.com/doc/master/mailer.html
<hr>
* можно прописать `MAILER_DSN` в `.env` и взять настройку из сервиса `https://mailtrap.io` для симфони нужной версии и письма для теста будут приходить все туда там же можно и ловить ссылки авторизации

# NOTES:

>Если вы не знаете, как работает команда, добавьте в конце команды `--help`, например `symfony console doctrine:schema:validate --help`, чтобы посмотреть по ней документацию, не выполняя ее.
>
>Создание базы данных (если она не была создана): `symfony console doctrine:database:create`
>
>Удаление базы данных: `symfony console doctrine:database:drop`
>
>Загрузка фикстур: `symfony console doctrine:fixtures:load`
>
>Выполнение sql-запроса: `symfony console doctrine:query:sql “...”`

<hr>

>Система защиты в среде symfony предоставляется бандлом symfony/security-bundle.

>Настройка аутентификации пользователя:

>Создание класса пользователя. `symfony console make:user` (укажем название класса, хранить ли в базе данных, уникальное поле отображения, используется ли хеширование пароля)

>Выполнить миграции, которые создадут таблицу с пользователями. `symfony console make:migration` и `symfony console doctrine:migrations:migrate`

>Добавить в `security.yaml`:
>
* раздел с провайдером пользователей `app_user_provider`. Основная задача которого является обновление данных пользователя из сессии.
>
* раздел с хешированием паролей `password_hashers`, в котором можно указать путь к классу сущности User и алгоритм шифрования.
>
* в раздел `firewalls` новый фаервол.

>Создать аутентификатор для фаервола и указать его у фаервола. Аутентификатор позволяет контролировать весь процесс обработки введенных данных пользователя при попытке авторизоваться.

>Создать входной маршрут, на который пользователь может отправлять данные, для попытки авторизоваться.

>Проставить проверки на роли пользователя, для доступа к защищенным разделам.

>Фаервол в симфони - система аутентификации, в которой указывается область действия, способ авторизации пользователей. На каждый запрос, может распространяться только один фаервол.

<hr>

>добавляем `remember_me` в файле `security.yaml`
> 
> Вы можете выполнить:
`bin/console config:dump-reference`  - стандартный конфиг (нужно выбрать бандл)
> 
> `bin/console config:dump-reference security` - стандартный конфиг для security с описанием опций
> 
> `bin/console debug:config`  - текущий конфиг (нужно выбрать бандл)
> 
> `bin/console debug:config security` - текущий конфиг для security (расширенный)
> 
> `bin/console debug:container` --parameters - зарегестрированные параметры
> 
> `bin/console debug:route`  - вывести все роуты включая API
>
> `bin/console debug:event-dispatcher`  - Список событий и их обработчиков

<hr>

# TESTS

> `php ./vendor/bin/phpunit --migrate-configuration` - создание бэкапа phpunit.xml.dist
> 
> `bin/console make:unit-test` - создание теста 
> - `\App\Tests\Utils\Generator\PasswordGeneratorTest` - для примера имя теста (структура)
> - `php ./vendor/bin/phpunit --testdox --group unit` - выполнение группы тестов группа прописывается в аннотации теста 
> 
> `bin/console make:test` - создание теста через опцию
> - `KernelTestCase` - выбрали для интеграционных
> - `\App\Tests\Integration\Security\Verifier\EmailVerifierTest` - имя
> - `php ./vendor/bin/phpunit --testdox --group integration` - выполнение группы тестов группа прописывается в аннотации теста 
> 
> `bin/run-tests.sh` - добавили файл
> 
> `sh ./bin/run-tests.sh` - запуск файла