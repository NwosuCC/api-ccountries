# API-Countries  [![Build Status](https://travis-ci.org/NwosuCC/api-ccountries.svg?branch=master)](https://travis-ci.org/NwosuCC/api-ccountries)

## Introduction
This API lets an authenticated User perform basic CRUD operations on Country model


## Overview
Just three simple steps to interact with the API:
- Create an account
- Authenticate and obtain an oauth access token
- Play with the Country model


### Setup and Configuration
-  Install dependencies including Laravel Passport and Laravel Auditing 
    ~~~
    composer install
    ~~~

-  Copy the .env.example file and rename to .env, then, set the variables
    - Your preferred APP_KEY_NAME for the to-be-generated tokens
    - Your Database credentials

-  Generate new App key and run migrations. Default Continents (7) will be seeded in the database.
    ~~~
    php artisan key:generate
    php artisan migrate
    ~~~

-  Next, install Laravel Passport to create the default Clients
    ~~~
       php artisan passport:install
    ~~~

-  (Optional) Add Laravel Passport service provider to Providers array in config/app.php
    ~~~
       Laravel\Passport\PassportServiceProvider::class,
    ~~~

-  Finally, seed the database with default Continents (7)
    ~~~
    php artisan db:seed
    ~~~


### Authentication
The API uses JWToken-based OAuth via Laravel Passport to authenticate each request.
A new token is generated and returned on each successful login

The token, as expected, gives each user exclusive MODIFY privileges to the resources the user created.


### Audit Trail
The Audit Trail monitors user activities on the Country model.
To view the audits, login with a test Admin account (use an email of the pattern admin@>anydomain<, i.e, 'admin' before the '@')


### Testing
The project includes some Unit and Feature tests.
To run the tests, you may want to create a separate test database.
The included phpunit.xml specifies a default name 'countries_testing' for the test database. Of course, you can change this according to your preference or need.
  ~~~
  <php>
      // ...
      <env name="DB_DATABASE" value="countries_testing"/>
  </php>
  ~~~

The tests are categorised into Suites and Groups to ease bug trace. You can run any of them like so:
~~~
"vendor/bin/phpunit" --group=Api
"vendor/bin/phpunit" --group=User
"vendor/bin/phpunit" --group=Country
"vendor/bin/phpunit" --testsuite=Unit --stop-on-defect
~~~

The argument **--stop-on-defect** will halt the test execution once any failure is encountered


### Documentation
For more information and sample calls, read the API documentation [here](https://documenter.getpostman.com/view/4155534/S11NMcFp)
