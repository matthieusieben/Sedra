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

	public static function open(\PDO $handle)
	{
		$handle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		self::$handle = $handle;
	}

	public static function close()
	{
		self::$handle = null;
	}

	public static function handle()
	{
		return self::$handle;
	}

	public static function get_model($model_name)
	{
		foreach (App::all('Sedra\Database\ModelProvider') as $model_provider) {
			try {
				$model = $model_provider->get_model($model_name)
				if ($model instanceof Model) {
					return $model;
				} else {
					# Invalid behavior... Should return Model or throw ModelNotFoundException
				}
			} catch(ModelNotFoundException $e) {
				continue;
			}
		}

		throw new ModelNotFoundException($model_name);
	}
}