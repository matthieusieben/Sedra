<?php

namespace Sedra;

use Sedra\Locale;

use Sedra\Request\HTTP as HTTPRequest;
use Sedra\Request\Cache as RequestCache;
use Sedra\Request\Exception\RequestTypeNotImplemented as RequestTypeNotImplementedException;

use Sedra\Router\Route;

/**
 *
 **/
class Request
{
	protected $method;
	protected $query;
	protected $route;
	protected $arguments;
	protected $cache;

	protected static $request;

	function __construct($method, $query)
	{
		$this->method = $method;
		$this->query = strtr(trim($query, '/'), '//', '/');
		$this->cache = new RequestCache($this);
	}

	public function __set($key, $value)
	{
		if (!isset($this->$key))
			return $this->$key = $value;

		$trace = debug_backtrace();
		throw new \ErrorException(
			'The attributes of Request objects are read only. The attribute "' . $key . '" cannot be modified.',
			500, E_USER_NOTICE, $trace[0]['file'], $trace[0]['line']
		);
	}

	public function __get($key)
	{
		if (!isset($this->$key)) {
			switch ($key) {
			case 'user':
				# TODO : Load session, user, etc.
				$this->user = new \stdClass;
				$this->user->locale = 'fr_BE';
				$this->user->id = 1;
				$this->user->role = 1;
				break;
			}
		}
		return $this->$key;
	}

	public function locale()
	{
		# TODO : accept-lang
		return $this->__get('user')->locale;
	}

	public static function get()
	{
		if (isset(static::$request))
			return static::$request;

		if (isset($_SERVER['REQUEST_METHOD'])) {
			static::$request = new HTTPRequest();
		}
		/* elseif(PHP_SAPI == 'cli') {
			static::$request = new CLIRequest(@$_SERVER['argv'][1] ?: 'help');
		} */
		else {
			throw new RequestTypeNotImplementedException();
		}

		return static::$request;
	}

	public static function process()
	{
		# Get the request object
		$request = static::get();

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