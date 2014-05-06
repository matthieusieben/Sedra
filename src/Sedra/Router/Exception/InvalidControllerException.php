<?php

namespace Sedra\Router\Exception;

use Sedra\Exception;
use Sedra\Router\Route;

class InvalidControllerException extends Exception
{
	protected $default_message = 'Invalid callback for route "@route_name".';
	protected $replace_keys = array('@route_name' => 'route_name');

	public $route_name;

	function __construct(Route &$route)
	{
		$this->route_name = $route->name ?: $route->query;
		parent::__construct();
	}
}