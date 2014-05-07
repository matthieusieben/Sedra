<?php

namespace Sedra;

use Sedra\Router;
use Sedra\Request;
use Sedra\Response;

defined('START_TIME') or define('START_TIME', microtime(TRUE));

/**
 *
 **/
class App
{
	protected static $hooks = array();

	public static function register(Controller $controller, $prepend = true)
	{
		foreach(class_implements($controller, false) as $interface) {
			if (!isset(self::$hooks[$interface])) {
				self::$hooks[$interface] = array();
			}

			if ($prepend) {
				array_unshift(self::$hooks[$interface], $controller);
			} else {
				array_push(self::$hooks[$interface], $controller);
			}
		}
	}

	public static function all($interface)
	{
		if (isset(self::$hooks[$interface]))
			return self::$hooks[$interface];

		return array();
	}

	public static function process()
	{
		# Get the request object
		$request = Request::get();

		# Get the corresponding response from the cache
		if ($response = $request->cache->get()) {
			# Nothing to do
		} else {
			# Generate the response
			$response = Router::process($request);

			# Store the response in the cache
			$request->cache->set($response);
		}

		# Send the response
		$response->send();
	}
}