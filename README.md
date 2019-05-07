# Router

![PHP Support](https://img.shields.io/badge/php%20tested-5.6-brightgreen.svg)
![PHP Support](https://img.shields.io/badge/php%20tested-7.1-brightgreen.svg)
![Dependencies](https://img.shields.io/badge/dependencies-none-brightgreen.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)
![Travis](https://api.travis-ci.org/GonistLelatel/aphp_router.svg?branch=master)

## Introduction

`aphp/router` is a lightweight and simple object oriented PHP Router.

## Installation
PHP5.6 , PHP7.0+

`composer require aphp/router`

## Features

* Supports GET, POST, PUT, DELETE, OPTIONS, PATCH and HEAD request methods.
* Routing shorthands such as get(), post(), put(), …
* Dynamic Route Patterns: PCRE-based.
* Route groups.
* Supports X-HTTP-Method-Override header.
* 404 handling.

## Syntax
Intialization
```php
$router = new Router();

$router->set404(function() use ($router) {
	header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
	// ...
});

// ...

$router->run();
```
Shorthands for single request methods are provided:
```php
$router->get('pattern', function() { /* ... */ });
$router->post('pattern', function() { /* ... */ });
$router->put('pattern', function() { /* ... */ });
$router->delete('pattern', function() { /* ... */ });
$router->options('pattern', function() { /* ... */ });
$router->patch('pattern', function() { /* ... */ });
```
PCRE-based Route Patterns

* `/movies/(\d+)` = One or more digits (0-9)
* `/profile/(\w+)` = One or more word characters (a-z 0-9 _)
* `[a-z0-9_-]+`  = One or more word characters (a-z 0-9 _) and the dash (-)
* `.*?` = Any character (including /), zero or more
* `[^/]+` = Any character but /, one or more
* `{param}` = Equivalent `(.*?)`

Note: [PHP-regex-cheat-sheet.pdf](https://courses.cs.washington.edu/courses/cse190m/12sp/cheat-sheets/php-regex-cheat-sheet.pdf)
### Groups
Use $router->group($baseroute, $fn) to group a collection of routes onto a subroute pattern.
```php
$router->group('/movies', function() use ($router) {
    // will result in '/movies/'
    $router->get('/', function() {
        echo 'movies overview';
    });
    // will result in '/movies/id'
    $router->get('/(\d+)', function($id) {
        echo 'movie id ' . htmlentities($id);
    });
});
```
### Cancel routing

In some cases needs to cancel routing, by the application logic.<br>
Use `$router->cancel()` method to handle this.

```php 
$router->set404(function() use ($router) {
	header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
	echo '404 Not Found';
});
$router->get('/movies/(\d+)', function($id) use($router) {
	if ($id == 5000) {
		$router->cancel(); // <<
		return;
	}
	echo 'movie id ' . htmlentities($id);
});
$router->run();
```
If `\movies\5000` then 404 page will show.

## More features
For more features:
* Read source code and examples
* Practice with `Router` in real code
