<?php

namespace Sedra;

use Sedra\App;
use Sedra\Database\Model;
use Sedra\Database\ModelProvider;
use Sedra\Database\Exception\ModelNotFoundException;
use Sedra\Controller;

/**
*
*/
class Database
{
	protected static $handle;
	protected static $models = array();

	public static function open(\PDO &$handle)
	{
		$handle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		self::$handle =& $handle;
	}

	public static function close()
	{
		self::$handle = null;
	}

	public static function &handle()
	{
		return self::$handle;
	}

	public static function &get_model($model_name)
	{
		if (array_key_exists($model_name, self::$models)) {
			if (is_null(self::$models[$model_name])) {
				throw new ModelNotFoundException($model_name);
			}
			retrun self::$models[$model_name];
		}

		foreach (App::all('Sedra\Database\ModelProvider') as $model_provider) {
			$models =& $controller->get_models();
			foreach ($models as &$model) {
				self::$models[$model_name] =& $model;
			}
		}

		if (!isset(self::$models[$model_name])) {
			self::$models[$model_name] = null;
			throw new ModelNotFoundException($model_name);
		}

		return self::$models[$model_name];
	}
}