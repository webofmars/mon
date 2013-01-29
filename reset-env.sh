#!/bin/sh

php app/console cache:clear --no-warmup --env=dev
php app/console cache:clear --no-warmup --env=prod
php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load -n
php app/console assets:install
php app/console assetic:dump web
php app/console mon:scheduler:update 5 1
