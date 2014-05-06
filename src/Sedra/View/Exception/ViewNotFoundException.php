<?php

namespace Sedra\View\Exception;

use Sedra\Exception;

class ViewNotFoundException extends Exception
{
	protected $default_message = 'The view "@view_name" could not be found.';
	protected $replace_keys = array('@view_name' => 'view_name');

	public $view_name;
	public $view_data;

	function __construct($view_name, $view_data)
	{
		$this->view_name = $view_name;
		$this->view_data = $view_data;

		parent::__construct();
	}
}