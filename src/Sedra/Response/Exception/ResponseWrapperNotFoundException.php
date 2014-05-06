<?php

namespace Sedra\Response\Exception;

use Sedra\Exception;
use Sedra\Router\Route;

class ResponseWrapperNotFoundException extends Exception
{
	protected $default_code = 500;
	protected $default_message = 'Default response wrapper undefined for route "@route_name".';
	protected $replace_keys = array('@route_name' => 'route_name');

	public $route;
	protected $route_name;

	function __construct(Route &$route)
	{
		$this->route =& $route;
		$this->route_name = $route->name ?: $route->query;

		parent::__construct();
	}
}