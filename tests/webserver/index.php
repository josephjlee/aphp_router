<?php 

require __DIR__ . '/../../vendor/autoload.php';

use aphp\Router\Router;

$router = new Router();

$router->set404(function() use ($router) {
	echo '404 ' . $router->request->getCurrentUri();
});

$router->get('/', function() {

	echo '<h1>aphp/router welcome</h1>';

	$links = [
		'/movies/name/photos/12',
		'/movies/all/man',
		'/movies/all/man/12',
		'/movies/car/woman',
		'/movies/name/photos/12',
		'/movies/',
		'/movies/41',
		'/photos/all',
		'/simplelastHelloWorld',
		'/404-request'
	];
	foreach ($links as $l) {
		echo '<a href="'.$l.'">'.$l.'</a><br>';
	}

	echo '<h2>POST example</h2>';

	echo '
	<form action="/" method="post">
	<p><input type="radio" name="answer" value="value1">value1<Br>
	<input type="radio" name="answer" value="value2">value2<Br>
	<input type="radio" name="answer" value="value3">value3</p>
	<p><input type="submit" value="Submit"></p>
	</form>
	';
});

$router->post('/', function() {
	echo '<h1>aphp/router POST</h1>';
	echo '<pre>';
	print_r($_POST);
});

// /movies/name/photos/12

$router->get('/movies/(\w+)/photos/(\d+)', function($p1, $p2) {
	//header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
	echo 'get /movies/(\w+)/photos/(\d+) = ' . $p1 . ', ' . $p2;
});

// /movies/all/man
// /movies/all/man/12
// /movies/car/woman

$router->group('/movies', function() use ($router) {

	$router->get('/', function() {
        echo 'movies overview';
	});
	
	// will result in '/movies/id'
    $router->get('/(\d+)', function($id) {
        echo 'movie id ' . htmlentities($id);
    });
	
	$router->group('/all', function() use ($router) {
		$router->get('/man', function() {
			echo 'group 2 : /movies/all/man';
		});
		$router->get('/man/(\d+)', function($p1) {
			echo 'group 2 : /movies/all/(\d+) = ' . $p1;
		});
	});

	$router->get('/car/woman', function() {
		echo 'group 1 : /movies/car/woman';
	});
});

// /photos/all

$router->group('/photos', function() use ($router) {
	$router->get('/all', function() {
		echo 'group photos : /photos/all';
	});
});

// /simplelastHelloWorld

$router->get('/simplelast{value}', function($p1) {
	echo 'get simplelast' . $p1;
});

$router->run();