# GitHub repository comparison tool

This tool will help you compare two repositories using a simple API

## How to run a project

If you are using a docker you can use a ready-made image

```
docker build -t gh-compare .
docker run -p 80:80 gh-compare
```

Next go to: ``http://localhost/api/doc``

Additionally, you can use the [symfony local web server](https://symfony.com/doc/current/setup/symfony_server.html) and just type only

```
symfony server:start
```

Next go to: ``https://127.0.0.1:8000``

## Example request

GET ``/api/compare``

**Params**

| In      | Type   | Name   | Description |
| ------- | -------| ------ | ------------|
| query   | string | first  | name or url |
| query   | string | second | name or url |

```
http://localhost/api/compare?first=symfony/symfony&second=laravel/laravel
```

```javascript
{
    "first": {
        "name": "symfony",
        "url": "https://api.github.com/repos/symfony/symfony",
        "stars_count": 26224,
        "forks_count": 8450,
        "watchers_count": 26224,
        "latest_release_date": "2021-12-09T13:47:16+00:00",
        "open_pull_request_count": 192,
        "closed_pull_request_count": 26717
    },
    "second": {
        "name": "laravel",
        "url": "https://api.github.com/repos/laravel/laravel",
        "stars_count": 67698,
        "forks_count": 21921,
        "watchers_count": 67698,
        "latest_release_date": "2021-12-07T16:10:52+00:00",
        "open_pull_request_count": 2,
        "closed_pull_request_count": 4008
    }
}

```

## How run test

```
php8.0 bin/phpunit
```

You are likely to exceed the limits
