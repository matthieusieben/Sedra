<?php

namespace Sedra\Controller;

use Sedra\Controller;

use Sedra\Database\ModelProvider;

use Sedra\Locale;
use Sedra\Locale\TranslationProvider;


/**
 *
 **/
class i18n extends Controller implements ModelProvider, TranslationProvider
{
	public function get_translation($string, $language)
	{
		# Get from DB
		return $string;
	}

	public function &get_models()
	{
		$this->models = $this->models ?: array();
		return $this->models;
	}
}