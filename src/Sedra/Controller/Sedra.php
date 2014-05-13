<?php

namespace Sedra\Controller;

use Sedra\App;

use Sedra\Controller;

use Sedra\Database\Model;
use Sedra\Database\ModelProvider;
use Sedra\Database\Exception\ModelNotFound as ModelNotFoundException;

use Sedra\Locale;

use Sedra\Request;

use Sedra\Router;
use Sedra\Router\Route;
use Sedra\Router\RouteProvider;
use Sedra\Router\Exception\NoSuchRoute as NoSuchRouteException;

use Sedra\View\DataProvider;
use Sedra\View\TemplateEngineProvider;
use Sedra\View\TemplateEngine\PHPTemplateEngine;

class Sedra extends Controller implements DataProvider, TemplateEngineProvider, ModelProvider, RouteProvider {

	protected $options = array(
		'site_name' => 'Sedra CMS',
	);

	function __construct($options)
	{
		parent::__construct($options);
		Router::option('default_route') or Router::setup(array('default_route' => 'SedraIndex'));
	}

	/**
	 * Interfaces implementation
	 **/

	public function get_route_names()
	{
		return array('SedraIndex');
	}

	public function get_route($route_name)
	{
		if (isset($this->routes[$route_name]))
			return $this->routes[$route_name];

		switch ($route_name) {
		case 'SedraIndex':
			return $this->routes[$route_name] = Route::factory(array(
				'name' => $route_name,
				'methods' => array('GET'),
				'query' => 'index',
				'handler' => array($this, 'handle_index'),
				'response_wrapper' => '\Sedra\Response\HTTP\Page',
			));
		default:
			throw new NoSuchRouteException($route_name);
		}
	}

	public function get_view_data($name, array &$data, Request $request)
	{
		if (isset($this->options[$name])) {
			$data[$name] = $this->options[$name];
		}
	}

	public function get_template_engine()
	{
		static $engine;
		$engine = $engine ?: new PHPTemplateEngine(dirname(__DIR__).DIRECTORY_SEPARATOR.'templates');
		return $engine;
	}

	public function get_model_names()
	{
		return array();
	}

	public function get_model($model_name)
	{
		if (isset($this->models[$model_name]))
			return $this->models[$model_name];

		switch ($model_name) {
		default:
			throw new ModelNotFoundException($model_name);
		}
	}
}
