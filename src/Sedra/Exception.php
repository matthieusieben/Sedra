<?php

namespace Sedra;

use Sedra\Request;
use Sedra\Locale;
use Sedra\View\Viewable;

/**
*
*/
abstract class Exception extends \Exception implements Viewable
{
	protected $default_code = 500;
	protected $default_message = 'An unknown error occured.';
	protected $default_view = 'exception';

	protected $replace_keys = array();
	protected $replace_pairs;

	function __construct($message = null, $code = 0)
	{
		parent::__construct(
			$this->translate($message ?: $this->default_message),
			$code ?: $this->default_code
		);
	}

	protected function translate($message)
	{
		try {
			return Locale::t($message, $this->get_translation_replace_pairs());
		} catch (\Exception $e) {
			return strtr($message, $this->get_translation_replace_pairs());
		}
	}

	protected function get_translation_replace_pairs()
	{
		if (isset($this->replace_pairs))
			return $this->replace_pairs;

		$this->replace_pairs = array();

		foreach ($this->replace_keys as $key => $var)
			if (property_exists($this, $var))
				$this->replace_pairs[$key] = (string) $this->$var;

		return $this->replace_pairs;
	}

	public function get_view()
	{
		return View::factory($this->default_view, array('exception' => $this));
	}
}