# laravel-11-docs-swagger

In this repository, we discuss an example of using swagger in Laravel 11. This zippository is a simple project with Laravel 11, which is a management panel along with a simple blog web service with CRUD operations.

## See example

You can see an example of the use of anodes in `/example/*`

## Installtion example

First enter the following directory

`example/laravel-admin-blog-api1`

Then install dependencies using Composer

```shell
$ composer install
```

If necessary, you can upgrade the dependencies

```shell
$ composer upgrade
```

Do the initial configurations of the project
create `.env` file from `.env.example`

Then run the following command

```shell
$ php artisan key:generate
```

Then configure the database in `.env` file and create tables and database

```shell
$ php artisan migrate
```

Run the local server with the following command

```shell
$ php artisan serve
```