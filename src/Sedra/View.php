<?php

namespace Sedra;

use Sedra\App;
use Sedra\Request;
use Sedra\View\DataProvider;
use Sedra\View\Exception as ViewException;
use Sedra\View\Exception\ViewNotFound as ViewNotFoundException;
use Sedra\View\TemplateEngine;
use Sedra\View\TemplateEngineProvider;

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

	public function set_data(array $data)
	{
		$this->data = $data + $this->data;
	}

	public function get_data($key)
	{
		if (array_key_exists($key, $this->data))
			return $this->data[$key];

		return static::static_data($key);
	}

	protected abstract function _render(array $data);

	public function render(array $data = array())
	{
		return $this->_render(array('view' => $this) + $this->data + $data);
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
		return $this->get_data($key);
	}

	public function __tostring()
	{
		return $this->render();
	}

	protected static $static_data = array();

	public static function static_data($key)
	{
		if (array_key_exists($key, self::$static_data))
			return self::$static_data[$key];

		foreach (App::all('Sedra\View\DataProvider') as &$data_provider)
			$data_provider->get_view_data($key, self::$static_data, Request::get());

		if (!array_key_exists($key, self::$static_data))
			self::$static_data[$key] = null;

		return self::$static_data[$key];
	}

	public static function factory($view_name, array $data = array())
	{
		foreach (App::all('Sedra\View\TemplateEngineProvider') as $engine_provider) {
			try {
				$template_engine = $engine_provider->get_template_engine();
				$view = $template_engine->factory($view_name, $data);
				if ($view instanceof View) {
					return $view;
				} else {
					# Invalid behavior... Should return a View or throw ViewNotFoundException
					throw new ViewException();
				}
			} catch(ViewNotFoundException $e) {
				continue;
			}
		}
		throw new ViewNotFoundException($view_name, $data);
	}
}