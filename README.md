Energy Project M2Miage Grenoble
------

**Dependencies with Composer**

Install Composer

```bash
$ curl -sS https://getcomposer.org/installer | php
$ mv composer.phar /usr/local/bin/composer
```

Install vendors

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

Create a mysql user `irise` with password `irise` and grand privilege

Create database and tables using propel command
```bash
    $ php app/console propel:database:create
    $ php app/console propel:build --insert-sql
```

Database schema is in `m2miageGre\energyProjectBundle\Resources/config`

Propel doc: http://symfony.com/fr/doc/current/book/propel.html

**Populate**

Irise's files are in: `app/Resources/irise/`

Create or update tables:

```bash
    $ php app/console irise:createTable filename v1|v2|v3|v4 [threshold]

    $ php app/console irise:createTable app/Resources/irise/1000080-2000900-3009906.txt v1
    $ php app/console irise:createTable app/Resources/irise/1000080-2000900-3009906.txt v4 3
```

**apache**

```bash
    #activate rewrite module
    $ sudo a2enmod rewrite
    # copy apache conf
    $ cp app/Resources/apache/iriseProject.conf /etc/apache2/site-available
    #activate virtual host
    $ sudo a2ensite iriseProject.conf
    #restart apache
    $ sudo service apache2 restart
```

Default port is *11000*
**API**

path: `/{version}/{houseId}/{year}_{month}_{day}.json`

Example:
    `http://127.0.0.1:11000/v3/2000903/1998_02_07.json`
    
Return:

```json
{
    "id": "2000903",
    "date": "1998-02-07",
    "capteurs": [
        {
            "name": "Halogen lamp 1 ()",
            "mesures": [
                {
                    "state": 0,
                    "energy": 66,
                    "timestamp": "1998-02-07 18:30:00"
                },
                {
                    "state": 0,
                    "energy": 75,
                    "timestamp": "1998-02-07 18:40:00"
                },
                {
                    "state": 0,
                    "energy": 69,
                    "timestamp": "1998-02-07 19:40:00"
                },
                {
                    "state": 0,
                    "energy": 50,
                    "timestamp": "1998-02-07 19:50:00"
                },
                {
                    "state": 0,
                    "energy": 51,
                    "timestamp": "1998-02-07 20:10:00"
                }
            ],
            "id": 2,
            "version": "v3",
            "prevMesure": {
                "state": 0,
                "energy": 0,
                "timestamp": "1998-02-06 23:30:00"
            }
        },
        {
            "name": "Halogen lamp 4 ()",
            "mesures": [
                {
                    "state": 0,
                    "energy": 66,
                    "timestamp": "1998-02-07 17:40:00"
                },
                {
                    "state": 0,
                    "energy": 75,
                    "timestamp": "1998-02-07 17:50:00"
                },
                {
                    "state": 0,
                    "energy": 73,
                    "timestamp": "1998-02-07 18:00:00"
                },
                {
                    "state": 0,
                    "energy": 74,
                    "timestamp": "1998-02-07 18:10:00"
                },
                {
                    "state": 0,
                    "energy": 75,
                    "timestamp": "1998-02-07 18:20:00"
                },
                {
                    "state": 0,
                    "energy": 73,
                    "timestamp": "1998-02-07 18:30:00"
                }
            ],
            "id": 6,
            "version": "v3",
            "prevMesure": {
                "state": 0,
                "energy": 0,
                "timestamp": "1998-02-02 23:00:00"
            }
        },
        {
            "name": "Chest freezer (273l)",
            "mesures": [
                {
                    "state": 0,
                    "energy": 0,
                    "timestamp": "1998-02-07 00:00:00"
                },
                {
                    "state": 0,
                    "energy": 7,
                    "timestamp": "1998-02-07 00:20:00"
                },
                {
                    "state": 0,
                    "energy": 8,
                    "timestamp": "1998-02-07 00:30:00"
                },
                {
                    "state": 0,
                    "energy": 0,
                    "timestamp": "1998-02-07 00:40:00"
                },
                {
                    "state": 0,
                    "energy": 15,
                    "timestamp": "1998-02-07 01:00:00"
                },
                {
                    "state": 0,
                    "energy": 0,
                    "timestamp": "1998-02-07 01:10:00"
                },
                {
                    "state": 0,
                    "energy": 9,
                    "timestamp": "1998-02-07 01:30:00"
                },
                {
                    "state": 0,
                    "energy": 5,
                    "timestamp": "1998-02-07 01:40:00"
                },
                {
                    "state": 0,
                    "energy": 0,
                    "timestamp": "1998-02-07 01:50:00"
                },
                {
                    "state": 0,
                    "energy": 15,
                    "timestamp": "1998-02-07 02:10:00"
                },
                {
                    "state": 0,
                    "energy": 0,
                    "timestamp": "1998-02-07 02:20:00"
                },
                {
                    "state": 0,
                    "energy": 10,
                    "timestamp": "1998-02-07 02:40:00"
                },
                {
                    "state": 0,
                    "energy": 5,
                    "timestamp": "1998-02-07 02:50:00"
                },
                {
                    "state": 0,
                    "energy": 0,
                    "timestamp": "1998-02-07 03:00:00"
                },
                {
                    "state": 0,
                    "energy": 14,
                    "timestamp": "1998-02-07 03:20:00"
                },
                {
                    "state": 0,
                    "energy": 0,
                    "timestamp": "1998-02-07 03:30:00"
                },
                {
                    "state": 0,
                    "energy": 9,
                    "timestamp": "1998-02-07 03:50:00"
                },
                {
                    "state": 0,
                    "energy": 6,
                    "timestamp": "1998-02-07 04:00:00"
                },
                {
                    "state": 0,
                    "energy": 0,
                    "timestamp": "1998-02-07 04:10:00"
                },
                {
                    "state": 0,
                    "energy": 15,
                    "timestamp": "1998-02-07 04:30:00"
                },
                {
                    "state": 0,
                    "energy": 0,
                    "timestamp": "1998-02-07 04:40:00"
                },
                {
                    "state": 0,
                    "energy": 5,
                    "timestamp": "1998-02-07 05:00:00"
                }
            ],
            "id": 9,
            "version": "v3",
            "prevMesure": {
                "state": 0,
                "energy": 15,
                "timestamp": "1998-02-06 23:50:00"
            }
        }
    ]

}
```
    

**Usefull links and ressources**

- [Éviter les fuites mémoire avec Propel](http://www.pmsipilot.org/2012/01/13/eviter-les-fuites-memoire-avec-propel/)
- [Garbage Collector et consommation mémoire](http://blog.pascal-martin.fr/post/php-5.3-garbage-collector-vs-consommation-memoire)
- [Databases and Propel](http://symfony.com/doc/current/book/propel.html)
