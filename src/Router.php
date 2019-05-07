<?php 

namespace aphp\Router;

class Router extends BaseRouter {
	public function all($pattern, $fn) {
		$this->match(['GET','POST','PUT','DELETE','OPTIONS','PATCH','HEAD'], $pattern, $fn);
	}

	public function get($pattern, $fn) {
		$this->match(['GET'], $pattern, $fn);
	}

	public function post($pattern, $fn) {
		$this->match(['POST'], $pattern, $fn);
	}

	public function patch($pattern, $fn) {
		$this->match(['PATCH'], $pattern, $fn);
	}

	public function delete($pattern, $fn) {
		$this->match(['DELETE'], $pattern, $fn);
	}

	public function put($pattern, $fn) {
		$this->match(['PUT'], $pattern, $fn);
	}

	public function options($pattern, $fn) {
		$this->match(['OPTIONS'], $pattern, $fn);
	}

	public function cancel() {
		$this->finished = false;
	}
}