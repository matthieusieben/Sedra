<?php

namespace Sedra\Response\HTTP;

use Sedra\Request;
use Sedra\Response\HTTP\View as ViewResponse;
use Sedra\View;
use Sedra\View\TemplateEngine\PHPTemplate;

/**
*
*/
class AdminPage extends ViewResponse
{
	function __construct(Request &$request, $content, $status = 200)
	{
		parent::__construct($request, View::factory('admin/page', array('content', $content)), $status);
	}
}