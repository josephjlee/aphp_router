<?php

require __DIR__ . '/../vendor/autoload.php';

use aphp\Router\Router;
use aphp\Router\Request;

// /movies/(\w+)/photos/(\w+)
// /movies/(\d+)/photos/(\d+)
// /movies/(\d+)/photos/(\d+)

$request = new Request();
//$request->uri = '/movies/ssdsd/photos/100'; // edit this line
//$request->uri = '/movies/5000'; // edit this line
$request->uri = '/movies/5001'; // edit this line
$request->method = 'GET';

$router = new Router($request);

$router->set404(function() use ($router) {
	//header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
	echo '404 Not Found';
});

$router->match(['GET'], '/movies/(\w+)/photos/(\d+)', function($p1, $p2) use ($router) {
	echo '/movies/(\w+)/photos/(\d+) =  ' . $p1 . ' ' . $p2;
});

$router->get('/movies/(\d+)', function($id) use($router) {
	if ($id == 5000) {
		echo 'cancel detected' . PHP_EOL;
		$router->cancel(); // <<
		return;
	}
	echo 'movie id ' . htmlentities($id);
});

$router->run();



