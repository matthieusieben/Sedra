<?php

namespace Sedra\Database\Exception;

use Sedra\Exception;

class ModelNotFoundException extends Exception
{
	protected $default_code = 500;
	protected $default_message = 'The model "@model_name" could not be found.';
	protected $replace_keys = array('@model_name' => 'model_name');

	protected $model_name;

	function __construct($model_name)
	{
		$this->model_name = $model_name;

		parent::__construct();
	}
}