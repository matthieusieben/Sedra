<?php

namespace Sedra\Controller;

use Sedra\Controller;
use Sedra\Request;

use Sedra\Router\Route;
use Sedra\Router\RouteProvider;

class Admin extends Controller implements RouteProvider {

	public function &get_routes()
	{
		$this->routes = $this->routes ?: array(
			Route::factory(array(
				'name' => 'AdminIndex',
				'methods' => array('GET'),
				'query' => 'admin',
				'handler' => array(&$this, 'admin'),
				'response_wrapper' => '\Sedra\Response\HTTP\AdminPage',
			)),
		);
		return $this->routes;
	}

	/**
	 * Routes Controllers
	 **/

	public function admin(Request &$request)
	{
		return $request;
	}
}