<?php

namespace Sedra\View\TemplateEngine;

use Sedra\View;

function render_php_template($__file, $__data)
{
	extract($__data);

	ob_start();
	require $__file;
	return ob_get_clean();
}

/**
*
*/
class PHPTemplate extends View
{
	protected function _render(array $__data) {
		return render_php_template($this->template, $__data);
	}
}