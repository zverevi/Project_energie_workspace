Symfony 2 conf
------

https://gist.github.com/luap/ef59ac49622ea171d430#file-config-symfon2


Install `php5-mysql`
`
sudo apt-get install php5-mysql
`

Create a mysql user `irise` with password `irise`

Create database and tables using propel command
`
php app/console propel:database:create
php app/console propel:build --insert-sql
`

Database schema is in `m2miageGre\energyProjectBundle\Resources/config`