<?php

namespace Sedra;

use Sedra\Request\HTTP as HTTPRequest;
use Sedra\Request\Cache as RequestCache;
use Sedra\Request\Exception\AccessForbiddenException;
use Sedra\Request\Exception\RequestTypeNotImplementedException;

use Sedra\Response\Exception\ResponseWrapperNotFoundException;

use Sedra\Router\Route;
use Sedra\Router\Exception\InvalidControllerException;

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
		$this->query = trim($query, '/');
		$this->cache = new RequestCache($this);
	}

	public function __set($key, $value)
	{
		$trace = debug_backtrace();
		throw new \ErrorException(
			'The attributes of Request objects are read only. The attribute "' . $key . '" cannot be modified.',
			500, E_USER_NOTICE, $trace[0]['file'], $trace[0]['line']
		);
	}

	public function &__get($key)
	{
		if (!isset($this->$key)) {
			switch ($key) {
			case 'user':
				# TODO : Load session, user, etc.
				$this->user = new stdClass;
				$this->user->locale = 'fr-BE';
				$this->user->id = 1;
				$this->user->role = 1;
				break;
			}
		}
		return $this->$key;
	}

	public static function &get()
	{
		if (isset(self::$request))
			return self::$request;

		if (isset($_SERVER['REQUEST_METHOD'])) {
			self::$request = new HTTPRequest();
		}
		/* elseif(PHP_SAPI == 'cli') {
			self::$request = new CLIRequest(@$_SERVER['argv'][1] ?: 'help');
		} */
		else {
			throw new RequestTypeNotImplementedException();
		}

		return self::$request;
	}
}