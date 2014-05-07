<?php

namespace Sedra;

use Sedra\App;
use Sedra\Request;
use Sedra\View\DataProvider;
use Sedra\View\TemplateEngine;
use Sedra\View\TemplateEngineProvider;
use Sedra\View\Exception\ViewNotFoundException;

/**
 *
 **/
abstract class View
{
	protected $template;
	protected $data = array();

	function __construct($template, array $data = array())
	{
		$this->template = $template;
		$this->data = $data;
	}

	protected abstract function _render(array $data);

	public function render(array $data = array())
	{
		return $this->_render($data + $this->data);
	}

	public function __tostring()
	{
		return $this->render();
	}

	public function data($key)
	{
		if (array_key_exists($key, $this->data))
			return $this->data[$key];

		foreach (App::all('Sedra\View\DataProvider') as &$controller)
			$controller->get_view_data($key, $this->data, Request::get());

		if (!array_key_exists($key, $this->data))
			$this->data[$key] = null;

		return $this->data[$key];
	}

	public function __set($key, $value)
	{
		return $this->data[$key] = $value;
	}

	public function __get($key)
	{
		return $this->data($key);
	}

	public function __invoke($key)
	{
		return $this->data($key);
	}

	protected static $data_providers = array();
	protected static $template_engines = array();

	protected static $static_data = array();

	public static function factory($view_name, array $data = array())
	{
		foreach (App::all('Sedra\View\TemplateEngineProvider') as &$engine_provider) {
			try {
				$template_engine = $engine_provider->get_template_engine();
				if ($view = $template_engine->factory($view_name, $data)) {
					return $view;
				}
			} catch(ViewNotFoundException $e) {
				continue;
			}
		}
		throw new ViewNotFoundException($view_name, $data);
	}
}