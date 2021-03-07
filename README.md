# About This Project

    This is a project for a blog using laravel v8 with jetstream and inertia.js with Vue2
    stack.This project is a Blog and CMS with features listed below.

## Features

    [*] Posts
    [] Permissons & Roles
    [] Video & Image
    [] Comments
    [] Rating

## How To Use

    - Clone This repo into a directory of your choosing
    - Copy and rename .env.example file to .env
    - Edit Credentials on .env file [APP_NAME,DB_NAME, DB_USERNAME, DB_PASSWORD] and save
    - Run these commands
        1) php artisan key:generate
        2) composer install
        3) php artisan migrate --seed
        4) npm install
        5) npm run dev
        6) create a postman collection using mablog.postman_collection.json (for api calls)
        7) php artisan storage:link

    - Run the app using
        *) php artisan serve
        1) php artisan r:l (see the routes/urls)

    - Commands for development enviorment
        1) php artisan serve  (run development server)
        2) npm run watch (for front-end development enviorment ) #with this browser auto compiles and refreshes on change of css/js localhost:3000

#### Requirements

    php >= 7.4
        - imagick PHP extension (required by intervention) 
        OR
        - gd PHP extension
