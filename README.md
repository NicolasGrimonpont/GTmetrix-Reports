## Getting started

Install php dependencies

    composer install

Create database

    php artisan migrate

Re-create the databse removing all datas

    php artisan migrate:fresh

## Ressources

The frontend ressources are served from node.

* The loader file for styles ressources is located in "scss/styles.scss"
* The loader for javascript ressources is located in "js/scripts.js"
* The node modules are loaded from the vendor folders "ressources/plugin/js/vendor/" and "ressources/plugin/scss/vendor/"

All other folders and files located in "ressources/plugin" are used to customize bootstrap or create custom html blocks.

## Work with assets and ressources

The compilation of resources works with webpack and node manages the tasks. (we don't use gulp or grunt)

install frontend dependencies

    npm install

Run watch task (watch for changes on project and minify in the fly)

    npm run watch

Compile ressources for development

    npm run dev

Compile and minify ressources for production

    npm run prod

## Security

* Brute force
* Cross site scripting
* Password hash
* Encrypted API key
* IP for check access
* Email company validation
* Send email validation
* Settings protected by password after inactivity
