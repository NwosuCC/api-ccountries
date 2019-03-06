# API-Countries

## Introduction
This API lets an authenticated User perform basic CRUD operations on Country model


## Overview
Just three simple steps to interact with the API:
- Create an account
- Authenticate and obtain an access token
- Play with the Country model


### Setup and Configuration
-  Install dependencies including Laravel Passport and Laravel Auditing 
    ~~~
    composer install
    ~~~

-  Copy the .env.example file and rename to .env, then, set the variables
    - Your preferred APP_KEY_NAME for the to-be-generated tokens
    - Your Database credentials

-  Publish the Laravel Auditing provider to create the config and migration files it needs 
    ~~~
     php artisan vendor:publish --provider "OwenIt\Auditing\AuditingServiceProvider" --tag="config"
     php artisan vendor:publish --provider "OwenIt\Auditing\AuditingServiceProvider" --tag="migrations"
    ~~~

-  Generate new App key and run migrations  
    ~~~
    php artisan key:generate
    php artisan migrate
    ~~~


### Authentication
The API uses JWToken-based OAuth via Laravel Passport to authenticate each request.
A new token is generated and returned on each successful login

The token, as expected, gives each user exclusive MODIFY privileges to the resources the user created


### Audit Trail
The Audit Trail monitors user activities on the Country model.
To view the audits, login with a test Admin account (use an email of the pattern admin@<any-domain>, i.e, 'admin' before the '@')


### Documentation
For more information and sample calls, read the API documentation [here](https://documenter.getpostman.com/view/4155534/S11NMcFp)
