<?php

namespace Sedra\Router\Exception;

use Sedra\Exception;
use Sedra\Locale;
use Sedra\Request;

class RouteNotFoundException extends Exception
{
	protected $default_code = 404;
	protected $default_message = 'The requested content "@query" does not exist.';
	protected $replace_keys = array('@query' => 'query');

	public $query;

	function __construct(Request &$request)
	{
		$this->query = $request->query;
		parent::__construct();
	}
}