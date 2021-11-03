# R-AFP

![Continuous integration](https://github.com/brionescl/r-afp/workflows/Continuous%20integration/badge.svg)
[![codecov](https://codecov.io/gh/brionescl/r-afp/branch/master/graph/badge.svg)](https://codecov.io/gh/brionescl/r-afp)

## Docker

First of all, you must copy the configuration of the docker-compose.yml.default file to docker-compose.yml

```
cp docker-compose.yml.default docker-compose.yml
```

If you have docker and docker-composer installed, just run the containers:

```
docker-compose up -d
```

To shut down the conbtainers, simply:

```
docker-compose stop
```

The next step is to configure the development environment

```
cp .env.example .env
```

Install the dependencies

```
docker-compose exec app composer install
```

## Database

Configure your database connection (edit .env file)

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=root
DB_PASSWORD=your_mysql_root_password
```

Generate encryption key and Configure cache

```
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan config:cache
```

Finally, it is necessary to execute the migrations and optionally the seeder.

```
docker-compose exec app php artisan migrate
```

```
docker-compose exec app php artisan db:seed
```

## Run tests

Execute phpunit

```
docker-compose exec app php -n vendor/bin/phpunit --testdox
```

Optionally, we can create a coverage documentation in HTML format

```
docker-compose exec app php -dxdebug.mode=coverage vendor/bin/phpunit --coverage-text --coverage-html ./public/test-coverage
```
