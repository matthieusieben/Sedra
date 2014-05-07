<?php

namespace Sedra;

defined('START_TIME') or define('START_TIME', microtime(TRUE));

/**
 *
 **/
class App
{
	protected static $hooks = array();

	public static function register(Controller $controller, $prepend = true)
	{
		foreach(class_implements($controller, false) as $interface) {
			if (!isset(self::$hooks[$interface])) {
				self::$hooks[$interface] = array();
			}

			if ($prepend) {
				array_unshift(self::$hooks[$interface], $controller);
			} else {
				array_push(self::$hooks[$interface], $controller);
			}
		}
	}

	public static function all($interface)
	{
		if (isset(self::$hooks[$interface]))
			return self::$hooks[$interface];

		static $null;
		return $null;
	}
}