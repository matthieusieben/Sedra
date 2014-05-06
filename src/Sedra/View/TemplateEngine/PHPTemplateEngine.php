<?php

namespace Sedra\View\TemplateEngine;

use Sedra\Request;

use Sedra\View;
use Sedra\View\TemplateEngine;
use Sedra\View\TemplateEngine\PHPTemplate;

/**
*
*/
class PHPTemplateEngine extends TemplateEngine
{
	protected $view_folder;

	function __construct($folder)
	{
		$folder = realpath($folder);
		# TODO : check that folder exists ?
		$this->view_folder = $folder . DIRECTORY_SEPARATOR;
	}

	public function factory($view_name, array $view_data = array())
	{
		$view_template = $this->view_folder . $view_name . '.php';
		if (file_exists($view_template)) {
			return new PHPTemplate($view_template, $view_data);
		}
	}
}