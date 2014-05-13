<?php

namespace Sedra;

use Sedra\Database\ModelProviderIterator;
use Sedra\Router\RouteProviderIterator;

/**
 *
 */
abstract class Controller
{
	protected $options = array();
	protected $routes = array();
	protected $models = array();

	function __construct(array $options = array())
	{
		$this->options = $options + $this->options;
	}

	public function get_routes()
	{
		return new RouteProviderIterator($this);
	}

	public function get_models()
	{
		return new ModelProviderIterator($this);
	}
}