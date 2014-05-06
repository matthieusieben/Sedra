<?php

namespace Sedra;


/**
 *
 **/
class App
{
	protected static $hooks = array();

	public static function register(Controller &$controller, $prepend = true)
	{
		foreach(class_implements($controller, false) as $interface) {
			if (!isset(self::$hooks[$interface])) {
				self::$hooks[$interface] = array();
			}

			if ($prepend) {
				self::$hooks[$interface] = array_merge(
					array(&$controller),
					self::$hooks[$interface]
				);
			} else {
				self::$hooks[$interface] = array_merge(
					self::$hooks[$interface],
					array(&$controller)
				);
			}
		}
	}

	public static function &all($interface)
	{
		if (isset(self::$hooks[$interface]))
			return self::$hooks[$interface];

		static $null;
		return $null;
	}
}