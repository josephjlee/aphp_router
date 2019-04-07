<?php 

class RouterTest extends Base_TestCase {
	
	// STATIC
	public static function setUpBeforeClass() {
		
	}

	public static function tearDownAfterClass() {
		
	}
	
	// override
	
	protected function setUp() {
		
	}
	
	protected function tearDown() {

	}
	
	protected function loadServerRequest($rq) {
		$c = file_get_contents('http://localhost:8008' . $rq);
		return $c;
	}
	
	// tests
	
	public function test_1() 
	{
		$t = $this->loadServerRequest('/movies/name/photos/12');
		$this->assertEquals($t , 'get /movies/(\w+)/photos/(\d+) = name, 12');

		$t = $this->loadServerRequest('/movies');
		$this->assertEquals($t , 'movies overview');

		$t = $this->loadServerRequest('/movies/');
		$this->assertEquals($t , 'movies overview');

		$t = $this->loadServerRequest('/movies/all/man');
		$this->assertEquals($t , 'group 2 : /movies/all/man');

		$t = $this->loadServerRequest('/movies/all/man/12');
		$this->assertEquals($t , 'group 2 : /movies/all/(\d+) = 12');

		$t = $this->loadServerRequest('/movies/car/woman');
		$this->assertEquals($t , 'group 1 : /movies/car/woman');

		$t = $this->loadServerRequest('/photos/all');
		$this->assertEquals($t , 'group photos : /photos/all');

		$t = $this->loadServerRequest('/simplelastHelloWorld');
		$this->assertEquals($t , 'get simplelastHelloWorld');
	}
}
	
	