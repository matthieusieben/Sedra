<?php

namespace Sedra\Controller;

use Sedra\Controller;
use Sedra\Request;

use Sedra\Router\Route;
use Sedra\Router\RouteProvider;
use Sedra\Router\Exception\NoSuchRoute as NoSuchRouteException;

class Admin extends Controller implements RouteProvider {

	public function get_route_names()
	{
		return array('AdminIndex');
	}

	public function get_route($route_name)
	{
		if (isset($this->routes[$route_name]))
			return $this->routes[$route_name];

		switch ($route_name) {
		case 'AdminIndex':
			return $this->routes[$route_name] = Route::factory(array(
				'name' => $route_name,
				'methods' => array('GET'),
				'query' => 'admin',
				'handler' => array($this, 'handle_admin'),
				'response_wrapper' => '\Sedra\Response\HTTP\AdminPage',
			));
		default:
			throw new NoSuchRouteException($route_name);
		}
	}

	/**
	 * Routes Controllers
	 **/

	public function handle_admin(Request $request)
	{
		return $request;
	}
}