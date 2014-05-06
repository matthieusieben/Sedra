<?php

namespace Sedra\Router\Exception;

use Sedra\Exception;
use Sedra\Locale;
use Sedra\Request;

class NoSuchRouteException extends Exception
{
	protected $default_message = 'The route "@route_name" could not be found.';
	protected $replace_keys = array('@route_name' => 'route_name');

	public $route_name;

	function __construct($route_name)
	{
		parent::__construct();
		$this->route_name = $route_name;
	}
}