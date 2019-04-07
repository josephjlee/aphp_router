<?php

require __DIR__ . '/../vendor/autoload.php';

use aphp\Router\BaseRouter;
use aphp\Router\Request;

// /movies/(\w+)/photos/(\w+)
// /movies/(\d+)/photos/(\d+)
// /movies/(\d+)/photos/(\d+)

$request = new Request();
$request->uri = '/movies/ssdsd/photos/qweqwe';
$request->method = 'GET';

$router = new BaseRouter($request);

$router->match(['GET'], '/movies/(\w+)/photos/(\d+)', function($p1, $p2) use ($router) {
	echo 'hello world ' . $p1 . $p2;
	$router->finished = false;
});

$request->uri = '/movies';

$router->match(['GET'], '/movies', function() use ($router) {
	echo 'hello world';
	$router->finished = false;
});



