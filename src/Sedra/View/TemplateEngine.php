<?php

namespace Sedra\View;

use Sedra\View;
use Sedra\Request;

/**
*
*/
abstract class TemplateEngine
{
	public abstract function factory($view_name, array $data = array());
}