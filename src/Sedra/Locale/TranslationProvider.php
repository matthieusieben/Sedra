<?php

namespace Sedra\Locale;

/**
 *
 **/
interface TranslationProvider
{
	public function get_translation($string, $locale);
}