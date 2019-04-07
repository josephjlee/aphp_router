<?php 

namespace aphp\Router;

/*
$router->get('/movies/(\d+)/photos/(\d+)', function($movieId, $photoId) {
    echo 'Movie #' . $movieId . ', photo #' . $photoId);
});
*/

// -----------
// RouterH
// -----------

abstract class BaseRouterH {
	/**
     * if true , then $pattern = RAW preg pattern
     */
	public $useRawPattern = false;
	public $request = null; // Request
	public $currentPattern = null;
	public $finished = false;
	
	/**
     * Set the 404 handling function.
     */
	abstract public function set404($fn);

	/**
     * Groups a collection of callbacks onto a base route.
     *
     * @param string $baseRoute The route sub pattern to mount the callbacks on
     * @param callable $fn        The callback method
     */
	abstract public function group($pattern, $fn);

	 /**
     * Store a route and a handling function to be executed when accessed using one of the specified methods.
     *
     * @param array  $methods [string] methods
     * @param string $pattern A route pattern such as /movies/(\d+)/photos/(\d+)
     * @param object|callable $fn The handling function to be executed
     */
	abstract public function match($methods, $pattern, $fn);

	/**
	 * Runs 404 if needed 
	 * Last method of the router
	*/
	abstract public function run();
}

// -----------
// Router
// -----------

class BaseRouter extends BaseRouterH {
	public function __construct(Request $request = null) {
		if (!$request) {
			$this->request = new Request();
		} else {
			$this->request = $request;
		}
	}

	public function set404($fn) {
		$this->fn_404 = $fn;
	}

	public function group($pattern, $fn) {
		$this->group[] = $this->patternString($pattern);
		if (is_callable($fn)) {
			call_user_func($fn);
		}
		array_pop($this->group);
	}

	public function match($methods, $pattern, $fn) {
		if ($this->finished) {
			return;
		}
		if (in_array($this->request->getRequestMethod(), $methods) === false) {
			return;
		}
		$pattern = implode('', $this->group) . $this->patternString($pattern);
		if ($pattern !== '/') {
			$pattern = rtrim($pattern, '/');
		}
		$this->currentPattern = $pattern;

		// Replace all curly braces matches {} into word patterns (like Laravel)
		$pattern = preg_replace('~{(.*?)}~', '(.*?)', $pattern);

		if (!$this->useRawPattern) {
			$pattern = '~^' . $pattern . '$~';
		}

		if (preg_match($pattern, $this->request->getCurrentUri(), $matches)) {
			$this->finished = true;
			$params = array_slice($matches, 1);
			if (is_callable($fn)) {
				call_user_func_array($fn, $params);
			}
		}
	}

	public function run() {
		if ($this->finished) {
			return;
		}
		if (is_callable($this->fn_404)) {
			call_user_func($this->fn_404);
		}
	}

	// Protected

	protected $fn_404;
	protected $group = [ ];

	protected function patternString($pattern) {
		return '/' . trim($pattern, '/');
	}
}