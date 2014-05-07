<?php

namespace Sedra;

use Sedra\Database\ModelProvider;
use Sedra\Exception;

/**
 *
 */
abstract class Controller
{
	protected $options;
	protected $default_options = array();

	function __construct(array $options = array())
	{
		$this->options = $options + $this->default_options;
	}

	function get_models()
	{
		if (!$this instanceof ModelProvider)
			throw new Exception('Trying to get the models of from a non ModelProvider controller.');

		$models = array();

		foreach ($this->get_model_names() as $model_name)
			$models[$model_name] = $this->get_model($model_name);

		return $models;
	}
}