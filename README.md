# Crawler API

A Laravel powered API that generates website craw jobs using [Apify](https://my.apify.com/). This API accepts a domain to be crawled and stores the resulting pages from Apify.


## Get started

[WIP] - API usage instructions coming soon.

### Authentication

[WIP] - API authentication instructions coming soon.

### Endpoints

| Verb | Path | Method |
|--|--|--|
| GET | [/api/websites]() | Index all websites |
| GET | [/api/websites/{domain}]() | Show a website by domain |


## Install API locally

**Step 1:** Clone this repository

```
git clone https://github.com/heyharmon/crawler-api.git
```

**Step 2:** Change directory into application

```
cd crawler-api
```

**Step 3:** Install dependencies

```
composer install
```

**Step 4:** Copy **env.example** to **.env** and setup environment
> Example database connection:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crawler-api
DB_USERNAME=root
DB_PASSWORD=
```

**Step 5:** Generate unique app key

```php
php artisan key:generate
```

**Step 5:** Migrate database

```php
php artisan migrate
```

**Step 7:** Serve application

> Using Artisan CLI:
```php
php artisan serve
```
`http://127.0.0.1:8000`

> Using Valet:
```
valet link crawler-api
```
`http://crawler-api.test`
