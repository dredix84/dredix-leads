#!/bin/bash
echo "Installing Project PHP Dependencies"
composer install --no-interaction

#echo "Migrating Database"
#php artisan migrate

#echo "Seeding Database"
#if [[ $APP_ENV = "dev" ]]; then php artisan db:seed; fi

#curl -X POST -H 'Content-type: application/json' --data "{'text':'*$APP_NAME* deployment completed on *$APP_ENV*'}" https://hooks.slack.com/services/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
