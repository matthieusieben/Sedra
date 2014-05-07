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
		# TODO : Get from DB
		return $string;
	}

	public function get_model_names()
	{
		return array();
	}

	public function get_model($model_name)
	{
		if (isset($this->models[$model_name]))
			return $this->models[$model_name];
		switch ($model_name) {
		default:
			return $this->models[$model_name] = null;
		}
	}
}