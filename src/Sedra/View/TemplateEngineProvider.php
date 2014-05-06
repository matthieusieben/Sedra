<?php

namespace Sedra\View;

use Sedra\View\TemplateEngine;

/**
 *
 **/
interface TemplateEngineProvider
{
	public function &get_template_engines();
}