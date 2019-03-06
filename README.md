# API-Countries

## Introduction
This API lets an authenticated User perform basic CRUD operations on Country model


## Overview
Just three simple steps:
- Create an account
- Authenticate and obtain an access token
- Play with the Country model

### Setup and Configuration
-  Install Laravel Passport
    ~~~
    composer require laravel/passport
    ~~~

- Install Laravel Auditing package

### Authentication
The API uses JWToken-based OAuth authentication via Laravel Passport

As expected, each user has exclusive MODIFY privileges to the resources the user created


### Audit Trail
There is an additional Audit Trail to monitor user activities on the Country model


### Error Codes
401 - Username and/or Password is incorrect
403 - Access/action unauthorised
404 - Requested resource not found

