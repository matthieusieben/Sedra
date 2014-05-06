<?php

namespace Sedra\Router;

use Sedra\Request;

/**
*
*/
class Route
{
	public $name;
	public $methods = array('GET');
	public $query;
	public $filters = array();
	public $handler;

	public function match_method(Request &$request)
	{
		if (in_array($request->method, $this->methods)) {
			return true;
		}
		if ($request->is_ajax && in_array('AJAX', $this->methods)) {
			return true;
		}
		return false;
	}

	public function match(Request &$request, &$arguments)
	{
		if (!$this->match_method($request)) {
			return false;
		}

		if(preg_match($this->get_regex(), $request->query, $matches)) {

			if (preg_match_all('/:([\w-]+)/', $this->query, $argument_keys)) {

				$argument_keys = $argument_keys[1];

				foreach ($argument_keys as $key => $name) {
					if (isset($matches[$key + 1])) {
						$arguments[$name] = $matches[$key + 1];
					}
				}
			}

			return true;
		}

		return false;
	}

	public function process(Request &$request, $arguments)
	{
		if (!$this->access($request)) {
			throw new AccessForbiddenException($request);
		}

		if (!is_callable($this->handler)) {
			throw new InvalidControllerException($this);
		}

		$response = call_user_func_array($this->handler, array(&$request, $arguments));

		if (!$response instanceof Response) {
			if (!isset($this->response_wrapper)) {
				throw new ResponseWrapperNotFoundException($this);
			}

			$response_wrapper = $this->response_wrapper;
			$response = new $response_wrapper($request, $response);
		}

		return $response;
	}

	public function get_regex()
	{
		if(isset($this->regex))
			return $this->regex;

		$this->regex = '@^' . preg_replace_callback('/:(\w+)/', array(&$this, 'substitute_filter'), $this->query) . '$@i';

		return $this->regex;
	}

	public function substitute_filter($matches)
	{
		if (isset($matches[1]) && isset($this->filters[$matches[1]])) {
			return '('.$this->filters[$matches[1]].')';
		}
		return '([\w-]+)';
	}

	public function access(Request &$request)
	{
		return true;
	}

	public function url(array $arguments = array())
	{
		$replace_pairs = array();

		foreach ($arguments as $key => $value) {
			$replace_pairs[':'.$key] = $value;
		}

		return strtr($this->query, $replace_pairs);
	}

	public static function factory(array $options, $class = __CLASS__)
	{
		$object = new $class;
		foreach ($options as $key => $value) {
			if ($key == 'method')
				$object->methods = (array) $value;
			else
				$object->$key = $value;
		}

		return $object;
	}
}