<?php

namespace Sedra;

use Sedra\App;
use Sedra\Controller;
use Sedra\Locale\TranslationProvider;

/**
 *
 **/
class Locale
{
	const REFERENCE = 'en-US';
	protected static $locale = self::REFERENCE;

	public static function set($new_locale = self::REFERENCE)
	{
		$new_locale = setlocale(LC_ALL, $new_locale);
		if ($new_locale) {
			self::$locale = $new_locale;
		}
	}

	public static function get_locales()
	{
		# XXX
		return array(self::REFERENCE, 'fr-BE');
	}

	public static function t($string, array $replace_pairs = array())
	{
		if (self::$locale !== self::REFERENCE) {
			foreach (App::all('Sedra\Locale\TranslationProvider') as &$translation_provider) {
				$translation = $translation_provider->get_translation($string, self::$locale);
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
