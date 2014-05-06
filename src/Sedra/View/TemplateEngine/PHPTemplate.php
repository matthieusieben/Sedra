<?php

namespace Sedra\View\TemplateEngine;

use Sedra\View;

/**
*
*/
class PHPTemplate extends View
{
	protected function _render(array $__data) {

		extract($__data);
		$view =& $this;

		ob_start();
		require $this->template;
		return ob_get_clean();
	}
}