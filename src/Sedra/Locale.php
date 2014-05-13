<?php

namespace Sedra;

use Sedra\App;
use Sedra\Request;
use Sedra\Locale\TranslationProvider;

/**
 *
 **/
class Locale
{
	const REFERENCE = 'en_US';
	protected static $current;
	protected static $enabled = array(self::REFERENCE);

	public static function set($new_locale)
	{
		return static::$current = setlocale(LC_ALL, $new_locale) ?: static::$current;
	}

	public static function enable(array $enabled)
	{
		return static::$enabled = $enabled;
	}

	public static function enabled()
	{
		return static::$enabled;
	}

	public static function t($string, array $replace_pairs = array())
	{
		isset(static::$current) or static::set(Request::get()->locale()) or static::set(self::REFERENCE);

		if (static::$current !== static::REFERENCE) {
			foreach (App::all('Sedra\Locale\TranslationProvider') as &$translation_provider) {
				$translation = $translation_provider->get_translation($string, static::$current);
				if ($translation) {
					$string = $translation;
					break;
				}
			}
			# else : Not found, not translated
		}

		# Transform arguments before inserting them.
		foreach ($replace_pairs as $key => &$value) {
			switch ($key[0]) {
			case '@':
			default:
				# XXX $replace_pairs[$key] = htmlentities($value, ENT_QUOTES, 'UTF-8');
				$value = htmlentities($value, ENT_QUOTES, 'UTF-8');
				break;

			case '!': # Do not escape the string
			}
		}
		return strtr($string, $replace_pairs);
	}
}
