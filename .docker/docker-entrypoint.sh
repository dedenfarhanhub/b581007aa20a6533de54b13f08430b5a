#!/bin/sh

echo "Running composer ..."
composer dump-autoload

echo "Running migration ..."
php /var/www/html/src/Migrations/CreateEmailLogsTable.php
php /var/www/html/src/Migrations/CreateOauthAccessTokensTable.php
php /var/www/html/src/Migrations/CreateOauthClientsTable.php
php /var/www/html/src/Migrations/CreateUsersTable.php
php /var/www/html/src/Seeders/SeedOAuthClients.php

echo "Start Queue"
php /var/www/html/script/run_worker.php

apache2-foreground