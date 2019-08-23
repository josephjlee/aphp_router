<?php 

namespace aphp\Router;

class Request {
	public $headers = null;
	public $method = null;
    public $uri = null;
    public $basePath = '';
	/**
     * Get all request headers.
     *
     * @return array The request headers
     */
	public function getRequestHeaders() {
		if ($this->headers) {
			return $this->headers;
		}
		$headers = [];
        // If getallheaders() is available, use that
        if (function_exists('getallheaders')) {
            $headers = getallheaders();

            // getallheaders() can return false if something went wrong
            if ($headers !== false) {
				$this->headers = $headers;
                return $headers;
            }
        }
        // Method getallheaders() not available or went wrong: manually extract 'm
        foreach ($_SERVER as $name => $value) {
            if ((substr($name, 0, 5) == 'HTTP_') || ($name == 'CONTENT_TYPE') || ($name == 'CONTENT_LENGTH')) {
                $headers[str_replace([' ', 'Http'], ['-', 'HTTP'], ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
		}
		$this->headers = $headers;
        return $headers;
	}

	/**
     * Get the request method used, taking overrides into account.
     *
     * @return string The Request method to handle
     */
	public function getRequestMethod()
    {
		if ($this->method) {
			return $this->method;
		}
		// Take the method as found in $_SERVER
        $method = $_SERVER['REQUEST_METHOD'];

        // If it's a HEAD request override it to being GET and prevent any output, as per HTTP Specification
        // @url http://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html#sec9.4
        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
			//ob_start(); // Router cant stop echo operations
			// Web application is the main logic, not router
            $method = 'GET';
        }

        // If it's a POST request, check for a method override header
        elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $headers = $this->getRequestHeaders();
            if (isset($headers['X-HTTP-Method-Override']) && in_array($headers['X-HTTP-Method-Override'], ['PUT', 'DELETE', 'PATCH'])) {
                $method = $headers['X-HTTP-Method-Override'];
            }
        }
		$this->method = $method;
        return $method;
	}
	/**
     * Define the current relative URI.
     *
     * @return string
     */
	public function getCurrentUri()
    {
		if ($this->uri) {
			return $this->uri;
		}
		// Get the current Request URI and remove rewrite base path from it (= allows one to run the router in a sub folder)
        $uri = substr(rawurldecode($_SERVER['REQUEST_URI']), strlen($this->getBasePath()));

        // Don't take query params into account on the URL
        if (strstr($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

		// Remove trailing slash + enforce a slash at the start
		$this->uri = '/' . trim($uri, '/');
        return $this->uri;
	}
	
	public function getBasePath()
    {
		// override if needed
		// return implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
		return $this->basePath;
	}
}