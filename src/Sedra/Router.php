<?php

namespace Sedra;

use Sedra\App;
use Sedra\Controller;

use Sedra\Request;
use Sedra\Request\HTTP as HTTPRequest;

use Sedra\Response;
use Sedra\Response\HTTP\Error as HTTPErrorResponse;

use Sedra\Router\Route;
use Sedra\Router\RouteProvider;

use Sedra\Router\Exception\NoSuchRouteException;
use Sedra\Router\Exception\RouteNotFoundException;
use Sedra\Router\Exception\PageNotFoundException;
use Sedra\Router\Exception\CommandNotFoundException;

Router::setup(array(
	'script_file' => basename($_SERVER["SCRIPT_NAME"]),
	'script_folder' => rtrim(dirname($_SERVER["SCRIPT_NAME"]), '/').'/',
));

/**
 *
 **/
class Router
{
	protected static $options = array(
		'rewrite' => false,
	);

	public static function setup(array $options = array())
	{
		self::$options = $options + self::$options;
	}

	public static function option($name, $default = null)
	{
		return array_key_exists($name, self::$options) ?
			self::$options[$name] :
			$default ;
	}

	public static function get_route($route_name)
	{
		foreach (App::all('Sedra\Router\RouteProvider') as $controller) {
			try {
				$route = $controller->get_route($route_name);
				if ($route instanceof Route) {
					return $route;
				} else {
					# Invalid behavior... Should return Route or throw NoSuchRouteException
				}
			} catch(NoSuchRouteException $e) {
				continue;
			}
		}

		throw new NoSuchRouteException($route_name);
	}

	public static function process(Request $request)
	{
		try {
			# Try and match the route providers
			foreach (App::all('Sedra\Router\RouteProvider') as $controller) {
				$routes = $controller->get_routes();
				foreach ($routes as $route) {
					$arguments = array();
					if ($route->match($request, $arguments)) {
						return $route->process($request, $arguments);
					}
				}
			}

			# Route not found
			throw new RouteNotFoundException($request);
		} catch (\Exception $e) {
			if ($request instanceof HTTPRequest) {
				return new HTTPErrorResponse($request, $e);
			} else {
				throw $e;
			}
		}
	}

	public static function url($route_name, array $arguments = array())
	{
		return self::wrap_uri(self::uri($route_name, $arguments));
	}

	public static function uri($route_name, array $arguments = array())
	{
		$route = self::get_route($route_name);
		return $route->url($arguments);
	}

	public static function wrap_uri($uri)
	{
		return self::option('script_folder') . (self::option('rewrite') ? $uri : self::option('script_file') . '?q=' . $uri);
	}
}