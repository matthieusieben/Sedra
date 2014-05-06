<?php

namespace Sedra\Response\HTTP;

use Sedra\Request;
use Sedra\Response\HTTP\ViewResponse;
use Sedra\View;
use Sedra\View\Viewable;
use Sedra\View\TemplateEngine\PHPTemplate;

/**
*
*/
class Page extends ViewResponse
{
	function __construct(Request &$request, $content, $status = 200)
	{
		if ($content instanceof Viewable)
			$content = $content->get_view();

		parent::__construct($request, View::factory('page', array('content' => $content)), $status);
	}
}