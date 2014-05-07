<?php

namespace Sedra\Controller;

use Sedra\App;

use Sedra\Controller;

use Sedra\Database\Model;
use Sedra\Database\ModelProvider;

use Sedra\Locale;

use Sedra\Request;

use Sedra\Router\Route;
use Sedra\Router\RouteProvider;

use Sedra\View\DataProvider;
use Sedra\View\TemplateEngineProvider;
use Sedra\View\TemplateEngine\PHPTemplateEngine;

class Sedra extends Controller implements DataProvider, TemplateEngineProvider, ModelProvider {

	protected $default_options = array(
		'locales' => array(Locale::REFERENCE),
		'views_data' => array(
			'site_name' => 'Sedra CMS',
		),
	);

	function __construct($options = array())
	{
		parent::__construct($options);
	}

	/**
	 * Interfaces implementation
	 **/

	public function get_view_data($name, array &$data, Request $request)
	{
		if (isset($this->options['views_data'][$name])) {
			$data[$name] = $this->options['views_data'][$name];
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
			return $this->models[$model_name] = null;
		}
	}
}
