Energy Project M2Miage Grenoble
------

**Install dependencies**

Step 1: Get Composer

```bash
$ curl -sS https://getcomposer.org/installer | php
$ mv composer.phar /usr/local/bin/composer
```

Step 2: Install vendors

```bash
$ composer install
```


**Setting up Permissions**

One common issue is that the app/cache and app/logs directories must be writable both by the web server and the command line user. On a UNIX system, if your web server user is different from your command line user, you can run the following commands just once in your project to ensure that permissions will be setup properly.

Note that not all web servers run as the user www-data as in the examples below. Instead, check which user your web server is being run as and use it in place of www-data.
```bash
    sudo setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX app/cache app/logs
    sudo setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
```

source

**Mysql**

Install `php5-mysql`
```bash
    $ sudo apt-get install php5-mysql
```

Create a mysql user `irise` with password `irise`

Create database and tables using propel command
```bash
    $ php app/console propel:database:create
    $ php app/console propel:build --insert-sql
```

Database schema is in `m2miageGre\energyProjectBundle\Resources/config`

Propel doc: http://symfony.com/fr/doc/current/book/propel.html

**Populate**

Irise's files are in: `app/Resources/irise/1000080-2000900-3009906.txt`

Create or update tables:

```bash
$ php app/console irise:createTable app/Resources/irise/1000080-2000900-3009906.txt
```
