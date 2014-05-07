<?php

namespace Sedra;

use Sedra\Exception;

use Sedra\Database\ModelProvider;
use Sedra\Router\RouteProvider;

/**
 *
 */
abstract class Controller
{
	protected $options;
	protected $default_options = array();

	protected $routes = array();
	protected $models = array();

	function __construct(array $options = array())
	{
		$this->options = $options + $this->default_options;
	}

	public function get_routes()
	{
		if (!$this instanceof RouteProvider)
			throw new Exception('Trying to get the models of from a non-RouteProvider controller.');

		foreach ($this->get_route_names() as $route_name)
			if (!isset($this->routes[$model_name]))
				$this->routes[$route_name] = $this->get_route($route_name);

		return $this->routes;
	}

	public function get_models()
	{
		if (!$this instanceof ModelProvider)
			throw new Exception('Trying to get the models of from a non-ModelProvider controller.');

		foreach ($this->get_model_names() as $model_name)
			if (!isset($this->models[$model_name]))
				$this->models[$model_name] = $this->get_model($model_name);

		return $this->models;
	}
}