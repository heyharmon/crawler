# Crawler

A Laravel powered API that helps you queue webpage crawl jobs. This app accepts a url and queues a job for that url. The job itself requests a scrape from an endpoint such as a Cheerio/Puppeteer instance and expects a response containing the content and links found on the page. For each link found, a job is queued. Each link that matches the original url host will be stored. 


## Get started

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/1241377-f6b7d178-8fc8-4064-b4b8-29161cd8b17a?action=collection%2Ffork&collection-url=entityId%3D1241377-f6b7d178-8fc8-4064-b4b8-29161cd8b17a%26entityType%3Dcollection%26workspaceId%3Da8e39e6f-193c-49f5-8474-38b7e2bfe224)

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
