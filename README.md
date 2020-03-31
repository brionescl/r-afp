# R-AFP

## Docker

If you have docker and docker-composer installed, just run the containers:

```
docker/up
```

To shut down the conbtainers, simply:

```
docker/down
```

### Development

First of all, you must copy the configuration of the docker-compose.yml.default file to docker-compose.yml

```
cp docker/docker-compose.yml.default docker/docker-compose.yml
```

Then you must run the containers

```
docker/up
```

The next step is to configure the development environment

```
cp .env.example .env
```

Install the dependencies

```
docker/composer install
```

Configure your database connection (edit .env file)

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=root
DB_PASSWORD=your_mysql_root_password
```

Finally, it is necessary to execute the migrations and optionally the seeder.

```
docker/artisan migrate
```

```
docker/artisan db:seeder
```

The following scripts can help the development process

#### Install dependencies

```
docker/composer install
```

#### Run migrations

```
docker/artisan migrate
```

#### Seed database

```
docker/artisan db:seed
```

#### Run tests

Create a testing database
```
touch database/test.sqlite
```

```
docker/test
```

## Server requirements

**Notice:** All server requirements are solved by docker images

-   PHP >= 7.2.0
-   BCMath PHP Extension
-   Ctype PHP Extension
-   JSON PHP Extension
-   Mbstring PHP Extension
-   OpenSSL PHP Extension
-   PDO PHP Extension
-   Tokenizer PHP Extension
-   XML PHP Extension

## Database

-   Mysql >= 5.7

## Development (without docker)

-   Load dependencies

```bash
composer install
```

-   Run development server (requires PHP > 7.2)

```bash
php artisan serve
```
