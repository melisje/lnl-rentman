#!/bin/bash

git pull
composer install
composer dump-autoload
php artisan migrate
php artisan db:seed --class=RentmanSeeder


php artisan queue:restart
