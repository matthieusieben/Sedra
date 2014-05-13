<?php

namespace Sedra;

use Sedra\App;
use Sedra\Controller;
use Sedra\Locale;

use Sedra\Request;
use Sedra\Request\HTTP as HTTPRequest;

use Sedra\Response;
use Sedra\Response\HTTP\Error as HTTPErrorResponse;

use Sedra\Router\Route;
use Sedra\Router\Exception as RouterException;
use Sedra\Router\RouteProvider;

use Sedra\Router\Exception\NoSuchRoute as NoSuchRouteException;
use Sedra\Router\Exception\RouteNotFound as RouteNotFoundException;

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
		static::$options = $options + static::$options;
	}

	public static function option($name, $default = null)
	{
		return array_key_exists($name, static::$options) ?
			static::$options[$name] :
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
					# Invalid behavior... Should return a Route or throw NoSuchRouteException
					throw new RouterException();
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
			if (!$request->query) {
				if (static::option('default_route')) {
					$route = static::get_route(static::option('default_route'));
					return $route->process($request);
				}
			} else {
				foreach (App::all('Sedra\Router\RouteProvider') as $controller) {
					$routes = $controller->get_routes();
					foreach ($routes as $route_name => $route) {
						$arguments = array();
						if ($route->match($request, $arguments)) {
							$request->route = $route;
							$request->arguments = $arguments;
							return $route->process($request, $arguments);
						}
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
		return static::wrap_uri(static::uri($route_name, $arguments));
	}

	public static function uri($route_name, array $arguments = array())
	{
		$route = static::get_route($route_name);
		return $route->url($arguments);
	}

	public static function wrap_uri($uri)
	{
		return static::option('script_folder') . (static::option('rewrite') ? $uri : static::option('script_file') . '?q=' . $uri);
	}
}