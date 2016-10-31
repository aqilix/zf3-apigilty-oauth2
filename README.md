API Development using ZF3, Apigility with OAuth2 support
============================================

Requirements
------------

Please see the [composer.json](composer.json) file.

Installation
------------

### Via Composer (create-project)

You can use the `create-project` command from [Composer](http://getcomposer.org/)
to create the project in one go (you need to install [composer](https://getcomposer.org/doc/00-intro.md#downloading-the-composer-executable)):

```bash
$ curl -s https://getcomposer.org/installer | php -- --filename=composer
$ ./composer create-project -sdev aqilix/zf3-apigilty-oauth2 path/to/install
```

### Via Git (clone)

First, clone the repository:

```bash
# git clone https://github.com/aqilix/zf3-apigilty-oauth2.git # optionally, specify the directory in which to clone
$ cd path/to/install
```

I have prepare [docker-compose.yml](docker-compose.yml), so you can use `docker-compose` to build this application.

```
docker-compose up -d
```

Install dependencies via the container:

```
docker-compose run api composer install
```

Manipulate dev mode from the container:

```
docker-compose run api composer development-enable
docker-compose run api composer development-status
```

Creating Database

```
docker-compose run api composer development-db-create
```

Importing Data Fixtures

```
docker-compose run api composer development-db-data-fixture-import
```

Or if you don't want to use `Docker`, you need to use [Composer](https://getcomposer.org/) to install
dependencies. Assuming you already have Composer:

```bash
$ ./composer install
```

Configuration
------------

There are several configuration files in `config/autoload/*.local.php.dist` need to be configured to use this application. And please remove `.dist` extension on the files. Example:

```
mv config/autoload/local.php.dist config/autoload/local.php
```

### Database Configuration
For database configuration, the related files need to be configured are:

- [local.php.dist](blob/master/config/autoload/local.php.dist)
- [doctrine.local.php.dist](blob/master/config/autoload/doctrine.local.php.dist)
- [oauth2.local.php.dist](blob/master/config/autoload/oauth2.local.php.dist) (this file also used to configure **OAuth2**)

### Email Transport Configuration
To configure mail transport, please modify this file [mailtransport.local.php.dist](blob/master/config/autoload/mailtransport.local.php.dist). For this example, I use Mailgun you can use another mail service by configure `host`, `username` and `password`.

### Email Configuration
At the moment, there are 3 events send emails from this application. 

- Signup (Welcome Email)
- Account Activated
- Request Reset Password

You can configure the **From**, **Sender Name**, and **Subject** from this file [user.local.php.dist](blob/master/config/autoload/user.local.php.dist)

### Project Configuration
For project configuration, there are 2 sections configuration. Those are `sites` and `php_process`. I think you just need to configure `sites` section to adjust with url on your site. And `php_process` is purposed to configure `cli`.

### CORS Configuration
You can configure [zfr-cors](https://github.com/zf-fr/zfr-cors) on this file [zfr_cors.local.php.dist](blob/master/config/autoload/zfr_cors.local.php.dist)


Run Application
-------------------
After configure all configs, you can access this application from browser `http://localhost:8080` (I configure this container using port 8080). 

If you have set this as `dev mode` you will see **Apigility Admin** on this url `http://localhost:8080/apigility/ui#/`

API Testing
-------------
I use [Swagger](http://swagger.io/) for built in API Documentation. You can see it on `dev mode` by access this URL `http://localhost:8080/apigility/swagger`. You also can try the API Resources by using this Swagger
