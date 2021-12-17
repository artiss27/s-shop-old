# memo:
| bash                                               | description                                       |
|----------------------------------------------------|---------------------------------------------------|
| `bin/console list`                                 | list of commands                                  |
| `--help`                                           | see command without run it                        |
| `symfony console make:migration`                   | create migrations                                 |
| `symfony console doctrine:migrations:migrate`      | run migrations                                    |
| `symfony console doctrine:migrations:migrate prev` | undo last migrations                              |
| `symfony console doctrine:database:create`         | create DB                                         |
| `symfony console doctrine:database:drop`           | drop DB                                           |
| `symfony console doctrine:fixtures:load`           | load fixtures                                     |
| `symfony console doctrine:query:sql "...";`        | run query                                         |
| `bin/console config:dump-reference`                | стандартный конфиг (нужно выбрать бандл)          |
| `bin/console config:dump-reference security`       | стандартный конфиг для security с описанием опций |
| `bin/console debug:config`                         | текущий конфиг (нужно выбрать бандл)              |
| `bin/console debug:config security`                | текущий конфиг для security (расширенный)         |
| `bin/console debug:container --parameters`         | зарегестрированные параметры                      |
| `bin/console debug:route`                          | вывести все роуты включая API                     |
| `bin/console debug:event-dispatcher`               | Список событий и их обработчиков                  |

# command
| command                | bash                                                             |
|------------------------|------------------------------------------------------------------|
| CREATE CONTROLLER      | `bin/console make:controller HomeController`                     |
| CREATE ENTITY          | `docker-compose run --rm manager-php-cli make:entity`            |
| CREATE MIGRATION       | `bin/console make:migration`                                     |
| MAKE MIGRATION         | `bin/console doctrine:migrations:migrate`                        |
| MAKE FORM              | `bin/console make:form`                                          |
| CREATE CRUD            | `docker-compose run --rm manager-php-cli make:crud`              |
| CREATE USER            | `docker-compose run --rm manager-php-cli make:user`              |
| ADD FIELDS TO USER     | `bin/console make:entity User`                                   |
| CREATE AUTH            | `docker-compose run --rm manager-php-cli make:auth`              |
| MAKE REGISTRATION FORM | `docker-compose run --rm manager-php-cli make:registration-form` |
| MAKE RESET PASSWORD    | `docker-compose run --rm manager-php-cli make:reset-password`    |
| MAKE TRANSLATIONS      | `bin/console translation:update --force ru`                      |
| MAKE COMMAND           | `bin/console make:command` (app:add-user)                        |
| MAKE SUBSCRIBER        | `bin/console make:subscriber`                                    |
| WRITE TEST CASES       | in the tests/ folder && Run `php bin/phpunit`                    |
| GET ENCODED PASSWORD   | `bin/console security:encode-password`                           |
| TWIG EXTENSIONS        | `bin/console make:twig-extension`                                |

# composer require
/** TODO:
`composer require api`
`composer require php-translation/symfony-bundle`
**/

| bush                                                       | description                | config                                                                       |
|------------------------------------------------------------|----------------------------|------------------------------------------------------------------------------|
| `composer require stof/doctrine-extensions-bundle`         | Slug                       | config/packages/stof_doctrine_extensions.yaml                                |
| `composer require symfony/doctrine-messenger`              | for doctrine               |                                                                              |
| `composer require api`                                     | Api                        |                                                                              |
| `composer require symfony/webpack-encore-bundle`           | Webpack                    | config/packages/webpack_encore.yaml                                          |
| `composer require symfony/messenger`                       | events                     | config/packages/messenger.yaml                                               |
| `composer require symfonycasts/reset-password-bundle`      | reset password             | config/packages/reset_password.yaml                                          |
| `composer require symfonycasts/verify-email-bundle`        | verify Emails              |                                                                              |
| `composer require knpuniversity/oauth2-client-bundle`      | auth from google and other | config/packages/knpu_oauth2_client.yaml                                      |
| `composer require league/oauth2-facebook`                  | auth from facebook         |                                                                              |
| `composer require league/oauth2-google`                    | auth from google           |                                                                              |
| `composer require imagine/imagine`                         | Images                     |                                                                              |
| `composer require twig/intl-extra`                         | Currency                   |                                                                              |
| `composer require twig/inky-extra`                         | for emails templates       |                                                                              |
| `composer require twig/cssinliner-extra`                   | Css inliner (for emails)   |                                                                              |
| `composer require php-translation/symfony-bundle`          | translation                | php_translation.yaml, php_translation.yaml, config/packages/translation.yaml |
| `composer require knplabs/knp-paginator-bundle`            | pagination                 | knp_paginator.yaml                                                           |
| `composer require lexik/form-filter-bundle`                | filters                    |                                                                              |
| `composer require --dev phpunit/phpunit symfony/test-pack` | unit tests                 | .env.test                                                                    |
| `composer req orm-fixtures --dev`                          | fixtures                   |                                                                              |
| `composer req --dev dama/doctrine-test-bundle`             | update DB from each test   | phpunit.xml.dist                                                             |
| `composer require friendsofphp/php-cs-fixer --dev`         | code style                 | .php-cs-fixer.dist.php                                                       |
| `composer require phpstan/phpstan --dev`                   | code style                 |                                                                              |

P.S. 
1. php-translation - `/admin/_trans` - интерфейс, `bin/console translation:extract app` - Сформировать файлы перевода (много пустых зачений), `bin/console translation:update --force en` - генерация переводов для языка)
2. messenger - `bin/console messenger:consume async -vv` - отправка евентов из бд через консоль
3. php-cs-fixer - запуск - vendor/bin/php-cs-fixer fix src/ --diff
4. phpstan - запуск - vendor/bin/phpstan analyse src/) (можно посмотреть --level и потом указать допустим --level=4 для более строгой проверки

# npm
| bash                                                                                            | description                  |
|-------------------------------------------------------------------------------------------------|------------------------------|
| `npm run dev`                                                                                   | run compile Webpack          |
| `yarn run watch`                                                                                | run watcher                  |
|                                                                                                 |                              |
| `npm install --save @fortawesome/fontawesome-free`                                              | fontawesome                  |
| `npm install bootstrap`                                                                         | bootstrap                    |
| `npm install --save-dev sass sass-loader`                                                       | sass                         | 
| `yarn add file-loader --dev`                                                                    | load files - Webpack         |
| `yarn add vue vue-loader vue-template-compiler`                                                 | Vue                          |
| `yarn add vuex`                                                                                 | Vuex                         |
| `yarn add axios`                                                                                | Axios                        |
| `yarn add http-status-codes`                                                                    | code statuses                |
| `npm install foundation-emails`                                                                 | emails template (inky-extra) |
| `npm install --save-dev eslint eslint-config-prettier eslint-plugin-prettier eslint-plugin-vue` | code style (.eslintrc.js)    |
| `npm i mdb-ui-kit`                                                                              | https://mdbootstrap.com      |

P.S.
1. запуск -  node_modules/.bin/eslint assets/js/ --ext .js,.vue --fix