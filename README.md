ZF3, Apigility, OAuth2  Skeleton Application
=================================

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

At this point, you need to use [Composer](https://getcomposer.org/) to install
dependencies. Assuming you already have Composer:

```bash
$ ./composer install
```

### Database Configuration
After all dependencies installed, need to prepare database for **Authentication & Authorization** using **OAuth2**.

Assuming you have create database `(eg: zf3_apigility )`. Please import these tables into database

```
$ mysql -h <dbhost> -u <dbuser> -p<password> zf3_apigility < vendor/zfcampus/zf-oauth2/data/db_oauth2.sql
```

### Importing Database Using Docker
If you using docker based on [docker-compose.yml](docker-compose.yml) on this repository you can use this command

```bash
$ docker exec -i zf3apigilityoauth2_db_1 mysql -h localhost -u zf3 -pzf3 zf3_apigility < vendor/zfcampus/zf-oauth2/data/db_oauth2.sql
```

* `zf3apigilityoauth2_db_1` is container name
* database credential like `zf3` and `zf3_apigility` based on configuration on [docker-compose.yml](docker-compose.yml) 


### Adjust database configuration by completing configuration file

```bash
$ cp config/autoload/local.php.dist config/autoload/local.php
```

Open `config/autoload/local.php` file and adjust `adapters` section (`database`, `username`, `password` and `DSN` also). It used **PDO_Mysql** by default

```
'adapters' => [
    'zf3_mysql' => [
         'database' => 'zf3_apigility',
         'driver' => 'PDO_Mysql',
         'hostname' => 'localhost',
         'username' => 'zf3',
         'password' => 'zf3',
         'port' => '3306',
         'dsn' => 'mysql:dbname=zf3_apigility;host=localhost',
    ],
],
```

And don't forget `authentication` section too

```
'authentication' => [
     'adapters' => [
         'oauth2 pdo' => [
         'adapter' => \ZF\MvcAuth\Authentication\OAuth2Adapter::class,
               'storage' => [
                    'adapter' => \pdo::class,
                    'dsn' => 'mysql:dbname=zf3_apigility;host=localhost',
                    'route' => '/oauth',
                    'username' => 'zf3',
                    'password' => 'zf3',
               ],
          ],
     ],
],
```

### Testing The API Authentication
We have created database for **Authentication**, and now we need to create a `client_id` and `client_secret` for testing the *API Authentication*

* Encrypt `client_secret` for value in database 
   ```
   vendor/bin/bcrypt.php  123456
   ```

  It will give result like this
```
$2y$10$iTyeoZyu/dvDC2QQSTdWee/WpG0L/QPaVaJDTf4B/BvxjHRM4TQ2q
```

* After encrypt the `client_secret`, add a new `client_id` with encrypted `client_secret` in the database using the following SQL statement:

```SQL
INSERT INTO oauth_clients (
    client_id,
    client_secret,
    redirect_uri)
VALUES (
    "testclient",
    "$2y$10$iTyeoZyu/dvDC2QQSTdWee/WpG0L/QPaVaJDTf4B/BvxjHRM4TQ2q",
    "/oauth/receivecode"
);
```

If you are using **Docker**, you need to use MySQL Client on docker container, then run sql code above 

```
docker exec -it zf3apigilityoauth2_db_1 mysql -h localhost -u zf3 -pzf3 zf3_apigility

```

### Run Application
* Run application (just use **PHP Web Server** for testing). This app will use port **8080**. But if you are using **Docker** don't need to run composer, because apache serve it.

```
$ ./composer serve
```


* Send `client_id` & `client_secret` for requesting token

```
curl -X POST -d "client_id=testclient&client_secret=123456&grant_type=client_credentials" http://localhost:8080/oauth
```

It will give token on it's response

```
{
    "access_token": "8bfebd0c55212e2efc91b367ff428acdebb89a62",
    "expires_in": 3600,
    "scope": null,
    "token_type": "Bearer"
}
```

* Use this token for requesting a resource

```
curl -X GET http://localhost:8080/oauth/resource --header "Authorization:Bearer 8bfebd0c55212e2efc91b367ff428acdebb89a62" 
```

It will give success response

```
{
    "message": "You accessed my APIs!",
    "success": true
}
```

And please try to request same resource without using token

```
curl -X GET http://localhost:8080/oauth/resource
```

It will give **Unauthorized** error response

```
{
    "detail": null,
    "status": 401,
    "title": "Unauthorized",
    "type": "http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html"
}
```

If you get same result from your installation, it mean this repository set up correctly.


### Development Mode

Once you have the basic installation, you need to put it in development mode:

```bash
cd path/to/install
php public/index.php development enable # put the skeleton in development mode
```

Now, fire it up! Do one of the following:

- Create a vhost in your web server that points the DocumentRoot to the
  `public/` directory of the project
- Fire up the built-in web server in PHP(**note**: do not use this for
  production!)

In the latter case, do the following:

```bash
$ cd path/to/install
$ php -S 0.0.0.0:8080 -ddisplay_errors=0 -t public public/index.php
# OR use the composer alias:
$ composer serve
```

You can then visit the site at http://localhost:8080/ - which will bring up a
welcome page and the ability to visit the dashboard in order to create and
inspect your APIs.

### NOTE ABOUT USING THE PHP BUILT-IN WEB SERVER

PHP's built-in web server did not start supporting the `PATCH` HTTP method until
5.4.8. Since the admin API makes use of this HTTP method, you must use a version
&gt;= 5.4.8 when using the built-in web server.

### NOTE ABOUT USING APACHE

Apache forbids the character sequences `%2F` and `%5C` in URI paths. However, the Apigility Admin
API uses these characters for a number of service endpoints. As such, if you wish to use the
Admin UI and/or Admin API with Apache, you will need to configure your Apache vhost/project to
allow encoded slashes:

```apacheconf
AllowEncodedSlashes On
```

This change will need to be made in your server's vhost file (it cannot be added to `.htaccess`).

### NOTE ABOUT OPCACHE

**Disable all opcode caches when running the admin!**

The admin cannot and will not run correctly when an opcode cache, such as APC or
OpCache, is enabled. Apigility does not use a database to store configuration;
instead, it uses PHP configuration files. Opcode caches will cache these files
on first load, leading to inconsistencies as you write to them, and will
typically lead to a state where the admin API and code become unusable.

The admin is a **development** tool, and intended for use a development
environment. As such, you should likely disable opcode caching, regardless.

When you are ready to deploy your API to **production**, however, you can
disable development mode, thus disabling the admin interface, and safely run an
opcode cache again. Doing so is recommended for production due to the tremendous
performance benefits opcode caches provide.

### NOTE ABOUT DISPLAY_ERRORS

The `display_errors` `php.ini` setting is useful in development to understand what warnings,
notices, and error conditions are affecting your application. However, they cause problems for APIs:
APIs are typically a specific serialization format, and error reporting is usually in either plain
text, or, with extensions like XDebug, in HTML. This breaks the response payload, making it unusable
by clients.

For this reason, we recommend disabling `display_errors` when using the Apigility admin interface.
This can be done using the `-ddisplay_errors=0` flag when using the built-in PHP web server, or you
can set it in your virtual host or server definition. If you disable it, make sure you have
reasonable error log settings in place. For the built-in PHP web server, errors will be reported in
the console itself; otherwise, ensure you have an error log file specified in your configuration.

`display_errors` should *never* be enabled in production, regardless.


QA Tools
--------

The skeleton does not come with any QA tooling by default, but does ship with
configuration for each of:

- [phpcs](https://github.com/squizlabs/php_codesniffer)
- [phpunit](https://phpunit.de)

Additionally, it comes with some basic tests for the shipped
`Application\Controller\IndexController`.

If you want to add these QA tools, execute the following:

```bash
$ composer require --dev phpunit/phpunit squizlabs/php_codesniffer zendframework/zend-test
```

We provide aliases for each of these tools in the Composer configuration:

```bash
# Run CS checks:
$ composer cs-check
# Fix CS errors:
$ composer cs-fix
# Run PHPUnit tests:
$ composer test
```
