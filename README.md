<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# Atoz

> Simple project made with laravel

## Build Setup Installation
``` bash

#1 Create .env or Copy .env.example file
cp .env.example .env

#2 Setup composer
composer install

#3 Generate key .env
php artisan key:generate

#4 Adjust database connection credential locally  at .env, make sure mysql must be installed
DB_DATABASE=atoz
DB_USERNAME=root
DB_PASSWORD=

#5 Do migration of database & user seeder
php artisan migrate:fresh --seed

#6 Try register user or login with existing any user email from user seeder with password 'password'

#7 To check a transaction that has not been paid for 5 minutes from the date of transaction, do it manually by running the following command
php artisan atoz:checkexpiredorder
```


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
